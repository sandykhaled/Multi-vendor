@extends('layouts.dashboard')
@section('title',$category->name)
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Categories</li>
    <li class="breadcrumb-item active">Show {{$category->name}}</li>

@endsection
@section('content')
    <table class="table">
        <thead>
        <tr>
            <th></th>
            <th>Name</th>
            <th>Store</th>
            <th>Status</th>
            <th>Created At</th>
            <th colspan="2"></th>
        </tr>
        </thead>
        <tbody>
        @php
            $products=$category->products()->with('store')->latest()->paginate(5)
        @endphp
        @forelse($products as $product)
            <tr>
                <td><img src="{{asset('storage/'.$product->image)}}" alt="" height="50"></td>
                <td>{{$product->name}}</td>
                <td>{{$product->store->name}}</td>
                <td>{{$product->status}}</td>
                <td>{{$product->created_at}}</td>
                <td>
                    <a href="{{route('dashboard.products.edit',$product->id)}}" class="btn btn-sm btn-outline-success">Edit</a>
                </td>
                <td>
                    <form action="{{route('dashboard.products.destroy',$product->id)}}" method="post">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7">No products defined. </td>
            </tr>
        @endforelse
        </tbody>
    </table>
    {{$products->links()}}
@endsection

