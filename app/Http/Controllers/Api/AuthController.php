<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Traits\RespondsWithHttpStatus;

class AuthController extends Controller
{
    use RespondsWithHttpStatus;

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->failure('These credentials do not match our records.');
        }
        $token = $user->createToken('my-app-token')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response($response, 201);
    }


    public function register(UserRequest $request)
    {

                $user = User::create([
                    'type' => $request->type,
                    'name' => $request->name,
                    'password' => bcrypt($request->password),
                    'email' => $request->email
                ]);

                $store = new Store();
                $store->merchant_id = $user->id;
                $store->save();



        return $this->success([
            'user' => $user,
            'token' => $user->createToken('tokens')->plainTextToken
        ]);
    }

    public function signout()
    {
        auth()->user()->tokens()->delete();
        return $this->success([], 'User Logged out successfully');
    }
}
