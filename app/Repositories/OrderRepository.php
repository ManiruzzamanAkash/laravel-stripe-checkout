<?php

namespace App\Repositories;

use App\Exceptions\InvalidProductJson;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderRepository
{
    private array $products;
    private ?Order $order;

    public const ORDER_DUE = 'due';
    public const ORDER_PAID = 'paid';
    public const ORDER_CANCELLED = 'cancel';
    public const ORDER_PARTIALLY_PAID = 'partially_paid';

    public function __construct()
    {
        $this->products = [];
        $this->order = null;
    }

    public function setOrderProducts(string $productsJsonEncoded)
    {
        try {
            $productsEncoded = json_decode($productsJsonEncoded);
            $products = [];
            foreach ($productsEncoded as $product) {
                $cartProduct = Product::find($product->id);
                $cartProduct->quantity = $product->quantity ?? 1;
                $products[] = $cartProduct;
            }

            $this->products = $products;
            return $this;
        } catch (InvalidProductJson $exception) {
            throw $exception;
        }
    }

    public function getOrderProducts(): array
    {
        return $this->products;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(int $orderId): self
    {
        $this->order = Order::find($orderId);

        return $this;
    }


    public function getOrderProductsWithQty()
    {
        try {
            $products = [];
            foreach ($this->products as $product) {
                $cartProduct = Product::find($product->id);
                $cartProduct->quantity = $product->quantity ?? 1;
                $products[] = $cartProduct;
            }

            return $products;
        } catch (InvalidProductJson $exception) {
            throw $exception;
        }
    }

    public function getOrderTotalAmount(): float
    {
        $total = 0;
        foreach ($this->products as $product) {
            $total += floatval($product->price) * intval($product->quantity);
        }
        return $total;
    }

    public function getTotalInCents(): float
    {
        $productTotal = $this->getOrderTotalAmount();

        // TODO: In case someone could handle with Delivery charge.
        $deliveryCharge = 0;

        // TODO: In case someone could handle with Tax charge.
        $taxCharge = 0;

        // TODO: In case someone could handle with discount or coupon.
        $discount = 0;

        $total = $productTotal + $deliveryCharge + $taxCharge - $discount;

        return $total * 100;
    }

    public function getPreparedOrderData($orderData): array
    {
        $orderData['user_id'] = Auth::check() ? Auth::id() : 0;
        $orderData['total'] = $this->getTotalInCents();
        $orderData['status'] = self::ORDER_DUE;

        return $orderData;
    }

    public function getPreparedOrderItemsData(): array
    {
        $orderItems = [];
        foreach ($this->products as $product) {
            $orderItems[] = [
                'order_id' => $this->order->id,
                'product_id' => $product->id,
                'quantity' => $product->quantity,
            ];
        }

        return $orderItems;
    }

    public function createOrder(array $orderData): self
    {
        $this->order = Order::create($this->getPreparedOrderData($orderData));

        OrderItem::insert($this->getPreparedOrderItemsData($this->order->id));

        return $this;
    }

    public function updateSessionId(string $sessionid): self
    {
        $this->order->update([
            'session_id' => $sessionid
        ]);

        return $this;
    }

    public function changeOrderStatus(string $status): self
    {
        $this->order->update([
            'status' => $status
        ]);

        return $this;
    }
}
