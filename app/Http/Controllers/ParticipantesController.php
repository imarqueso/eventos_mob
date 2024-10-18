<?php

namespace App\Http\Controllers;

use App\Models\Participante;
use Illuminate\Http\Request;

class ParticipanteController extends Controller
{
  public function store(Request $request)
  {
    $request->validate([
      'nome' => 'required|string|max:255',
      'email' => 'required|email|max:255',
      'cpf' => 'required|string|max:255|unique:participantes,cpf,NULL,id,evento_id,' . $request->evento_id,
      'evento_id' => 'required|exists:eventos,id',
    ]);

    return Participante::create($request->all());
  }

  public function destroy($id)
  {
    Participante::findOrFail($id)->delete();
    return response()->json(['message' => 'Participante excluído com sucesso']);
  }
}
