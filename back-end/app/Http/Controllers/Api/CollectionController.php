<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true,
            'data' => [],
            'message' => 'Coleções listadas com sucesso'
        ]);
    }

    public function store(Request $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'Coleção criada com sucesso'
        ], 201);
    }

    public function show($id)
    {
        return response()->json([
            'status' => true,
            'data' => null,
            'message' => 'Coleção encontrada'
        ]);
    }

    public function update(Request $request, $id)
    {
        return response()->json([
            'status' => true,
            'message' => 'Coleção atualizada com sucesso'
        ]);
    }

    public function destroy($id)
    {
        return response()->json([
            'status' => true,
            'message' => 'Coleção removida com sucesso'
        ]);
    }

    public function search($term)
    {
        return response()->json([
            'status' => true,
            'data' => [],
            'message' => 'Busca realizada com sucesso'
        ]);
    }
}
