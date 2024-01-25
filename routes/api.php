<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DaftarPoliController;
use App\Http\Controllers\DetailPeriksaController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\JadwalPeriksaController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PeriksaController;
use App\Http\Controllers\PoliController;
use App\Models\DetailPeriksa;
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
Route::get('/pasien/ById/{id}',[PasienController::class, 'getById']);
Route::get('/dokter/getAll',[DokterController::class, 'Index']);
Route::post('/dokter/PostDokter',[DokterController::class, 'postDokter']);
Route::post('/dokter/LoginDokter',[DokterController::class, 'LoginDokter']);
Route::get('/dokter/ById/{id}',[DokterController::class, 'getById']);

Route::get('/poli/getAll',[PoliController::class, 'Index']);
Route::post('/poli/PostPoli',[PoliController::class, 'postPoli']);

Route::get('/obat/getAll',[ObatController::class, 'Index']);
Route::get('/obat/ById/{id}',[ObatController::class, 'ById']);
Route::post('/obat/PostObat',[ObatController::class, 'postObat']);
Route::put('/obat/UpdateObat',[ObatController::class, 'updateObat']);
Route::delete('/obat/DeleteObat/{id}',[ObatController::class, 'destroyObat']);

Route::get('/jadwal/getAll',[JadwalPeriksaController::class, 'Index']);
Route::get('/jadwal/ById/{id}',[JadwalPeriksaController::class, 'ById']);
Route::post('/jadwal/PostJadwal',[JadwalPeriksaController::class, 'postJadwal']);
Route::put('/jadwal/UpdateJadwal/{id}',[JadwalPeriksaController::class, 'updateJadwal']);
Route::delete('/jadwal/DeleteJadwal/{id}',[JadwalPeriksaController::class, 'destroyJadwal']);

Route::get('/daftar/getAll',[DaftarPoliController::class, 'Index']);
Route::get('/daftar/ById/{id}',[DaftarPoliController::class, 'ById']);
Route::post('/daftar/PostJadwal',[DaftarPoliController::class, 'postDaftar']);
Route::put('/daftar/UpdateJadwal',[DaftarPoliController::class, 'updateDaftar']);
Route::delete('/daftar/DeleteJadwal/{id}',[DaftarPoliController::class, 'destroyDaftar']);
Route::get('/daftar/pasien/{id}',[DaftarPoliController::class, 'getByPasienId']);

Route::get('/periksa/getAll',[PeriksaController::class, 'Index']);
Route::get('/periksa/ById/{id}',[PeriksaController::class, 'ById']);
Route::post('/periksa/PostJadwal',[PeriksaController::class, 'postPeriksa']);
Route::put('/periksa/UpdateJadwal',[PeriksaController::class, 'updatePeriksa']);
Route::delete('/periksa/DeleteJadwal/{id}',[PeriksaController::class, 'destroyPeriksa']);
Route::get('/periksa/DaftarPoli/{id}',[PeriksaController::class, 'ByDaftarPoliId']);

Route::get('/detailPeriksa/getAll',[DetailPeriksaController::class, 'Index']);
Route::get('/detailPeriksa/ById/{id}',[DetailPeriksaController::class, 'ById']);
Route::post('/detailPeriksa/PostJadwal',[DetailPeriksaController::class, 'postDetail']);
Route::put('/detailPeriksa/UpdateJadwal',[DetailPeriksaController::class, 'updateDetail']);
Route::delete('/detailPeriksa/DeleteJadwal/{id}',[DetailPeriksaController::class, 'destroyDetail']);
Route::get('/detailPeriksa/Periksa/{id}',[DetailPeriksaController::class, 'GetByPeriksaId']);
Route::delete('/detailPeriksa/Obat/{id}',[DetailPeriksaController::class, 'destroyByIdObat']);
