<?php

namespace App\Jobs;

use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOrderConfirmationEmail implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $order;
    protected $email;

    public function __construct(Order $order, string $email)
    {
        $this->order = $order;
        $this->email = $email;
    }

    public function handle(): void
    {
        Mail::to($this->email)->send(new OrderConfirmationMail($this->order));
    }
}
