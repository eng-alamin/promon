<x-mail::message>
# New enquiry {{ $enquiry->reference }}

**Business:** {{ $enquiry->customer->business_name }}
**Contact:** {{ $enquiry->customer->contact_name }}
**Email:** {{ $enquiry->customer->email ?? '-' }}
**Phone:** {{ $enquiry->customer->phone ?? '-' }}
**WhatsApp:** {{ $enquiry->customer->whatsapp ?? '-' }}
**Location:** {{ $enquiry->customer->city }}, {{ $enquiry->customer->country }}

**Categories:** {{ $enquiry->categories->pluck('name')->join(', ') ?: '-' }}

**Message:**
{{ $enquiry->message ?? '-' }}

<x-mail::button :url="route('admin.enquiries.show', $enquiry)">
View in admin
</x-mail::button>
</x-mail::message>