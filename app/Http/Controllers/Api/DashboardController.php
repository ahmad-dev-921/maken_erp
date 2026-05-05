<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $today = Carbon::today();
            $month = Carbon::now()->startOfMonth();

            // =========================
            // BASIC STATS
            // =========================
            $stats = [
                'today_sales' => Sale::whereDate('date', $today)->sum('total_bill'),
                'month_sales' => Sale::where('date', '>=', $month)->sum('total_bill'),
                'total_customers' => Customer::count(),
                'total_products' => Product::count(),
                'low_stock' => Product::where('qty', '<=', 5)->count(),
            ];

            // =========================
            // RECENT SALES
            // =========================
            $recent_sales = Sale::with('customer')
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get();

            // =========================
            // WEEKLY GRAPH (Mon-Sun)
            // =========================
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();

            $weeklySales = Sale::select(
                    DB::raw('DAYNAME(date) as day'),
                    DB::raw('SUM(total_bill) as total')
                )
                ->whereBetween('date', [$startOfWeek, $endOfWeek])
                ->groupBy('day')
                ->get();

            $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];

            $weeklyData = [];
            foreach ($days as $day) {
                $found = $weeklySales->firstWhere('day', $day);
                $weeklyData[] = [
                    'day' => $day,
                    'total' => $found ? (float)$found->total : 0
                ];
            }

            // =========================
            // MONTHLY GRAPH (Last 6 Months)
            // =========================
            $start = Carbon::now()->subMonths(5)->startOfMonth();
            $end = Carbon::now()->endOfMonth();

            $monthlySales = Sale::select(
                    DB::raw('MONTHNAME(date) as month'),
                    DB::raw('SUM(total_bill) as total')
                )
                ->whereBetween('date', [$start, $end])
                ->groupBy('month')
                ->orderByRaw('MIN(date)')
                ->get();

            $months = [];
            for ($i = 5; $i >= 0; $i--) {
                $months[] = Carbon::now()->subMonths($i)->format('F');
            }

            $monthlyData = [];
            foreach ($months as $m) {
                $found = $monthlySales->firstWhere('month', $m);
                $monthlyData[] = [
                    'month' => $m,
                    'total' => $found ? (float)$found->total : 0
                ];
            }

            // =========================
            // FINAL RESPONSE
            // =========================
            return response()->json([
                'success' => true,
                'stats' => $stats,
                'recent_sales' => $recent_sales,
                'weekly_sales' => $weeklyData,
                'monthly_sales' => $monthlyData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
