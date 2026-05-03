@extends('layout.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<style>
    :root {
        --maken-amber: #fbbf24;
        --maken-slate: #0f172a;
        --maken-surface: #f1f5f9;
        --maken-white: #ffffff;
        --maken-line: #e2e8f0;
    }

    body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--maken-surface); }
    .report-page { max-width: 1300px; margin: 0 auto; padding: 20px; }
    
    .mk-card { background: var(--maken-white); border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,.05); border: 1px solid var(--maken-line); margin-bottom: 20px; }
    .card-body { padding: 20px; }

    .filter-bar { display: flex; gap: 15px; align-items: flex-end; margin-bottom: 20px; flex-wrap: wrap; }
    .filter-item { display: flex; flex-direction: column; gap: 5px; }
    .filter-item label { font-size: 12px; font-weight: 700; color: var(--maken-slate); }
    .filter-input { height: 38px; border: 1.5px solid var(--maken-line); border-radius: 8px; padding: 0 10px; outline: none; }

    .btn-search { height: 38px; padding: 0 20px; background: var(--maken-amber); border: none; border-radius: 8px; font-weight: 700; cursor: pointer; }

    .mk-table { width: 100%; border-collapse: collapse; }
    .mk-table thead tr { background: #f8fafc; border-bottom: 2px solid var(--maken-line); }
    .mk-table th { padding: 12px 15px; text-align: left; font-size: 12px; text-transform: uppercase; color: #64748b; }
    .mk-table td { padding: 12px 15px; border-bottom: 1px solid var(--maken-line); font-size: 14px; }

    .badge { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; }
    .badge-info { background: #e0f2fe; color: #0369a1; }

    /* Modal */
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
    .modal-content { background: #fff; margin: 5% auto; padding: 25px; width: 600px; border-radius: 15px; }
    .modal-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 15px; }
    .close { cursor: pointer; font-size: 20px; }
</style>

<div class="report-page">
    <h2 style="margin-bottom:20px;">Sales Report</h2>

    <div class="mk-card">
        <div class="card-body">
            <div class="filter-bar">
                <div class="filter-item">
                    <label>Start Date</label>
                    <input type="date" id="start_date" class="filter-input">
                </div>
                <div class="filter-item">
                    <label>End Date</label>
                    <input type="date" id="end_date" class="filter-input">
                </div>
                <div class="filter-item">
                    <label>Customer</label>
                    <select id="customer_id" class="filter-input" style="width:200px;">
                        <option value="">All Customers</option>
                    </select>
                </div>
                <button class="btn-search" onclick="fetchSales()">
                    <i class="fas fa-search"></i> FILTER
                </button>
            </div>
        </div>
    </div>

    <div class="mk-card">
        <div class="card-body" style="overflow-x:auto;">
            <table class="mk-table">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Total Amount</th>
                        <th>Items</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="salesTableBody">
                    <tr><td colspan="6" style="text-align:center; padding:30px;">Set filters and click search.</td></tr>
                </tbody>
                <tfoot>
                    <tr style="background:#f8fafc; font-weight:800;">
                        <td colspan="3" style="text-align:right;">TOTAL REVENUE:</td>
                        <td id="totalRevenue" colspan="3">Rs. 0.00</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div id="detailsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Sale Details #<span id="modalInvoiceId"></span></h3>
            <div style="display:flex; gap:10px; align-items:center;">
                <button onclick="printSale(document.getElementById('modalInvoiceId').textContent)" style="padding:5px 15px; background:var(--maken-amber); border:none; border-radius:5px; font-weight:700; cursor:pointer;">
                    <i class="fas fa-print"></i> PRINT
                </button>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
        </div>
        <div style="margin-top:20px;">
            <p><strong>Customer:</strong> <span id="modalCustomer"></span></p>
            <p><strong>Date:</strong> <span id="modalDate"></span></p>
            <table class="mk-table" style="margin-top:15px;">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody id="modalBody"></tbody>
            </table>
            <div style="text-align:right; margin-top:15px; font-weight:800; font-size:18px;">
                Total: Rs. <span id="modalTotal"></span>
            </div>
        </div>
    </div>
</div>

<script>
let allSales = [];

function loadInitial() {
    axios.get('/api/customers?name_list=true').then(r => {
        const select = document.getElementById('customer_id');
        r.data.data.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c.id;
            opt.textContent = c.name;
            select.appendChild(opt);
        });
    });
    
    // Set default dates (current month)
    const now = new Date();
    const firstDay = new Date(now.getFullYear(), now.getMonth(), 1).toISOString().split('T')[0];
    const lastDay = now.toISOString().split('T')[0];
    document.getElementById('start_date').value = firstDay;
    document.getElementById('end_date').value = lastDay;
    
    fetchSales();
}

function fetchSales() {
    const start = document.getElementById('start_date').value;
    const end = document.getElementById('end_date').value;
    const cid = document.getElementById('customer_id').value;
    
    let url = `/api/sales?start_date=${start}&end_date=${end}`;
    if (cid) url += `&customer_id=${cid}`;
    
    document.getElementById('salesTableBody').innerHTML = '<tr><td colspan="6" style="text-align:center;">Loading...</td></tr>';
    
    axios.get(url).then(r => {
        allSales = r.data.data;
        renderSales();
    });
}

function renderSales() {
    const body = document.getElementById('salesTableBody');
    let totalRev = 0;
    
    if (allSales.length === 0) {
        body.innerHTML = '<tr><td colspan="6" style="text-align:center; padding:30px;">No sales found for selected period.</td></tr>';
        document.getElementById('totalRevenue').textContent = 'Rs. 0.00';
        return;
    }
    
    body.innerHTML = allSales.map(s => {
        totalRev += parseFloat(s.total_bill);
        return `
            <tr>
                <td>INV-${s.id}</td>
                <td>${s.date}</td>
                <td style="font-weight:700;">${s.customer ? s.customer.name : 'Unknown'}</td>
                <td style="font-weight:700; color:var(--maken-amber-dark)">Rs. ${parseFloat(s.total_bill).toLocaleString()}</td>
                <td><span class="badge badge-info">${s.details.length} Items</span></td>
                <td>
                    <div style="display:flex; gap:10px;">
                        <button onclick="viewDetails(${s.id})" style="border:none; background:none; color:#0ea5e9; cursor:pointer;" title="View Details"><i class="fas fa-eye"></i></button>
                        <button onclick="printSale(${s.id})" style="border:none; background:none; color:var(--maken-amber-dark); cursor:pointer;" title="Print Invoice"><i class="fas fa-print"></i></button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
    
    document.getElementById('totalRevenue').textContent = `Rs. ${totalRev.toLocaleString()}`;
}

function viewDetails(id) {
    const s = allSales.find(s => s.id === id);
    document.getElementById('modalInvoiceId').textContent = s.id;
    document.getElementById('modalCustomer').textContent = s.customer ? s.customer.name : 'Unknown';
    document.getElementById('modalDate').textContent = s.date;
    document.getElementById('modalTotal').textContent = parseFloat(s.total_bill).toLocaleString();
    
    document.getElementById('modalBody').innerHTML = s.details.map(d => `
        <tr>
            <td>${d.product ? d.product.name : 'Unknown'}</td>
            <td>Rs. ${parseFloat(d.price).toLocaleString()}</td>
            <td>${d.qty}</td>
            <td style="font-weight:700;">Rs. ${parseFloat(d.total).toLocaleString()}</td>
        </tr>
    `).join('');
    
    document.getElementById('detailsModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('detailsModal').style.display = 'none';
}

window.onclick = function(event) {
    if (event.target == document.getElementById('detailsModal')) closeModal();
}

window.onload = loadInitial;
</script>

@endsection
