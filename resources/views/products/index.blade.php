@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Products</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Create Product</a>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->price }}</td>
                <td>
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">Update</a>
                    <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
