<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="bg-gray-100 text-center">
                    <div class="flex flex-row flex-wrap">
                        @foreach($products as $product)
                            <div class="bg-white max-w-[200px] m-5 py-5 transition shadow hover:shadow-lg border">
                                <img src="{{ $product->image_url }}"
                                     class="w-full transition scale-[95%] hover:scale-100 delay-300"/>
                                <h2 class="pt-4">
                                    {{ $product->name }}
                                </h2>
                                <p>
                                    <span class="text-yellow-500">${{ $product->price }}</span>
                                </p>
                                <p class="mt-3">
                                    <button onclick="addToCart({{ json_encode($product) }})"
                                        class="bg-blue-600 text-white px-4 py-2 rounded-md transition-all hover:shadow-md scale-[95%] hover:scale-100">
                                        Add to cart
                                    </button>
                                </p>
                            </div>
                        @endforeach
                    </div>
                    <div>
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            const cartAddKey = 'cartProducts';

            setCartCount();
            function addToCart(product) {
                product.quantity = 1;
                let cartProducts = localStorage.getItem(cartAddKey);

                if (typeof cartProducts === 'undefined' || cartProducts === null || cartProducts === '') {
                    addSingleProductToCart(product);
                    return;
                }

                if (typeof cartProducts.length !== 'number') {
                    return;
                }

                cartProducts = JSON.parse(cartProducts);
                const cartMatchProducts = cartProducts.filter(prod => {
                    return prod.id == product.id
                });

                if (cartMatchProducts.length > 0) {
                    const items = cartProducts.map(prod => {
                        if (prod.id == product.id) {
                            return {
                                ...prod,
                                quantity: prod.quantity + 1,
                            }
                        }

                        return prod;
                    })
                    localStorage.setItem(cartAddKey, JSON.stringify(items));
                } else {
                    localStorage.setItem(cartAddKey, JSON.stringify(
                        [
                            ...cartProducts,
                            product
                        ]
                    ));
                }
                setCartCount();
            }

            function addSingleProductToCart(product) {
                localStorage.setItem(cartAddKey, JSON.stringify([product]));
                setCartCount()
            }

            function setCartCount() {
                let cartProducts = localStorage.getItem(cartAddKey);
                if (typeof cartProducts === 'undefined' || cartProducts === null || cartProducts === '') {
                    cartCount.innerHTML = "0";
                    return;
                }

                cartProducts = JSON.parse(cartProducts);
                if (cartProducts.length === 0) {
                    cartCount.innerHTML = "0";
                    return;
                }

                let total = 0;
                console.log(cartProducts);
                cartProducts.map(cart => {
                    total += cart.quantity;

                    return cart;
                });
                const cartCount = document.getElementById("cart-count");
                cartCount.innerHTML = "" + total;
            }
        </script>
    @endsection
</x-app-layout>
