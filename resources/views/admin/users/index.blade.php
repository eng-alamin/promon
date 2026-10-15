@extends('layouts.admin')

@section('title', 'Users')

@section('content')
    <div class="content">
        <livewire:admin.users.user-table />
    </div>
@endsection
