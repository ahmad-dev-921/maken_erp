<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Product::query();

            if ($request->filled('search')) {
                $query->where(function($q) use ($request) {
                    $q->where('name', 'LIKE', "%{$request->search}%")
                      ->orWhere('barcode', 'LIKE', "%{$request->search}%");
                });
            }

            if ($request->has('all')) {
                return response()->json([
                    'success' => true,
                    'data' => $query->get()
                ]);
            }

            $data = $query->paginate($request->get('limit', 25));
            return response()->json([
                'success' => true,
                'data' => $data->items(),
                'total' => $data->total(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage()
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'barcode' => 'nullable|string|max:255',
                'price' => 'required|numeric|min:0',
                'qty' => 'required|integer|min:0',
            ]);

            $product = Product::create($request->all());
            return response()->json(['success' => true, 'message' => 'Product created successfully.', 'data' => $product], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Product $product)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'barcode' => 'nullable|string|max:255',
                'price' => 'required|numeric|min:0',
                'qty' => 'required|integer|min:0',
            ]);

            $product->update($request->all());
            return response()->json(['success' => true, 'message' => 'Product updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $ids = explode(',', $request->get('ids'));
            Product::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Products deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
