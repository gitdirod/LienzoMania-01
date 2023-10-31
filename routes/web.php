<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConfirmCount;
use App\Http\Controllers\InvoiceController;

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


Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', function () {
    return php_ini_loaded_file();
});
// Route::get('/generate-pdf/{invoiceId}', [InvoiceController::class, 'generatePDF']);
Route::get('/view-pdf', function () {
    return view('pdf.example', [
        'title' => "quieroLab"
    ]);
});
