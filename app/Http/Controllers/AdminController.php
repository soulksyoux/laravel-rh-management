<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{
    public function home()
    {
        if (!Auth::user()->can('admin')) {
            abort(403, 'You are not authorized to access this page');
        }

        $data = [];

        $data['total_colaborators'] = User::whereNull('deleted_at')->count();
        $data['total_colaborators_deleted'] = User::onlyTrashed()->count();

        $data['total_salaries'] = User::withoutTrashed()->with('detail')->get()->sum(function ($col) {
            return $col->detail->salary;
        });
        $data['total_salaries'] = number_format($data['total_salaries'], 2, ',', '.') . ' $';

        $data['total_colaborators_per_department'] = User::withoutTrashed()
            ->with('department')
            ->get()
            ->groupBy('department_id')
            ->map(function ($users_per_department) {
                return [
                    'department' => $users_per_department->first()->department->name ?? 'No Department',
                    'total' => $users_per_department->count()
                ];
            });

        $data['total_salaries_per_department'] = User::withoutTrashed()
            ->with('department', 'detail')
            ->get()
            ->groupBy('department_id')
            ->map(function ($users_per_department) {
                return [
                    'department' => $users_per_department->first()->department->name ?? 'No Department',
                    'total' => $users_per_department->sum(function ($col){
                        return $col->detail->salary;
                    })
                ];
            });

        $data['total_salaries_per_department'] = $data['total_salaries_per_department']->map(function ($department) {
            return [
                'department' => $department['department'],
                'total' => number_format($department['total'], 2, ',', '.') . ' $'
            ];
        });

        return view('home', ['data' => $data]);
    }
}
