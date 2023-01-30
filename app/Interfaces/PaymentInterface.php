<?php

namespace App\Interfaces;

use App\Models\Order;

interface PaymentInterface
{
    public function getOrder(): Order;

    public function setOrder(Order $order): self;

    public function pay();

    public function getItems(): array;

    public function getRedirectedUrl(): string;

    public function setItems(): self;
}
