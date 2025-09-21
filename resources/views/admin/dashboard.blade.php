@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <div class="mb-4 text-center">
        <h1 class="fw-bold">Welcome, {{ auth()->user()->name }}!</h1>
        <p class="text-muted">You are logged in as <strong>{{ ucfirst(auth()->user()->role) }}</strong></p>
    </div>

    <div class="row g-4">
        <div class="col-md-3">
            <div class="card text-center border-primary shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-center">
                    <i class="bi bi-folder-fill fs-1 text-primary mb-2"></i>
                    <h5 class="card-title">Categories</h5>
                    <p class="card-text text-muted">Manage your product categories</p>
                    <a href="{{ route('categories.index') }}" class="btn btn-primary mt-auto">Go</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center border-success shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-center">
                    <i class="bi bi-box-seam fs-1 text-success mb-2"></i>
                    <h5 class="card-title">Items</h5>
                    <p class="card-text text-muted">View and manage your items</p>
                    <a href="{{ route('items.index') }}" class="btn btn-success mt-auto">Go</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center border-warning shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-center">
                    <i class="bi bi-truck fs-1 text-warning mb-2"></i>
                    <h5 class="card-title">Suppliers</h5>
                    <p class="card-text text-muted">Manage supplier information</p>
                    <a href="{{ route('suppliers.index') }}" class="btn btn-warning mt-auto">Go</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center border-info shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-center">
                    <i class="bi bi-receipt fs-1 text-info mb-2"></i>
                    <h5 class="card-title">Transactions</h5>
                    <p class="card-text text-muted">Track all transactions</p>
                    <a href="{{ route('transactions.index') }}" class="btn btn-info mt-auto">Go</a>
                </div>
            </div>
        </div>

        <!-- Tambahan Card User -->
        <div class="col-md-3">
            <div class="card text-center border-dark shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-center">
                    <i class="bi bi-people-fill fs-1 text-dark mb-2"></i>
                    <h5 class="card-title">Users</h5>
                    <p class="card-text text-muted">Manage application users</p>
                    <a href="{{ route('users.index') }}" class="btn btn-dark mt-auto">Go</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
