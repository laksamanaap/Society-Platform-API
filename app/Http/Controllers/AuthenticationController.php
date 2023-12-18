<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function loginUsers(Request $request) {
        $validator = Validator::make($request->all(), [
            'id_card_number' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 419);
        }

        try {
            $user = User::where('id_card_number', $request->input('id_card_number'))->first();

            if ($user && $request->input('password') === $user->password) {
                Auth::login($user);

                $token = $user->createToken('auth_token')->plainTextToken;
                $user->update(['login_tokens' => $token]);

                $response = [
                    'name' => $user->name,
                    'born_date' => $user->born_date,
                    'gender' => $user->gender,
                    'address' => $user->address,
                    'token' => $token,
                    'regional' => [
                        'id' => $user->regionals->id,
                        'province' => $user->regionals->province,
                        'district' => $user->regionals->district,
                    ]
                ];

                return response()->json([
                    $response
                ], 200);
            } else {
                return response()->json(['message' => 'ID Card Number or Password incorrect'], 401);
            }
        } catch (DecryptException $e) {
            return response()->json(['message' => 'Error in password hashing'], 500);
        }
    }



    public function logoutUsers(Request $request)
    {
        $validator = Validator::make($request->all(), [
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Token is required'], 400);
        }

        $inputToken = $request->input('token');

        $societies = User::where('login_tokens', $inputToken)
            ->update(['login_tokens' => null]);

        if ($societies > 0) {
            return response()->json(['message' => 'Logout success'], 200);
        } else {
            return response()->json(['message' => 'Invalid token'], 500);
        }
    }

}