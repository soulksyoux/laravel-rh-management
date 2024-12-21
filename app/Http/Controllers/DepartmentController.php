<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Can;

class DepartmentController extends Controller
{
    public function index()
    {

        if (!Auth::user()->can('admin')) {
            abort(403, 'You are not authorized to access this page');
        }

        $departments = Department::all();

        return view('department.departments', ['departments' => $departments]);
    }

    public function newDepartment()
    {
        if (!Auth::user()->can('admin')) {
            abort(403, 'You are not authorized to access this page');
        }
        return view('department.add-department');
    }

    public function createDepartment(Request $request)
    {
        if (!Auth::user()->can('admin')) {
            abort(403, 'You are not authorized to access this page');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:departments']
        ]);

        Department::create([
            'name' => $request->name
        ]);

        return redirect()->route('departments')->with(['success_create' => 'Department was successul created!']);
    }

    public function editDepartment($id)
    {
        if (!Auth::user()->can('admin')) {
            abort(403, 'You are not authorized to access this page');
        }

        if($this->isDepartmentBlocked($id)) {
            return redirect()->route('departments')->with(['error' => 'Something went wrong!']);
        }

        $department = Department::findOrFail($id);

        return view('department.edit-department', ['department' => $department]);
    }

    public function updateDepartment(Request $request)
    {

        if (!Auth::user()->can('admin')) {
            abort(403, 'You are not authorized to access this page');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:departments'],
            'id' => ['required']
        ]);

        if($this->isDepartmentBlocked($request->id)) {
            return redirect()->route('departments')->with(['error' => 'Something went wrong!']);
        }

        $department = Department::findOrFail($request->id);

        $department->update([
            'name' => $request->name
        ]);


        return redirect()->route('departments')->with(['success_edit' => 'Department updated successul!']);
    }

    public function deleteDepartment($id) {
        if (!Auth::user()->can('admin')) {
            abort(403, 'You are not authorized to access this page');
        }

        if($this->isDepartmentBlocked($id)) {
            return redirect()->route('departments')->with(['error' => 'Something went wrong!']);
        }

        $department = Department::findOrFail($id);


        return view('department.delete-department-confirm', ["department" => $department]);
    }

    public function deleteDepartmentConfirm($id) {
        if (!Auth::user()->can('admin')) {
            abort(403, 'You are not authorized to access this page');
        }

        if($this->isDepartmentBlocked($id)) {
            return redirect()->route('departments')->with(['error' => 'Something went wrong!']);
        }

        $department = Department::findOrFail($id);

        $department->delete();

        return redirect()->route('departments')->with(['success_delete' => 'Department was successfully delected!']);
    }

    private function isDepartmentBlocked($id) {
        return in_array(intval($id), [1,2]);
    }
}
