@extends('layouts.admin')

@section('title', 'Enquiry ' . $enquiry->reference)

@section('content')
    <div class="content">
        <p><a class="btn" href="{{ route('admin.enquiries.index') }}"><i class="bi bi-arrow-left" aria-hidden="true"></i> Back to enquiries</a></p>

        <livewire:admin.enquiries.enquiry-show :enquiry="$enquiry" />
    </div>
@endsection
