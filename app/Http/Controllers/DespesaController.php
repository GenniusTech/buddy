<?php

namespace App\Http\Controllers;

use App\Models\Despesa;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DespesaController extends Controller
{
    public function store(Request $request)
    {
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

        Despesa::create($request->all());

        return response()->json('Sucesso!');
    }

    public function destroy(Request $request, $id)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        Despesa::where('id', $id)->delete();

        return response()->json('Sucesso!');
    }

    public function check(Request $request, $id)
    {
        $request->validate([
            'check' => 'required|integer',
            'token' => 'required|string',
        ]);

        $despesa = Despesa::find($id);

        if (!$despesa) {
            return response()->json('Despesa não encontrada.', 404);
        }

        if ($despesa->check == 1) {
            return response()->json('Despesa já atribuída.');
        }

        $wallet = Wallet::where('id', $despesa->wallet)->first();
        $wallet->valor -= $despesa->valor;
        $wallet->save();

        $despesa->check = 1;
        $despesa->save();

        return response()->json('Sucesso!');
    }

    public function index(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'check' => 'required|integer',
            'dataInicial' => 'date',
            'dataFinal' => 'date',
        ]);

        $query = Despesa::where('id_user', $request->id)
            ->where('check', $request->check);

        if ($request->has('dataInicial')) {
            $dataInicial = Carbon::createFromFormat('Y-m-d', $request->dataInicial)->startOfDay();
            $query->whereDate('data', '>=', $dataInicial);
        }

        if ($request->has('dataFinal')) {
            $dataFinal = Carbon::createFromFormat('Y-m-d', $request->dataFinal)->endOfDay();
            $query->whereDate('data', '<=', $dataFinal);
        }

        $despesas = $query->get();

        return response()->json($despesas);
    }
}

