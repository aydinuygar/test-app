@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Product</h1>
    <form action="{{ route('products.update', $product) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}" required>
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" class="form-control" id="price" name="price" value="{{ $product->price }}" required>
        </div>
        <button type="submit" class="btn btn-success mt-3">Update</button>
    </form>
</div>
@endsection
