<?php

use App\Http\Controllers\ContractController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('dependencies/details', [ContractController::class, 'show'])->name('dependencies.details');
Route::post('stats/contracts/generals', [ContractController::class, 'contractGenerals'])->name('contracts.generals');
Route::post('stats/contracts/types', [ContractController::class, 'contractTypes'])->name('contracts.types');
Route::post('stats/contracts/currency', [ContractController::class, 'contractCurrency'])->name('contracts.currency');
