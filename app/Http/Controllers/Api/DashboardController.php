<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $today = Carbon::today();
            $month = Carbon::now()->startOfMonth();

            $stats = [
                'today_sales' => Sale::whereDate('date', $today)->sum('total_bill'),
                'month_sales' => Sale::where('date', '>=', $month)->sum('total_bill'),
                'total_customers' => Customer::count(),
                'total_products' => Product::count(),
                'low_stock' => Product::where('qty', '<=', 5)->count(),
            ];

            $recent_sales = Sale::with('customer')
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get();

            return response()->json([
                'success' => true,
                'stats' => $stats,
                'recent_sales' => $recent_sales
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
