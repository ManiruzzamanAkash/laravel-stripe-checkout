<?php

namespace App\Repositories;

use App\Abstracts\PaymentRepository;
use Stripe\StripeClient;

class StripePaymentRepository extends PaymentRepository
{
    public StripeClient $stripe;
    private \Stripe\Checkout\Session $session;

    public function __construct()
    {
        $this->stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
    }

    public function setItems(): self
    {
        if (empty($this->order)) {
            return $this;
        }

        foreach ($this->order->items as $orderItem) {
            $this->items[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $orderItem->product->name,
                        'images' => [$orderItem->product->image_url],
                    ],
                    'unit_amount' => $orderItem->product->price * 100,
                ],
                'quantity' => $orderItem->quantity,
            ];
        }

        return $this;
    }

    public function pay(): self
    {
        $this->createSession($this->order);

        return $this;
    }

    public function getRedirectedUrl(): string
    {
        return $this->session->url;
    }

    private function createSession(): self
    {
        $this->session = $this->stripe->checkout->sessions->create([
            'line_items'  => $this->items,
            'metadata'    => [
                'order_id' => $this->order->id
            ],
            'mode'        => 'payment',
            'success_url' => route('checkout.success') . '?order_id=' . $this->order->id . '&session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('checkout.cancel') . '?order_id=' . $this->order->id . '?session_id={CHECKOUT_SESSION_ID}',
        ]);

        return $this;
    }

    public function getSession(): \Stripe\Checkout\Session
    {
        return $this->session;
    }
}
