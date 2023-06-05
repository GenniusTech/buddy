<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
 public function cadastrarUser(Request $request)
{
    $dados = $request->validate([
        'nome' => 'required',
        'cpfcnpj' => 'required',
        'apelido' => 'required',
        'seguranca' => 'required',
        'email' => 'required|email',
        'senha' => 'required',
    ]);

    // Verificar se o email já existe
    $emailExistente = User::where('email', $dados['email'])->exists();
    if ($emailExistente) {
        return response()->json(['message' => 'Email existente'], 400);
    }

    // Criptografar a senha
    $dados['senha'] = bcrypt($dados['senha']);

    // Salvar o usuário no banco de dados
    $usuario = User::create($dados);

    // Retornar uma resposta de sucesso
    return response()->json(['message' => 'Usuário cadastrado com sucesso'], 201);
}


public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'senha' => 'required',
    ]);

    $user = User::where('email', $credentials['email'])->first();

    if (!$user || !Hash::check($credentials['senha'], $user->senha)) {
        return response()->json(['message' => 'Usuário não existe ou senha incorreta'], 401);
    }

    $user->makeHidden('senha');

    return response()->json(['user' => $user]);
}

public function resetPassword(Request $request)
{
    $dados = $request->validate([
        'email' => 'required|email',
        'seguranca' => 'required',
        'senha' => 'required',
    ]);

    $user = User::where('email', $dados['email'])->first();

    if (!$user || $user->seguranca !== $dados['seguranca']) {
        return response()->json(['message' => 'Dados inválidos'], 400);
    }

    if ($user->seguranca === $dados['seguranca']) {
        $user->senha = Hash::make($dados['senha']);
        $user->save();

        return response()->json(['message' => 'Senha alterada com sucesso']);
    }

    return response()->json(['message' => 'Dados inválidos'], 400);
}

public function listUser()
{
    $users = User::select('id', 'nome', 'cpfcnpj', 'apelido', 'seguranca', 'email')->get();

    return response()->json(['users' => $users]);
}

}
