<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Exception;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class Usercontroller extends Controller
{


    /**
     * Summary of index
     * Este metodo recupera uma lista de usuarios do banco de dados e retorna como uma resposta JSON.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        // Recuperar os Usuarios
        $user = User::orderBy('id', 'DESC')->get();

        // Retornar os Dados.
        return response()->json([
            'status' => true,
            'Users' => $user,
        ], 200);

    }

    /**
     * Summary of show
     * Metodo que tras os dados de um Usuario específico.
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        //Retorna os dados do usuario
        return response()->json([
            'status' => true,
            'Users' => $user,
        ], 200);
    }

    /**
     * Summary of show
     * Metodo que Cadastra Usuário.
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserRequest $request): JsonResponse
    {
        // Iniciar a transação
        DB::beginTransaction();

        try {
            // Criar o usuário com a senha criptografada
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Criptografando a senha
            ]);

            // Operação concluída
            DB::commit();

            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => "Usuário cadastrado com sucesso!",
            ], 201);

        } catch (Exception $e) {
            // Operação não é concluída com êxito
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => "Usuário não cadastrado! Erro: " . $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Editando o Usuário
     * @param \App\Http\Requests\UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserRequest $request, User $user): JsonResponse
    {
        // Iniciar a transação
        DB::beginTransaction();

        try {
            // Editar
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Criptografando a senha
            ]);

            // Operação concluída
            DB::commit();

            // Retorna os dados do usuário editado e uma mensagem de sucesso com status 200
            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => "Usuário editado com sucesso!",
            ], 200);

        } catch (Exception $e) {
            // Operação não concluída com sucesso
            DB::rollBack();

            // Retorna uma mensagem de erro com status 400
            return response()->json([
                'status' => false,
                'message' => "Usuário não editado!",
            ], 400);
        }
    }


    /**
     * Deletando o Usuário
     * @param \App\Http\Requests\UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            //Apagando registro do banco de dados
            $user->delete();

            //Retorna dados do usuario apagado e uma mensagem de sucesso com status 200
            return response()->json([
                'status' => false,
                'message' => "Usuário apagado com sucesso!",
            ], 400);

        } catch (Exception $e) {
            //Retorna uma mensagem de erro com status 400
            return response()->json([
                'status' => false,
                'message' => "Usuário não apagado!",
                $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Logando o Usuário
     * @param \App\Http\Requests\UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        // Validação dos campos do login
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Verifica se as credenciais são válidas
        if (Auth::attempt($credentials)) {
            // Usuário autenticado com sucesso
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken; // Gerando token

            return response()->json([
                'status' => true,
                'message' => 'Login realizado com sucesso!',
                'user' => $user,
                'token' => $token,
            ], 200);
        } else {
            // Falha na autenticação
            return response()->json([
                'status' => false,
                'message' => 'Credenciais inválidas.',
            ], 401);
        }
    }

}
