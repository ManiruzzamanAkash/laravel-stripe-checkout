<?php

namespace App\Http\Controllers;

use App\Repositories\OrderRepository;
use App\Repositories\StripePaymentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        private OrderRepository $orderRepository,
        private StripePaymentRepository $stripePaymentRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->stripePaymentRepository = $stripePaymentRepository;
    }

    public function success(Request $request)
    {

    }

    public function cancel(Request $request)
    {

    }
}
