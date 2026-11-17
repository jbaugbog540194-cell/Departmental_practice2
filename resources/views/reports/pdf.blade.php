<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <style>
        body{font-family:DejaVu Sans,sans-serif;color:#26372e;font-size:11px}
        h1{font-size:22px;margin:0 0 6px;color:#174e38}
        p{color:#637067;margin:0 0 18px}
        table{width:100%;border-collapse:collapse}
        th{background:#e9f1eb;text-align:left;color:#294a37}
        th,td{border:1px solid #d7e0d8;padding:8px}
        .empty{text-align:center;color:#68756b;padding:24px}
    </style>
</head>
<body>
    <h1>Pickleball equipment report</h1>
    <p>
        Prices are in Philippine pesos (PHP). Category: {{ ucfirst($filters['category'] ?? 'All') }} ·
        Availability: {{ ucfirst($filters['availability_status'] ?? 'All') }} ·
        Generated {{ now()->format('Y-m-d H:i') }}
    </p>
    <table>
        <thead><tr><th>Equipment name</th><th>Category</th><th>Unit price (PHP)</th><th>Availability status</th></tr></thead>
        <tbody>
        @forelse ($equipment as $item)
            <tr>
                <td>{{ $item->equipment_name }}</td>
                <td>{{ ucfirst($item->category) }}</td>
                <td>₱{{ number_format((float) $item->unit_price, 2) }}</td>
                <td>{{ ucfirst($item->availability_status) }}</td>
            </tr>
        @empty
            <tr><td class="empty" colspan="4">No equipment matches the selected filters.</td></tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>
