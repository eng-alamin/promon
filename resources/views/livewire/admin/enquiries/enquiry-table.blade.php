<div>
    <div class="tools">
        <input class="f" type="search" wire:model.live.debounce.300ms="search" placeholder="Search reference, name, phone or email..." aria-label="Search enquiries">
        <select class="f" wire:model.live="statusFilter" aria-label="Filter by status">
            <option value="">All statuses</option>
            @foreach ($statuses as $status)
                <option value="{{ $status->value }}">{{ $status->label() }}</option>
            @endforeach
        </select>
        <select class="f" wire:model.live="channelFilter" aria-label="Filter by channel">
            <option value="">All channels</option>
            @foreach ($channels as $channel)
                <option value="{{ $channel->value }}">{{ $channel->label() }}</option>
            @endforeach
        </select>
        <a class="btn pri" href="{{ route('admin.enquiries.create') }}"><i class="bi bi-plus-lg" aria-hidden="true"></i> New enquiry</a>
    </div>

    <div class="card">
        @if ($enquiries->isEmpty())
            <div class="empty">
                <div class="empty-ic" aria-hidden="true"><i class="bi bi-chat-left-text"></i></div>
                @if (trim($search) !== '' || $statusFilter !== '' || $channelFilter !== '')
                    <h3>No enquiries found</h3>
                    <p>Nothing matches your search or filters. Try a different one.</p>
                @else
                    <h3>No enquiries yet</h3>
                    <p>Enquiries from the website and WhatsApp will appear here. You can also add one yourself.</p>
                @endif
            </div>
        @else
            <table>
                <tr>
                    <th scope="col">Reference</th>
                    <th scope="col">Customer</th>
                    <th scope="col">Items</th>
                    <th scope="col">Channel</th>
                    <th scope="col">Status</th>
                    <th scope="col">Received</th>
                </tr>
                @foreach ($enquiries as $enquiry)
                    <tr wire:key="enquiry-{{ $enquiry->id }}">
                        <td data-label="Reference"><a href="{{ route('admin.enquiries.show', $enquiry) }}"><code>{{ $enquiry->reference }}</code></a></td>
                        <td data-label="Customer">{{ $enquiry->customer->business_name ?: $enquiry->customer->contact_name }}</td>
                        <td data-label="Items">{{ $enquiry->items_count }}</td>
                        <td data-label="Channel">{{ $enquiry->channel->label() }}</td>
                        <td data-label="Status"><x-admin.status-pill :status="$enquiry->status" /></td>
                        <td data-label="Received" title="{{ $enquiry->created_at->format('d M Y, H:i') }}">{{ $enquiry->created_at->diffForHumans() }}</td>
                    </tr>
                @endforeach
            </table>

            {{ $enquiries->links('livewire.admin.pagination') }}
        @endif
    </div>
</div>
