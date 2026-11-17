@extends('layouts.app')

@section('title', 'Add equipment')

@section('content')
    <div class="page-heading">
        <div><h1>Add equipment</h1><p class="subtitle">Enter the item details for the inventory.</p></div>
        <a class="btn btn-secondary" href="{{ route('equipment.index') }}">Back to inventory</a>
    </div>
    <section class="card">
        @include('equipment._form', ['action' => route('equipment.store'), 'method' => 'POST'])
    </section>
@endsection
