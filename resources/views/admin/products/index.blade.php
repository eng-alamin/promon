@extends('layouts.admin')

@section('title', 'Products')

@section('content')
    <div class="content">
        @include('admin.partials.catalogue-tabs')

        <livewire:admin.products.product-table />
    </div>
@endsection
