<?php

namespace App\Http\Controllers;

use App\Models\Presenca;
use App\Models\Participante;
use Illuminate\Http\Request;

class PresencaController extends Controller
{
  public function store(Request $request)
  {
    $request->validate([
      'data' => 'required|date',
      'participante_id' => 'required|exists:participantes,id',
    ]);

    $participante = Participante::findOrFail($request->participante_id);
    $evento = $participante->evento;

    if ($request->data < $evento->datainicio || $request->data > $evento->datafim) {
      return response()->json(['message' => 'A data deve estar no período do evento.'], 422);
    }

    if (Presenca::where('participante_id', $request->participante_id)->where('data', $request->data)->exists()) {
      return response()->json(['message' => 'Presença já registrada para essa data.'], 422);
    }

    return Presenca::create($request->all());
  }
}
