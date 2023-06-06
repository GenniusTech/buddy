<?php

use App\Http\Controllers\ReceitaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Middleware\APITokenMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
Route::group(['middleware' => APITokenMiddleware::class], function () {
    Route::post('/cadastro', [UsuarioController::class ,'cadastrarUser']) ->name('cadastro');
    Route::post('/login', [UsuarioController::class ,'login']) ->name('login');
    Route::post('/resetPassword', [UsuarioController::class ,'resetPassword']) ->name('resetPassword');
    Route::post('/listUser', [UsuarioController::class ,'listUser']) ->name('listUser');

    Route::post('/receitas', [ReceitaController::class ,'store'])->name('receitas');
    Route::post('/receitas/excluir', [ReceitaController::class ,'destroy'])->name('receitas_excluir');
    Route::post('/receitas/verificar', [ReceitaController::class ,'verify'])->name('receitas_verificar');
    Route::get('/receitas',[ReceitaController::class ,'index'])->name('receitas');

});
