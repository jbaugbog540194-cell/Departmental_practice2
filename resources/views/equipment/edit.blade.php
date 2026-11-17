@extends('layouts.app')

@section('title', 'Edit equipment')

@section('content')
    <div class="page-heading">
        <div><h1>Edit equipment</h1><p class="subtitle">Update {{ $equipment->equipment_name }}.</p></div>
        <a class="btn btn-secondary" href="{{ route('equipment.show', $equipment) }}">Cancel</a>
    </div>
    <section class="card">
        @include('equipment._form', ['action' => route('equipment.update', $equipment), 'method' => 'PUT'])
    </section>
@endsection
