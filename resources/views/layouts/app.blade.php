<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Court & Paddle') · Pickleball Inventory</title>
    <style>
        :root{font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;color:#1f2937;background:#f4f6f2;font-synthesis:none}
        *{box-sizing:border-box}body{margin:0;min-width:320px}a{color:#176b4d;text-decoration:none}a:hover{text-decoration:underline}
        .topbar{background:#123d32;color:#fff}.nav{max-width:1180px;margin:auto;padding:17px 24px;display:flex;align-items:center;justify-content:space-between;gap:20px}
        .brand{font-weight:750;color:#fff;font-size:17px;letter-spacing:.01em}.nav-links{display:flex;align-items:center;gap:22px}.nav-links a{color:#d9ebe3;font-size:14px}
        .nav form{margin:0}.nav button{background:transparent;color:#d9ebe3;border:0;padding:0;font:inherit;cursor:pointer}
        main{max-width:1180px;margin:36px auto;padding:0 24px 60px}.page-heading{display:flex;align-items:flex-end;justify-content:space-between;gap:20px;margin-bottom:24px}
        h1{font-size:29px;letter-spacing:-.03em;margin:0 0 7px;color:#18332b}h2{font-size:19px;margin:0 0 17px;color:#19392f}
        .muted,.subtitle{color:#6b7280}.subtitle{margin:0;font-size:14px}.card{background:#fff;border:1px solid #e4e9e3;border-radius:12px;padding:22px;box-shadow:0 4px 14px #1a34200b;margin-bottom:20px}
        .btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;background:#176b4d;color:#fff;border:1px solid #176b4d;border-radius:7px;padding:10px 15px;font-size:14px;font-weight:650;cursor:pointer}
        .btn:hover{background:#11573e;color:#fff;text-decoration:none}.btn-secondary{background:#fff;color:#315247;border-color:#d6dfd8}.btn-secondary:hover{background:#f5f8f5;color:#183b2f}
        .btn-danger{background:#fff;color:#b43d3d;border-color:#e8caca}.btn-danger:hover{background:#fff5f5;color:#972d2d}
        .actions{display:flex;align-items:center;gap:9px;flex-wrap:wrap}.alert{padding:13px 16px;border-radius:8px;margin:0 0 18px;font-size:14px}
        .alert-success{background:#e8f5ec;color:#22613b;border:1px solid #c9e6d1}.alert-error{background:#fff0ef;color:#a53a35;border:1px solid #f1d0cd}
        .table-wrap{overflow-x:auto}table{width:100%;border-collapse:collapse;font-size:14px}th{text-align:left;color:#66736a;font-weight:650;font-size:12px;text-transform:uppercase;letter-spacing:.045em;background:#f7f9f6}
        th,td{padding:13px 14px;border-bottom:1px solid #e9eee9;vertical-align:middle}tbody tr:last-child td{border-bottom:0}.row-actions{display:flex;align-items:center;gap:12px;white-space:nowrap}
        .badge{display:inline-flex;padding:4px 9px;border-radius:999px;font-size:12px;font-weight:650;text-transform:capitalize}.badge-available{background:#e7f5eb;color:#287143}.badge-unavailable{background:#f9ecea;color:#a0443c}
        .form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px}.field{display:flex;flex-direction:column;gap:7px}.field-full{grid-column:1/-1}
        label{font-size:13px;font-weight:650;color:#35443b}input,select,textarea{width:100%;border:1px solid #d5ddd6;border-radius:7px;padding:10px 11px;background:#fff;color:#23352b;font:inherit;font-size:14px}
        input:focus,select:focus,textarea:focus{outline:3px solid #176b4d20;border-color:#32815f}textarea{min-height:120px;resize:vertical}.field-error{font-size:12px;color:#a53a35}
        .filter-grid{display:grid;grid-template-columns:1fr 1fr auto;align-items:end;gap:13px}.stats{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:15px;margin-bottom:20px}
        .stat{background:#fff;border:1px solid #e4e9e3;border-radius:11px;padding:18px 20px}.stat-label{color:#69756d;font-size:13px}.stat-value{font-size:27px;font-weight:750;color:#1d4435;margin-top:3px}
        .report-links{display:flex;flex-wrap:wrap;gap:8px}.pagination{margin-top:17px}.pagination nav{display:flex;justify-content:space-between;align-items:center;gap:12px;font-size:13px}
        .detail-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px}.detail-label{font-size:12px;color:#78837b;text-transform:uppercase;letter-spacing:.04em}.detail-value{margin-top:5px;font-size:15px;line-height:1.55}.detail-full{grid-column:1/-1}
        .login-shell{max-width:470px;margin:48px auto}.login-brand{font-size:14px;letter-spacing:.1em;text-transform:uppercase;color:#317155;font-weight:700;margin-bottom:12px}
        @media(max-width:700px){.nav{padding:15px 18px;align-items:flex-start}.nav-links{gap:12px;flex-wrap:wrap;justify-content:flex-end}.nav-links a,.nav button{font-size:13px}main{padding:0 16px 40px;margin-top:26px}.page-heading{align-items:flex-start;flex-direction:column}.filter-grid,.form-grid,.detail-grid,.stats{grid-template-columns:1fr}.field-full,.detail-full{grid-column:auto}.card{padding:17px}}
    </style>
</head>
<body>
    <header class="topbar">
        <nav class="nav" aria-label="Main navigation">
            <a class="brand" href="{{ auth()->check() ? route('equipment.index') : route('login') }}">Court &amp; Paddle</a>
            @auth
                <div class="nav-links">
                    <a href="{{ route('equipment.index') }}">Equipment</a>
                    <a href="{{ route('reports.index') }}">Reports</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Log out</button>
                    </form>
                </div>
            @endauth
        </nav>
    </header>
    <main>
        @if (session('success'))
            <div class="alert alert-success" role="status">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>
</body>
</html>
