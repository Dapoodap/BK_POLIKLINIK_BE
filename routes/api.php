<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PoliController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/admin/getAll',[AdminController::class, 'Index']);
Route::post('/admin/LoginAdmin',[AdminController::class, 'LoginAdmin']);
Route::post('/admin/PostAdmin',[AdminController::class, 'PostAdmin']);
Route::get('/admin/ById/{id}',[AdminController::class, 'getById']);
Route::get('/pasien/getAll',[PasienController::class, 'Index']);
Route::post('/pasien/LoginPasien',[PasienController::class, 'LoginPasien']);
Route::post('/pasien/PostPasien',[PasienController::class, 'postPasien']);
Route::get('/pasien/ById{id}',[PasienController::class, 'getById']);
Route::get('/dokter/getAll',[DokterController::class, 'Index']);
Route::post('/dokter/PostDokter',[DokterController::class, 'postDokter']);
Route::post('/dokter/LoginDokter',[DokterController::class, 'LoginDokter']);
Route::get('/dokter/ById/{id}',[DokterController::class, 'getById']);
Route::get('/poli/getAll',[PoliController::class, 'Index']);
Route::post('/poli/PostPoli',[PoliController::class, 'postPoli']);

