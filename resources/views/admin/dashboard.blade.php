@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="content">
        <p class="lead">Welcome back, {{ auth()->user()->name }}.</p>

        <h2 class="sec">Enquiries</h2>
        <div class="kpi">
            <div class="card"><small>New enquiries</small><b>{{ number_format($summary['new_enquiries']) }}</b></div>
            <div class="card"><small>Total enquiries</small><b>{{ number_format($summary['total_enquiries']) }}</b></div>
            <div class="card"><small>Customers</small><b>{{ number_format($summary['customers']) }}</b></div>
            <div class="card"><small>Active products</small><b>{{ number_format($summary['active_products']) }}</b></div>
        </div>

        <h2 class="sec">Catalogue</h2>
        <div class="grid">
            <div class="card"><small>Categories</small><b>{{ number_format($summary['categories']) }}</b></div>
            <div class="card"><small>Brands</small><b>{{ number_format($summary['brands']) }}</b></div>
            <div class="card"><small>Advance order products</small><b>{{ number_format($summary['advance_order_products']) }}</b></div>
            <div class="card"><small>Unavailable products</small><b>{{ number_format($summary['unavailable_products']) }}</b></div>
        </div>

        <h2 class="sec">Recent enquiries</h2>
        <div class="card">
            @if ($summary['recent_enquiries']->isEmpty())
                <div class="empty">
                    <div class="empty-ic" aria-hidden="true"><i class="bi bi-chat-left-text"></i></div>
                    <h3>No enquiries yet</h3>
                    <p>New enquiries from the website and WhatsApp will appear here.</p>
                </div>
            @else
                <table>
                    <tr>
                        <th scope="col">Reference</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Channel</th>
                        <th scope="col">Status</th>
                        <th scope="col">Received</th>
                    </tr>
                    @foreach ($summary['recent_enquiries'] as $enquiry)
                        <tr>
                            <td data-label="Reference"><a href="{{ route('admin.enquiries.show', $enquiry) }}"><code>{{ $enquiry->reference }}</code></a></td>
                            <td data-label="Customer">{{ $enquiry->customer->business_name ?: $enquiry->customer->contact_name }}</td>
                            <td data-label="Channel">{{ $enquiry->channel->label() }}</td>
                            <td data-label="Status"><x-admin.status-pill :status="$enquiry->status" /></td>
                            <td data-label="Received" title="{{ $enquiry->created_at->format('d M Y, H:i') }}">{{ $enquiry->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </table>
            @endif
        </div>
    </div>
@endsection
