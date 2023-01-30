<?php

namespace App\Abstracts;

use App\Interfaces\PaymentInterface;
use Illuminate\Http\Response;
use App\Models\Order;
use Exception;

abstract class PaymentRepository implements PaymentInterface
{
    protected Order $order;
    protected array $items;

    public function __construct()
    {
        $this->items = [];
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(): self
    {
        throw new Exception("This method setItems() should implement on child method", Response::HTTP_BAD_REQUEST);
    }

    public function pay(): self
    {
        throw new Exception("This method pay() should implement on child method", Response::HTTP_BAD_REQUEST);
    }

    public function getRedirectedUrl(): string
    {
        throw new Exception("This method getRedirectedUrl() should implement on child method", Response::HTTP_BAD_REQUEST);
    }
}
