<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
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
        } else {
            return $this->failure("Product Not Found");
        }
    }

    public function calc_cart(Request $request)
    {
        $user = auth()->user();

        if ($user->cart && count($user->cart) > 0) {
            $total_price = 0;
            $shipping = 0;
            $price = 0;
            $vat = 0;
            foreach ($user->cart as $cart) {

                if ($product = $cart->product) {
                    if ($product->store && $product->store->vat_included) {
                        $total_price = $total_price + ($product->price + ($product->price * ($product->store->vat_value_percentage / 100)));
                        $price = $price + $product->price;
                        $vat = $vat + ($product->price * ($product->store->vat_value_percentage / 100));
                    } else {
                        $total_price = $total_price + $product->price;
                        $price = $price + $product->price;
                    }
                } else {
                    return $this->failure("Product Not Found", 404);
                }
            }

            return $this->success([
                'Shipping' => $product->store->shipping_fees,
                'Net Price' => $price,
                'VAT' => $vat,
                'Total Price' => $total_price + $product->store->shipping_fees,
            ]);

            return $total_price + $product->store->shipping_fees;

            return $this->success([

            ]);
        } else {
            return $this->failure("Cart Empty", 404);
        }
    }
}
