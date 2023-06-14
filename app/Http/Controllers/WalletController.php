<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function store(Request $request)
    {
        // Validação dos campos recebidos no corpo da requisição
        $request->validate([
            'titulo' => 'required|string',
            'valor' => 'required|numeric',
            'id_user' => 'required|integer',
        ]);

        // Criação da nova wallet
        $wallet = Wallet::create($request->all());

        // Retorno da resposta com o objeto criado
        return response()->json(['message' => 'Sucesso!', 'data' => $wallet]);

    }

    public function destroy(Request $request)
    {
        // Validação dos campos recebidos no corpo da requisição
        $request->validate([
            'id' => 'required|integer',
        ]);

        // Verificação se a wallet existe
        $wallet = Wallet::find($request->id);

        if (!$wallet) {
            return response()->json('Wallet não encontrada.', 404);
        }

        // Exclusão da wallet
        if ($wallet->delete()) {
            // Retorno da resposta de sucesso
            return response()->json('Sucesso!');
        } else {
            // Retorno da resposta de falha
            return response()->json('Erro ao excluir a wallet.', 500);
        }

    }

    public function index(Request $request)
    {
        $request->validate([
            'id' => 'integer',
        ]);

        $query = Wallet::query();

        if ($request->has('id')) {
            $query->where('id_user', $request->id);
        }

        // Execução da consulta
        $receitas = $query->get();

        // Retorno da resposta com os dados das receitas
        return response()->json($receitas);
    }
}
