<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true,
            'data' => [],
            'message' => 'Itens listados com sucesso'
        ]);
    }

    public function store(Request $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'Item criado com sucesso'
        ], 201);
    }

    public function show($id)
    {
        return response()->json([
            'status' => true,
            'data' => null,
            'message' => 'Item encontrado'
        ]);
    }

    public function update(Request $request, $id)
    {
        return response()->json([
            'status' => true,
            'message' => 'Item atualizado com sucesso'
        ]);
    }

    public function destroy($id)
    {
        return response()->json([
            'status' => true,
            'message' => 'Item removido com sucesso'
        ]);
    }

    public function uploadImages(Request $request, $id)
    {
        return response()->json([
            'status' => true,
            'message' => 'Imagens enviadas com sucesso'
        ]);
    }

    public function deleteImage(Request $request, $id, $type)
    {
        return response()->json([
            'status' => true,
            'message' => 'Imagem removida com sucesso'
        ]);
    }

    public function getByCollection($collectionId)
    {
        return response()->json([
            'status' => true,
            'data' => [],
            'message' => 'Itens da coleção listados com sucesso'
        ]);
    }
}
