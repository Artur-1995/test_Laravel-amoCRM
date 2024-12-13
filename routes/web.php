<?php

use App\Http\Controllers\AccessTokenController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\LinkContactToTransactionController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [Controller::class, 'getRequest'])->name('home');
Route::post('/', [Controller::class, 'postRequest'])->name('home');
Route::get('/get-token', AccessTokenController::class)->name('token');
Route::get('/get-transactions{answer?}', TransactionController::class)->name('transactions');
Route::post('/add-contact-to-transaction', LinkContactToTransactionController::class)->name('add_contact_to_transactions');
Route::get('/get-history', HistoryController::class)->name('history');
