@extends('layout.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    :root {
        --maken-amber: #fbbf24;
        --maken-amber-dark: #d97706;
        --maken-slate: #0f172a;
        --maken-slate-2: #1e293b;
        --maken-surface: #f1f5f9;
        --maken-white: #ffffff;
        --maken-line: #e2e8f0;
        --radius-lg: 16px;
    }

    .dash-container { max-width: 1300px; margin: 0 auto; animation: fadeIn 0.5s ease; }
    @keyframes fadeIn { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }

    .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .stat-card { background: var(--maken-white); padding: 24px; border-radius: var(--radius-lg); box-shadow: 0 4px 15px rgba(0,0,0,.05); border: 1px solid var(--maken-line); display: flex; align-items: center; gap: 20px; transition: transform 0.3s ease; }
    .stat-card:hover { transform: translateY(-5px); }
    
    .stat-icon { width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
    .icon-sales { background: #fffbeb; color: #d97706; }
    .icon-cust { background: #eff6ff; color: #2563eb; }
    .icon-prod { background: #f0fdf4; color: #16a34a; }
    .icon-warn { background: #fef2f2; color: #dc2626; }

    .stat-info h3 { font-size: 13px; color: #64748b; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px; }
    .stat-info .val { font-size: 24px; font-weight: 800; color: var(--maken-slate); }

    .main-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; }
    @media(max-width: 991px) { .main-grid { grid-template-columns: 1fr; } }

    .mk-card { background: var(--maken-white); border-radius: var(--radius-lg); box-shadow: 0 4px 15px rgba(0,0,0,.05); border: 1px solid var(--maken-line); overflow: hidden; }
    .card-header { padding: 20px; border-bottom: 1px solid var(--maken-line); display: flex; justify-content: space-between; align-items: center; font-weight: 700; }
    .card-body { padding: 20px; }

    .recent-table { width: 100%; border-collapse: collapse; }
    .recent-table th { text-align: left; font-size: 11px; color: #94a3b8; text-transform: uppercase; padding: 12px 15px; }
    .recent-table td { padding: 12px 15px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
    .recent-table tr:last-child td { border-bottom: none; }

    .quick-link { display: flex; align-items: center; gap: 12px; padding: 12px; border-radius: 12px; text-decoration: none; color: var(--maken-slate); background: #f8fafc; margin-bottom: 10px; transition: all 0.2s; border: 1.5px solid transparent; }
    .quick-link:hover { background: #fff; border-color: var(--maken-amber); transform: translateX(5px); }
    .quick-link i { width: 32px; height: 32px; background: #fff; border-radius: 8px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 5px rgba(0,0,0,.05); color: var(--maken-amber-dark); }
</style>

<div class="dash-container">
    <div style="margin-bottom:25px;">
        <h2 style="font-weight:800; color:var(--maken-slate);">Business Overview</h2>
        <p style="color:#64748b; font-size:14px;">Welcome back! Here's what's happening today.</p>
    </div>

    <!-- Stats Section -->
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon icon-sales"><i class="fas fa-chart-line"></i></div>
            <div class="stat-info">
                <h3>Today's Sales</h3>
                <div class="val" id="todaySales">Rs. 0</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon icon-cust"><i class="fas fa-users"></i></div>
            <div class="stat-info">
                <h3>Total Customers</h3>
                <div class="val" id="totalCust">0</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon icon-prod"><i class="fas fa-box"></i></div>
            <div class="stat-info">
                <h3>Products In Stock</h3>
                <div class="val" id="totalProd">0</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon icon-warn"><i class="fas fa-triangle-exclamation"></i></div>
            <div class="stat-info">
                <h3>Low Stock Items</h3>
                <div class="val" id="lowStock">0</div>
            </div>
        </div>
    </div>

    <div class="main-grid">
        <!-- Recent Sales -->
        <div class="mk-card">
            <div class="card-header">
                <span><i class="fas fa-receipt" style="color:var(--maken-amber-dark); margin-right:8px;"></i> Recent Transactions</span>
                <a href="/report" style="font-size:12px; color:var(--maken-amber-dark); text-decoration:none;">View All</a>
            </div>
            <div class="card-body" style="padding:0;">
                <table class="recent-table">
                    <thead>
                        <tr>
                            <th>Inv #</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th style="text-align:right;">Amount</th>
                        </tr>
                    </thead>
                    <tbody id="recentSalesBody">
                        <tr><td colspan="4" style="text-align:center; padding:30px; color:#94a3b8;">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mk-card">
            <div class="card-header">
                <span><i class="fas fa-bolt" style="color:var(--maken-amber-dark); margin-right:8px;"></i> Quick Actions</span>
            </div>
            <div class="card-body">
                <a href="/pos" class="quick-link">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Open POS Terminal</span>
                </a>
                <a href="/inventory" class="quick-link">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add New Product</span>
                </a>
                <a href="/customer" class="quick-link">
                    <i class="fas fa-user-plus"></i>
                    <span>Add New Customer</span>
                </a>
                <a href="/report" class="quick-link">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Monthly Sales Report</span>
                </a>
            </div>
        </div>

    </div>
    <div class="main-grid" style="margin-top: 20px;">

    <!-- Weekly Sales -->
    <div class="mk-card">
        <div class="card-header">
            <span>
                <i class="fas fa-chart-bar" style="color:var(--maken-amber-dark); margin-right:8px;"></i>
                Weekly Sales
            </span>
        </div>
        <div class="card-body">
            <canvas id="weeklyChart" height="120"></canvas>
        </div>
    </div>

    <!-- Monthly Sales -->
    <div class="mk-card">
        <div class="card-header">
            <span>
                <i class="fas fa-chart-line" style="color:var(--maken-amber-dark); margin-right:8px;"></i>
                Monthly Sales
            </span>
        </div>
        <div class="card-body">
            <canvas id="monthlyChart" height="255"></canvas>
        </div>
    </div>

</div>
</div>

<script>
let weeklyChartInstance = null;
let monthlyChartInstance = null;

function loadDashboard() {
    axios.get('/api/dashboard-stats').then(r => {
        const d = r.data;
        const stats = d.stats;

        // ===== Stats =====
        document.getElementById('todaySales').textContent = 'Rs. ' + parseFloat(stats.today_sales).toLocaleString();
        document.getElementById('totalCust').textContent = stats.total_customers;
        document.getElementById('totalProd').textContent = stats.total_products;
        document.getElementById('lowStock').textContent = stats.low_stock;

        // ===== Recent Sales =====
        const body = document.getElementById('recentSalesBody');

        if (d.recent_sales.length === 0) {
            body.innerHTML = '<tr><td colspan="4">No recent sales</td></tr>';
        } else {
            body.innerHTML = d.recent_sales.map(s => `
                <tr>
                    <td>INV-${s.id}</td>
                    <td>${s.customer ? s.customer.name : 'Walking Customer'}</td>
                    <td>${s.date}</td>
                    <td style="text-align:right;">Rs. ${parseFloat(s.total_bill).toLocaleString()}</td>
                </tr>
            `).join('');
        }

        // ===== WEEKLY GRAPH =====
        const weeklyLabels = d.weekly_sales.map(x => x.day);
        const weeklyValues = d.weekly_sales.map(x => x.total);

        if (weeklyChartInstance) weeklyChartInstance.destroy();

        weeklyChartInstance = new Chart(document.getElementById('weeklyChart'), {
            type: 'bar',
            data: {
                labels: weeklyLabels,
                datasets: [{
                    data: weeklyValues,
                    backgroundColor: '#fbbf24'
                }]
            },
            options: {
                plugins: { legend: { display: false } }
            }
        });

        // ===== MONTHLY GRAPH =====
        const monthlyLabels = d.monthly_sales.map(x => x.month);
        const monthlyValues = d.monthly_sales.map(x => x.total);

        if (monthlyChartInstance) monthlyChartInstance.destroy();

        monthlyChartInstance = new Chart(document.getElementById('monthlyChart'), {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    data: monthlyValues,
                    borderColor: '#d97706',
                    fill: true,
                    tension: 0.4
                }]
            }
        });

    }).catch(err => console.error(err));
}

window.onload = loadDashboard;
</script>

@endsection
