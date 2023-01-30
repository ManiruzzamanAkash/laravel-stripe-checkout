<x-app-layout>
    @section('styles')
    @endsection

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Checkout Successful') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white text-center shadow-lg p-4">
                <h3 class="font-bold">Congratulations !!</h3>
                <p class="mb-3">Your order has been taken successfully !</p>
                <hr>
                <h4 class="mt-3">Order ID: {{ $order->id }}</h4>
                <h4>Total amount: <span class="text-red-500">{{ $order->total / 100 }}$</span></h4>

                <h4 class="mt-2">Payment Status:
                    @if ($order->status === 'paid')
                        <span class="bg-green-500 px-4 py-1 text-black font-bold">
                            {{ ucfirst($order->status) }}
                        </span>
                    @endif
                    @if ($order->status === 'due')
                        <span class="bg-red-500 px-4 py-1 text-white font-bold">
                            {{ ucfirst($order->status) }}
                        </span>
                    @endif
                </h4>
            </div>
        </div>
    </div>

    @section('scripts')
    @endsection
</x-app-layout>
