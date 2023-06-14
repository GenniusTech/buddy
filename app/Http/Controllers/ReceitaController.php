<?php

namespace App\Http\Controllers;

use App\Models\Receita;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReceitaController extends Controller
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
        ]);

        $wallet = Receita::create([
            'titulo' => $request->titulo,
            'valor' => $request->valor,
            'tag' => $request->tag,
            'check' => $request->check,
            'data' => date('Y-m-d', strtotime($request->data)),
            'wallet' => $request->wallet,
            'id_user' => $request->id_user,
        ]);

        return response()->json('Sucesso!');
    }

    public function destroy(Request $request)
    {

        $request->validate([
            'id' => 'required|integer',
        ]);

        $receita = Receita::find($request->id);

        if (!$receita) {
            return response()->json('Receita não encontrada.', 404);
        }

        $receita->delete();

        return response()->json('Sucesso!');
    }

    public function verify(Request $request)
    {

        $request->validate([
            'id' => 'required|integer',
        ]);

        $receita = Receita::find($request->id);

        if (!$receita) {
            return response()->json('Receita não encontrada.', 404);
        }

        if ($receita->check == 1) {
            return response()->json('Receita já atribuída!');
        }

        Wallet::where('id', $receita->wallet)->increment('valor', $receita->valor);

        $receita->check = 1;
        $receita->save();

        return response()->json('Sucesso!');
    }

    public function index(Request $request)
    {

        $request->validate([
            'id' => 'integer',
            'check' => 'integer',
            'dataInicial' => 'date',
            'dataFinal' => 'date',
        ]);

        $query = Receita::query();

        if ($request->has('id')) {
            $query->where('id_user', $request->id);
        }

        if ($request->has('check')) {
            $query->where('check', '=', $request->check);
        }

        if ($request->has('dataInicial')) {
            $dataInicial = Carbon::createFromFormat('Y-m-d', $request->dataInicial)->startOfDay();
            $query->where('created_at', '>=', $dataInicial);
        }

        if ($request->has('dataFinal')) {
            $dataFinal = Carbon::createFromFormat('Y-m-d', $request->dataFinal)->endOfDay();
            $query->where('created_at', '<=', $dataFinal);
        }

        $receitas = $query->get();

        return response()->json($receitas);
    }



}
