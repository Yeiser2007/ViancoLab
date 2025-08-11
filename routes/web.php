<?php


use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RolesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

Route::group(['prefix' => 'admin','as' => 'admin.','middleware' => ['auth', 'admin'],], function () {
    Route::resource('/users', UserController::class);
    Route::resource('/roles', RolesController::class);
    Route::resource('/permissions', PermissionController::class);

    Route::get('/all-permissions', [RolesController::class, 'getAllPermissions']);
    Route::get('/role-permissions/{role}', [RolesController::class, 'getPermissionsByRole']);
    Route::put('/role-permissions/{role}', [RolesController::class, 'updateRolePermissions']);
});

require __DIR__ . '/auth.php';
