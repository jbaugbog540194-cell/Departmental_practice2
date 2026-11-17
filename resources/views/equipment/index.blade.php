@extends('layouts.app')

@section('title', 'Equipment')

@section('content')
    <div class="page-heading">
        <div>
            <h1>Equipment inventory</h1>
            <p class="subtitle">Manage paddles, balls, nets, and accessories.</p>
        </div>
        <a class="btn" href="{{ route('equipment.create') }}">＋ Add equipment</a>
    </div>

    <section class="card">
        @if ($equipment->isEmpty())
            <p class="muted" style="margin:0">No equipment has been added yet. Add a record to start your inventory.</p>
        @else
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Equipment</th><th>Category</th><th>Unit price (PHP)</th><th>Availability</th><th>Actions</th></tr></thead>
                    <tbody>
                    @foreach ($equipment as $item)
                        <tr>
                            <td><strong>{{ $item->equipment_name }}</strong></td>
                            <td style="text-transform:capitalize">{{ $item->category }}</td>
                            <td>₱{{ number_format((float) $item->unit_price, 2) }}</td>
                            <td><span class="badge badge-{{ $item->availability_status }}">{{ $item->availability_status }}</span></td>
                            <td>
                                <div class="row-actions">
                                    <a href="{{ route('equipment.show', $item) }}">View</a>
                                    <a href="{{ route('equipment.edit', $item) }}">Edit</a>
                                    <form method="POST" action="{{ route('equipment.destroy', $item) }}" onsubmit="return confirm('Delete this equipment record? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger" type="submit" style="padding:5px 9px;font-size:12px">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination">{{ $equipment->links() }}</div>
        @endif
    </section>
@endsection
