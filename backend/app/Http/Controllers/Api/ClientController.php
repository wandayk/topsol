<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true,
            'data' => [],
            'message' => 'Clientes listados com sucesso'
        ]);
    }

    public function store(Request $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'Cliente criado com sucesso'
        ], 201);
    }

    public function show($id)
    {
        return response()->json([
            'status' => true,
            'data' => null,
            'message' => 'Cliente encontrado'
        ]);
    }

    public function update(Request $request, $id)
    {
        return response()->json([
            'status' => true,
            'message' => 'Cliente atualizado com sucesso'
        ]);
    }

    public function destroy($id)
    {
        return response()->json([
            'status' => true,
            'message' => 'Cliente removido com sucesso'
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
