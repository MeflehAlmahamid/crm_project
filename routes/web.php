<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Authenticate;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\Auth\LoginController;
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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [LoginController::class, 'showLoginForm']);
Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware([Authenticate::class])->group(function () {

    Route::resource('companies', CompaniesController::class);

    Route::get('/companies/{company}/employees', [CompaniesController::class, 'viewEmployees'])
    ->name('companies.viewEmployees');

    Route::get('/companies/{company}/edit', [CompaniesController::class, 'edit']);
    Route::put('/companies/{company}', [CompaniesController::class, 'update']);

    Route::get('/export-excel', [CompaniesController::class, 'exportExcel'])->name('companies.exportExcel');
    Route::get('/export-all-companies-pdf', [CompaniesController::class, 'exportAllCompaniesPDF'])->name('export.all.companies.pdf');

});
