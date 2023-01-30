const cartAddKey = 'cartProducts';

initCarts();

function initCarts() {
    setCartCount();
    setCartItems();
    setCartSummary();
}

function findCartByProductId(cartProductId) {
    return getCartItems().find(cartItem => {
        return cartItem.id == cartProductId;
    });
}

function addToCart(product) {
    product.quantity = 1;
    let cartProducts = localStorage.getItem(cartAddKey);

    if (typeof cartProducts === 'undefined' || cartProducts === null || cartProducts === '') {
        setCartInLocalStorage([product]);
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
        setCartInLocalStorage(items);
    } else {
        setCartInLocalStorage([
            ...cartProducts,
            product
        ]);
    }

    initCarts();
}

function clearCartItems() {
    localStorage.removeItem(cartAddKey);
}

function setCartInLocalStorage(products) {
    localStorage.setItem(cartAddKey, JSON.stringify(products));
    initCarts();
}

function getCartItems() {
    let cartProducts = localStorage.getItem(cartAddKey);
    if (typeof cartProducts === 'undefined' || cartProducts === null || cartProducts === '') {
        return [];
    }

    return JSON.parse(cartProducts);
}

function deleteSingleProductFromCart(productId) {
    const cartsAfterDeleted = getCartItems().filter(cartItem => {
        return cartItem.id != productId;
    });
    setCartInLocalStorage(cartsAfterDeleted);
}


function setCartCount() {
    let cartProducts = getCartItems();
    const cartCount = document.getElementById("cart-count");

    if (cartProducts.length === 0) {
        cartCount.innerHTML = "0";
        return;
    }

    let total = 0;
    cartProducts.map(cart => {
        total += cart.quantity;

        return cart;
    });

    cartCount.innerHTML = "" + total;
}

function setCartItems() {
    const cartItems = document.getElementById("cart-items");
    if (cartItems === null) {
        return;
    }

    const cartProducts = getCartItems();
    if (cartProducts.length === 0) {
        cartItems.innerHTML = `
            <div>
                <h3>No cart item found</h3>
                <p class="mt-4"><a class="bg-slate-500 p-2 rounded text-white" href="/">Continue shopping</a></p>
            </div>
        `;
        return;
    }

    let html = '';
    cartProducts.forEach(cartProduct => {
        html += buildCartItem(cartProduct);
    });

    cartItems.innerHTML = html;
}

function deleteCart(cartProductId) {
    deleteSingleProductFromCart(cartProductId);
}

function decCart(cartProductId) {
    let cartProducts = getCartItems().map(cartProduct => {
        if (cartProductId == cartProduct.id && cartProduct.quantity > 1) {
            cartProduct.quantity = cartProduct.quantity - 1;
        }
        return cartProduct;
    });
    setCartInLocalStorage(cartProducts);
}

function incCart(cartProductId) {
    let cartProducts = getCartItems().map(cartProduct => {
        if (cartProductId == cartProduct.id) {
            cartProduct.quantity = cartProduct.quantity + 1;
        }
        return cartProduct;
    });
    setCartInLocalStorage(cartProducts);
}

function buildCartItem(cartProduct) {
    return `
        <div class="bg-white text-left shadow-md p-5 mb-4">
            <div class="flex justify-between">
                <div class="flex">
                    <div>
                        <img src="${cartProduct.image_url}" class="w-10 mr-4" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold">${cartProduct.name}</h2>
                        <h2 class="text-red-500">
                        ${cartProduct.quantity} X ${cartProduct.price}$
                        = ${(cartProduct.quantity * cartProduct.price).toFixed(2)}$
                        </h2>
                    </div>
                </div>
                <div class="place-end">
                    <button class="text-red-500" onClick="deleteCart(${cartProduct.id})">Delete</button>
                </div>
            </div>

            <div class="mt-4">
                <button class="bg-slate-400 w-8 h-8 text-black rounded-0" onClick="decCart(${cartProduct.id})">-</button>
                <input class="w-20 text-center" value="${cartProduct.quantity}" disabled />
                <button class="bg-slate-400 w-8 h-8 text-black rounded-0" onClick="incCart(${cartProduct.id})">+</button>
            </div>
        </div>
    `;
}

function setCartSummary() {
    const cartProducts = getCartItems();
    if (cartProducts.length === 0) {
        return;
    }
    const cartSummary = document.getElementById("cart-summary");
    if (cartSummary === null) {
        return;
    }

    let totalItems = 0;
    let totalAmount = 0;

    cartProducts.forEach(cartProduct => {
        totalItems += parseInt(cartProduct.quantity);
        totalAmount += parseInt(cartProduct.quantity) * parseFloat(cartProduct.price);
    });

    const html = `
        <h3 class="text-lg font-bold mb-5">Total Items: ${totalItems}</h3>
        <h3 class="text-lg font-bold">Subtotal: ${totalAmount.toFixed(2)}$</h3>
    `;

    cartSummary.innerHTML = html;
}
