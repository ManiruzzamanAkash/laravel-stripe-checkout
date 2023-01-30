<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800">
                <div class="bg-gray-100">
                    <div class="flex flex-col md:flex-row flex-wrap w-full">
                        <div class="md:basis-3/5">
                            <div id="cart-items"></div>
                        </div>
                        <div class="md:basis-2/5">
                            <div class="shadow-lg bg-white p-4 md:ml-4">
                                <div id="cart-summary"></div>
                            </div>

                            <form method="POST" action="{{ route('checkout.store') }}" id="payment-form"
                                class="shadow-lg bg-white p-4 md:ml-4">
                                @csrf

                                <div>
                                    <h4 class="font-bold">Select payment method</h4>
                                    <div class="flex mt-2">
                                        <label class="basis-2/5">
                                            <input type="radio" name="payment_method" id="payment_method"
                                                value="cash">
                                            Cash In delivery
                                        </label>
                                        <img src="{{ asset('images/default/cash-in.png') }}" alt=""
                                            class="basis-3/5 max-w-[100px]">
                                    </div>
                                    <div class="flex mt-2">
                                        <label class="basis-2/5">
                                            <input type="radio" name="payment_method" id="payment_method"
                                                value="online">
                                            Online Payment
                                        </label>
                                        <img src="{{ asset('images/default/stripe-payments.png') }}" alt=""
                                            class="basis-3/5 max-w-[100px]">
                                    </div>
                                </div>
                                <button type="submit" id="submit-button"
                                    class="bg-slate-600 px-5 py-3 mt-4 rounded text-white"
                                    href="{{ route('checkout') }}">
                                    <span id="order-confirm-text">
                                        Confirm Order
                                    </span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            const checkoutUrl = "{{ route('checkout.store') }}";
            const paymentForm = document.getElementById('payment-form');
            const submitButton = paymentForm.querySelector('button[type="submit"]');

            const submitPayment = () => {
                submitButton.disabled = true;
                if(getCartItems().length === 0) {
                    alert('Please add a product to cart first.');
                    return;
                }

                const formData = new FormData(paymentForm);
                formData.append('products', JSON.stringify(getCartItems()));

                fetch(paymentForm.action, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        clearCartItems();
                        window.location.href=`${data.redirect_url}`;
                        submitButton.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error submitting payment: ', error);
                        submitButton.disabled = false;
                    });
            };

            paymentForm.addEventListener('submit', event => {
                event.preventDefault();
                submitPayment();
            });
        </script>
    @endsection
</x-app-layout>
