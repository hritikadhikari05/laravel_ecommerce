<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/login', function () {
    return view('login');
})->name('login');

//Logout Route
Route::get('/logout', function () {
    session()->forget('userId');
    session()->forget('isAdmin');
    return redirect()->route('login');
})->name('logout');

Route::get('/signup', function () {
    return view('signup');
})->name('signup');


Route::post('/login', [ProductController::class, 'signin'])->name('login.sumbit');
Route::post('/signup', [ProductController::class, 'signUp'])->name('signup');


Route::get('/', [ProductController::class, 'getHomepage'])->name('homepage');
Route::get('/product/{id}', [ProductController::class, 'getProduct'])->name('product');


//Admin
Route::get('/dashboard', [ProductController::class, 'getDasbboard'])->name('dashboard');
Route::get('/add-product', [ProductController::class, 'addProduct'])->name('add-product');
Route::post('/add-product', [ProductController::class, 'addNewProduct'])->name('add-product');
Route::get('/manage-product', [ProductController::class, 'manageProduct'])->name('manage-product');
Route::get('/edit-product/{id}', [ProductController::class, 'editProduct'])->name('edit-product');
Route::post('/edit-product/{id}', [ProductController::class, 'updateProduct'])->name('edit-product');
Route::delete('/delete-product/{id}', [ProductController::class, 'deleteProduct'])->name('delete-product');
