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
    .quotation-page { max-width: 1200px; margin: 0 auto; padding: 20px; }
    
    .mk-card { background: var(--maken-white); border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,.05); border: 1px solid var(--maken-line); margin-bottom: 20px; }
    .card-body { padding: 20px; }

    .mk-table { width: 100%; border-collapse: collapse; }
    .mk-table thead tr { background: #f8fafc; border-bottom: 2px solid var(--maken-line); }
    .mk-table th { padding: 12px 15px; text-align: left; font-size: 12px; text-transform: uppercase; color: #64748b; }
    .mk-table td { padding: 12px 15px; border-bottom: 1px solid var(--maken-line); font-size: 14px; }

    .btn-action { padding: 5px 12px; border-radius: 6px; border: 1px solid var(--maken-line); background: #fff; cursor: pointer; font-size: 12px; font-weight: 600; }
    .btn-action.restore:hover { background: var(--maken-amber); border-color: var(--maken-amber); }
    .btn-action.delete:hover { background: #fee2e2; color: #ef4444; border-color: #fecaca; }
</style>

<div class="quotation-page">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2>Held Carts / Quotations</h2>
        <a href="/pos" class="btn-action restore" style="text-decoration:none; display:inline-flex; align-items:center; gap:5px; height:38px; padding:0 15px;">
            <i class="fas fa-plus"></i> NEW SALE
        </a>
    </div>

    <div class="mk-card">
        <div class="card-body">
            <table class="mk-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Reference Name</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody id="quotationTableBody">
                    <tr><td colspan="6" style="text-align:center; padding:30px;">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function fetchQuotations() {
    axios.get('/api/quotations').then(r => {
        const body = document.getElementById('quotationTableBody');
        const data = r.data.data;
        
        if (data.length === 0) {
            body.innerHTML = '<tr><td colspan="6" style="text-align:center; padding:30px;">No held carts found.</td></tr>';
            return;
        }
        
        body.innerHTML = data.map(q => `
            <tr>
                <td>${q.date}</td>
                <td style="font-weight:700;">${q.reference_name || 'Unnamed'}</td>
                <td>${q.customer ? q.customer.name : 'Walking Customer'}</td>
                <td>${q.items.length} Items</td>
                <td style="font-weight:700;">Rs. ${parseFloat(q.total).toLocaleString()}</td>
                <td style="text-align:center;">
                    <div style="display:flex; gap:8px; justify-content:center;">
                        <button class="btn-action restore" onclick="restore(${q.id})">Restore to POS</button>
                        <button class="btn-action delete" onclick="remove(${q.id})"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            </tr>
        `).join('');
    });
}

function restore(id) {
    // Redirect to POS with a parameter to load this quotation
    window.location.href = '/pos?restore=' + id;
}

function remove(id) {
    if (!confirm('Delete this quotation?')) return;
    axios.delete('/api/quotations/' + id).then(() => fetchQuotations());
}

window.onload = fetchQuotations;
</script>

@endsection
