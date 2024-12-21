<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ColaboratorsController;
use App\Http\Controllers\ConfirmAccountController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RhManagmentController;
use App\Http\Controllers\RhUserController;
use App\Mail\ConfirmAccountEmail;
use App\Models\Department;
use App\Models\User;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function(){
    Route::get('/confirm-account/{token}', [ConfirmAccountController::class, 'confirmAccount'])->name('confirm-account');
    Route::post('/confirm-account', [ConfirmAccountController::class, 'confirmAccountSubmit'])->name('confirm-account-submit');
});

Route::middleware('auth')->group(function(){
    Route::redirect('/', '/home');
    Route::get("/home", function() {
        if(auth()->user()->role === 'admin') {
            return redirect()->route('admin.home');
        }elseif(auth()->user()->role == 'rh') {
            return redirect()->route('rh.managment.home');
        }else{
            return redirect()->route('colaborators.home');
        }
    })->name('home');


    Route::get("/user/profile", [ProfileController::class, 'index'])->name('user.profile');
    Route::post('/user/profile/update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
    Route::post('/user/profile/update-user-data', [ProfileController::class, 'updateUserData'])->name('update-user-data');
    Route::post('/user/profile/update-user-address', [ProfileController::class, 'updateUserAddress'])->name('update-user-address');



    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments');
    Route::get('/departments/new-department', [DepartmentController::class, 'newDepartment'])->name('departments.new-department');
    Route::post('/departments/create-department', [DepartmentController::class, 'createDepartment'])->name('departments.create-department');
    Route::get('/departments/edit-department/{id}', [DepartmentController::class, 'editDepartment'])->name('departments.edit-department');
    Route::post('/departments/update-department', [DepartmentController::class, 'updateDepartment'])->name('departments.update-department');
    Route::get('/departments/delete-department/{id}', [DepartmentController::class, 'deleteDepartment'])->name('departments.delete-department');
    Route::get('/departments/delete-department-confirm/{id}', [DepartmentController::class, 'deleteDepartmentConfirm'])->name('departments.delete-department-confirm');
    Route::get('/rh-users', [RhUserController::class, 'index'])->name('colaborators.rh-users');
    Route::get('/rh-users/new-colaborator', [RhUserController::class, 'newRhColaborator'])->name('colaborators.rh.new-colaborator');
    Route::post('/rh-users/create-colaborator', [RhUserController::class, 'createRhColaborator'])->name('colaborators.rh.create-colaborator');
    Route::get('/rh-users/edit-colaborator/{id}', [RhUserController::class, 'editRhColaborator'])->name('colaborators.rh.edit-colaborator');
    Route::post('/rh-users/update-colaborator', [RhUserController::class, 'updateRhColaborator'])->name('colaborators.rh.update-colaborator');
    Route::get('/rh-users/delete-colaborator/{id}', [RhUserController::class, 'deleteRhColaborator'])->name('colaborators.rh.delete-colaborator');
    Route::get('/rh-users/delete-colaborator-confirm/{id}', [RhUserController::class, 'deleteRhColaboratorConfirm'])->name('colaborators.rh.delete-colaborator-confirm');
    Route::get('/rh-users/restore-colaborator/{id}', [RhUserController::class, 'restoreRhColaborator'])->name('colaborators.rh.restore-colaborator');

    Route::get('/rh-users/managment/home', [RhManagmentController::class, 'home'])->name('rh.managment.home'); 
    Route::get('/rh-users/managment/new-colaborator', [RhManagmentController::class, 'newColaborator'])->name('rh.managment.new-colaborator'); 
    Route::post('/rh-users/managment/create-colaborator', [RhManagmentController::class, 'createColaborator'])->name('rh.managment.create-colaborator'); 
    Route::get('/rh-users/managment/edit-colaborator/{id}', [RhManagmentController::class, 'editColaborator'])->name('rh.managment.edit-colaborator');
    Route::post('/rh-users/managment/update-colaborator', [RhManagmentController::class, 'updateColaborator'])->name('rh.managment.update-colaborator');
    Route::get('/rh-users/managment/details-colaborator/{id}', [RhManagmentController::class, 'detailsColaborator'])->name('rh.managment.details-colaborator');
    Route::get('/rh-users/managment/delete-colaborator/{id}', [RhManagmentController::class, 'deleteColaborator'])->name('rh.managment.delete-colaborator');
    Route::get('/rh-users/managment/delete-confirm-colaborator/{id}', [RhManagmentController::class, 'deleteConfirmColaborator'])->name('rh.managment.delete-confirm-colaborator');
    Route::get('/rh-users/managment/restore-colaborator/{id}', [RhManagmentController::class, 'restoreColaborator'])->name('rh.managment.restore-colaborator');





    Route::get('/colaborators', [ColaboratorsController::class, 'index'])->name('colaborators.all-colaborators');
    Route::get('/colaborators/details/{id}', [ColaboratorsController::class, 'showDetails'])->name('colaborators.details');
    Route::get('/colaborators/delete/{id}', [ColaboratorsController::class, 'deleteColaborator'])->name('colaborators.delete');
    Route::get('/colaborators/delete-confirm/{id}', [ColaboratorsController::class, 'deleteColaboratorConfirm'])->name('colaborators.delete-confirm');
    Route::get('/colaborators/restore-colaborator/{id}', [ColaboratorsController::class, 'restoreColaborator'])->name('colaborators.restore-colaborator');
    Route::get('/colaborators/home', [ColaboratorsController::class, 'home'])->name('colaborators.home');

    Route::get('/admin/home', [AdminController::class, 'home'])->name('admin.home');



    
});
