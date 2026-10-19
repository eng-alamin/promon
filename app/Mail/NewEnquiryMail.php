<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Enquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewEnquiryMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Enquiry $enquiry)
    {
        $this->enquiry->loadMissing('customer', 'categories');
    }

    public function envelope(): Envelope
    {
        $customer = $this->enquiry->customer;

        return new Envelope(
            subject: 'New enquiry ' . $this->enquiry->reference . ' - ' . $customer->business_name,
            replyTo: $customer->email
                ? [new Address($customer->email, $customer->contact_name)]
                : [],
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.new-enquiry');
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}