<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EventoController extends Controller
{
  public function index()
  {
    return Evento::all();
  }

  public function pesquisar(Request $request)
  {
    try {
      $query = Evento::query();

      if ($request->filled('nome')) {
        $query->where('nome', 'like', '%' . $request->nome . '%');
      }

      if ($request->filled('datainicio')) {
        $query->where('datainicio', '>=', $request->datainicio);
      }

      if ($request->filled('datafim')) {
        $query->where('datafim', '<=', $request->datafim);
      }

      return $query->get();
    } catch (\Exception $e) {
      Log::error('Erro ao pesquisar eventos: ' . $e->getMessage(), [
        'request' => $request->all(),
        'exception' => $e,
      ]);
      return response()->json(['error' => 'Erro ao pesquisar eventos.'], 500);
    }
  }

  public function store(Request $request)
  {
    try {
      $request->validate([
        'nome' => 'required|string|max:255',
        'datainicio' => 'required|date',
        'datafim' => 'required|date|after_or_equal:datainicio',
      ]);

      return Evento::create($request->all());
    } catch (\Exception $e) {
      Log::error('Erro ao criar evento: ' . $e->getMessage(), [
        'request' => $request->all(),
        'exception' => $e
      ]);

      return response()->json(['error' => 'Erro ao criar evento.'], 500);
    }
  }

  public function show($id)
  {
    return Evento::with('participantes')->findOrFail($id);
  }

  public function update(Request $request, $id)
  {
    $evento = Evento::findOrFail($id);
    $evento->update($request->all());
    return $evento;
  }

  public function destroy($id)
  {
    Evento::findOrFail($id)->delete();
    return response()->json(['message' => 'Evento excluído com sucesso']);
  }
}
