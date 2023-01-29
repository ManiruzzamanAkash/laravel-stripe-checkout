<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(): Renderable
    {
        return view('carts.index');
    }
}
