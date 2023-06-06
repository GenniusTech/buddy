<?php

namespace App\Http\Controllers;

use App\Models\Despesa;
use Illuminate\Http\Request;

class DespesaController extends Controller
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
            'token' => 'required|string',
        ]);

        // Criação da despesa
        Despesa::create($request->all());

        // Retorno da resposta
        return response()->json('Sucesso!');
    }

    public function destroy(Request $request, $id)
    {
        // Validação dos campos recebidos no corpo da requisição
        $request->validate([
            'token' => 'required|string',
        ]);

        // Exclusão da despesa
        Despesa::where('id', $id)->delete();

        // Retorno da resposta
        return response()->json('Sucesso!');
    }

    public function check(Request $request, $id)
    {
        // Validação dos campos recebidos no corpo da requisição
        $request->validate([
            'check' => 'required|integer',
            'token' => 'required|string',
        ]);

        // Atualização da wallet com o valor enviado via check (REDUZIR)
        Despesa::where('id', $id)->decrement('wallet', $request->check);

        // Retorno da resposta
        return response()->json('Sucesso!');
    }

    public function index(Request $request)
    {
        // Validação dos campos enviados na requisição GET
        $request->validate([
            'id' => 'required|integer',
            'check' => 'required|integer',
            'dataInicial' => 'date',
            'dataFinal' => 'date',
        ]);

        // Construção da consulta base
        $query = Despesa::where('id_user', $request->id)
            ->where('check', $request->check);

        // Aplicação dos filtros opcionais
        if ($request->has('dataInicial')) {
            $query->whereDate('data', '>=', $request->dataInicial);
        }

        if ($request->has('dataFinal')) {
            $query->whereDate('data', '<=', $request->dataFinal);
        }

        // Execução da consulta
        $despesas = $query->get();

        // Retorno da resposta com os dados das despesas
        return response()->json($despesas);
    }
}

