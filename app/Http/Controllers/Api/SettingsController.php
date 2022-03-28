<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    use RespondsWithHttpStatus;

    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user && $user->type == 'merchant' && $user->store) {

            if ($request->store_name) {
                $user->store->name = $request->store_name;
                $user->store->save();
            }

            if ($request->shipping_fees) {
                $user->store->shipping_fees = $request->shipping_fees;
                $user->store->save();
            }

            if ($request->shipping_fees) {
                $user->store->shipping_fees = $request->shipping_fees;
                $user->store->save();
            }

            if ($request->vat_value_percentage) {
                $user->store->vat_value_percentage = $request->vat_value_percentage;
                $user->store->save();
            }
            return $this->success([], 'Settings Updated successfully');
        } else {
            return $this->failure("You don't have store");
        }

    }

}
