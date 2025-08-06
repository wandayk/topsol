<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true,
            'data' => [],
            'message' => 'Notas listadas com sucesso'
        ]);
    }

    public function store(Request $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'Nota criada com sucesso'
        ], 201);
    }

    public function show($id)
    {
        return response()->json([
            'status' => true,
            'data' => null,
            'message' => 'Nota encontrada'
        ]);
    }

    public function update(Request $request, $id)
    {
        return response()->json([
            'status' => true,
            'message' => 'Nota atualizada com sucesso'
        ]);
    }

    public function destroy($id)
    {
        return response()->json([
            'status' => true,
            'message' => 'Nota removida com sucesso'
        ]);
    }

    public function addItem(Request $request, $id)
    {
        return response()->json([
            'status' => true,
            'message' => 'Item adicionado à nota com sucesso'
        ]);
    }

    public function updateItem(Request $request, $noteId, $itemId)
    {
        return response()->json([
            'status' => true,
            'message' => 'Item da nota atualizado com sucesso'
        ]);
    }

    public function removeItem(Request $request, $noteId, $itemId)
    {
        return response()->json([
            'status' => true,
            'message' => 'Item removido da nota com sucesso'
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
