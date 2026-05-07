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
        --maken-danger: #ef4444;
        --maken-success: #22c55e;
        --radius-lg: 14px;
        --shadow-card: 0 4px 20px rgba(0,0,0,.08);
    }

    body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--maken-surface); color: var(--maken-slate); }
    .pos-container { display: grid; grid-template-columns: 1fr 400px; gap: 20px; max-width: 1400px; margin: 0 auto; padding: 20px; }
    
    .mk-card { background: var(--maken-white); border-radius: var(--radius-lg); box-shadow: var(--shadow-card); border: 1px solid var(--maken-line); overflow: hidden; display: flex; flex-direction: column; }
    .card-header { padding: 15px 20px; border-bottom: 1px solid var(--maken-line); background: #fafafa; font-weight: 700; display: flex; justify-content: space-between; align-items: center; }
    .card-body { padding: 20px; flex: 1; }

    .search-input { width: 100%; height: 42px; padding: 0 15px; border: 1.5px solid var(--maken-line); border-radius: 10px; outline: none; font-family: inherit; font-size: 13px; }
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
    .cart-input { width: 60px; height: 30px; border: 1px solid var(--maken-line); border-radius: 5px; text-align: center; font-family: inherit; }
    .price-input { width: 80px; height: 30px; border: 1px solid var(--maken-line); border-radius: 5px; text-align: center; font-family: inherit; }

    .total-box { padding: 20px; background: #f8fafc; border-top: 1px solid var(--maken-line); }
    .total-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; font-weight: 600; }
    .total-grand { font-size: 20px; font-weight: 800; color: var(--maken-slate); border-top: 2px dashed var(--maken-line); padding-top: 10px; margin-top: 4px; }

    /* Discount row */
    .discount-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
    /* .discount-label { font-weight: 600; font-size: 14px; color: var(--maken-danger); display: flex; align-items: center; gap: 5px; } */
    .discount-input-wrap { display: flex; align-items: center; gap: 6px; }
    .discount-input {
        width: 100px; height: 32px;
        border: 1.5px solid var(--maken-line);
        border-radius: 7px;
        padding: 0 10px;
        font-family: inherit; font-size: 13px; font-weight: 600;
        text-align: right;
        outline: none;
        color: var(--maken-slate);
        background: #fff;
        transition: border-color .2s;
    }
    .discount-input:focus { border-color: var(--maken-amber); }
    .discount-type-btn {
        height: 32px; padding: 0 10px;
        border: 1.5px solid var(--maken-line);
        border-radius: 7px;
        background: #fff; font-family: inherit;
        font-size: 12px; font-weight: 700;
        cursor: pointer; color: #64748b;
        transition: all .2s;
    }
    .discount-type-btn:hover, .discount-type-btn.active { border-color: var(--maken-amber); background: var(--maken-amber); color: var(--maken-slate); }

    .btn-submit { width: 100%; height: 50px; background: var(--maken-amber); border: none; border-radius: 10px; color: var(--maken-slate); font-weight: 800; font-size: 16px; cursor: pointer; transition: all .2s; font-family: inherit; }
    .btn-submit:hover { background: var(--maken-amber-dark); color: #fff; }

    .btn-ghost { background: none; border: 1.5px solid var(--maken-line); border-radius: 8px; cursor: pointer; font-family: inherit; font-weight: 600; color: var(--maken-slate); transition: all .2s; }
    .btn-ghost:hover { border-color: var(--maken-amber); background: #fffbeb; }

    /* ── Customer row ── */
    .customer-row { display: flex; gap: 8px; align-items: center; margin-bottom: 15px; }
    .customer-select { flex: 1; height: 42px; border: 1.5px solid var(--maken-line); border-radius: 10px; padding: 0 10px; outline: none; font-family: inherit; font-size: 13px; background: #fff; color: var(--maken-slate); }
    .customer-select:focus { border-color: var(--maken-amber); }

    .new-cust-btn { width: 42px; height: 42px; border: 1.5px solid var(--maken-line); border-radius: 10px; background: #fff; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; transition: all .2s; }
    .new-cust-btn:hover { border-color: var(--maken-amber); background: #fffbeb; color: var(--maken-amber-dark); }
    .new-cust-btn.active { background: var(--maken-amber); border-color: var(--maken-amber); color: var(--maken-slate); }

    /* ── Quick add form ── */
    .quick-add-form { display: none; background: #fffbeb; border: 1.5px solid var(--maken-amber); border-radius: 10px; padding: 14px; margin-bottom: 15px; }
    .quick-add-form.open { display: block; }
    .qa-title { font-size: 12px; font-weight: 700; color: var(--maken-slate); margin-bottom: 10px; display: flex; align-items: center; gap: 6px; }
    .qa-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 10px; }
    .qa-field label { display: block; font-size: 11px; font-weight: 600; color: #64748b; margin-bottom: 3px; }
    .qa-field input { width: 100%; height: 34px; padding: 0 10px; border: 1.5px solid var(--maken-line); border-radius: 7px; font-size: 12px; font-family: inherit; background: #fff; color: var(--maken-slate); box-sizing: border-box; outline: none; }
    .qa-field input:focus { border-color: var(--maken-amber); }
    .qa-field input.has-error { border-color: var(--maken-danger); }
    .qa-field.full { grid-column: 1 / -1; }
    .qa-save { width: 100%; height: 34px; background: var(--maken-slate); color: #fff; border: none; border-radius: 7px; font-size: 12px; font-weight: 700; font-family: inherit; cursor: pointer; transition: all .2s; }
    .qa-save:hover { background: var(--maken-slate-2); }
    .qa-save:disabled { opacity: 0.5; cursor: not-allowed; }
</style>

<div class="pos-container">
    <div class="main-panel">
        <div class="mk-card" style="height: 100%;">
            <div class="card-header">
                <span><i class="fas fa-th-large"></i> Products</span>
                <div style="display:flex; gap:10px;">
                    <input type="text" id="prodSearch" class="search-input" style="width:250px; height:34px;" placeholder="Search products...">
                </div>
            </div>
            <div class="card-body" style="overflow-y: auto; max-height: 70vh;">
                <div id="productGrid" class="product-grid"></div>
            </div>
        </div>
    </div>

    <div class="side-panel">
        <div class="mk-card" style="height: 100%;">
            <div class="card-header">
                <span><i class="fas fa-shopping-cart"></i> Cart</span>
                <div style="display:flex; gap:10px;">
                    <button onclick="showHeldCarts()" class="btn-ghost" style="height:30px; font-size:11px; padding:0 10px;"><i class="fas fa-list"></i> Held</button>
                    <button onclick="clearCart()" style="background:none; border:none; color:var(--maken-danger); cursor:pointer;"><i class="fas fa-trash"></i></button>
                </div>
            </div>
            <div class="card-body">

                {{-- Customer row --}}
                <div class="customer-row">
                    <select id="customer_id" class="customer-select">
                        <option value="">Select Customer</option>
                    </select>
                    <button class="new-cust-btn" id="newCustBtn" onclick="toggleNewCustomer()" title="Add new customer">
                        <i class="fas fa-user-plus"></i>
                    </button>
                </div>

                {{-- Quick-add form --}}
                <div class="quick-add-form" id="quickAddForm">
                    <div class="qa-title">
                        <i class="fas fa-user-plus" style="color:var(--maken-amber-dark);"></i>
                        New Customer
                    </div>
                    <div class="qa-grid">
                        <div class="qa-field full">
                            <label>Name <span style="color:var(--maken-danger)">*</span></label>
                            <input type="text" id="qa_name" placeholder="Full name" />
                        </div>
                        <div class="qa-field">
                            <label>Phone</label>
                            <input type="text" id="qa_phone" placeholder="0300-0000000" />
                        </div>
                        <div class="qa-field">
                            <label>Opening Balance <span style="color:var(--maken-danger)">*</span></label>
                            <input type="number" id="qa_opening_balance" placeholder="0" min="0" />
                        </div>
                        <div class="qa-field">
                            <label>Email</label>
                            <input type="email" id="qa_email" placeholder="optional" />
                        </div>
                        <div class="qa-field">
                            <label>Address</label>
                            <input type="text" id="qa_address" placeholder="optional" />
                        </div>
                    </div>
                    <button class="qa-save" id="qaSaveBtn" onclick="saveNewCustomer()">
                        <i class="fas fa-check"></i> Save & Select
                    </button>
                </div>

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
                        <tbody id="cartBody"></tbody>
                    </table>
                </div>
            </div>
            
            <div class="total-box">
                {{-- Subtotal --}}
                <div class="total-row">
                    <span>Subtotal</span>
                    <span id="subtotal">Rs. 0.00</span>
                </div>

                {{-- Discount row --}}
                <div class="total-row">
                    <span>
                       Discount
                    </span>
                    <div class="discount-input-wrap">
                        {{-- Toggle: flat (Rs.) or percent (%) --}}
                        <button class="discount-type-btn active" id="discountTypeBtn" onclick="toggleDiscountType()" title="Switch between Rs. and %">
                            Rs.
                        </button>
                        <input
                            type="number"
                            id="discountInput"
                            class="discount-input"
                            placeholder="0"
                            min="0"
                            value="0"
                            oninput="recalcTotals()"
                        />
                    </div>
                </div>

                {{-- Grand Total --}}
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
let products   = [];
let cart       = [];
let customers  = [];
let formOpen   = false;
let discountType = 'flat'; // 'flat' = Rs.  |  'percent' = %
let subtotalValue = 0;     // always keep raw subtotal in memory

/* ══════════════════════════════════════
   DISCOUNT LOGIC
══════════════════════════════════════ */
function toggleDiscountType() {
    discountType = discountType === 'flat' ? 'percent' : 'flat';
    const btn = document.getElementById('discountTypeBtn');
    btn.textContent = discountType === 'flat' ? 'Rs.' : '%';
    // Reset input to 0 when switching type
    document.getElementById('discountInput').value = 0;
    recalcTotals();
}

function getDiscountAmount() {
    const val = parseFloat(document.getElementById('discountInput').value) || 0;
    if (discountType === 'percent') {
        // Cap at 100%
        const pct = Math.min(val, 100);
        return (subtotalValue * pct) / 100;
    }
    // Flat: cap at subtotal so grand total never goes negative
    return Math.min(val, subtotalValue);
}

function recalcTotals() {
    const discount   = getDiscountAmount();
    const grandTotal = Math.max(0, subtotalValue - discount);

    document.getElementById('subtotal').textContent  = 'Rs. ' + subtotalValue.toLocaleString('en', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('grandTotal').textContent = 'Rs. ' + grandTotal.toLocaleString('en', {minimumFractionDigits: 2, maximumFractionDigits: 2});
}

/* ══════════════════════════════════════
   LOAD DATA
══════════════════════════════════════ */
function loadData() {
    axios.get('/api/products?all=true').then(r => {
        products = r.data.data || r.data;
        renderProducts();
    });

    axios.get('/api/customers?name_list=true').then(r => {
        customers = r.data.data || r.data;
        populateCustomerSelect();

        const urlParams = new URLSearchParams(window.location.search);
        const restoreId = urlParams.get('restore');
        if (restoreId) {
            axios.get('/api/quotations').then(r2 => {
                const h = r2.data.data.find(x => x.id == restoreId);
                if (h) {
                    cart = h.items;
                    document.getElementById('customer_id').value = h.customer_id || '';
                    renderCart();
                    deleteHeld(h.id, true);
                    window.history.replaceState({}, document.title, '/pos');
                }
            });
        }
    });
}

function populateCustomerSelect() {
    const select  = document.getElementById('customer_id');
    const current = select.value;
    while (select.options.length > 1) select.remove(1);
    customers.forEach(c => {
        const opt = document.createElement('option');
        opt.value = c.id;
        opt.textContent = c.name + (c.phone ? ' — ' + c.phone : '');
        select.appendChild(opt);
    });
    if (current) select.value = current;
}

/* ══════════════════════════════════════
   QUICK-ADD CUSTOMER
══════════════════════════════════════ */
function toggleNewCustomer() { formOpen ? closeNewCustomer() : openNewCustomer(); }

function openNewCustomer() {
    formOpen = true;
    document.getElementById('quickAddForm').classList.add('open');
    document.getElementById('newCustBtn').classList.add('active');
    setTimeout(() => document.getElementById('qa_name').focus(), 50);
}

function closeNewCustomer() {
    formOpen = false;
    document.getElementById('quickAddForm').classList.remove('open');
    document.getElementById('newCustBtn').classList.remove('active');
    ['qa_name','qa_phone','qa_opening_balance','qa_email','qa_address'].forEach(id => {
        const el = document.getElementById(id);
        el.value = '';
        el.classList.remove('has-error');
    });
}

function saveNewCustomer() {
    const nameEl    = document.getElementById('qa_name');
    const balanceEl = document.getElementById('qa_opening_balance');
    const name      = nameEl.value.trim();
    const balance   = balanceEl.value.trim();

    let ok = true;
    if (!name)         { nameEl.classList.add('has-error');    nameEl.focus();    ok = false; }
    if (balance === '') { balanceEl.classList.add('has-error'); if (ok) balanceEl.focus(); ok = false; }
    if (!ok) return;

    nameEl.classList.remove('has-error');
    balanceEl.classList.remove('has-error');

    const btn = document.getElementById('qaSaveBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

    const payload = {
        name,
        opening_balance: parseFloat(balance),
        phone:   document.getElementById('qa_phone').value.trim()   || null,
        email:   document.getElementById('qa_email').value.trim()   || null,
        address: document.getElementById('qa_address').value.trim() || null,
    };

    axios.post('/api/customers', payload)
        .then(r => {
            console.log('Customer API response:', r.data);

            let c = null;
            if (r.data?.data?.id)         c = r.data.data;
            else if (r.data?.customer?.id) c = r.data.customer;
            else if (r.data?.id)           c = r.data;

            if (c && c.id) {
                customers.unshift(c);
                populateCustomerSelect();
                document.getElementById('customer_id').value = c.id;
                showPopup(c.name + ' added & selected!', 'success');
            } else {
                // Fallback: re-fetch list and auto-select by name
                axios.get('/api/customers?name_list=true').then(r2 => {
                    customers = r2.data.data || r2.data;
                    populateCustomerSelect();
                    const found = customers.find(x => x.name === payload.name);
                    if (found) document.getElementById('customer_id').value = found.id;
                });
                showPopup(payload.name + ' added & selected!', 'success');
            }

            closeNewCustomer();
        })
        .catch(err => {
            console.error(err.response?.data);
            showPopup(err.response?.data?.message || err.response?.data?.error || 'Failed to save customer', 'error');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check"></i> Save & Select';
        });
}

/* ══════════════════════════════════════
   PRODUCTS
══════════════════════════════════════ */
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
    if (p.qty <= 0) { showPopup('Out of stock!', 'error'); return; }
    const existing = cart.find(item => item.product_id === id);
    if (existing) {
        if (existing.qty < p.qty) existing.qty++;
        else showPopup('No more stock available!', 'error');
    } else {
        cart.push({ product_id: p.id, name: p.name, qty: 1, price: p.price, stock: p.qty });
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
                    <a href="#" onclick="removeItem(${index}); return false;" style="color:red; font-size:10px;">Remove</a>
                </td>
                <td><input type="number" class="cart-input" value="${item.qty}" min="1" max="${item.stock}" onchange="updateQty(${index}, this.value)"></td>
                <td><input type="number" class="price-input" value="${item.price}" onchange="updatePrice(${index}, this.value)"></td>
                <td style="text-align:right; font-weight:700;">Rs. ${itemTotal.toFixed(2)}</td>
            </tr>
        `;
    }).join('');

    subtotalValue = total;  // update global subtotal
    recalcTotals();         // this sets both subtotal and grandTotal displays
}

function updateQty(index, val)   { cart[index].qty   = parseInt(val);   renderCart(); }
function updatePrice(index, val) { cart[index].price = parseFloat(val); renderCart(); }
function removeItem(index)       { cart.splice(index, 1);               renderCart(); }

function clearCart() {
    cart = [];
    // Reset discount too
    document.getElementById('discountInput').value = 0;
    subtotalValue = 0;
    renderCart();
}

/* ══════════════════════════════════════
   SUBMIT SALE
══════════════════════════════════════ */
function submitSale() {
    const customerId = document.getElementById('customer_id').value;
    if (!customerId)       { showPopup('Please select a customer', 'error'); return; }
    if (cart.length === 0) { showPopup('Cart is empty', 'error'); return; }

    const discount   = getDiscountAmount();
    const grandTotal = Math.max(0, subtotalValue - discount);

    const data = {
        customer_id: customerId,
        total_bill:  subtotalValue,   // raw subtotal — controller recalculates grand total
        discount:    discount,         // flat Rs. amount after resolving % if needed
        date:        new Date().toISOString().split('T')[0],
        items: cart.map(item => ({
            product_id: item.product_id,
            qty:   item.qty,
            price: item.price,
            total: item.qty * item.price
        }))
    };

    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

    axios.post('/api/sales', data)
        .then(r => {
            showPopup('Sale completed successfully!', 'success');
            showPopup('Do you want to print the invoice?', 'confirm', () => {
                printSale(r.data.sale_id);
            });
            clearCart();
            loadData();
        })
        .catch(err => showPopup(err.response?.data?.message || 'Error completing sale', 'error'))
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check-circle"></i> COMPLETE';
        });
}

/* ══════════════════════════════════════
   HOLD / UNHOLD
══════════════════════════════════════ */
function holdCart() {
    if (cart.length === 0) { showPopup('Cart is empty', 'error'); return; }
    const ref = prompt('Enter a reference name for this hold:');
    if (ref === null) return;

    axios.post('/api/quotations', {
        customer_id:    document.getElementById('customer_id').value || null,
        reference_name: ref || 'Unnamed',
        items:          cart,
        total:          subtotalValue,
        date:           new Date().toISOString().split('T')[0]
    }).then(() => {
        showPopup('Cart held successfully', 'success');
        clearCart();
    }).catch(() => showPopup('Failed to hold cart', 'error'));
}

function showHeldCarts() {
    axios.get('/api/quotations').then(r => {
        const held = r.data.data;
        if (held.length === 0) { showPopup('No held carts found', 'error'); return; }

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
        overlay.style = 'position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:2000; display:flex; align-items:center; justify-content:center;';
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
            if (document.getElementById('heldOverlay')) document.getElementById('heldOverlay').remove();
            deleteHeld(id, true);
        }
    });
}

function deleteHeld(id, silent = false) {
    const heldPopup = document.getElementById('heldOverlay');
    if (heldPopup) heldPopup.remove();

    if (silent) {
        return axios.delete(`/api/quotations/${id}`)
            .then(() => { showPopup('Removed successfully', 'success'); showHeldCarts(); })
            .catch(() => showPopup('Failed to delete', 'error'));
    }

    showPopup('Remove this held cart?', 'confirm', () => {
        axios.delete(`/api/quotations/${id}`)
            .then(() => { showPopup('Removed successfully', 'success'); showHeldCarts(); })
            .catch(() => showPopup('Failed to delete', 'error'));
    });
}

document.getElementById('prodSearch').oninput = e => renderProducts(e.target.value);
window.onload = loadData;
</script>

@endsection
