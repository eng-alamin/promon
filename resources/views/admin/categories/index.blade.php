@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
    <div class="content">
        <livewire:admin.categories.category-table />
    </div>
@endsection
