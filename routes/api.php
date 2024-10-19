<?php

use App\Http\Controllers\EventoController;
use App\Http\Controllers\ParticipantesController;
use App\Http\Controllers\PresencaController;
use Illuminate\Support\Facades\Route;

Route::get('eventos', [EventoController::class, 'index']);
Route::get('eventos/pesquisar', [EventoController::class, 'pesquisar']);
Route::post('eventos', [EventoController::class, 'store']);
Route::get('eventos/{id}', [EventoController::class, 'show']);
Route::put('eventos/{id}', [EventoController::class, 'update']);
Route::delete('eventos/{id}', [EventoController::class, 'destroy']);
Route::get('eventos/{id}/mostrar', [EventoController::class, 'mostrar']);

Route::post('participantes', [ParticipantesController::class, 'store']);
Route::put('participantes/{id}', [ParticipantesController::class, 'update']);
Route::delete('participantes/{id}', [ParticipantesController::class, 'destroy']);

Route::post('presencas', [PresencaController::class, 'store']);
