<?php

namespace App\Http\Controllers;

use App\Models\Presenca;
use App\Models\Participante;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PresencaController extends Controller
{
  public function store(Request $request)
  {
    try {
      $request->validate([
        'data' => 'required|date',
        'participante_id' => 'required|exists:participantes,id',
      ]);

      $participante = Participante::findOrFail($request->participante_id);
      $evento = $participante->evento;

      $dataInicioFormatada = Carbon::parse($evento->datainicio)->format('d/m/Y');
      $dataFimFormatada = Carbon::parse($evento->datafim)->format('d/m/Y');

      if ($request->data < $evento->datainicio || $request->data > $evento->datafim) {
        return response()->json([
          'errors' => [
            'data' => ['A data deve estar no período do evento, entre ' . $dataInicioFormatada . ' e ' . $dataFimFormatada . '.']
          ]
        ], 422);
      }

      if (Presenca::where('participante_id', $request->participante_id)
        ->where('data', $request->data)
        ->exists()
      ) {
        return response()->json([
          'errors' => ['data' => ['Presença já registrada para essa data.']]
        ], 422);
      }

      return Presenca::create($request->all());
    } catch (\Illuminate\Validation\ValidationException $e) {
      return response()->json([
        'errors' => $e->errors()
      ], 422);
    } catch (\Exception $e) {
      Log::error('Erro ao registrar presença: ' . $e->getMessage(), [
        'request' => $request->all(),
        'exception' => $e
      ]);

      return response()->json(['error' => 'Erro ao registrar presença.'], 500);
    }
  }
}
