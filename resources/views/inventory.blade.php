@extends('layout.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<style>
    /* ── MAKEN SOLAR DESIGN TOKENS ───────────────── */
    :root {
        --maken-amber:      #fbbf24;
        --maken-amber-dark: #d97706;
        --maken-amber-soft: rgba(251,191,36,.12);
        --maken-amber-glow: rgba(251,191,36,.25);
        --maken-slate:      #0f172a;
        --maken-slate-2:    #1e293b;
        --maken-slate-3:    #334155;
        --maken-slate-4:    #475569;
        --maken-surface:    #f1f5f9;
        --maken-white:      #ffffff;
        --maken-line:       #e2e8f0;
        --maken-success:    #10b981;
        --maken-success-bg: #d1fae5;
        --maken-danger:     #ef4444;
        --maken-danger-bg:  #fee2e2;
        --maken-warn-bg:    #fef3c7;
        --maken-warn:       #d97706;
        --radius:           10px;
        --radius-lg:        14px;
        --shadow-card:      0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.06);
        --shadow-amber:     0 6px 20px rgba(251,191,36,.4);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--maken-surface); color: var(--maken-slate); font-size: 14px; }

    .mk-page { max-width: 1320px; margin: 0 auto; animation: mkIn .4s ease both; }
    @keyframes mkIn { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }

    .mk-title { display: flex; align-items: center; gap: 10px; font-size: 16px; font-weight: 700; color: var(--maken-slate); margin-bottom: 16px; }
    .mk-title::before { content: ''; width: 4px; height: 20px; background: var(--maken-amber); border-radius: 4px; display: block; flex-shrink: 0; }

    .mk-card { background: var(--maken-white); border-radius: var(--radius-lg); box-shadow: var(--shadow-card); border: 1px solid var(--maken-line); margin-bottom: 28px; overflow: hidden; }
    .mk-card-body { padding: 26px 28px 22px; }

    .form-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px 20px; }
    @media(max-width:900px) { .form-grid { grid-template-columns: 1fr 1fr; } }
    @media(max-width:580px) { .form-grid { grid-template-columns: 1fr; } }

    .mk-field label { display: block; font-size: 12.5px; font-weight: 600; color: var(--maken-slate-3); margin-bottom: 6px; }
    .mk-field label .req { color: var(--maken-danger); margin-left: 2px; }

    .mk-input-wrap { position: relative; }
    .mk-input-wrap .ico { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 13px; pointer-events: none; z-index: 1; }

    .mk-input { width: 100%; height: 42px; border: 1.5px solid var(--maken-line); border-radius: var(--radius); background: var(--maken-white); padding: 0 14px 0 36px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13.5px; color: var(--maken-slate); transition: border-color .2s, box-shadow .2s; outline: none; }
    .mk-input:focus { border-color: var(--maken-amber); box-shadow: 0 0 0 3px var(--maken-amber-glow); }

    .form-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; padding-top: 18px; border-top: 1px solid var(--maken-line); }

    .btn-ghost { height: 42px; padding: 0 20px; border-radius: var(--radius); border: 1.5px solid var(--maken-line); background: transparent; color: var(--maken-slate-4); font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13.5px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 7px; transition: all .2s; }
    .btn-amber { height: 42px; padding: 0 22px; border-radius: var(--radius); border: none; background: var(--maken-amber); color: var(--maken-slate); font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13.5px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all .22s; box-shadow: 0 2px 8px var(--maken-amber-glow); }
    .btn-amber:hover { background: var(--maken-amber-dark); color: #fff; transform: translateY(-1px); }
    .btn-amber.slate { background: var(--maken-slate-2); color: #fff; }

    .table-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; padding: 16px 24px; border-bottom: 1px solid var(--maken-line); }
    .search-inp { height: 38px; width: 230px; border: 1.5px solid var(--maken-line); border-radius: 30px; background: var(--maken-surface); padding: 0 16px 0 36px; font-size: 13px; outline: none; transition: all .2s; }
    .search-inp:focus { border-color: var(--maken-amber); background: #fff; }

    .mk-table { width: 100%; border-collapse: collapse; }
    .mk-table thead tr { background: var(--maken-slate); }
    .mk-table thead th { padding: 13px 14px; text-align: left; font-size: 11.5px; font-weight: 600; color: #94a3b8; text-transform: uppercase; }
    .mk-table tbody tr { border-bottom: 1px solid var(--maken-line); transition: background .15s; }
    .mk-table tbody tr:hover { background: #f8fafc; }
    .mk-table td { padding: 13px 14px; font-size: 13.5px; color: var(--maken-slate); }

    .act-btn { width: 30px; height: 30px; border-radius: 7px; border: 1px solid var(--maken-line); background: var(--maken-white); cursor: pointer; font-size: 12px; color: var(--maken-slate-4); display: inline-flex; align-items: center; justify-content: center; transition: all .18s; }
    .act-btn.edit:hover { background: var(--maken-amber); color: var(--maken-slate); border-color: var(--maken-amber); }
    .act-btn.del:hover { background: var(--maken-danger); color: #fff; border-color: var(--maken-danger); }

    .empty-cell { text-align: center; padding: 52px 20px; color: #94a3b8; }
    .mk-popup {
    position: fixed;
    inset: 0;
    background: rgba(15,23,42,.55);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    opacity: 0;
    pointer-events: none;
    transition: .25s;
}
.mk-popup.show {
    opacity: 1;
    pointer-events: all;
}

.mk-popup-box {
    width: 320px;
    background: #fff;
    border-radius: 14px;
    padding: 22px;
    text-align: center;
    box-shadow: 0 20px 50px rgba(0,0,0,.2);
    animation: popIn .25s ease;
}
@keyframes popIn {
    from { transform: scale(.9); opacity:0; }
    to { transform: scale(1); opacity:1; }
}

.mk-popup-icon {
    font-size: 32px;
    margin-bottom: 10px;
}
.mk-popup-msg {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 18px;
}
.mk-popup-actions {
    display: flex;
    gap: 10px;
    justify-content: center;
}

.mk-btn {
    padding: 8px 16px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-weight: 600;
}
.mk-btn.ok { background: var(--maken-amber); }
.mk-btn.cancel { background: #e2e8f0; }
.mk-btn.danger { background: var(--maken-danger); color:#fff; }
</style>

<div class="mk-page">

    <div class="mk-title">Product Form</div>
    <div class="mk-card">
        <div class="mk-card-body">
            <form id="productForm">
                <input type="hidden" id="product_id">
                <div class="form-grid">
                    <div class="mk-field">
                        <label>Product Name <span class="req">*</span></label>
                        <div class="mk-input-wrap">
                            <i class="fas fa-box ico"></i>
                            <input type="text" id="name" class="mk-input" placeholder="Enter product name" required>
                        </div>
                    </div>
                    <div class="mk-field">
                        <label>Barcode</label>
                        <div class="mk-input-wrap">
                            <i class="fas fa-barcode ico"></i>
                            <input type="text" id="barcode" class="mk-input" placeholder="Enter barcode">
                        </div>
                    </div>
                    <div class="mk-field">
                        <label>Price <span class="req">*</span></label>
                        <div class="mk-input-wrap">
                            <i class="fas fa-tag ico"></i>
                            <input type="number" step="0.01" id="price" class="mk-input" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="mk-field">
                        <label>Quantity <span class="req">*</span></label>
                        <div class="mk-input-wrap">
                            <i class="fas fa-layer-group ico"></i>
                            <input type="number" id="qty" class="mk-input" placeholder="0" required>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-ghost" onclick="resetForm()"><i class="fas fa-rotate-left"></i> Reset</button>
                    <button type="submit" class="btn-amber" id="saveBtn"> <i class="fas fa-floppy-disk"></i> Save Product</button>
                </div>
            </form>
        </div>
    </div>

    <div class="mk-title">Inventory List</div>
    <div class="mk-card">
        <div class="table-header">
            <div class="search-wrap" style="position:relative; display:flex; align-items:center;">
                <i class="fas fa-magnifying-glass" style="position:absolute; left:12px; color:#94a3b8; font-size:13px;"></i>
                <input type="text" id="tableSearch" class="search-inp" placeholder="Search products...">
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table class="mk-table">
                <thead>
                    <tr>
                        <th style="width:48px;">#</th>
                        <th>Product Name</th>
                        <th>Barcode</th>
                        <th>Price</th>
                        <th>Stock Qty</th>
                        <th style="width:100px; text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody id="productTableBody">
                    <tr><td colspan="6" class="empty-cell">Loading products…</td></tr>
                </tbody>
            </table>
        </div>
    </div>
   
</div>
</div>

<script>
const apiBase = '/api/products';
let all = [];

function fetchProducts() {
    axios.get(apiBase + '?limit=1000')
        .then(r => { 
            all = r.data.data || []; 
            render(); 
        })
        .catch(() => {
            document.getElementById('productTableBody').innerHTML =
                '<tr><td colspan="6" class="empty-cell">Failed to load products.</td></tr>';
        });
}

function render() {
    const q = (document.getElementById('tableSearch').value || '').toLowerCase();
    const filtered = q 
        ? all.filter(p => p.name.toLowerCase().includes(q) || (p.barcode||'').toLowerCase().includes(q)) 
        : all;
    
    if (!filtered.length) {
        document.getElementById('productTableBody').innerHTML =
            '<tr><td colspan="6" class="empty-cell">No products found.</td></tr>';
        return;
    }

    document.getElementById('productTableBody').innerHTML = filtered.map((p, i) => `
        <tr>
            <td>${i+1}</td>
            <td style="font-weight:600">${p.name}</td>
            <td>${p.barcode || '—'}</td>
            <td>Rs. ${parseFloat(p.price).toLocaleString()}</td>
            <td>
                <span style="font-weight:700; color:${p.qty <= 5 ? 'var(--maken-danger)' : 'inherit'}">
                    ${p.qty}
                </span>
            </td>
            <td style="text-align:center;">
                <div style="display:flex; gap:4px; justify-content:center;">
                    <button class="act-btn edit" onclick='editProduct(${JSON.stringify(p)})'>
                        <i class="fas fa-pen"></i>
                    </button>
                    <button class="act-btn del" onclick="deleteProduct(${p.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

document.getElementById('productForm').onsubmit = function(e) {
    e.preventDefault();

    const id = document.getElementById('product_id').value;

    const data = {
        name: document.getElementById('name').value,
        barcode: document.getElementById('barcode').value,
        price: document.getElementById('price').value,
        qty: document.getElementById('qty').value,
    };

    (id ? axios.put(`${apiBase}/${id}`, data) : axios.post(apiBase, data))
        .then(r => {
            showPopup(r.data.message, 'success');
            resetForm();
            fetchProducts();
        })
        .catch(err => {
            showPopup(err.response?.data?.message || 'Error saving.', 'error');
        });
};

function editProduct(p) {
    document.getElementById('product_id').value = p.id;
    document.getElementById('name').value = p.name;
    document.getElementById('barcode').value = p.barcode || '';
    document.getElementById('price').value = p.price;
    document.getElementById('qty').value = p.qty;

    document.getElementById('saveBtn').innerHTML = '<i class="fas fa-floppy-disk"></i> Update Product';
    document.getElementById('saveBtn').classList.add('slate');

    const scrollArea = document.querySelector('.scroll-area');
    if (scrollArea) {
        scrollArea.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Wait for scroll to finish before focusing
    setTimeout(() => document.getElementById('name').focus(), 350);
}
function deleteProduct(id) {
    showPopup('Are you sure you want to delete this product?', 'confirm', () => {
        axios.delete(`${apiBase}?ids=${id}`)
            .then(r => {
                showPopup(r.data.message, 'success');
                fetchProducts();
            })
            .catch(() => {
                showPopup('Failed to delete.', 'error');
            });
    });
}

function resetForm() {
    document.getElementById('productForm').reset();
    document.getElementById('product_id').value = '';
    document.getElementById('saveBtn').textContent = 'Save Product';
    document.getElementById('saveBtn').classList.remove('slate');
}



/* ---------------- EVENTS ---------------- */

document.getElementById('tableSearch').oninput = render;
window.onload = fetchProducts;

</script>

@endsection
