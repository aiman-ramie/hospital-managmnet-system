<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

function respond(array $payload, int $status = 200): never
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

function input(): array
{
    $raw = file_get_contents('php://input');
    if (!$raw) {
        return $_POST;
    }
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function required(array $data, array $fields): void
{
    foreach ($fields as $field) {
        if (!isset($data[$field]) || trim((string) $data[$field]) === '') {
            respond(['error' => "Missing required field: {$field}"], 422);
        }
    }
}

function code(string $prefix): string
{
    return $prefix . '-' . date('ymdHis') . random_int(10, 99);
}

try {
    $pdo = database();
    $resource = strtolower((string) ($_GET['resource'] ?? 'health'));
    $method = $_SERVER['REQUEST_METHOD'];

    if ($resource === 'health' && $method === 'GET') {
        respond(['ok' => true, 'service' => 'HMS API', 'database' => 'connected']);
    }

    if ($resource === 'appointments') {
        if ($method === 'GET') {
            $status = $_GET['status'] ?? null;
            $sql = 'SELECT * FROM appointments';
            $params = [];
            if ($status) {
                $sql .= ' WHERE status = :status';
                $params['status'] = $status;
            }
            $sql .= ' ORDER BY appointment_date DESC, appointment_time DESC';
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            respond(['data' => $stmt->fetchAll()]);
        }
        if ($method === 'POST') {
            $data = input();
            required($data, ['patient_name', 'phone', 'doctor_name', 'appointment_date', 'appointment_time', 'department', 'reason']);
            $stmt = $pdo->prepare('INSERT INTO appointments (appointment_code, patient_name, phone, doctor_name, appointment_date, appointment_time, appointment_type, department, reason, notes, status) VALUES (:code, :patient_name, :phone, :doctor_name, :appointment_date, :appointment_time, :appointment_type, :department, :reason, :notes, :status)');
            $stmt->execute([
                'code' => code('APT'), 'patient_name' => $data['patient_name'], 'phone' => $data['phone'], 'doctor_name' => $data['doctor_name'],
                'appointment_date' => $data['appointment_date'], 'appointment_time' => $data['appointment_time'], 'appointment_type' => $data['appointment_type'] ?? 'Consultation',
                'department' => $data['department'], 'reason' => $data['reason'], 'notes' => $data['notes'] ?? null, 'status' => $data['status'] ?? 'Confirmed',
            ]);
            respond(['data' => ['id' => (int) $pdo->lastInsertId()], 'message' => 'Appointment created'], 201);
        }
    }

    $resources = [
        'patients' => ['table' => 'patients', 'fields' => ['patient_code', 'first_name', 'last_name', 'gender', 'date_of_birth', 'phone', 'email', 'address', 'emergency_contact'], 'code' => 'PT'],
        'doctors' => ['table' => 'doctors', 'fields' => ['doctor_code', 'name', 'specialty', 'phone', 'email', 'schedule', 'status'], 'code' => 'DOC'],
        'medicines' => ['table' => 'medicines', 'fields' => ['medicine_code', 'name', 'category', 'unit', 'reorder_level', 'unit_price', 'controlled'], 'code' => 'MED'],
        'suppliers' => ['table' => 'suppliers', 'fields' => ['supplier_code', 'name', 'phone', 'email', 'address', 'payment_terms', 'status'], 'code' => 'SUP'],
    ];

    if (isset($resources[$resource])) {
        $definition = $resources[$resource];
        if ($method === 'GET') {
            $stmt = $pdo->query("SELECT * FROM {$definition['table']} ORDER BY id DESC");
            respond(['data' => $stmt->fetchAll()]);
        }
        if ($method === 'POST') {
            $data = input();
            $required = $resource === 'patients' ? ['first_name', 'last_name'] : ($resource === 'doctors' ? ['name', 'specialty'] : ($resource === 'medicines' ? ['name', 'category', 'unit'] : ['name']));
            required($data, $required);
            $fields = $definition['fields'];
            $values = [];
            foreach ($fields as $field) {
                if ($field === $definition['fields'][0]) {
                    $values[$field] = $data[$field] ?? code($definition['code']);
                } elseif (array_key_exists($field, $data)) {
                    $values[$field] = $data[$field];
                }
            }
            $columns = array_keys($values);
            $placeholders = array_map(static fn(string $field): string => ':' . $field, $columns);
            $stmt = $pdo->prepare("INSERT INTO {$definition['table']} (" . implode(',', $columns) . ') VALUES (' . implode(',', $placeholders) . ')');
            $stmt->execute($values);
            respond(['data' => ['id' => (int) $pdo->lastInsertId()], 'message' => ucfirst($resource) . ' created'], 201);
        }
    }

    respond(['error' => 'Unsupported resource or method'], 404);
} catch (Throwable $error) {
    respond(['error' => 'Server error', 'detail' => $error->getMessage()], 500);
}
