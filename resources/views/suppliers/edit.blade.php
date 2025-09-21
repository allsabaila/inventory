@extends('layouts.app')

@section('title', 'Edit Supplier')

@section('content')
    <h3>Edit Supplier</h3>
    <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Supplier Name</label>
            <input type="text" name="name" value="{{ $supplier->name }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" value="{{ $supplier->phone }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="{{ $supplier->email }}" class="form-control">
        </div>
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Back</a>
    </form>
@endsection
