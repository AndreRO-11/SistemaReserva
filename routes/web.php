<?php

use App\Livewire\Buildings;
use App\Livewire\Seats;
use App\Livewire\Services;
use App\Livewire\Types;
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

Route::get('/', function () {
    return view('dashboard');
});

// Route::middleware(['auth:sanchtum', 'verified'])->get('/dashboard', function() {
//     return view('dashboard');
// })->name('dashboard');

Route::get('buildings', Buildings::class);
Route::get('/seats', Seats::class);
Route::get('/types', Types::class);
Route::get('/services', Services::class);



