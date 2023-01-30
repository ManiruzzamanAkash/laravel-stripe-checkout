<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Repositories\StripePaymentRepository;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function __construct(
        private OrderRepository $orderRepository,
        private StripePaymentRepository $stripePaymentRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->stripePaymentRepository = $stripePaymentRepository;
    }

    public function index(): Renderable
    {
        return view('checkout.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string'
        ]);

        $this->orderRepository->setOrderProducts($request->products);

        try {
            $this->orderRepository->createOrder([
                'products' => $this->orderRepository->getOrderProducts(),
                'payment_method' => $request->payment_method
            ]);

            $order = $this->orderRepository->getOrder();
            if (!$order instanceof Order) {
                throw new Exception('Order does not created successfully !', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $redirectedUrl = route('checkout.success');
            switch ($request->payment_method) {
                case 'online':
                    $redirectedUrl = $this->stripePaymentRepository
                        ->setOrder($order)
                        ->setItems()
                        ->pay()
                        ->getRedirectedUrl();

                    $this->orderRepository->updateSessionId(
                        $this->stripePaymentRepository->getSession()->id
                    );
                    break;

                default:
                    break;
            }

            return response()->json([
                'redirect_url' => $redirectedUrl
            ]);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function success(Request $request)
    {
        $request->validate([
            'order_id' => 'required|numeric'
        ]);

        $order = $this->orderRepository
            ->setOrder(intval($request->order_id))
            ->getOrder();

        if (!$order) {
            abort(Response::HTTP_NOT_FOUND, 'Sorry, your order does not exist !');
        }

        try {
            if ($order->payment_method === 'online' && $order->session_id === $request->session_id) {
                $stripeSession = $this->stripePaymentRepository->stripe->checkout->sessions->retrieve($request->session_id);
                Log::debug($stripeSession);
                Log::debug($order);
                if (floatval($stripeSession->amount_total) === floatval($order->total)) {
                    $this->orderRepository->changeOrderStatus(OrderRepository::ORDER_PAID);
                }
            }
        } catch (\Stripe\Exception\ApiErrorException $exception) {
            abort(Response::HTTP_BAD_REQUEST, 'Invalid session or session has expired.');
        }

        return view('checkout.success', [
            'order' => $order
        ]);
    }
}
