<?php

use App\Http\Controllers\ReportsController;
use App\Livewire\Admin\Reports;
use App\Livewire\Buildings;
use App\Livewire\Login;
use App\Livewire\Places;
use App\Livewire\Reservations;
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
    return redirect('/places');
});

Route::get('/login', Login::class);
Route::get('/places', Places::class)->name('places');

// Admin
Route::group(['middleware' => 'auth'], function() {

    Route::get('/buildings', Buildings::class);
    Route::get('/seats', Seats::class);
    Route::get('/types', Types::class);
    Route::get('/services', Services::class);
    Route::get('/reservations', Reservations::class);

    Route::get('/logout', [Login::class, 'logout'])->name('logout');

    // Reportes
    Route::get('/reports', Reports::class);
    // Route::get('/donwload-place/{id}', [ReportsController::class, 'downloadPlace'])->name('download-place','{id}');
    Route::get('/download-place/{id}', [ReportsController::class, 'downloadPlace'])->name('download-place');
    // Route::get('/download-place/{id}/{dateFrom}/{dateTo}', [ReportsController::class, 'downloadPlace'])->name('download-place');
    Route::get('/download-dates/{id}/{dateFrom}/{dateTo}', [ReportsController::class, 'downloadDates'])->name('download-dates');


    // Email
    Route::get('/confirmation/{id}', 'ConfirmationEmailController@confirmationEmail');
    Route::get('/reservation/{id', 'ReservationEmailController@reservationEmail');
});






