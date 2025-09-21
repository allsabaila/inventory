@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit Transaksi</h3>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="transaction_date">Tanggal Transaksi</label>
            <input type="date" name="transaction_date" id="transaction_date" class="form-control"
                   value="{{ old('transaction_date', $transaction->transaction_date) }}" required>
            @error('transaction_date')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <hr>

        <h5>Detail Barang</h5>

        <div class="form-group mb-3">
            <label for="item_id">Pilih Barang</label>
            <select name="item_id" id="item_id" class="form-control" required>
                <option value="">-- Pilih Barang --</option>
                @foreach ($items as $item)
                    <option value="{{ $item->id }}"
                            {{ old('item_id', $transaction->item_id) == $item->id ? 'selected' : '' }}>
                        {{ $item->name }}
                    </option>
                @endforeach
            </select>
            @error('item_id')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="type">Jenis Transaksi</label>
            <select name="type" id="type" class="form-control" required>
                <option value="masuk" {{ old('type', $transaction->type) == 'masuk' ? 'selected' : '' }}>Masuk</option>
                <option value="keluar" {{ old('type', $transaction->type) == 'keluar' ? 'selected' : '' }}>Keluar</option>
            </select>
            @error('type')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="quantity">Jumlah</label>
            <input type="number" name="quantity" id="quantity" class="form-control"
                   value="{{ old('quantity', $transaction->quantity) }}" min="1" required>
            @error('quantity')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="description">Deskripsi (Opsional)</label>
            <textarea name="description" id="description" class="form-control">{{ old('description', $transaction->description) }}</textarea>
            @error('description')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection