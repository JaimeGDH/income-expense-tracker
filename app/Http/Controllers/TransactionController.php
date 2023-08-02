<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transaction_type_id = $request->query('transaction_type_id');

        $query = Transaction::with('category');

        if ($transaction_type_id !== null) {
            $query->where('transaction_type_id', $transaction_type_id);
        }

        $transactions = $query->get();

        return response()->json($transactions);
    }

    public function store(Request $request)
    {
        $transaction = Transaction::create($request->all());
        return response()->json($transaction, 201);
    }

    public function show($id)
    {
        $transaction = Transaction::findOrFail($id);
        return response()->json($transaction);
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update($request->all());
        return response()->json($transaction);
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();
        return response()->json(null, 204);
    }

    public function filterByTransactionType($id)
    {
        // Filtrar las transacciones por el tipo de transacción
        $transactions = Transaction::where('transaction_type_id', $id)->with('category')->get();

        return response()->json($transactions);
    }

    public function summary()
    {
        // Obtener los datos de ingresos agrupados por mes y año
        $incomeSummary = Transaction::select(
            DB::raw("YEAR(date) as year"),
            DB::raw("MONTH(date) as month"),
            DB::raw("SUM(amount) as total_amount")
        )
        ->where('transaction_type_id', 1) // Filtrar solo los ingresos
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();

        // Obtener los datos de egresos agrupados por mes y año
        $expenseSummary = Transaction::select(
            DB::raw("YEAR(date) as year"),
            DB::raw("MONTH(date) as month"),
            DB::raw("SUM(amount) as total_amount")
        )
        ->where('transaction_type_id', 2) // Filtrar solo los egresos
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();

        return view('summary', compact('incomeSummary', 'expenseSummary'));        
    }
}
