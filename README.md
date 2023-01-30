## Laravel Stripe Checkout

A beautiful Checkout example using Laravel supporting both Cash-in and Stripe Checkout with Best Coding practices.

-----

## Features
1. [x] Authentication - Login, Register, Forget Password
2. [x] Product List Seeder
3. [x] Add to cart, Edit, Delete cart - Localstorage
4. [x] Cart list
5. [x] Checkout Cash-in
6. [x] Checkout Stripe
7. [ ] Webhook Integration
8. [ ] Pest unit test

## Setup

#### Clone this repo -
```sh
git clone https://github.com/ManiruzzamanAkash/laravel-stripe-checkout.git
```

#### Create `.env` file
Create `.env` file by copying `.env.example`

#### Change Database in `.env`
```sh
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_stripe_checkout
DB_USERNAME=root
DB_PASSWORD=
```

#### Add Stripe public and secret key in `.env`
To get this visit - https://dashboard.stripe.com/test/apikeys

```sh
STRIPE_PUBLIC_KEY=
STRIPE_SECRET_KEY=
```

#### Run migration and seeder
```sh
php artisan migrate --seed
```


#### Serve application
```sh
php artisan serve
```

and booom, visit http://localhost:8000


## Demo

### Product list page
![Product list page](https://i.ibb.co/py2w2bB/01-product-list.png "Product list page").


### Cart list page
![Cart list page](https://i.ibb.co/Q8c9cPP/02-cart-list.png "Cart list page").


### Checkout page
![Checkout page](https://i.ibb.co/PNfH9Fd/03-checkout.png "Checkout page").


### Stripe checkout page
![Stripe checkout page](https://i.ibb.co/y6tLMCV/04-stripe-checkout.png "Stripe checkout page").


### Login page
![Login page](https://i.ibb.co/3dn7pKS/05-login.png "Login page").

### Stripe Payment Dashboard
![Stripe Payment Dashboard](https://i.ibb.co/S0snyhD/06-stripe-payment.png "Stripe Payment Dashboard").


## Contribution
I'm Maniruzzaman Akash, Maintainer of this repo.

Yes, it's open and you can contribute too. If any question regarding this, email me at - manirujjamanakash@gmail.com.
