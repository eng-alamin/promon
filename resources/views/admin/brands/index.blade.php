@extends('layouts.admin')

@section('title', 'Brands')

@section('content')
    <div class="content">
        @include('admin.partials.catalogue-tabs')

        <livewire:admin.brands.brand-table />
    </div>
@endsection
