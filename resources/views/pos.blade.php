@extends('layout.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<style>
    :root {
        --maken-amber:     #fbbf24;
        --maken-amber-dark:#d97706;
        --maken-slate:     #0f172a;
        --maken-slate-2:   #1e293b;
        --maken-surface:   #f1f5f9;
        --maken-white:     #ffffff;
        --maken-line:      #e2e8f0;
        --maken-danger:    #ef4444;
        --maken-success:   #22c55e;
        --radius-lg:       14px;
        --shadow-card:     0 4px 20px rgba(0,0,0,.08);
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
    .product-item.out-of-stock { opacity: .5; cursor: not-allowed; }
    .product-name  { font-weight: 700; font-size: 14px; margin-bottom: 5px; }
    .product-price { color: var(--maken-amber-dark); font-weight: 800; font-size: 13px; }
    .product-stock { font-size: 11px; color: #94a3b8; margin-top: 5px; }

    .cart-table { width: 100%; border-collapse: collapse; }
    .cart-table th { text-align: left; font-size: 12px; text-transform: uppercase; color: #94a3b8; padding-bottom: 10px; }
    .cart-table td { padding: 10px 0; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .cart-input  { width: 60px; height: 30px; border: 1px solid var(--maken-line); border-radius: 5px; text-align: center; font-family: inherit; }
    .price-input { width: 80px; height: 30px; border: 1px solid var(--maken-line); border-radius: 5px; text-align: center; font-family: inherit; }

    .total-box { padding: 20px; background: #f8fafc; border-top: 1px solid var(--maken-line); }
    .total-row  { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; font-weight: 600; }
    .total-grand { font-size: 20px; font-weight: 800; color: var(--maken-slate); border-top: 2px dashed var(--maken-line); padding-top: 10px; margin-top: 4px; }

    .discount-input-wrap { display: flex; align-items: center; gap: 6px; }
    .discount-input {
        width: 100px; height: 32px;
        border: 1.5px solid var(--maken-line); border-radius: 7px;
        padding: 0 10px; font-family: inherit; font-size: 13px; font-weight: 600;
        text-align: right; outline: none; color: var(--maken-slate); background: #fff;
        transition: border-color .2s;
    }
    .discount-input:focus { border-color: var(--maken-amber); }
    .discount-type-btn {
        height: 32px; padding: 0 10px;
        border: 1.5px solid var(--maken-line); border-radius: 7px;
        background: #fff; font-family: inherit; font-size: 12px; font-weight: 700;
        cursor: pointer; color: #64748b; transition: all .2s;
    }
    .discount-type-btn.active,
    .discount-type-btn:hover { border-color: var(--maken-amber); background: var(--maken-amber); color: var(--maken-slate); }

    .btn-submit { width: 100%; height: 50px; background: var(--maken-amber); border: none; border-radius: 10px; color: var(--maken-slate); font-weight: 800; font-size: 16px; cursor: pointer; transition: all .2s; font-family: inherit; }
    .btn-submit:hover { background: var(--maken-amber-dark); color: #fff; }
    .btn-ghost  { background: none; border: 1.5px solid var(--maken-line); border-radius: 8px; cursor: pointer; font-family: inherit; font-weight: 600; color: var(--maken-slate); transition: all .2s; }
    .btn-ghost:hover { border-color: var(--maken-amber); background: #fffbeb; }

    /* Customer row */
    .customer-row { display: flex; gap: 8px; align-items: center; margin-bottom: 15px; }
    .customer-select { flex: 1; height: 42px; border: 1.5px solid var(--maken-line); border-radius: 10px; padding: 0 10px; outline: none; font-family: inherit; font-size: 13px; background: #fff; }
    .customer-select:focus { border-color: var(--maken-amber); }
    .new-cust-btn { width: 42px; height: 42px; border: 1.5px solid var(--maken-line); border-radius: 10px; background: #fff; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; transition: all .2s; }
    .new-cust-btn:hover  { border-color: var(--maken-amber); background: #fffbeb; color: var(--maken-amber-dark); }
    .new-cust-btn.active { background: var(--maken-amber); border-color: var(--maken-amber); color: var(--maken-slate); }

    /* Quick-add customer form */
    .quick-add-form { display: none; background: #fffbeb; border: 1.5px solid var(--maken-amber); border-radius: 10px; padding: 14px; margin-bottom: 15px; }
    .quick-add-form.open { display: block; }
    .qa-title { font-size: 12px; font-weight: 700; margin-bottom: 10px; display: flex; align-items: center; gap: 6px; }
    .qa-grid  { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 10px; }
    .qa-field label { display: block; font-size: 11px; font-weight: 600; color: #64748b; margin-bottom: 3px; }
    .qa-field input { width: 100%; height: 34px; padding: 0 10px; border: 1.5px solid var(--maken-line); border-radius: 7px; font-size: 12px; font-family: inherit; background: #fff; box-sizing: border-box; outline: none; }
    .qa-field input:focus { border-color: var(--maken-amber); }
    .qa-field input.has-error { border-color: var(--maken-danger); }
    .qa-field.full { grid-column: 1 / -1; }
    .qa-save { width: 100%; height: 34px; background: var(--maken-slate); color: #fff; border: none; border-radius: 7px; font-size: 12px; font-weight: 700; font-family: inherit; cursor: pointer; transition: background .2s; }
    .qa-save:hover { background: var(--maken-slate-2); }
    .qa-save:disabled { opacity: .5; cursor: not-allowed; }

    /* Popup / toast */
    .mk-popup { position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 8px; pointer-events: none; }
    .mk-toast { padding: 12px 18px; border-radius: 10px; font-size: 13px; font-weight: 600; box-shadow: 0 4px 15px rgba(0,0,0,.12); animation: fadeIn .3s; pointer-events: auto; }
    .mk-toast.success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    .mk-toast.error   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
    @keyframes fadeIn { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }

    /* Hold modal */
    .hold-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 2000; display: flex; align-items: center; justify-content: center; }
    .hold-modal { background: #fff; width: 380px; border-radius: 14px; padding: 24px; box-shadow: 0 20px 50px rgba(0,0,0,.2); }
    .hold-modal h3 { margin: 0 0 16px; font-size: 16px; }
    .hold-field { margin-bottom: 14px; }
    .hold-field label { display: block; font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 5px; text-transform: uppercase; letter-spacing: .4px; }
    .hold-field input { width: 100%; height: 40px; border: 1.5px solid var(--maken-line); border-radius: 9px; padding: 0 12px; font-size: 13px; font-family: inherit; outline: none; box-sizing: border-box; }
    .hold-field input:focus { border-color: var(--maken-amber); }
    .hold-field .expiry-hint { font-size: 11px; color: #94a3b8; margin-top: 4px; }
    .hold-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 18px; }
    .hold-btn-confirm { height: 42px; background: var(--maken-slate); color: #fff; border: none; border-radius: 9px; font-weight: 800; font-size: 14px; font-family: inherit; cursor: pointer; }
    .hold-btn-cancel  { height: 42px; background: none; border: 1.5px solid var(--maken-line); border-radius: 9px; font-weight: 700; font-size: 14px; font-family: inherit; cursor: pointer; }
</style>

<div id="popupContainer" class="mk-popup"></div>

<div class="pos-container">

    {{-- LEFT: Product grid --}}
    <div class="main-panel">
        <div class="mk-card" style="height:100%;">
            <div class="card-header">
                <span> <i data-lucide="package"></i>  Products</span>
                <input type="text" id="prodSearch" class="search-input" style="width:250px; height:34px;" placeholder="Search products or barcode...">
            </div>
            <div class="card-body" style="overflow-y:auto; max-height:70vh;">
                <div id="productGrid" class="product-grid">
                    <p style="color:#94a3b8; grid-column:1/-1;">Loading products...</p>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT: Cart panel --}}
    <div class="side-panel">
        <div class="mk-card" style="height:100%;">
            <div class="card-header">
                <span><i class="fas fa-shopping-cart"></i> Cart</span>
                <div style="display:flex; gap:10px;">
                    <button onclick="showHeldCarts()" class="btn-ghost" style="height:30px; font-size:11px; padding:0 10px;">
                        <i class="fas fa-list"></i> Held
                    </button>
                    <button onclick="clearCart()" style="background:none; border:none; color:var(--maken-danger); cursor:pointer;" title="Clear cart">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>

            <div class="card-body">

                {{-- Customer selector --}}
                <div class="customer-row">
                    <select id="customer_id" class="customer-select">
                        <option value="">Select Customer</option>
                    </select>
                    <button class="new-cust-btn" id="newCustBtn" onclick="toggleNewCustomer()" title="Add new customer">
                        <i class="fas fa-user-plus"></i>
                    </button>
                </div>

                {{-- Quick-add customer form --}}
                <div class="quick-add-form" id="quickAddForm">
                    <div class="qa-title">
                        <i class="fas fa-user-plus" style="color:var(--maken-amber-dark);"></i> New Customer
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
                        <!-- <div class="qa-field">
                            <label>Opening Balance <span style="color:var(--maken-danger)">*</span></label>
                            <input type="number" id="qa_opening_balance" placeholder="0" min="0" />
                        </div> -->
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

                {{-- Cart items --}}
                <div style="overflow-y:auto; max-height:40vh;">
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
                            <tr>
                                <td colspan="4" style="text-align:center; color:#94a3b8; font-size:13px; padding:20px 0;">
                                    <i class="fas fa-shopping-cart" style="font-size:24px; display:block; margin-bottom:8px; opacity:.4;"></i>
                                    Cart is empty
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="total-box">
                <div class="total-row">
                    <span>Subtotal</span>
                    <span id="subtotal">Rs. 0.00</span>
                </div>

                <div class="total-row">
                    <span>Discount</span>
                    <div class="discount-input-wrap">
                        <button class="discount-type-btn active" id="discountTypeBtn" onclick="toggleDiscountType()">Rs.</button>
                        <input type="number" id="discountInput" class="discount-input" placeholder="0" min="0" value="0" oninput="recalcTotals()">
                    </div>
                </div>

                <div class="total-row total-grand">
                    <span>Grand Total</span>
                    <span id="grandTotal">Rs. 0.00</span>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-top:10px;">
                    <button class="btn-ghost" style="height:50px; font-weight:800;" onclick="openHoldModal()">
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

{{-- ── Hold Cart Modal ── --}}
<div class="hold-overlay" id="holdOverlay" style="display:none;">
    <div class="hold-modal">
        <h3><i class="fas fa-pause-circle" style="color:var(--maken-amber-dark);"></i> Hold Cart</h3>
        <div class="hold-field">
            <label>Reference Name</label>
            <input type="text" id="holdRef" placeholder="e.g. Ahmed — Summer Deal" />
        </div>
        <div class="hold-field">
            <label>Valid for (days)</label>
            <input type="number" id="holdDays" value="1" min="1" max="365" />
            <div class="expiry-hint" id="expiryPreview"></div>
        </div>
        <div class="hold-actions">
            <button class="hold-btn-cancel" onclick="closeHoldModal()">Cancel</button>
            <button class="hold-btn-confirm" onclick="confirmHold()">
                <i class="fas fa-pause-circle"></i> Hold Cart
            </button>
        </div>
    </div>
</div>

<script>
let products     = [];
let cart         = [];
let customers    = [];
let formOpen     = false;
let discountType = 'flat';
let subtotalValue = 0;

/* ── Helpers ── */
function fmt(n) { return parseFloat(n).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }

/* ══════════════════════════════════
   TOAST / POPUP
══════════════════════════════════ */
function showPopup(msg, type = 'success') {
    const c  = document.getElementById('popupContainer');
    const el = document.createElement('div');
    el.className = 'mk-toast ' + type;
    el.innerHTML = (type === 'success' ? '<i class="fas fa-check-circle"></i> ' : '<i class="fas fa-exclamation-circle"></i> ') + msg;
    c.appendChild(el);
    setTimeout(() => el.remove(), 3500);
}

/* ══════════════════════════════════
   DISCOUNT
══════════════════════════════════ */
function toggleDiscountType() {
    discountType = discountType === 'flat' ? 'percent' : 'flat';
    const btn = document.getElementById('discountTypeBtn');
    btn.textContent = discountType === 'flat' ? 'Rs.' : '%';
    document.getElementById('discountInput').value = 0;
    recalcTotals();
}

function getDiscountAmount() {
    const val = parseFloat(document.getElementById('discountInput').value) || 0;
    if (discountType === 'percent') return (subtotalValue * Math.min(val, 100)) / 100;
    return Math.min(val, subtotalValue);
}

function recalcTotals() {
    const discount   = getDiscountAmount();
    const grandTotal = Math.max(0, subtotalValue - discount);
    document.getElementById('subtotal').textContent  = 'Rs. ' + fmt(subtotalValue);
    document.getElementById('grandTotal').textContent = 'Rs. ' + fmt(grandTotal);
}

/* ══════════════════════════════════
   LOAD DATA
══════════════════════════════════ */
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
            axios.get('/api/quotations/' + restoreId).then(r2 => {
                const h = r2.data.data;
                if (h) {
                    cart = h.items;
                    document.getElementById('customer_id').value = h.customer_id || '';
                    renderCart();
                    axios.delete('/api/quotations/' + h.id).catch(() => {});
                    window.history.replaceState({}, document.title, '/pos');
                    showPopup('Cart restored: ' + (h.reference_name || 'Unnamed'));
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

/* ══════════════════════════════════
   QUICK-ADD CUSTOMER
══════════════════════════════════ */
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
    ['qa_name','qa_phone','qa_email','qa_address'].forEach(id => {
        const el = document.getElementById(id);
        el.value = '';
        el.classList.remove('has-error');
    });
}

function saveNewCustomer() {

    const nameEl = document.getElementById('qa_name');
    const name   = nameEl.value.trim();

    // validation
    if (!name) {
        nameEl.classList.add('has-error');
        nameEl.focus();
        return;
    }

    nameEl.classList.remove('has-error');

    const btn = document.getElementById('qaSaveBtn');

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

    const payload = {
        name,
        phone: document.getElementById('qa_phone').value.trim() || null,
        email: document.getElementById('qa_email').value.trim() || null,
        address: document.getElementById('qa_address').value.trim() || null,
    };

    axios.post('/api/customers', payload)

        .then(r => {

            let c = r.data?.data?.id ? r.data.data
                  : r.data?.customer?.id ? r.data.customer
                  : r.data?.id ? r.data
                  : null;

            if (c && c.id) {

                customers.unshift(c);

                populateCustomerSelect();

                // auto select newly added customer
                document.getElementById('customer_id').value = c.id;

            } else {

                axios.get('/api/customers?name_list=true')

                    .then(r2 => {

                        customers = r2.data.data || r2.data;

                        populateCustomerSelect();

                        const found = customers.find(x => x.name === payload.name);

                        if (found) {
                            document.getElementById('customer_id').value = found.id;
                        }
                    });
            }

            showPopup(payload.name + ' added & selected!', 'success');

            closeNewCustomer();
        })

        .catch(err => {

            showPopup(
                err.response?.data?.message || 'Failed to save customer',
                'error'
            );

        })

        .finally(() => {

            btn.disabled = false;

            btn.innerHTML = '<i class="fas fa-check"></i> Save & Select';

        });
}

/* ══════════════════════════════════
   PRODUCTS
══════════════════════════════════ */
function renderProducts(search = '') {
    const grid = document.getElementById('productGrid');
    const filtered = products.filter(p =>
        p.name.toLowerCase().includes(search.toLowerCase()) ||
        (p.barcode && p.barcode.includes(search))
    );

    if (filtered.length === 0) {
        grid.innerHTML = `<p style="color:#94a3b8; grid-column:1/-1; text-align:center; padding:30px 0;">No products found.</p>`;
        return;
    }

    grid.innerHTML = filtered.map(p => `
        <div class="product-item ${p.qty <= 0 ? 'out-of-stock' : ''}" onclick="addToCart(${p.id})">
            <div class="product-name">${p.name}</div>
            <div class="product-price">Rs. ${parseFloat(p.price).toLocaleString()}</div>
            <div class="product-stock">Stock: ${p.qty}</div>
        </div>
    `).join('');
}

function addToCart(id) {
    const p = products.find(p => p.id === id);
    if (!p || p.qty <= 0) { showPopup('Out of stock!', 'error'); return; }

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

    if (cart.length === 0) {
        body.innerHTML = `
            <tr>
                <td colspan="4" style="text-align:center; color:#94a3b8; font-size:13px; padding:20px 0;">
                    <i class="fas fa-shopping-cart" style="font-size:24px; display:block; margin-bottom:8px; opacity:.4;"></i>
                    Cart is empty
                </td>
            </tr>`;
        subtotalValue = 0;
        recalcTotals();
        return;
    }

    let total = 0;
    body.innerHTML = cart.map((item, index) => {
        const itemTotal = item.qty * item.price;
        total += itemTotal;
        return `
            <tr>
                <td>
                    <div style="font-weight:700; font-size:13px; display:flex; align-items:center; gap:6px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        <span onclick="removeItem(${index})" style="color:var(--maken-danger); font-size:10px; cursor:pointer; flex-shrink:0;" title="Remove">
                            <i class="fas fa-trash"></i>
                        </span>
                        <span style="overflow:hidden; text-overflow:ellipsis;">${item.name}</span>
                    </div>
                </td>
                <td>
                    <input type="number" class="cart-input" value="${item.qty}" min="1" max="${item.stock}"
                        onchange="updateQty(${index}, this.value)">
                </td>
                <td>
                    <input type="number" class="price-input" value="${item.price}"
                        onchange="updatePrice(${index}, this.value)">
                </td>
                <td style="text-align:right; font-weight:700; white-space:nowrap;">
                    Rs. ${itemTotal.toFixed(2)}
                </td>
            </tr>
        `;
    }).join('');

    subtotalValue = total;
    recalcTotals();
}

function updateQty(index, val)   { cart[index].qty   = parseInt(val)   || 1; renderCart(); }
function updatePrice(index, val) { cart[index].price = parseFloat(val) || 0; renderCart(); }
function removeItem(index)       { cart.splice(index, 1);                    renderCart(); }

function clearCart() {
    cart = [];
    document.getElementById('discountInput').value = 0;
    subtotalValue = 0;
    renderCart();
}

/* ══════════════════════════════════
   SUBMIT SALE
══════════════════════════════════ */
function submitSale() {
    const customerId = document.getElementById('customer_id').value;
    if (!customerId)       { showPopup('Please select a customer', 'error'); return; }
    if (cart.length === 0) { showPopup('Cart is empty', 'error'); return; }

    const discount   = getDiscountAmount();
    const grandTotal = Math.max(0, subtotalValue - discount);

    const data = {
        customer_id: customerId,
        total_bill:  subtotalValue,
        discount:    discount,
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
            if (confirm('Print the invoice?')) printSale(r.data.sale_id);
            clearCart();
            loadData();
        })
        .catch(err => showPopup(err.response?.data?.message || 'Error completing sale', 'error'))
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check-circle"></i> COMPLETE';
        });
}

/* ══════════════════════════════════
   HOLD CART — modal
══════════════════════════════════ */
function openHoldModal() {
    if (cart.length === 0) { showPopup('Cart is empty', 'error'); return; }
    document.getElementById('holdRef').value  = '';
    document.getElementById('holdDays').value = 1;
    updateExpiryPreview();
    document.getElementById('holdOverlay').style.display = 'flex';
    setTimeout(() => document.getElementById('holdRef').focus(), 50);
}

function closeHoldModal() {
    document.getElementById('holdOverlay').style.display = 'none';
}

function updateExpiryPreview() {
    const days = parseInt(document.getElementById('holdDays').value) || 1;
    const d = new Date();
    d.setDate(d.getDate() + days);
    document.getElementById('expiryPreview').textContent =
        'Receipt will expire on ' + d.toLocaleDateString('en-PK', { year:'numeric', month:'long', day:'numeric' });
}

function confirmHold() {
    const ref  = document.getElementById('holdRef').value.trim() || 'Unnamed';
    const days = parseInt(document.getElementById('holdDays').value) || 1;

    const expiry = new Date();
    expiry.setDate(expiry.getDate() + days);
    const expiryStr = expiry.toISOString().split('T')[0];

    axios.post('/api/quotations', {
        customer_id:    document.getElementById('customer_id').value || null,
        reference_name: ref,
        items:          cart,
        total:          subtotalValue,
        discount:       getDiscountAmount(),
        date:           new Date().toISOString().split('T')[0],
        expiry_date:    expiryStr,
    }).then(() => {
        showPopup('Cart held — expires ' + expiryStr);
        closeHoldModal();
        clearCart();
    }).catch(() => showPopup('Failed to hold cart', 'error'));
}

/* ══════════════════════════════════
   SHOW HELD CARTS (quick popup)
══════════════════════════════════ */
function showHeldCarts() {
    axios.get('/api/quotations').then(r => {
        const held = r.data.data;
        if (held.length === 0) { showPopup('No held carts', 'error'); return; }

        const today = new Date(); today.setHours(0,0,0,0);

        let html = '<div style="max-height:380px; overflow-y:auto;">';
        held.forEach(h => {
            const expired = h.expiry_date && new Date(h.expiry_date) < today;
            html += `
                <div style="padding:12px 15px; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <div style="font-weight:700; font-size:13px;">${h.reference_name || 'Unnamed'}</div>
                        <div style="font-size:11px; color:#64748b;">${h.date} &bull; Rs. ${parseFloat(h.total).toLocaleString()}</div>
                        ${h.expiry_date ? `
                            <span style="font-size:10px; font-weight:700; padding:1px 7px; border-radius:4px;
                                background:${expired ? '#fee2e2' : '#fef3c7'};
                                color:${expired ? '#b91c1c' : '#92400e'};">
                                ${expired ? '⚠ Expired' : '✓ Expires: ' + h.expiry_date}
                            </span>` : ''}
                    </div>
                    <div style="display:flex; gap:5px;">
                        <button onclick="restoreHeld(${h.id})" style="padding:5px 12px; background:var(--maken-amber); border:none; border-radius:6px; cursor:pointer; font-size:11px; font-weight:700;">Restore</button>
                        <button onclick="deleteHeld(${h.id})" style="padding:5px 8px; background:none; border:none; color:var(--maken-danger); cursor:pointer;"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            `;
        });
        html += '</div>';

        if (document.getElementById('heldOverlay')) document.getElementById('heldOverlay').remove();
        const overlay = document.createElement('div');
        overlay.id = 'heldOverlay';
        overlay.style = 'position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:2000; display:flex; align-items:center; justify-content:center;';
        overlay.innerHTML = `
            <div style="background:#fff; width:420px; padding:20px; border-radius:15px; box-shadow:0 15px 40px rgba(0,0,0,.2);">
                <div style="display:flex; justify-content:space-between; margin-bottom:15px; align-items:center;">
                    <h3 style="margin:0; font-size:15px;"><i class="fas fa-pause-circle" style="color:var(--maken-amber-dark);"></i> Held Carts</h3>
                    <button onclick="document.getElementById('heldOverlay').remove()" style="border:none; background:none; font-size:22px; cursor:pointer; color:#94a3b8;">&times;</button>
                </div>
                ${html}
                <div style="margin-top:12px; text-align:center;">
                    <a href="/quotations" style="font-size:12px; color:#64748b; text-decoration:none;">View all quotations →</a>
                </div>
            </div>
        `;
        document.body.appendChild(overlay);
    });
}

function restoreHeld(id) {
    axios.get('/api/quotations/' + id).then(r => {
        const h = r.data.data;
        if (h) {
            if (cart.length > 0 && !confirm('Discard current cart and restore this one?')) return;
            cart = h.items;
            document.getElementById('customer_id').value = h.customer_id || '';
            renderCart();
            if (document.getElementById('heldOverlay')) document.getElementById('heldOverlay').remove();
            axios.delete('/api/quotations/' + id).catch(() => {});
            showPopup('Cart restored: ' + (h.reference_name || 'Unnamed'));
        }
    });
}

function deleteHeld(id) {
    if (!confirm('Remove this held cart?')) return;
    axios.delete('/api/quotations/' + id)
        .then(() => {
            showPopup('Removed successfully');
            if (document.getElementById('heldOverlay')) document.getElementById('heldOverlay').remove();
            showHeldCarts();
        })
        .catch(() => showPopup('Failed to delete', 'error'));
}

/* ══════════════════════════════════
   EVENT BINDINGS
══════════════════════════════════ */
document.getElementById('prodSearch').oninput = e => renderProducts(e.target.value);
document.getElementById('holdDays').oninput   = updateExpiryPreview;

/* Close hold modal on background click */
document.getElementById('holdOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeHoldModal();
});

window.onload = loadData;
</script>

@endsection
