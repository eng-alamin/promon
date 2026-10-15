@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
    <div class="content">
        <livewire:admin.customers.customer-table />
    </div>
@endsection
