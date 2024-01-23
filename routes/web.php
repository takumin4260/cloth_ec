<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\RegisteredUserController;

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



Route::prefix('products')
->middleware(['auth'])
->name('products.')
 ->controller(ProductController::class) // コントローラ指定(laravel9から)
 ->group(function(){ // グループ化
    Route::get('/', 'index')->name('index'); 
    Route::get('/mycart', 'mycart')->name('mycart'); 
    Route::post('/addmycart', 'addmycart')->name('addmycart'); 
    Route::post('/deleteMyCartStock', 'deleteMyCartStock')->name('deleteMyCartStock');
    Route::get('/checkout', 'checkout')->name('checkout');
    Route::get('/admin', 'adminindex')->name('adminindex');
    Route::get('/create', 'create')->name('create'); 
    Route::post('/', 'store')->name('store'); 
    Route::get('/{id}', 'show')->name('show'); 
    Route::get('/admin/{id}', 'adminshow')->name('adminshow'); 
    Route::get('/{id}/edit', 'edit')->name('edit'); 
    Route::post('/{id}', 'update')->name('update'); 
    Route::post('/{id}/destroy', 'destroy')->name('destroy'); 
});

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

require __DIR__.'/auth.php';
