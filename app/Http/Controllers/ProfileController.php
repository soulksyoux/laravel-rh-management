<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View {
        return view('user.profile');
    }

    public function updatePassword(Request $request): RedirectResponse {
        $request->validate([
            'current_password' => ['required', 'min:8', 'max:16'],
            'new_password' => ['required', 'min:8', 'max:16', 'different:current_password'],
            'new_password_confirmation' => ['required', 'same:new_password'],
        ]);

        $user = auth()->user();

        if(!password_verify($request->current_password, $user->password)) {
            return redirect()->back()->with([
                'error' => 'Current password is incorrect'
            ]);
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Pasword updated with success');
    }

    public function updateUserData(Request $request) {
        $request->validate([
            'name' => ['required', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . auth()->id() ],
        ]);

        $user =  Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;

        $user->save();

        return redirect()->back()->with(['success_change_data' => 'Profile updated with success!']);


    }
}
