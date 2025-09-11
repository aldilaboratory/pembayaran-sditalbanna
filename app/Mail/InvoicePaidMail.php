<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoicePaidMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Transaction $transaction,
        public string $pdfBinary // isi: $pdf->output())
    )
    {
        //
    }

    public function build()
    {
        $t = $this->transaction;

        return $this->subject('Invoice '.$t->invoice_code.' - SDIT Albanna')
            ->markdown('emails.invoice_paid', ['t' => $t])
            ->attachData(
                $this->pdfBinary,
                'Invoice-'.$t->invoice_code.'.pdf',
                ['mime' => 'application/pdf']
            );
    }

    /**
     * Get the message envelope.
     */
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'Invoice Paid Mail',
    //     );
    // }

    // /**
    //  * Get the message content definition.
    //  */
    // public function content(): Content
    // {
    //     return new Content(
    //         markdown: 'emails.invoice_paid',
    //     );
    // }

    // /**
    //  * Get the attachments for the message.
    //  *
    //  * @return array<int, \Illuminate\Mail\Mailables\Attachment>
    //  */
    // public function attachments(): array
    // {
    //     return [];
    // }
}
