@extends('layouts.app')

@section('title', $equipment->equipment_name)

@section('content')
    <div class="page-heading">
        <div><h1>{{ $equipment->equipment_name }}</h1><p class="subtitle">Equipment record details.</p></div>
        <div class="actions">
            <a class="btn btn-secondary" href="{{ route('equipment.index') }}">Back to inventory</a>
            <a class="btn" href="{{ route('equipment.edit', $equipment) }}">Edit equipment</a>
        </div>
    </div>
    <section class="card detail-grid">
        <div><div class="detail-label">Category</div><div class="detail-value" style="text-transform:capitalize">{{ $equipment->category }}</div></div>
        <div><div class="detail-label">Unit price (PHP)</div><div class="detail-value">₱{{ number_format((float) $equipment->unit_price, 2) }}</div></div>
        <div><div class="detail-label">Availability</div><div class="detail-value"><span class="badge badge-{{ $equipment->availability_status }}">{{ $equipment->availability_status }}</span></div></div>
        <div><div class="detail-label">Added by</div><div class="detail-value">{{ $equipment->user->name }}</div></div>
        <div class="detail-full"><div class="detail-label">Description and specifications</div><div class="detail-value">{{ $equipment->description }}</div></div>
    </section>
@endsection
