@extends('layouts.app')

@section('title', 'Equipment reports')

@section('content')
    <div class="page-heading">
        <div><h1>Equipment reports</h1><p class="subtitle">Inventory reports reflect the latest saved equipment records. Prices are in Philippine pesos (PHP).</p></div>
        <div class="actions">
            <a class="btn btn-secondary" href="{{ route('reports.pdf', array_filter($filters)) }}">Download PDF</a>
            <a class="btn" href="{{ route('reports.excel', array_filter($filters)) }}">Export to Excel</a>
        </div>
    </div>

    <div class="stats">
        <a class="stat" href="{{ route('reports.index', ['availability_status' => 'available']) }}">
            <div class="stat-label">Available equipment</div><div class="stat-value">{{ $availableCount }}</div>
        </a>
        <a class="stat" href="{{ route('reports.index', ['availability_status' => 'unavailable']) }}">
            <div class="stat-label">Unavailable equipment</div><div class="stat-value">{{ $unavailableCount }}</div>
        </a>
    </div>

    <section class="card">
        <h2>Filter reports</h2>
        <form method="GET" action="{{ route('reports.index') }}" class="filter-grid">
            <div class="field">
                <label for="category">Category</label>
                <select id="category" name="category">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" @selected(($filters['category'] ?? null) === $category)>{{ ucfirst($category) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="availability_status">Availability</label>
                <select id="availability_status" name="availability_status">
                    <option value="">All statuses</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(($filters['availability_status'] ?? null) === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="actions">
                <button class="btn" type="submit">Apply filters</button>
                <a class="btn btn-secondary" href="{{ route('reports.index') }}">Reset</a>
            </div>
        </form>
    </section>

    <section class="card">
        <h2>Report views</h2>
        <p class="subtitle" style="margin:-8px 0 14px">Choose a saved inventory view; filters can also be combined above.</p>
        <div class="report-links">
            <a class="btn btn-secondary" href="{{ route('reports.index', ['availability_status' => 'available']) }}">Available equipment</a>
            <a class="btn btn-secondary" href="{{ route('reports.index', ['availability_status' => 'unavailable']) }}">Unavailable equipment</a>
            @foreach ($categories as $category)
                <a class="btn btn-secondary" href="{{ route('reports.index', ['category' => $category]) }}">{{ ucfirst($category) }}</a>
            @endforeach
            <a class="btn btn-secondary" href="{{ route('reports.index') }}">Equipment price list</a>
        </div>
    </section>

    <section class="card">
        <div class="page-heading" style="margin-bottom:14px">
            <div><h2 style="margin-bottom:4px">Equipment price list</h2><p class="subtitle">{{ $equipment->total() }} matching records</p></div>
        </div>
        @if ($equipment->isEmpty())
            <div class="alert alert-error" role="status" style="margin:0">No equipment matches the selected filters.</div>
        @else
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Equipment name</th><th>Category</th><th>Unit price (PHP)</th><th>Availability status</th></tr></thead>
                    <tbody>
                    @foreach ($equipment as $item)
                        <tr>
                            <td>{{ $item->equipment_name }}</td>
                            <td style="text-transform:capitalize">{{ $item->category }}</td>
                            <td>₱{{ number_format((float) $item->unit_price, 2) }}</td>
                            <td><span class="badge badge-{{ $item->availability_status }}">{{ $item->availability_status }}</span></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination">{{ $equipment->links() }}</div>
        @endif
    </section>
@endsection
