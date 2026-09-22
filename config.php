<?php
declare(strict_types=1);

function envValue(string $key, string $fallback = ''): string
{
    $value = getenv($key);
    return $value === false ? $fallback : $value;
}

function database(): PDO
{
    static $pdo;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = envValue('HMS_DB_HOST', '127.0.0.1');
    $port = envValue('HMS_DB_PORT', '3306');
    $name = envValue('HMS_DB_NAME', 'hms');
    $user = envValue('HMS_DB_USER', 'root');
    $pass = envValue('HMS_DB_PASS', '');
    $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";

    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}
