@extends('layouts.admin')

@section('title')
    Off-Store Sales
@endsection

@section('content')
<div class="section-content section-dashboard-home" data-aos="fade-up">
    <div class="container-fluid">
        <div class="dashboard-heading">
            <h2 class="dashboard-title">Off-Store Sales</h2>
            <p class="dashboard-subtitle">Record offline store sales</p>
        </div>
        <div class="dashboard-content">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="products_id">Product</label>
                                    <select name="products_id" id="products_id" class="form-control" required>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }} (Stock: {{ $product->stock }}, Price: {{ $product->price }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="quantity">Quantity Sold</label>
                                    <input type="number" name="quantity" id="quantity" class="form-control" required min="1">
                                </div>
                                <button type="submit" class="btn btn-primary">Record Sale</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection