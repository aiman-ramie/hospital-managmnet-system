<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Laboratory | HMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <style>
        :root { --canvas:#091b35; --panel:#122b50; --panel-2:#173660; --line:#2b4770; --gold:#edbd3f; --ink:#fff; --muted:#bdc8db; --danger:#e58a8e; }
        * { box-sizing:border-box; } body { margin:0; background:var(--canvas); color:var(--ink); font:14px 'DM Sans', sans-serif; }
        .wrap { max-width:1450px; margin:auto; padding:25px 28px 44px; } .topbar { display:flex; justify-content:space-between; align-items:center; gap:20px; margin-bottom:20px; }
        h1 { margin:0; font:800 29px Manrope,sans-serif; } h2 { margin:0; font:700 16px Manrope,sans-serif; } h3 { margin:0 0 12px; font:700 14px Manrope,sans-serif; }
        .subtitle { margin:6px 0 0; color:var(--muted); } .back { color:var(--gold); border:1px solid var(--line); border-radius:8px; padding:10px 14px; text-decoration:none; white-space:nowrap; }
        .banner { margin-bottom:16px; border:1px solid var(--gold); border-radius:8px; padding:12px 15px; color:var(--gold); background:#292310; } .banner.error { border-color:var(--danger); color:#ffd6d8; background:#3a202b; }
        .kpis { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin-bottom:16px; } .kpi { padding:16px; border:1px solid var(--line); border-radius:10px; background:var(--panel); }
        .kpi small { color:var(--muted); } .kpi strong { display:block; margin-top:7px; font:800 25px Manrope,sans-serif; color:var(--gold); }
        .grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px; } .grid-wide { display:grid; grid-template-columns:1.25fr .75fr; gap:16px; margin-bottom:16px; }
        .card { min-width:0; overflow:hidden; border:1px solid var(--line); border-radius:10px; background:var(--panel); } .card-head { display:flex; justify-content:space-between; align-items:center; gap:10px; padding:15px 16px; border-bottom:1px solid var(--line); }
        .card-head i { color:var(--gold); margin-right:8px; } .body { padding:16px; } .table-wrap { overflow:auto; } table { width:100%; border-collapse:collapse; font-size:12px; } th,td { padding:10px 11px; text-align:left; border-bottom:1px solid var(--line); white-space:nowrap; } th { color:var(--gold); background:var(--panel-2); font-size:11px; } td { color:#f7f9ff; }
        form label { display:grid; gap:6px; margin-bottom:11px; color:var(--muted); font-size:12px; } input,select,textarea { width:100%; min-height:39px; border:1px solid #466387; border-radius:7px; padding:8px 10px; background:#0d2547; color:#fff; font:inherit; } textarea { min-height:66px; resize:vertical; } .form-row { display:grid; grid-template-columns:1fr 1fr; gap:11px; }
        button { min-height:39px; border:0; border-radius:7px; padding:0 14px; background:var(--gold); color:#10284b; font-weight:800; cursor:pointer; } .small-btn { min-height:31px; padding:0 9px; font-size:11px; } .muted-btn { background:#385777; color:#fff; } .inline-form { display:flex; align-items:center; gap:7px; } .inline-form input,.inline-form select { min-height:31px; padding:5px 7px; }
        .badge { display:inline-flex; padding:4px 8px; border-radius:999px; background:var(--gold); color:#11294c; font-size:10px; font-weight:800; } .badge.muted { background:#496786; color:#fff; } .badge.alert { background:#d78167; color:#fff; } .empty { padding:20px; text-align:center; color:var(--muted); }
        details { border-top:1px solid var(--line); padding:10px 0 0; } summary { cursor:pointer; color:var(--gold); font-size:12px; } .request-detail { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-top:11px; padding:12px; border-radius:7px; background:#0d2547; } .request-detail form { margin:0; } .request-detail .full { grid-column:1/-1; }
        .section-note { margin:-4px 0 13px; color:var(--muted); font-size:12px; } .money { color:#f7d676; font-weight:700; } .nowrap { white-space:nowrap; }
        @media(max-width:1050px){ .grid,.grid-wide { grid-template-columns:1fr; } .kpis { grid-template-columns:repeat(2,1fr); } } @media(max-width:600px){ .wrap{padding:18px 12px 30px;} .topbar{align-items:flex-start; flex-direction:column;} .kpis{grid-template-columns:1fr 1fr;} .form-row,.request-detail{grid-template-columns:1fr;} .request-detail .full{grid-column:auto;} }
    </style>
<style>
        html { scrollbar-width: thin; scrollbar-color: var(--gold) var(--canvas); scroll-behavior: smooth; }
        ::-webkit-scrollbar { width: 10px; height: 10px; }
        ::-webkit-scrollbar-track { background: var(--canvas); }
        ::-webkit-scrollbar-thumb { background: var(--gold); border: 2px solid var(--canvas); border-radius: 999px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--gold); }
        ::-webkit-scrollbar-corner { background: var(--canvas); }
        .table-wrap { scrollbar-width: thin; scrollbar-color: var(--gold) var(--canvas); }
    </style>
</head>
<body>
<main class="wrap">
    <header class="topbar">
        <div><h1><i class="fa-solid fa-flask-vial" style="color:var(--gold)"></i> Laboratory Operations</h1><p class="subtitle">Catalogue, request queue, staff accountability, authorized reports and billing control.</p></div>
        <a class="back" href="{{ url('/') }}"><i class="fa-solid fa-arrow-left"></i> Dashboard</a>
    </header>

    @if(session('status')) <div class="banner"><i class="fa-solid fa-circle-check"></i> {{ session('status') }}</div> @endif
    @if($errors->any()) <div class="banner error"><i class="fa-solid fa-triangle-exclamation"></i> {{ $errors->first() }}</div> @endif

    <section class="kpis">
        <div class="kpi"><small>Active tests</small><strong>{{ $kpis['tests'] }}</strong></div>
        <div class="kpi"><small>Requests today</small><strong>{{ $kpis['requests_today'] }}</strong></div>
        <div class="kpi"><small>Reports awaiting action</small><strong>{{ $kpis['pending_reports'] }}</strong></div>
        <div class="kpi"><small>Outstanding billing</small><strong>Rs {{ number_format($kpis['outstanding'], 0) }}</strong></div>
    </section>

    <section class="grid">
        <article class="card"><div class="card-head"><h2><i class="fa-solid fa-list-check"></i>Test catalogue &amp; pricing</h2></div><div class="body">
            <p class="section-note">Maintain the approved test list, specimen and turnaround time used for new requests.</p>
            <form method="POST" action="{{ route('laboratory.catalogue.store') }}">@csrf
                <div class="form-row"><label>Test code<input name="code" placeholder="CBC-001" required></label><label>Test name<input name="name" placeholder="Complete Blood Count" required></label></div>
                <div class="form-row"><label>Category<input name="category" placeholder="Hematology" required></label><label>Specimen<input name="specimen" placeholder="EDTA blood"></label></div>
                <div class="form-row"><label>Turnaround (hours)<input type="number" name="turnaround_hours" min="1" value="24" required></label><label>Price (PKR)<input type="number" name="price" min="0" step="0.01" required></label></div>
                <button type="submit"><i class="fa-solid fa-plus"></i> Add test</button>
            </form>
        </div><div class="table-wrap"><table><thead><tr><th>Code</th><th>Test</th><th>Specimen / TAT</th><th>Price</th><th>Update</th></tr></thead><tbody>
            @forelse($tests as $test)<tr><td>{{ $test->code }}</td><td>{{ $test->name }}<br><span class="badge {{ $test->is_active ? '' : 'muted' }}">{{ $test->is_active ? 'Active' : 'Paused' }}</span></td><td>{{ $test->specimen ?: '—' }} / {{ $test->turnaround_hours }}h</td><td class="money">Rs {{ number_format($test->price, 0) }}</td><td><form class="inline-form" method="POST" action="{{ route('laboratory.catalogue.update', $test) }}">@csrf @method('PATCH')<input type="number" name="price" min="0" step="0.01" value="{{ $test->price }}" aria-label="Price"><select name="is_active"><option value="1" @selected($test->is_active)>Active</option><option value="0" @selected(!$test->is_active)>Paused</option></select><button class="small-btn" type="submit">Save</button></form></td></tr>@empty<tr><td colspan="5" class="empty">No catalogue tests yet.</td></tr>@endforelse
        </tbody></table></div></article>

        <article class="card"><div class="card-head"><h2><i class="fa-solid fa-file-circle-plus"></i>Monitor test request</h2></div><div class="body">
            <p class="section-note">Create a request from the catalogue. A bill is generated automatically at the current catalogue price.</p>
            <form method="POST" action="{{ route('laboratory.requests.store') }}">@csrf
                <label>Patient<select name="patient_id" required><option value="">Select patient</option>@foreach($patients as $patient)<option value="{{ $patient->id }}">{{ $patient->patient_code }} · {{ $patient->name }}</option>@endforeach</select></label>
                <div class="form-row"><label>Requesting doctor<select name="doctor_id" required><option value="">Select doctor</option>@foreach($doctors as $doctor)<option value="{{ $doctor->id }}">{{ $doctor->name }} · {{ $doctor->specialty }}</option>@endforeach</select></label><label>Priority<select name="priority" required><option>Routine</option><option>Urgent</option><option>Stat</option></select></label></div>
                <label>Test<select name="lab_test_id" required><option value="">Select catalogue test</option>@foreach($tests->where('is_active', true) as $test)<option value="{{ $test->id }}">{{ $test->code }} · {{ $test->name }} (Rs {{ number_format($test->price, 0) }})</option>@endforeach</select></label>
                <label>Clinical notes<textarea name="clinical_notes" placeholder="Relevant clinical information or collection notes"></textarea></label><button type="submit"><i class="fa-solid fa-paper-plane"></i> Create request &amp; bill</button>
            </form>
        </div></article>
    </section>

    <section class="grid-wide">
        <article class="card"><div class="card-head"><h2><i class="fa-solid fa-bars-progress"></i>Request queue, assignment &amp; report workflow</h2></div><div class="table-wrap"><table><thead><tr><th>Request</th><th>Patient / Test</th><th>Priority</th><th>Status</th><th>Assigned staff</th><th>Report</th></tr></thead><tbody>
            @forelse($requests as $request)<tr><td>{{ $request->request_number }}<br><small>{{ $request->created_at->format('d M, h:i A') }}</small></td><td>{{ $request->patient->name ?? '—' }}<br><small>{{ $request->test->name ?? $request->test_name }}</small></td><td><span class="badge {{ $request->priority !== 'Routine' ? 'alert' : '' }}">{{ $request->priority }}</span></td><td><span class="badge {{ $request->status === 'Ready' ? '' : 'muted' }}">{{ $request->status }}</span></td><td>{{ $request->assignedStaff->name ?? 'Unassigned' }}</td><td><span class="badge {{ $request->report && $request->report->status === 'Verified' ? '' : 'muted' }}">{{ $request->report->status ?? 'Not entered' }}</span></td></tr>
                <tr><td colspan="6"><details><summary>Manage {{ $request->request_number }}</summary><div class="request-detail">
                    <form method="POST" action="{{ route('laboratory.requests.assign', $request) }}">@csrf @method('PATCH')<h3>Assign bench staff</h3><label>Staff<select name="assigned_staff_id" required><option value="">Select staff</option>@foreach($staff as $member)<option value="{{ $member->id }}" @selected($request->assigned_staff_id === $member->id)>{{ $member->name }} · {{ $member->labAssignments->first()->responsibility ?? $member->role }}</option>@endforeach</select></label><label>Status<select name="status"><option @selected($request->status === 'Collected')>Collected</option><option @selected($request->status === 'Processing')>Processing</option><option @selected($request->status === 'Ready')>Ready</option></select></label><button class="small-btn" type="submit">Update assignment</button></form>
                    <form method="POST" action="{{ route('laboratory.reports.store', $request) }}">@csrf<h3>Enter / revise report</h3><label>Prepared by<select name="prepared_by" required><option value="">Select technologist</option>@foreach($staff as $member)<option value="{{ $member->id }}" @selected($request->report && $request->report->prepared_by === $member->id)>{{ $member->name }}</option>@endforeach</select></label><div class="form-row"><label>Result value<input name="result_value" value="{{ $request->report->result_value ?? '' }}" placeholder="e.g. 12.4 g/dL" required></label><label>Reference range<input name="reference_range" value="{{ $request->report->reference_range ?? '' }}" placeholder="e.g. 12–16 g/dL"></label></div><label>Findings<textarea name="findings" required>{{ $request->report->findings ?? '' }}</textarea></label><label>Remarks<textarea name="remarks">{{ $request->report->remarks ?? '' }}</textarea></label><button class="small-btn" type="submit">Save draft report</button></form>
                    @if($request->report && $request->report->status !== 'Verified')<form class="full" method="POST" action="{{ route('laboratory.reports.verify', $request->report) }}">@csrf @method('PATCH')<h3>Authorized verification</h3><div class="inline-form"><select name="verified_by" required><option value="">Select authorized verifier</option>@foreach($staff as $member)<option value="{{ $member->id }}">{{ $member->name }} · {{ $member->role }}</option>@endforeach</select><button class="small-btn" type="submit">Verify report</button></div></form>@endif
                </div></details></td></tr>
            @empty<tr><td colspan="6" class="empty">No laboratory requests yet.</td></tr>@endforelse
        </tbody></table></div></article>

        <article class="card"><div class="card-head"><h2><i class="fa-solid fa-user-gear"></i>Lab staff &amp; responsibilities</h2></div><div class="body"><p class="section-note">Create staff records with a clear bench responsibility and shift.</p><form method="POST" action="{{ route('laboratory.staff.store') }}">@csrf<div class="form-row"><label>Name<input name="name" placeholder="Sana Malik" required></label><label>Role<input name="role" placeholder="Senior Technologist" required></label></div><div class="form-row"><label>Shift<select name="shift"><option>Morning</option><option>Evening</option><option>Night</option></select></label><label>Bench / section<input name="bench" placeholder="Hematology bench"></label></div><label>Responsibility<input name="responsibility" placeholder="CBC processing and quality control" required></label><button type="submit"><i class="fa-solid fa-user-plus"></i> Add staff assignment</button></form></div><div class="table-wrap"><table><thead><tr><th>Staff</th><th>Shift</th><th>Responsibility</th><th>Status</th></tr></thead><tbody>@forelse($staff as $member)<tr><td>{{ $member->name }}<br><small>{{ $member->role }}</small></td><td>{{ $member->shift }}</td><td>{{ $member->labAssignments->first()->responsibility ?? '—' }}<br><small>{{ $member->labAssignments->first()->bench ?? '' }}</small></td><td><span class="badge">{{ $member->status }}</span></td></tr>@empty<tr><td colspan="4" class="empty">No laboratory staff assigned.</td></tr>@endforelse</tbody></table></div></article>
    </section>

    <section class="card"><div class="card-head"><h2><i class="fa-solid fa-receipt"></i>Laboratory billing &amp; payment tracking</h2><span class="section-note">Record partial payments until each bill is fully settled.</span></div><div class="table-wrap"><table><thead><tr><th>Bill</th><th>Patient / Request</th><th>Total</th><th>Paid</th><th>Balance</th><th>Status</th><th>Record payment</th></tr></thead><tbody>@forelse($bills as $bill)<tr><td>{{ $bill->bill_number }}</td><td>{{ $bill->patient->name ?? '—' }}<br><small>{{ $bill->request->request_number ?? '—' }}</small></td><td class="money">Rs {{ number_format($bill->net_amount, 0) }}</td><td>Rs {{ number_format($bill->paid_amount, 0) }}</td><td class="money">Rs {{ number_format($bill->net_amount - $bill->paid_amount, 0) }}</td><td><span class="badge {{ $bill->status === 'Paid' ? '' : 'muted' }}">{{ $bill->status }}</span></td><td>@if($bill->status !== 'Paid')<form class="inline-form" method="POST" action="{{ route('laboratory.payments.store', $bill) }}">@csrf<input type="number" name="amount" min="0.01" max="{{ $bill->net_amount - $bill->paid_amount }}" step="0.01" placeholder="Amount" required><select name="payment_method"><option>Cash</option><option>Card</option><option>Insurance</option><option>Online</option></select><input name="received_by" placeholder="Received by" required><button class="small-btn" type="submit">Post</button></form>@else<span class="badge">Settled</span>@endif</td></tr>@empty<tr><td colspan="7" class="empty">No laboratory bills yet.</td></tr>@endforelse</tbody></table></div></section>
</main>
</body>
</html>