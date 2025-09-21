@extends('layouts.app')

@section('title', 'Edit Item')

@section('content')
    <h3>Edit Item</h3>
    <form action="{{ route('items.update', $item->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Item Name</label>
            <input type="text" name="name" value="{{ $item->name }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Stock</label>
            <input type="number" name="stock" value="{{ $item->stock }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Price</label>
            <input type="number" name="price" value="{{ $item->price }}" step="0.01" class="form-control" required>
        </div>
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('items.index') }}" class="btn btn-secondary">Back</a>
    </form>
@endsection
