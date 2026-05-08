<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    // GET /api/quotations  — list all
    public function index()
    {
        $quotations = Quotation::with('customer')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($q) {
                $q->is_expired = $q->expiry_date
                    ? now()->startOfDay()->gt($q->expiry_date)
                    : false;
                return $q;
            });

        return response()->json([
            'success' => true,
            'data'    => $quotations,
        ]);
    }

    // GET /api/quotations/{id}  — single quotation for receipt
    public function show($id)
    {
        $quotation = Quotation::with('customer')->findOrFail($id);

        $quotation->is_expired = $quotation->expiry_date
            ? now()->startOfDay()->gt($quotation->expiry_date)
            : false;

        return response()->json([
            'success' => true,
            'data'    => $quotation,
        ]);
    }

    // POST /api/quotations  — create / hold cart
    public function store(Request $request)
    {
        try {
            $request->validate([
                'items'       => 'required|array',
                'total'       => 'required|numeric',
                'date'        => 'required|date',
                'expiry_date' => 'nullable|date|after_or_equal:date',
            ]);

            $quotation = Quotation::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Cart held successfully.',
                'data'    => $quotation,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // DELETE /api/quotations/{id}
    public function destroy($id)
    {
        try {
            Quotation::findOrFail($id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Quotation removed successfully.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
