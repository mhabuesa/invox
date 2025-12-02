<?php

namespace App\Services;

use App\Mail\InvoiceMail;
use Illuminate\Support\Facades\Mail;

class InvoiceMailService
{
    public function send($invoice)
    {
        Mail::to($invoice->client->email)
            ->send(new InvoiceMail($invoice));
    }
}
