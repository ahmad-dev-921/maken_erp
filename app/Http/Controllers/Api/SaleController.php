<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Sale::with('customer', 'details.product');

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('date', [$request->start_date, $request->end_date]);
            }

            if ($request->filled('customer_id')) {
                $query->where('customer_id', $request->customer_id);
            }

            $data = $query->orderBy('id', 'desc')->paginate($request->get('limit', 25));
            return response()->json([
                'success' => true,
                'data' => $data->items(),
                'total' => $data->total()
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $sale = Sale::with('customer', 'details.product')->findOrFail($id);
            return response()->json(['success' => true, 'data' => $sale]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'total_bill' => 'required|numeric',
                'date' => 'required|date',
                'items' => 'required|array',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.qty' => 'required|integer|min:1',
                'items.*.price' => 'required|numeric',
                'items.*.total' => 'required|numeric',
            ]);

            return DB::transaction(function () use ($request) {
                $sale = Sale::create([
                    'customer_id' => $request->customer_id,
                    'total_bill' => $request->total_bill,
                    'date' => $request->date,
                ]);

                foreach ($request->items as $item) {
                    SaleDetail::create([
                        'sale_id' => $sale->id,
                        'product_id' => $item['product_id'],
                        'qty' => $item['qty'],
                        'price' => $item['price'],
                        'total' => $item['total'],
                    ]);

                    // Update stock
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $product->decrement('qty', $item['qty']);
                    }
                }

                return response()->json(['success' => true, 'message' => 'Sale recorded successfully.', 'sale_id' => $sale->id], 201);
            });
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $ids = explode(',', $request->get('ids'));
            Sale::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Sales deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
