<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmAccountEmail;
use App\Models\Department;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RhUserController extends Controller
{
    public function index() {
        if (!Auth::user()->can('admin')) {
            abort(403, 'You are not authorized to access this page');
        }

        $colaborators = User::withTrashed()->with('detail')->where('role', 'rh')->get();

        return view('colaborators.rh-users', ['colaborators' => $colaborators]);
    }

    public function newRhColaborator() {
        if (!Auth::user()->can('admin')) {
            abort(403, 'You are not authorized to access this page');
        }

        $departments = Department::all();

        return view('colaborators.add-rh-user', ['departments' => $departments]);
    }

    public function createRhColaborator(Request $request) {
        if (!Auth::user()->can('admin')) {
            abort(403, 'You are not authorized to access this page');
        }

        $request->validate(
            [
                'name' => ['required', 'max:255', 'string'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'select_department' => ['required', 'exists:departments,id'],
                'address' => ['required', 'max:255', 'string'],
                'zip_code' => ['required', 'max:10', 'string'],
                'city' => ['required', 'max:50', 'string'],
                'phone' => ['required', 'string', 'max:50'],
                'salary' => ['required', 'decimal:2'],
                'admission_date' => ['required', 'date_format:Y-m-d']
            ]);

        if($request->select_department != 2) {
            return redirect()->route('home');
        }

        $token = Str::random(60);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->confirmation_token = $token;
        $user->role = "rh";
        $user->department_id = $request->select_department;
        $user->permissions = '["rh"]';

        $user->save();

        $user->detail()->create([
            'address' => $request->address, 
            'zip_code' => $request->zip_code, 
            'city' => $request->city, 
            'phone' => $request->phone, 
            'salary' => $request->salary, 
            'admission_date' => $request->admission_date, 
        ]);

        //send an email to user
        Mail::to($user->email)->send(new ConfirmAccountEmail(route('confirm-account', $token)));

        return redirect()->route('colaborators.rh-users')->with(['success_create' => 'Colaborator was created successfully!']);
    }

    public function editRhColaborator($id) {
        if (!Auth::user()->can('admin')) {
            abort(403, 'You are not authorized to access this page');
        }

        $colaborator = User::with('detail')->where('role', 'rh')->findOrFail($id);

        return view('colaborators.edit-th-user', ["colaborator" => $colaborator]);
    }

    public function updateRhColaborator(Request $request) {
        if (!Auth::user()->can('admin')) {
            abort(403, 'You are not authorized to access this page');
        }

        $request->validate(
            [
                'user_id' => ['required', 'exists:users,id'], 
                'salary' => ['required', 'decimal:2'],
                'admission_date' => ['required', 'date_format:Y-m-d']
            ]);

        $user = User::findOrFail($request->user_id);

        $user->detail->update([
            'salary' => $request->salary,
            'admission_date' => $request->admission_date
        ]);

        return redirect()->route('colaborators.rh-users')->with(['success_edit' => 'Colaborator updated succesfully']);
    }

    public function deleteRhColaborator($id) {
        if (!Auth::user()->can('admin')) {
            abort(403, 'You are not authorized to access this page');
        }

        $colaborator = User::with('detail')->findOrFail($id);

        return view('colaborators.delete-rh-user-confirm', ['colaborator' => $colaborator]);
    }

    public function deleteRhColaboratorConfirm($id) {
        if (!Auth::user()->can('admin')) {
            abort(403, 'You are not authorized to access this page');
        }

        $colaborator = User::findOrFail($id);

        $colaborator->delete();

        return redirect()->route('colaborators.rh-users')->with(['success_delete' => 'Colaborator deleted succesfully']);

    }

    public function restoreRhColaborator($id) {
        if (!Auth::user()->can('admin')) {
            abort(403, 'You are not authorized to access this page');
        }

        $colaborator = User::withTrashed()->where('role', 'rh')->findOrFail($id);

        $colaborator->restore();
    
        return redirect()->route('colaborators.rh-users')->with(['success_restore' => 'Colaborator restored succesfully']);

    } 
}
