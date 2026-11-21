<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventoFormRequest;
use App\Http\Requests\StorePerguntaRequest;
use App\Models\Evento;
use App\Models\Pergunta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class EventoController extends Controller
{
    public function index()
    {
        $eventos = Evento::all();

        return view('eventos.index', compact('eventos'));
    }

    
    public function show($id)
    {
        $evento = Evento::findOrFail($id);

        // SPRINT 06 - TICKET #012
        $perguntas = Pergunta::where('evento_id', $evento->id)
            ->where('is_public', true)
            ->with('user')
            ->withCount('votos')
            ->orderByDesc('votos_count')
            ->latest()
            ->paginate(10);

        return view('eventos.show', compact('evento', 'perguntas'));
    }


    public function storePergunta(StorePerguntaRequest $request, $id)
    {
        $evento = Evento::findOrFail($id);

        Pergunta::create([
            'evento_id' => $evento->id,
            'user_id'   => Auth::id(),
            'texto'     => $request->input('texto'),
            'status'    => 'pendente',
            'is_public' => false,
        ]);

        return redirect()->route('eventos.show', $evento->id)
            ->with('sucesso', 'Sua pergunta foi enviada com sucesso!');
    }

    // SPRINT 05 - TICKET #003
    public function destroyPergunta(Pergunta $pergunta)
    {
        Gate::authorize('delete', $pergunta);

        $eventoId = $pergunta->evento_id;

        $pergunta->delete();

        return redirect()->route('eventos.show', $eventoId)
            ->with('sucesso', 'Pergunta excluída com sucesso!');
    }

    // SPRINT 06 - TICKET #011
    public function votar(Pergunta $pergunta)
    {
        $pergunta->votos()->toggle(Auth::id());

        return back()->with('sucesso', 'Voto atualizado com sucesso!');
    }

    public function create()
    {
        return view('eventos.create');
    }

    public function store(EventoFormRequest $request)
    {
        $evento = $request->user()->eventos()
            ->create($request->validated());

        return redirect()->route('eventos.show', $evento->id);
    }
}
