<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => ['auth']], function () {
    Route::get('/', function () {
        return view('pages.dashboard');
    });
    Route::group(['prefix' => 'organization'], function () {
        Route::get('/', [OrganizationController::class, 'index']);
        Route::get('/getdt', [OrganizationController::class, 'getDtOrganization']);
        Route::get('/getdtPerson', [OrganizationController::class, 'getDtOrganizationPerson']);
        Route::get('/detail/{id}', [OrganizationController::class, 'detail']);
        Route::get('/edit/{id}', [OrganizationController::class, 'edit'])->middleware(['permission:edit organizations']);
        Route::post('/edit/{id}', [OrganizationController::class, 'update'])->middleware(['permission:edit organizations']);
        Route::delete('/delete/{id}', [OrganizationController::class, 'delete'])->middleware(['permission:delete organizations']);
        Route::get('/create', [OrganizationController::class, 'create'])->middleware(['permission:create organizations']);
        Route::post('/create', [OrganizationController::class, 'store'])->middleware(['permission:create organizations']);
    });
    Route::group(['prefix' => 'person'], function () {
        Route::get('/', [PersonController::class, 'index'])->middleware(['permission:create person']);
        Route::get('/getdt', [PersonController::class, 'getDtPerson'])->middleware(['permission:create person']);
        Route::get('/create', [PersonController::class, 'create'])->middleware(['permission:create person']);
        Route::post('/create', [PersonController::class, 'store'])->middleware(['permission:create person']);
        Route::get('/detail/{id}', [PersonController::class, 'detail'])->middleware(['permission:show person']);
        Route::get('/edit/{id}', [PersonController::class, 'edit'])->middleware(['permission:edit person']);
        Route::post('/edit/{id}', [PersonController::class, 'update'])->middleware(['permission:edit person']);
        Route::delete('/delete/{id}', [PersonController::class, 'delete'])->middleware(['permission:delete person']);
    });
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UserController::class, 'index'])->middleware(['permission:show user']);
        Route::get('/getdt', [UserController::class, 'getDtUser'])->middleware(['permission:show user']);
        Route::get('/create', [UserController::class, 'create'])->middleware(['permission:create user']);
        Route::post('/create', [UserController::class, 'store'])->middleware(['permission:create user']);
        Route::get('/edit/{id}', [UserController::class, 'edit'])->middleware(['permission:edit user']);
        Route::post('/edit/{id}', [UserController::class, 'update'])->middleware(['permission:edit user']);
        Route::delete('/delete/{id}', [UserController::class, 'delete'])->middleware(['permission:delete user']);
    });
    Route::get('/logout', [AuthController::class, 'logout']);
});

Route::group(['middleware' => ['guest']], function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', [AuthController::class, 'login']);
});