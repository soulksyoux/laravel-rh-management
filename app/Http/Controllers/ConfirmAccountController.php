<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ConfirmAccountController extends Controller
{
    public function confirmAccount($token) {

        // validar se existe algum user na bd com este token e recuperar o mesmo
        $user = User::where('confirmation_token', $token)->first();

        if(!$user) {
            abort(403, "Invalid confirmation token");
        }

        return view('auth.confirm-account', ['user' => $user]);

    }


    public function confirmAccountSubmit(Request $request) {

        $request->validate(
            [
                'token' => ['required', 'string', 'size:60'],
                'password' => ['required', 'min:8', 'max:16', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/']
            ]
        );

        $user = User::where('confirmation_token', $request->token)->first();

        if(!$user) {
            abort(403, "Invalid confirmation token");
        }

        $user->password = bcrypt($request->password);
        $user->confirmation_token = null;
        $user->email_verified_at = now();
        $user->save();

        return view('auth.welcome', ['user' => $user]);

    }
}