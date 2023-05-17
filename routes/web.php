<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosyanduController;
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
    return view('welcome');
});
Route::get('/posyandus', [PosyanduController::class, 'index']);
// tambah data baru
Route::post('/posyandus/tambah-data', [PosyanduController::class, 'store']);
//generate token csrf
Route::get('/generate-token', [PosyanduController::class, 'createToken']);
Route::get('/posyandus/show/trash',[PosyanduController::class,'trash']);
//ambil satu data spesifik
Route::get('/posyandus/{id}', [PosyanduController::class, 'show']);
// mengubah data tertentu
Route::patch('/posyandus/update/{id}', [PosyanduController::class, 'update']);
// menghapus data tertentu
Route::delete('/posyandus/delete/{id}', [PosyanduController::class, 'destroy']);
//mengembalikan data yang sudah dihapus
Route::get('/posyandus/trash/restore/{id}', [PosyanduController::class, 'restore']);
//menghapus permanen data spesifik
Route::get('/posyandus/trash/delete/permanent/{id}',[PosyanduController::class, 'permanentDelete']);