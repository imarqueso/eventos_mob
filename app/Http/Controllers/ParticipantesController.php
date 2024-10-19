<?php

namespace App\Http\Controllers;

use App\Models\Participante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Evento;

class ParticipantesController extends Controller
{
  public function store(Request $request)
  {
    try {
      $request->validate([
        'nome' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'cpf' => 'required|string|max:255|unique:participantes,cpf,NULL,id,evento_id,' . $request->evento_id,
        'evento_id' => 'required|exists:eventos,id',
      ]);

      return Participante::create($request->all());
    } catch (\Exception $e) {
      Log::error('Erro ao criar participante: ' . $e->getMessage(), [
        'request' => $request->all(),
        'exception' => $e
      ]);

      return response()->json(['error' => 'Erro ao criar participante.'], 500);
    }
  }

  public function update(Request $request, $id)
  {
    $participante = Participante::findOrFail($id);
    $participante->update($request->all());
    return $participante;
  }

  public function destroy($id)
  {
    Participante::findOrFail($id)->delete();
    return response()->json(['message' => 'Participante excluído com sucesso']);
  }
}
