<?php

use App\Http\Controllers\ReportsController;
use App\Livewire\Admin\Report\ReportsByDates;
use App\Livewire\Admin\Report\ReportsByPlaces;
use App\Livewire\Admin\Reports;
use App\Livewire\Buildings;
use App\Livewire\Campuses;
use App\Livewire\Login;
use App\Livewire\Places;
use App\Livewire\Reservations;
use App\Livewire\Seats;
use App\Livewire\Services;
use App\Livewire\Admin\Users;
use App\Livewire\Auth\ResetPassword;
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
Route::get('password/reset/{token}', ResetPassword::class)->name('password.reset');
Route::get('/places', Places::class)->name('places');

// Admin
Route::group(['middleware' => 'auth'], function() {

    Route::get('/users', Users::class);

    Route::get('/buildings', Buildings::class);
    Route::get('/campus', Campuses::class);
    Route::get('/seats', Seats::class);
    Route::get('/types', Types::class);
    Route::get('/services', Services::class);
    Route::get('/reservations', Reservations::class);

    Route::get('/logout', [Login::class, 'logout'])->name('logout');

    // Reportes
    Route::get('/reports', Reports::class);

    // Email
    Route::get('/confirmation/{id}', 'ConfirmationEmailController@confirmationEmail');
    Route::get('/reservation/{id}', 'ReservationEmailController@reservationEmail');
});






