<?php

namespace App\Http\Controllers;

use App\Models\Participante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Evento;
use Illuminate\Validation\Rule;

class ParticipantesController extends Controller
{
  public function store(Request $request)
  {
    try {
      $request->validate([
        'nome' => 'required|string|max:255',
        'cpf' => 'required|string|max:255|unique:participantes,cpf,NULL,id,evento_id,' . $request->evento_id,
        'email' => 'required|email|max:255',
        'evento_id' => 'required|exists:eventos,id',
      ]);

      return Participante::create($request->all());
    } catch (\Illuminate\Validation\ValidationException $e) {
      Log::error('Erro ao criar participante: ' . $e->getMessage(), [
        'request' => $request->all(),
        'exception' => $e
      ]);
      return response()->json([
        'errors' => $e->errors()
      ], 422);
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
    try {
      $participante = Participante::findOrFail($id);

      $request->validate([
        'nome' => 'required|string|max:255',
        'cpf' => [
          'required',
          'string',
          'max:255',
          Rule::unique('participantes')->where(function ($query) use ($request, $participante) {
            return $query->where('evento_id', $request->evento_id)
              ->where('id', '!=', $participante->id);
          })
        ],
        'email' => 'required|email|max:255',
        'evento_id' => 'required|exists:eventos,id',
      ]);

      $participante->update($request->all());
      return $participante;
    } catch (\Illuminate\Validation\ValidationException $e) {
      return response()->json([
        'errors' => $e->errors()
      ], 422);
    } catch (\Exception $e) {
      Log::error('Erro ao editar participante: ' . $e->getMessage(), [
        'request' => $request->all(),
        'exception' => $e
      ]);

      return response()->json(['error' => 'Erro ao editar participante.'], 500);
    }
  }

  public function destroy($id)
  {
    Participante::findOrFail($id)->delete();
    return response()->json(['message' => 'Participante excluído com sucesso']);
  }
}
