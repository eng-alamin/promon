<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('admin.products.index');
    }

    public function create(): View
    {
        return view('admin.products.form', ['product' => null]);
    }

    public function edit(Product $product): View
    {
        return view('admin.products.form', ['product' => $product]);
    }
}
