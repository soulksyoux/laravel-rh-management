<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ColaboratorsController extends Controller
{
    public function index() {

        if (!Auth::user()->can('admin')) {
            abort(403, 'You are not authorized to access this page');
        }

        $colaborators = User::withTrashed()->with('detail', 'department')->where('role', '<>', 'admin')->get();

        //return view
        return view('colaborators.admin-all-colaborators')->with('colaborators', $colaborators);
        
    }

    public function showDetails($id) {
        if (!Auth::user()->can('admin', 'rh')) {
            abort(403, 'You are not authorized to access this page');
        }

        if(Auth::user()->id === $id) {
            return redirect()->route('home');
        }

        $colaborator = User::with('detail', 'department')->where('id', $id)->first();

        if(!$colaborator) {
            abort(404);
        }

        return view('colaborators.show-details')->with('colaborator', $colaborator);
 

    }

    public function deleteColaborator($id) {
        if (!Auth::user()->can('admin', 'rh')) {
            abort(403, 'You are not authorized to access this page');
        }

        if(Auth::user()->id === $id) {
            return redirect()->route('home');
        }

        $colaborator = User::findOrFail($id);

        return view('colaborators.delete-colaborator-confirm', ['colaborator' => $colaborator]);
    }

    public function deleteColaboratorConfirm($id) {
        if (!Auth::user()->can('admin', 'rh')) {
            abort(403, 'You are not authorized to access this page');
        }

        if(Auth::user()->id === $id) {
            return redirect()->route('home');
        }

        $colaborator = User::findOrFail($id);
        $colaborator->delete();

        return redirect()->route('colaborators.all-colaborators');
    }

    public function restoreColaborator($id) {
        if (!Auth::user()->can('admin')) {
            abort(403, 'You are not authorized to access this page');
        }

        $colaborator = User::withTrashed()->findOrFail($id);

        $colaborator->restore();
    
        return redirect()->route('colaborators.all-colaborators');

    }

    public function home() {
        if (!Auth::user()->can('colaborator')) {
            abort(403, 'You are not authorized to access this page');
        }

        $colaborator = User::with('detail', 'department')->where('id', Auth::user()->id)->first();

        return view('colaborators.show-details', ['colaborator' => $colaborator]);

    }
}
