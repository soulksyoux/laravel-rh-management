<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmAccountEmail;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RhManagmentController extends Controller
{
    public function home() {
        if(!Auth::user()->can('rh')) {
            abort(403, 'You are not authorized to access this page');
        }
    
        $colaborators = User::with('detail', 'department')->where('role', 'colaborator')->withTrashed()->get();
    
        return view('colaborators.colaborators', ['colaborators' => $colaborators]);
    }


    public function newColaborator() {
        if(!Auth::user()->can('rh')) {
            abort(403, 'You are not authorized to access this page');
        }

        $departments = Department::where('id', '>', '2')->get();

        if($departments->count() === 0) {
            abort(403, 'There are no departments to add a colaborator, contact the sys admin to add at least one department.');
        }

        return view('colaborators.add-colaborator', ['departments' => $departments]);
    }

    public function createColaborator(Request $request) {
        if(!Auth::user()->can('rh')) {
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

        if($request->select_department <= 2) {
            return redirect()->route('home');
        }

        $token = Str::random(60);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->confirmation_token = $token;
        $user->role = "colaborator";
        $user->department_id = $request->select_department;
        $user->permissions = '["colaborator"]';

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

        return redirect()->route('rh.managment.home')->with(['success' => 'Colaborator was created successfully!']);

    }

    public function editColaborator($id) {
        if(!Auth::user()->can('rh')) {
            abort(403, 'You are not authorized to access this page');
        }

        $colaborator = User::with('detail')->findOrFail($id);
        $departments = Department::where('id', '>', 2)->get();

        return view('colaborators.edit-colaborator', ["colaborator" => $colaborator, "departments" => $departments]);
    }

    public function updateColaborator(Request $request) {
        if(!Auth::user()->can('rh')) {
            abort(403, 'You are not authorized to access this page');
        }

        $request->validate(
            [
                'user_id' => ['required', 'exists:users,id'], 
                'salary' => ['required', 'decimal:2'],
                'admission_date' => ['required', 'date_format:Y-m-d'],
                'select_department' => ['required'],
            ]);

        if($request->select_department <= 2) {
            return redirect()->route('home');
        }

        $user = User::findOrFail($request->user_id);


        $user->update([
            'department_id' => $request->select_department,
        ]);

        $user->detail->update([
            'salary' => $request->salary,
            'admission_date' => $request->admission_date,
        ]);

        return redirect()->route('rh.managment.home')->with(['success' => 'Colaborator updated succesfully']);

    }

    public function detailsColaborator($id) {
        if(!Auth::user()->can('rh')) {
            abort(403, 'You are not authorized to access this page');
        }

        $colaborator = User::with('detail', 'department')->findOrFail($id);

        return view('colaborators.show-details', ['colaborator' => $colaborator]);
    }

    public function deleteColaborator($id) {

        if(!Auth::user()->can('rh')) {
            abort(403, 'You are not authorized to access this page');
        }

        $colaborator = User::findOrFail($id);

        return view('colaborators.delete-colaborator', ['colaborator' => $colaborator]);

    }

    public function deleteConfirmColaborator($id) {
        if(!Auth::user()->can('rh')) {
            abort(403, 'You are not authorized to access this page');
        }

        $colaborator = User::findOrFail($id);

        $colaborator->delete();

        return redirect()->route('rh.managment.home', ['sucess' => 'Colaborator was deleted with success']);
    }

    public function restoreColaborator($id) {

        if(!Auth::user()->can('rh')) {
            abort(403, 'You are not authorized to access this page');
        }

        $colaborator = User::withTrashed()->findOrFail($id);

        $colaborator->restore();

        return redirect()->route('rh.managment.home', ['sucess' => 'Colaborator was restored with success']);

    }

}
