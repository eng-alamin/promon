@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
    <div class="content">
        @include('admin.partials.catalogue-tabs')

        <livewire:admin.categories.category-table />
    </div>
@endsection
