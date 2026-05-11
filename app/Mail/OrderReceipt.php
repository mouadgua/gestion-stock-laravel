<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderReceipt extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
        $this->order->load('items.product', 'user');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre reçu de commande #' . $this->order->id_commande . ' — The Vault',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-receipt',
        );
    }
}
