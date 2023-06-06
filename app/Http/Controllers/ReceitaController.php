<?php

namespace App\Http\Controllers;

use App\Models\Receita;
use Illuminate\Http\Request;

class ReceitaController extends Controller
{
    public function store(Request $request)
    {
        // Validação dos campos recebidos no corpo da requisição
        $request->validate([
            'titulo' => 'required|string',
            'valor' => 'required|numeric',
            'tag' => 'required|string',
            'check' => 'required|integer',
            'data' => 'required|date',
            'wallet' => 'required|integer',
            'id_user' => 'required|integer',
        ]);

        // Criação da nova receita
        $receita = Receita::create($request->all());

        // Retorno da resposta
        return response()->json('Sucesso!');
    }

    public function destroy(Request $request)
    {
        // Validação dos campos recebidos no corpo da requisição
        $request->validate([
            'id' => 'required|integer',
        ]);
    
        // Verificação se a receita existe
        $receita = Receita::find($request->id);
    
        if (!$receita) {
            return response()->json('Receita não encontrada.', 404);
        }
    
        // Exclusão da receita
        $receita->delete();
    
        // Retorno da resposta
        return response()->json('Sucesso!');
    }
    
    public function verify(Request $request)
    {
        // Validação dos campos recebidos no corpo da requisição
        $request->validate([
            'id' => 'required|integer',
            'check' => 'required',
        ]);
          // Verificação se a receita existe
          $receita = Receita::find($request->id);
    
          if (!$receita) {
              return response()->json('Receita não encontrada.', 404);
          }
        // Atualização da wallet
        Receita::where('id', $request->id)->increment('wallet', $request->check);

        // Retorno da resposta
        return response()->json('Sucesso!');
    }

    
    public function index(Request $request)
    {
        // Validação dos campos enviados na requisição GET
        $request->validate([
            'id' => 'integer',
            'check' => 'integer',
            'dataInicial' => 'date',
            'dataFinal' => 'date',
        ]);
    
        // Consulta das receitas com base nos critérios fornecidos
        $query = Receita::query();
    
        if ($request->has('id')) {
            $query->where('id_user', $request->id);
        }
    
        if ($request->has('check')) {
            $query->where('check', $request->check);
        }
    
        if ($request->has('dataInicial')) {
            $query->where('Created_at', '>=', $request->dataInicial);
        }
    
        if ($request->has('dataFinal')) {
            $query->where('Created_at', '<=', $request->dataFinal);
        }
    
        // Execução da consulta
        $receitas = $query->get();
    
        // Retorno da resposta com os dados das receitas
        return response()->json($receitas);
    }
    
    
    
}
