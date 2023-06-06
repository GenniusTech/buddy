<?php

use App\Http\Controllers\DespesaController;
use App\Http\Controllers\ReceitaController;
use App\Http\Controllers\TagController;
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

    Route::post('/despesas', [DespesaController::class, 'store']);
    Route::post('/despesas/{id}', [DespesaController::class, 'destroy']);
    Route::post('/despesas/check/{id}', [DespesaController::class, 'check']);
    Route::get('/despesas', [DespesaController::class, 'index']);

    Route::post('/tags', [TagController::class, 'store']);
    Route::post('/tags/{id}', [TagController::class, 'destroy']);
    Route::get('/tags', [TagController::class, 'index']);

});
