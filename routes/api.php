<?php

use App\Http\Controllers\EventoController;
use App\Http\Controllers\ParticipanteController;
use App\Http\Controllers\PresencaController;
use Illuminate\Support\Facades\Route;

Route::get('eventos', [EventoController::class, 'index']);
Route::get('eventos/pesquisar', [EventoController::class, 'pesquisar']);
Route::post('eventos', [EventoController::class, 'store']);
Route::get('eventos/{id}', [EventoController::class, 'show']);
Route::put('eventos/{id}', [EventoController::class, 'update']);
Route::delete('eventos/{id}', [EventoController::class, 'destroy']);

Route::post('participantes', [ParticipanteController::class, 'store']);
Route::delete('participantes/{id}', [ParticipanteController::class, 'destroy']);

Route::post('presencas', [PresencaController::class, 'store']);
