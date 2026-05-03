@extends('layout.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<style>
    :root {
        --maken-amber: #fbbf24;
        --maken-amber-dark: #d97706;
        --maken-slate: #0f172a;
        --maken-slate-2: #1e293b;
        --maken-surface: #f1f5f9;
        --maken-white: #ffffff;
        --maken-line: #e2e8f0;
        --radius-lg: 14px;
        --shadow-card: 0 4px 20px rgba(0,0,0,.08);
    }

    body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--maken-surface); color: var(--maken-slate); }
    .pos-container { display: grid; grid-template-columns: 1fr 400px; gap: 20px; max-width: 1400px; margin: 0 auto; padding: 20px; }
    
    .mk-card { background: var(--maken-white); border-radius: var(--radius-lg); box-shadow: var(--shadow-card); border: 1px solid var(--maken-line); overflow: hidden; display: flex; flex-direction: column; }
    .card-header { padding: 15px 20px; border-bottom: 1px solid var(--maken-line); background: #fafafa; font-weight: 700; display: flex; justify-content: space-between; align-items: center; }
    .card-body { padding: 20px; flex: 1; }

    .search-wrap { position: relative; margin-bottom: 20px; }
    .search-wrap i { position: absolute; left: 12px; top: 12px; color: #94a3b8; }
    .search-input { width: 100%; height: 42px; padding: 0 15px 0 40px; border: 1.5px solid var(--maken-line); border-radius: 10px; outline: none; }
    .search-input:focus { border-color: var(--maken-amber); }

    .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px; }
    .product-item { background: #fff; border: 1px solid var(--maken-line); border-radius: 10px; padding: 15px; cursor: pointer; transition: all .2s; text-align: center; }
    .product-item:hover { border-color: var(--maken-amber); transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,.05); }
    .product-name { font-weight: 700; font-size: 14px; margin-bottom: 5px; }
    .product-price { color: var(--maken-amber-dark); font-weight: 800; font-size: 13px; }
    .product-stock { font-size: 11px; color: #94a3b8; margin-top: 5px; }

    .cart-table { width: 100%; border-collapse: collapse; }
    .cart-table th { text-align: left; font-size: 12px; text-transform: uppercase; color: #94a3b8; padding-bottom: 10px; }
    .cart-table td { padding: 10px 0; border-bottom: 1px solid #f1f5f9; }
    .cart-input { width: 60px; height: 30px; border: 1px solid var(--maken-line); border-radius: 5px; text-align: center; }
    .price-input { width: 80px; height: 30px; border: 1px solid var(--maken-line); border-radius: 5px; text-align: center; }

    .total-box { padding: 20px; background: #f8fafc; border-top: 1px solid var(--maken-line); }
    .total-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-weight: 600; }
    .total-grand { font-size: 20px; font-weight: 800; color: var(--maken-slate); border-top: 2px dashed var(--maken-line); pt: 10px; }

    .btn-submit { width: 100%; height: 50px; background: var(--maken-amber); border: none; border-radius: 10px; color: var(--maken-slate); font-weight: 800; font-size: 16px; cursor: pointer; transition: all .2s; }
    .btn-submit:hover { background: var(--maken-amber-dark); color: #fff; }

    .customer-select { width: 100%; height: 42px; border: 1.5px solid var(--maken-line); border-radius: 10px; padding: 0 10px; margin-bottom: 15px; outline: none; }
</style>

<div class="pos-container">
    <div class="main-panel">
        <div class="mk-card" style="height: 100%;">
            <div class="card-header">
                <span><i class="fas fa-th-large"></i> Products</span>
                <div style="display:flex; gap:10px;">
                    <input type="text" id="prodSearch" class="search-input" style="width:250px; height:34px; margin-bottom:0;" placeholder="Search products...">
                </div>
            </div>
            <div class="card-body" style="overflow-y: auto; max-height: 70vh;">
                <div id="productGrid" class="product-grid">
                    <!-- Products will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <div class="side-panel">
        <div class="mk-card" style="height: 100%;">
            <div class="card-header">
                <span><i class="fas fa-shopping-cart"></i> Cart</span>
                <div style="display:flex; gap:10px;">
                    <button onclick="showHeldCarts()" class="btn-ghost" style="height:30px; font-size:11px; padding:0 10px;" title="View Held Carts"><i class="fas fa-list"></i> Held</button>
                    <button onclick="clearCart()" style="background:none; border:none; color:var(--maken-danger); cursor:pointer;"><i class="fas fa-trash"></i></button>
                </div>
            </div>
            <div class="card-body">
                <select id="customer_id" class="customer-select">
                    <option value="">Select Customer</option>
                </select>

                <div style="overflow-y: auto; max-height: 40vh;">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th style="width:70px;">Qty</th>
                                <th style="width:90px;">Price</th>
                                <th style="text-align:right;">Total</th>
                            </tr>
                        </thead>
                        <tbody id="cartBody">
                            <!-- Cart items -->
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="total-box">
                <div class="total-row">
                    <span>Subtotal</span>
                    <span id="subtotal">Rs. 0.00</span>
                </div>
                <div class="total-row total-grand">
                    <span>Grand Total</span>
                    <span id="grandTotal">Rs. 0.00</span>
                </div>
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px; margin-top:10px;">
                    <button class="btn-ghost" style="height:50px; font-weight:800;" onclick="holdCart()">
                        <i class="fas fa-pause-circle"></i> HOLD
                    </button>
                    <button class="btn-submit" onclick="submitSale()" id="submitBtn">
                        <i class="fas fa-check-circle"></i> COMPLETE
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let products = [];
let cart = [];
let customers = [];

function loadData() {
    axios.get('/api/products?all=true').then(r => { products = r.data.data; renderProducts(); });
    axios.get('/api/customers?name_list=true').then(r => { 
        customers = r.data.data;
        const select = document.getElementById('customer_id');
        customers.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c.id;
            opt.textContent = c.name;
            select.appendChild(opt);
        });

        // Check for restore parameter
        const urlParams = new URLSearchParams(window.location.search);
        const restoreId = urlParams.get('restore');
        if (restoreId) {
            axios.get('/api/quotations').then(r => {
                const h = r.data.data.find(x => x.id == restoreId);
                if (h) {
                    cart = h.items;
                    document.getElementById('customer_id').value = h.customer_id || '';
                    renderCart();
                    // Optionally delete from DB
                    deleteHeld(h.id, true);
                    // Clear URL parameter without reload
                    window.history.replaceState({}, document.title, "/pos");
                }
            });
        }
    });
}

function renderProducts(search = '') {
    const grid = document.getElementById('productGrid');
    const filtered = products.filter(p => 
        p.name.toLowerCase().includes(search.toLowerCase()) || 
        (p.barcode && p.barcode.includes(search))
    );
    
    grid.innerHTML = filtered.map(p => `
        <div class="product-item" onclick="addToCart(${p.id})">
            <div class="product-name">${p.name}</div>
            <div class="product-price">Rs. ${parseFloat(p.price).toLocaleString()}</div>
            <div class="product-stock">Stock: ${p.qty}</div>
        </div>
    `).join('');
}

function addToCart(id) {
    const p = products.find(p => p.id === id);
    if (p.qty <= 0) { alert('Out of stock!'); return; }
    
    const existing = cart.find(item => item.product_id === id);
    if (existing) {
        if (existing.qty < p.qty) existing.qty++;
        else alert('No more stock available!');
    } else {
        cart.push({
            product_id: p.id,
            name: p.name,
            qty: 1,
            price: p.price,
            stock: p.qty
        });
    }
    renderCart();
}

function renderCart() {
    const body = document.getElementById('cartBody');
    let total = 0;
    
    body.innerHTML = cart.map((item, index) => {
        const itemTotal = item.qty * item.price;
        total += itemTotal;
        return `
            <tr>
                <td>
                    <div style="font-weight:700; font-size:13px;">${item.name}</div>
                    <a href="#" onclick="removeItem(${index})" style="color:red; font-size:10px;">Remove</a>
                </td>
                <td>
                    <input type="number" class="cart-input" value="${item.qty}" min="1" max="${item.stock}" onchange="updateQty(${index}, this.value)">
                </td>
                <td>
                    <input type="number" class="price-input" value="${item.price}" onchange="updatePrice(${index}, this.value)">
                </td>
                <td style="text-align:right; font-weight:700;">Rs. ${itemTotal.toFixed(2)}</td>
            </tr>
        `;
    }).join('');
    
    document.getElementById('subtotal').textContent = `Rs. ${total.toLocaleString()}`;
    document.getElementById('grandTotal').textContent = `Rs. ${total.toLocaleString()}`;
}

function updateQty(index, val) {
    cart[index].qty = parseInt(val);
    renderCart();
}

function updatePrice(index, val) {
    cart[index].price = parseFloat(val);
    renderCart();
}

function removeItem(index) {
    cart.splice(index, 1);
    renderCart();
}

function clearCart() {
    cart = [];
    renderCart();
}

function submitSale() {
    const customerId = document.getElementById('customer_id').value;
    if (!customerId) { alert('Please select a customer'); return; }
    if (cart.length === 0) { alert('Cart is empty'); return; }
    
    const totalBill = cart.reduce((sum, item) => sum + (item.qty * item.price), 0);
    
    const data = {
        customer_id: customerId,
        total_bill: totalBill,
        date: new Date().toISOString().split('T')[0],
        items: cart.map(item => ({
            product_id: item.product_id,
            qty: item.qty,
            price: item.price,
            total: item.qty * item.price
        }))
    };
    
    document.getElementById('submitBtn').disabled = true;
    document.getElementById('submitBtn').textContent = 'Processing...';
    
    axios.post('/api/sales', data)
        .then(r => {
            alert('Sale completed successfully!');
            if(confirm('Do you want to print the invoice?')) {
                printSale(r.data.sale_id);
            }
            clearCart();
            loadData(); // Reload products to update stock
        })
        .catch(err => alert(err.response?.data?.message || 'Error completing sale'))
        .finally(() => {
            document.getElementById('submitBtn').disabled = false;
            document.getElementById('submitBtn').textContent = 'COMPLETE';
        });
}

/* HOLD / UNHOLD */
function holdCart() {
    if (cart.length === 0) { alert('Cart is empty'); return; }
    const ref = prompt("Enter a reference name for this hold:");
    if (ref === null) return;

    const data = {
        customer_id: document.getElementById('customer_id').value || null,
        reference_name: ref || 'Unnamed',
        items: cart,
        total: cart.reduce((sum, item) => sum + (item.qty * item.price), 0),
        date: new Date().toISOString().split('T')[0]
    };

    axios.post('/api/quotations', data).then(r => {
        alert('Cart held successfully');
        clearCart();
    }).catch(err => alert('Failed to hold cart'));
}

function showHeldCarts() {
    axios.get('/api/quotations').then(r => {
        const held = r.data.data;
        if (held.length === 0) { alert('No held carts found'); return; }
        
        let html = '<div style="max-height:400px; overflow-y:auto;">';
        held.forEach(h => {
            html += `
                <div style="padding:10px; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <div style="font-weight:700;">${h.reference_name}</div>
                        <div style="font-size:11px; color:#666;">${h.date} | Rs. ${parseFloat(h.total).toLocaleString()}</div>
                    </div>
                    <div style="display:flex; gap:5px;">
                        <button onclick="restoreHeld(${h.id})" style="padding:5px 10px; background:var(--maken-amber); border:none; border-radius:5px; cursor:pointer; font-size:11px; font-weight:700;">Restore</button>
                        <button onclick="deleteHeld(${h.id})" style="padding:5px 10px; background:none; border:none; color:red; cursor:pointer;"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        
        if (document.getElementById('heldOverlay')) document.getElementById('heldOverlay').remove();

        const overlay = document.createElement('div');
        overlay.id = 'heldOverlay';
        overlay.style = "position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:2000; display:flex; align-items:center; justify-content:center;";
        overlay.innerHTML = `
            <div style="background:#fff; width:400px; padding:20px; border-radius:15px; box-shadow:0 10px 30px rgba(0,0,0,0.2);">
                <div style="display:flex; justify-content:space-between; margin-bottom:15px; align-items:center;">
                    <h3 style="margin:0;">Held Carts</h3>
                    <button onclick="document.getElementById('heldOverlay').remove()" style="border:none; background:none; font-size:24px; cursor:pointer;">&times;</button>
                </div>
                ${html}
            </div>
        `;
        document.body.appendChild(overlay);
    });
}

function restoreHeld(id) {
    axios.get('/api/quotations').then(r => {
        const h = r.data.data.find(x => x.id === id);
        if (h) {
            if (cart.length > 0 && !confirm('Discard current cart and restore this one?')) return;
            cart = h.items;
            document.getElementById('customer_id').value = h.customer_id || '';
            renderCart();
            if(document.getElementById('heldOverlay')) document.getElementById('heldOverlay').remove();
            deleteHeld(id, true);
        }
    });
}

function deleteHeld(id, silent = false) {
    if (!silent && !confirm('Remove this held cart?')) return;
    axios.delete(`/api/quotations/${id}`).then(r => {
        if (!silent) {
            if(document.getElementById('heldOverlay')) document.getElementById('heldOverlay').remove();
            showHeldCarts();
        }
    });
}

document.getElementById('prodSearch').oninput = (e) => renderProducts(e.target.value);

window.onload = loadData;
</script>

@endsection
