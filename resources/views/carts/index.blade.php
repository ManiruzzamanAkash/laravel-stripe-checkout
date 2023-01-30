<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Carts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800">
                <div class="bg-gray-100 text-center">
                    <div class="flex flex-col md:flex-row flex-wrap w-full">
                        <div class="md:basis-4/5">
                            <div id="cart-items"></div>
                        </div>
                        <div class="md:basis-1/5">
                            <div class="shadow-lg bg-white p-4 md:ml-4">
                                <div id="cart-summary"></div>
                            </div>
                            <div class="mt-8 text-right">
                                <p>
                                    <a class="bg-slate-600 px-5 py-3 rounded text-white" href="{{ route('checkout') }}">
                                        Checkout
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
    @endsection
</x-app-layout>
