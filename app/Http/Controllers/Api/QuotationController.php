<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Quotation::with('customer')->orderBy('id', 'desc')->get()
        ]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'items' => 'required|array',
                'total' => 'required|numeric',
                'date' => 'required|date',
            ]);

            $quotation = Quotation::create($request->all());
            return response()->json(['success' => true, 'message' => 'Cart held successfully.', 'data' => $quotation], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            Quotation::findOrFail($id)->delete();
            return response()->json(['success' => true, 'message' => 'Quotation removed successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
