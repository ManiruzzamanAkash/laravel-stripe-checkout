<?php

namespace App\Http\Controllers;

use App\Repositories\OrderRepository;
use App\Repositories\StripePaymentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function __construct(
        private OrderRepository $orderRepository,
        private StripePaymentRepository $stripePaymentRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->stripePaymentRepository = $stripePaymentRepository;
    }

    public function createClientSecret(Request $request): JsonResponse
    {
        $paymentIntent = $this->stripePaymentRepository->createPaymentIntent(
            $this->orderRepository->getTotalInCents($request->products)
        );

        return response()->json([
            'clientSecret' => $paymentIntent->client_secret,
        ]);
    }
}
