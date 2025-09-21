@extends('layouts.app')

@section('title', 'Staff Dashboard')

@section('content')
<div class="container">
    <h1 class="mb-4">Staff Dashboard</h1>

    <div class="card mb-4">
        <div class="card-body">
            <p>Selamat datang, <strong>{{ Auth::user()->name }}</strong>!</p>
            <p>Anda login sebagai <span class="badge bg-info">{{ Auth::user()->role }}</span>.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Menu Staff
        </div>
        <div class="list-group list-group-flush">
            <a href="{{ route('transactions.index') }}" class="list-group-item list-group-item-action">
                Kelola Transaksi
            </a>
        </div>
    </div>
</div>
@endsection
