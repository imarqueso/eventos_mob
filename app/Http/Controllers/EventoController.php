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
    } catch (\Illuminate\Validation\ValidationException $e) {
      return response()->json([
        'errors' => $e->errors()
      ], 422);
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

  public function mostrar($id)
  {
    $evento = Evento::with(['participantes.presencas'])->findOrFail($id);

    $evento->participantes = $evento->participantes->map(function ($participante) use ($evento) {
      $totalDiasEvento = $this->calcularTotalDiasEvento($evento->datainicio, $evento->datafim);
      $diasComPresenca = $this->calcularDiasComPresenca($participante->presencas);

      $participante->porcentagem_presenca = ($diasComPresenca / $totalDiasEvento) * 100;
      return $participante;
    });

    return response()->json($evento);
  }

  private function calcularTotalDiasEvento($inicio, $fim)
  {
    $inicio = new \DateTime($inicio);
    $fim = new \DateTime($fim);
    $diff = $inicio->diff($fim);
    return $diff->days + 1;
  }

  private function calcularDiasComPresenca($presencas)
  {
    $diasComPresenca = new \Illuminate\Support\Collection();

    foreach ($presencas as $presenca) {
      $dataPresenca = \Carbon\Carbon::parse($presenca->data)->toDateString();
      $diasComPresenca->add($dataPresenca);
    }

    return $diasComPresenca->unique()->count();
  }

  public function update(Request $request, $id)
  {
    try {
      $request->validate([
        'nome' => 'required|string|max:255',
        'datainicio' => 'required|date',
        'datafim' => 'required|date|after_or_equal:datainicio',
      ]);

      $evento = Evento::findOrFail($id);
      $evento->update($request->all());
      return $evento;
    } catch (\Illuminate\Validation\ValidationException $e) {
      return response()->json([
        'errors' => $e->errors()
      ], 422);
    } catch (\Exception $e) {
      Log::error('Erro ao editar evento: ' . $e->getMessage(), [
        'request' => $request->all(),
        'exception' => $e
      ]);

      return response()->json(['error' => 'Erro ao editar evento.'], 500);
    }
  }

  public function destroy($id)
  {
    Evento::findOrFail($id)->delete();
    return response()->json(['message' => 'Evento excluído com sucesso']);
  }
}
