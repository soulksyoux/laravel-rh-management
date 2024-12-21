<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View {
        $colaborator = User::with('detail', 'department')->findOrFail(Auth::user()->id);

        return view('user.profile', ['colaborator' => $colaborator]);
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

    public function updateUserAddress(Request $request) {
        $request->validate([
            'address' => ['required', 'min:3', 'max:100'],
            'zip_code' => ['required', 'min:8', 'max:10'],
            'city' => ['required', 'min:3', 'max:50'],
            'phone' => ['required', 'min:6', 'max:20'],
        ]);

        $user = User::with('detail')->findOrFail(Auth::user()->id);

        $user->detail->address = $request->address;
        $user->detail->zip_code = $request->zip_code;
        $user->detail->city = $request->city;
        $user->detail->phone = $request->phone;

        $user->detail->save();

        return redirect()->back()->with(['success_change_address' => 'Profile updated with success!']);
    }
}
