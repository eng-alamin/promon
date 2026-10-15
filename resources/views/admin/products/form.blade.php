@extends('layouts.admin')

@section('title', $product ? 'Edit product' : 'Add product')

@section('content')
    <div class="content">
        @include('admin.partials.catalogue-tabs')

        <p><a class="btn" href="{{ route('admin.products.index') }}"><i class="bi bi-arrow-left" aria-hidden="true"></i> Back to products</a></p>

        <div class="stack">
            <livewire:admin.products.product-form :product="$product" />

            @if ($product)
                <livewire:admin.products.product-images :product="$product" />
                <livewire:admin.products.product-price-tiers :product="$product" />
            @else
                <div class="card">
                    <h2 class="sec">Images and quantity prices</h2>
                    <p class="hint">Save the product first. You can then add images and quantity prices on the next page.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
