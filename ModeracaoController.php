<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreModeracaoRequest;
use App\Models\Evento;
use App\Models\Moderacao;
use App\Models\Pergunta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ModeracaoController extends Controller
{
    public function store(StoreModeracaoRequest $request, Pergunta $pergunta)
    {
        Moderacao::create([
            'pergunta_id' => $pergunta->id,
            'motivo' => $request->validated()['motivo'],
            'status' => 'analisando',
        ]);

        return back()->with('sucesso', 'Solicitação de moderação enviada!');
    }

    public function index(Evento $evento)
    {
        Gate::authorize('moderate', $evento);

        $moderacoes = Moderacao::with('pergunta')
            ->whereHas('pergunta', function ($query) use ($evento) {
                $query->where('evento_id', $evento->id);
            })
            ->latest()
            ->paginate(15);

        return view('moderacoes.index', compact('evento', 'moderacoes'));
    }

    public function updateStatus(Request $request, Moderacao $moderacao)
    {
        Gate::authorize('moderate', $moderacao->pergunta->evento);

        $dados = $request->validate([
            'status' => ['required', 'string', 'in:aprovada,rejeitada'],
        ]);

        $moderacao->update([
            'status' => $dados['status'],
        ]);

        $moderacao->pergunta->update([
            'status' => $dados['status'],
            'is_public' => $dados['status'] === 'aprovada',
        ]);

        return back()->with('sucesso', 'Status da moderação atualizado!');
    }
}
