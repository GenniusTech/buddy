<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function store(Request $request)
    {
        // Validação dos campos recebidos no corpo da requisição
        $request->validate([
            'titulo' => 'required|string',
            'id_user' => 'required|integer',
            'token' => 'required|string',
        ]);

        // Criação da tag
        Tag::create($request->all());

        // Retorno da resposta
        return response()->json('Sucesso!');
    }

    public function destroy(Request $request, $id)
    {
        // Validação dos campos recebidos no corpo da requisição
        $request->validate([
            'token' => 'required|string',
        ]);

        // Exclusão da tag
        Tag::where('id', $id)->delete();

        // Retorno da resposta
        return response()->json('Sucesso!');
    }

    public function index(Request $request)
    {
        // Validação dos campos enviados na requisição GET
        $request->validate([
            'id' => 'required|integer',
        ]);

        // Consulta das tags com base no ID_USER e ordenação alfabética
        $tags = Tag::where('id_user', $request->id)
            ->orderBy('titulo', 'asc')
            ->get();

        // Retorno da resposta com os dados das tags
        return response()->json($tags);
    }
}
