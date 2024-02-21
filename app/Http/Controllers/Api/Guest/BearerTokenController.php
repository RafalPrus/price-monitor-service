<?php

namespace App\Http\Controllers\Api\Guest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Guest\LoginRequestForm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class BearerTokenController extends Controller
{
    public function __invoke(LoginRequestForm $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'name' => 'Invalid username or password'
            ]);
        }

        $token = auth()->user()->createToken('login_token');

        return ['token' => $token->plainTextToken];

    }
}
