<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
//use App\Jobs\SendProductNotification; // Adicione a importação da Job

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        // Recupera os produtos do usuário autenticado
        $products = Product::where('user_id', Auth::id())->get();

        // Retorna a lista de produtos
        return response()->json([
            'status' => 'success',
            'products' => $products,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        // Adicione o log aqui
        \Log::info('User ID:', ['user_id' => Auth::id()]);

        // Valida os dados recebidos na requisição
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|string|in:active,inactive,pending',
        ]);

        // Coleta os dados do produto
        $productData = $request->only(['name', 'description', 'price', 'status']);
        $productData['user_id'] = Auth::id(); // Adiciona o ID do usuário autenticado

        // Verifica se uma imagem foi enviada e trata a imagem base64
        if ($request->has('image')) {
            $imageData = $request->input('image');

            // Verifica se a imagem é uma string base64 válida
            if (strpos($imageData, 'data:image/png;base64,') === 0) {
                $image = str_replace('data:image/png;base64,', '', $imageData);
                $image = str_replace(' ', '+', $image); // Corrige espaços em branco
                $imageName = uniqid() . '.png';
                \Storage::disk('public')->put('products/' . $imageName, base64_decode($image));
                $productData['image_path'] = 'products/' . $imageName;
            } else {
                return response()->json(['status' => 'error', 'message' => 'Imagem inválida.'], 400);
            }
        }

        // Tente criar o produto e capture erros
        try {
            $product = Product::create($productData);

            // Enfileirar a Job para enviar notificação
            //SendProductNotification::dispatch($product, 'cadastrado');

            return response()->json(['status' => 'success', 'message' => 'Produto criado com sucesso!', 'product' => $product], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Erro ao criar produto: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): JsonResponse
    {
        // Verifica se o produto pertence ao usuário autenticado
        if ($product->user_id !== Auth::id()) {
            return response()->json(['status' => 'error', 'message' => 'Você não tem permissão para visualizar este produto.'], 403);
        }

        return response()->json(['status' => true, 'product' => $product], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    public function updateStatus(Request $request, $id): JsonResponse
    {
        // Valida o status recebido
        $request->validate([
            'status' => 'required|string|in:active,inactive,pending', // Ajuste os valores conforme necessário
        ]);

        // Encontra o produto pelo ID e verifica se pertence ao usuário autenticado
        $product = Product::where('id', $id)->where('user_id', Auth::id())->first();

        // Verifica se o produto existe e pertence ao usuário autenticado
        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Produto não encontrado ou não pertence a este usuário.'], 403);
        }

        // Atualiza o status do produto
        $product->status = $request->input('status');
        $product->save();

        return response()->json(['status' => 'success', 'message' => 'Status do produto atualizado com sucesso!', 'product' => $product], 200);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        // Valida os dados recebidos na requisição
        $request->validate([
            'name' => 'required|string|max:255', // Nome obrigatório
            'price' => 'required|numeric|min:0', // Preço obrigatório e positivo
            'description' => 'nullable|string',
            'status' => 'required|string|in:active,inactive,pending',
            'image' => 'nullable|string', // Alterada para string se você estiver enviando a imagem em base64
        ]);

        // Encontra o produto pelo ID e verifica se pertence ao usuário autenticado
        $product = Product::where('id', $id)->where('user_id', Auth::id())->first();

        // Verifica se o produto existe e pertence ao usuário autenticado
        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Produto não encontrado ou não pertence a este usuário.'], 403);
        }

        // Coleta os dados do produto a serem atualizados
        $productData = $request->only(['name', 'description', 'price', 'status']);

        // Verifica se uma nova imagem foi enviada
        if ($request->has('image')) {
            $imageData = $request->input('image');

            // Verifica se a imagem é uma string base64 válida
            if (strpos($imageData, 'data:image/png;base64,') === 0) {
                $image = str_replace('data:image/png;base64,', '', $imageData);
                $image = str_replace(' ', '+', $image);
                $imageName = uniqid() . '.png';
                \Storage::disk('public')->put('products/' . $imageName, base64_decode($image));

                // Atualiza o caminho da imagem no banco de dados
                $productData['image_path'] = 'products/' . $imageName;
            } else {
                return response()->json(['status' => 'error', 'message' => 'Imagem inválida.'], 400);
            }
        }

        // Atualiza os atributos do produto
        $product->update($productData);

        // Enfileirar a Job para enviar notificação
        //SendProductNotification::dispatch($product, 'atualizado');

        // Retorna a resposta com o produto atualizado
        return response()->json(['status' => 'success', 'message' => 'Produto atualizado com sucesso!', 'product' => $product], 200);
    }


}
