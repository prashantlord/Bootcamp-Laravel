<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class RegisterUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): JsonResponse
    {
        try{
            $request->validated();
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->string('password')),
            ]);

            event(new Registered($user));
            Auth::login($user);

            $token = $user->createToken("Bearer-Token")->plainTextToken;
            return \response()->json([
                "message" => "Registered Successfully",
                "token" => $token
            ]);
        }catch (\Exception $exception){
            return \response()->json([
                "message" => "Register Failed",
                "error" => $exception->getMessage()
            ]);
        }

    }
}
