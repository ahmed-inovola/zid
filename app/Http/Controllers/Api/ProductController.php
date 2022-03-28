<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Cart;
use App\Models\Product;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use RespondsWithHttpStatus;

    public function set_product(ProductRequest $request)
    {
        $user = auth()->user();

        if ($user && $user->type == 'merchant' && $user->store) {

            $product = new Product();
            $product->store_id = $user->store->id;
            $product->price = $request->price;
            $product->code = $request->code;

            $name_translations = ['ar' => $request->name_ar, 'en' => $request->name_en];
            $product->name = $name_translations;
            $description_translations = ['ar' => $request->description_ar, 'en' => $request->description_en];
            $product->description = $description_translations;

            $product->save();
            return $this->success([], 'Product Added successfully');

        } else {
            return $this->failure("You don't have store");
        }
    }

    public function add_cart(Request $request)
    {
        $product = Product::find($request->product_id);

        if ($product) {
            $cart = new Cart();
            $cart->product_id = $product->id;
            $cart->user_id = auth()->user()->id;
            $cart->save();
            return $this->success([], 'Product Added To Cart successfully');
        }else{
            return $this->failure("Product Not Found");
        }
    }
}
