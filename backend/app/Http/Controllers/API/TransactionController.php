<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::query();

        // Filtrado por fecha
        if ($request->has('date_from')) {
            $query->where('creation_date', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->where('creation_date', '<=', $request->date_to);
        }

        // Filtrado por tipo
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Ordenamiento
        $sortField = $request->input('sort_by', 'creation_date');
        $sortDirection = $request->input('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        // Paginación
        return $query->paginate($request->input('per_page', 15));
    }

    public function store(TransactionRequest $request)
    {
        try {
            DB::beginTransaction();

            $transaction = new Transaction($request->validated());
            
            // Generar identificadores únicos
            $transaction->transaction_id = 'TXN-' . Str::random(10);
            $transaction->trace_number = 'TRC-' . time() . '-' . Str::random(5);
            
            $transaction->save();

            DB::commit();

            return response()->json([
                'message' => 'Transaction created successfully',
                'data' => $transaction
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error creating transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $transaction = Transaction::where('transaction_id', $id)
                                    ->orWhere('id', $id)
                                    ->firstOrFail();
            
            $transaction->delete();

            return response()->json([
                'message' => 'Transaction deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
