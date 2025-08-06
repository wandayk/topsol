<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FinancialController extends Controller
{
    public function dashboard()
    {
        return response()->json([
            'status' => true,
            'data' => [
                'total_revenue' => 0,
                'total_expenses' => 0,
                'pending_payments' => 0,
                'monthly_revenue' => 0
            ],
            'message' => 'Dashboard financeiro carregado com sucesso'
        ]);
    }

    public function addPayment(Request $request, $noteId)
    {
        return response()->json([
            'status' => true,
            'message' => 'Pagamento adicionado com sucesso'
        ]);
    }

    public function updatePayment(Request $request, $noteId, $paymentId)
    {
        return response()->json([
            'status' => true,
            'message' => 'Pagamento atualizado com sucesso'
        ]);
    }

    public function reports()
    {
        return response()->json([
            'status' => true,
            'data' => [],
            'message' => 'Relatórios carregados com sucesso'
        ]);
    }
}
