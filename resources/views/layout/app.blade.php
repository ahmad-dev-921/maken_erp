<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maken ERP | Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        :root {
            --maken-amber: #fbbf24;
            --maken-amber-dark: #d97706;
            --maken-amber-soft: #fffbeb;
            --maken-slate: #1c2d54;
            --maken-light: #f8fafc;
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 72px;
            --topbar-height: 64px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--maken-light);
            margin: 0;
            overflow: hidden;
            height: 100vh;
        }

        /* ─── SIDEBAR ─────────────────────────────── */
        #sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: radial-gradient(circle at top right,
                #123a6f 0%, #0a2550 40%, #07162f 75%, #050b1a 100%);
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            transition: var(--transition);
            z-index: 1000;
            overflow: hidden;
        }

        #sidebar.collapsed { width: var(--sidebar-collapsed-width); }

        .sidebar-header {
            height: var(--topbar-height);
            padding: 0 1.2rem;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #f1f5f9;
            flex-shrink: 0;
            gap: 12px;
            white-space: nowrap;
        }

        .brand-name {
            font-size: 1rem;
            font-weight: 800;
            letter-spacing: 2px;
            color: var(--maken-light);
            transition: opacity 0.2s, width 0.3s;
        }
        #sidebar.collapsed .brand-name { opacity: 0; width: 0; }

        .sidebar-toggle-btn-inner {
            margin-left: auto;
            width: 28px; height: 28px;
            border: 1px solid #e2e8f0;
            background: #fff;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #64748b;
            transition: var(--transition);
        }
        .sidebar-toggle-btn-inner:hover { border-color: var(--maken-amber); color: var(--maken-amber); }
        #sidebar.collapsed .sidebar-toggle-btn-inner { display: none; }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 1rem 0;
        }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }

        .menu-section-label {
            font-size: 10px; font-weight: 700; letter-spacing: 1.5px;
            text-transform: uppercase; color: #94a3b8;
            padding: 0 1.4rem; margin: 1rem 0 0.5rem;
            transition: opacity 0.2s;
        }
        #sidebar.collapsed .menu-section-label { opacity: 0; }

        .nav-item-wrapper { padding: 0 0.6rem; }
        .nav-link {
            display: flex; align-items: center; gap: 12px;
            padding: 0.7rem 0.9rem; border-radius: 10px;
            color: #cbd5e1; font-weight: 600; font-size: 0.875rem;
            text-decoration: none; transition: var(--transition);
            background: transparent;
        }
        .nav-link svg { width: 18px; height: 18px; flex-shrink: 0; }
        #sidebar.collapsed .nav-link { justify-content: center; gap: 0; padding: 0.7rem; }
        #sidebar.collapsed .nav-text { display: none; }
        .sidebar-nav .nav-link:hover,
        .sidebar-nav .nav-link.active {
            background: var(--maken-amber-soft);
            color: var(--maken-amber);
        }

        .sidebar-user {
            border-top: 1px solid #f1f5f9; padding: 1rem;
            display: flex; align-items: center; gap: 10px;
        }
        .user-avatar {
            width: 36px; height: 36px; background: var(--maken-slate);
            color: #fff; border-radius: 10px; display: flex;
            align-items: center; justify-content: center; font-weight: 800;
        }
        #sidebar.collapsed .user-info-text { display: none; }

        /* ─── MAIN WRAPPER ────────────────────────── */
        #main-wrapper {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            height: 100vh;
            display: flex;
            flex-direction: column;
            transition: var(--transition);
        }
        #main-wrapper.expanded {
            margin-left: var(--sidebar-collapsed-width);
            width: calc(100% - var(--sidebar-collapsed-width));
        }

        /* ─── TOP BAR ─────────────────────────────── */
        .top-bar {
            height: var(--topbar-height);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-shrink: 0;
        }

        .header-toggle-btn {
            width: 38px; height: 38px;
            border: 1.5px solid #e2e8f0;
            background: #f8fafc;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            color: #64748b;
        }
        .header-toggle-btn:hover { background: var(--maken-amber-soft); border-color: var(--maken-amber); color: var(--maken-amber-dark); }
        .header-toggle-btn svg { width: 18px; height: 18px; }

        .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 0.75rem; }
        .scroll-area { flex: 1; overflow-y: auto; padding: 2rem; }

        #overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(15,23,42,0.5); z-index: 999;
        }
        #overlay.active { display: block; }

        @media (max-width: 991px) {
            #sidebar { left: calc(-1 * var(--sidebar-width)); }
            #sidebar.mobile-open { left: 0; width: var(--sidebar-width) !important; }
            #main-wrapper { margin-left: 0 !important; width: 100% !important; }
        }

        /* ══════════════════════════════════════════════
           80mm THERMAL RECEIPT — hidden on screen,
           visible only when printing sales receipts
        ══════════════════════════════════════════════ */
        #thermalReceipt {
            display: none; /* hidden always on screen */
        }

        @media print {
            /* Hide everything except the thermal receipt */
            body > *:not(#thermalReceipt) { display: none !important; }
            #thermalReceipt {
                display: block !important;
                position: fixed !important;
                inset: 0 !important;
                background: #fff !important;
            }

            /* 80mm thermal paper = 72mm printable width */
            @page {
                size: 80mm auto;   /* width fixed, height auto = cuts at content */
                margin: 0;
            }

            body {
                background: #fff !important;
                margin: 0 !important;
                overflow: visible !important;
                height: auto !important;
            }
        }
    </style>
</head>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<body>

<div id="overlay"></div>

<aside id="sidebar">
    <div class="sidebar-header">
        <span class="brand-name">Maken Solar Energy</span>
        <!-- <div class="sidebar-toggle-btn-inner d-none d-lg-flex" id="sidebarInnerToggle">
            <i data-lucide="chevron-left" id="innerToggleIcon"></i>
        </div> -->
    </div>

    <div class="sidebar-nav">
        <p class="menu-section-label">Main</p>
        <div class="nav-item-wrapper">
            <a href="/dashboard" class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard"></i>
                <span class="nav-text">Dashboard</span>
            </a>
        </div>

        <p class="menu-section-label">Inventory & Sales</p>

        <div class="nav-item-wrapper">
            <a href="/inventory" class="nav-link {{ request()->is('inventory') ? 'active' : '' }}">
                <i data-lucide="package"></i>
                <span class="nav-text">Inventory Hub</span>
            </a>
        </div>

        <div class="nav-item-wrapper">
            <a href="/quotations" class="nav-link {{ request()->is('quotations') ? 'active' : '' }}">
                <i data-lucide="file-text"></i>
                <span class="nav-text">Quotations</span>
            </a>
        </div>

        <div class="nav-item-wrapper">
            <a href="/pos" class="nav-link {{ request()->is('pos') ? 'active' : '' }}">
                <i data-lucide="shopping-cart"></i>
                <span class="nav-text">POS Terminal</span>
            </a>
        </div>

        <div class="nav-item-wrapper">
            <a href="/report" class="nav-link {{ request()->is('report') ? 'active' : '' }}">
                <i data-lucide="bar-chart-3"></i>
                <span class="nav-text">Sales Report</span>
            </a>
        </div>

        <p class="menu-section-label">People</p>

        <div class="nav-item-wrapper">
            <a href="/customer" class="nav-link {{ request()->is('customer') ? 'active' : '' }}">
                <i data-lucide="users"></i>
                <span class="nav-text">Customer Leads</span>
            </a>
        </div>
    </div>

    <div class="sidebar-user">
        <div class="user-avatar">AD</div>
        <div class="user-info-text">
            <div class="user-name">Admin User</div>
            <div class="user-role">Shop Owner</div>
        </div>
    </div>
</aside>

<div id="main-wrapper">
    <header class="top-bar">
        <button class="header-toggle-btn" id="globalToggleBtn">
            <i data-lucide="menu" id="globalToggleIcon"></i>
        </button>

        <span class="page-title d-none d-sm-inline fw-bold text-muted text-uppercase small">
            Premium Solar Solutions
        </span>

        <div class="topbar-right">
            <div class="user-avatar" style="width:auto; padding:0 10px; height:32px; font-size:12px; display:flex; align-items:center;">
                {{ \Carbon\Carbon::now()->format('l, d F Y') }}
            </div>
        </div>
    </header>

    <div class="scroll-area">
        @yield('content')
    </div>
</div>


<!-- ══════════════════════════════════════════════════════
     80mm THERMAL RECEIPT
     Hidden on screen. Shown only on window.print().
     Filled dynamically by printSale(id).
     Width: 72mm printable (80mm roll - margins).
     Font: monospace-style for clean thermal output.
══════════════════════════════════════════════════════ -->
<div id="thermalReceipt">
    <style>
        /* Scoped styles inside the receipt div */
        #thermalReceipt {
            width: 72mm;
            margin: 0 auto;
            padding: 4mm 3mm;
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            color: #000;
            background: #fff;
            line-height: 1.45;
        }

        /* Shop header */
        #thermalReceipt .th-shop-name {
            text-align: center;
            font-size: 15px;
            font-weight: 900;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 1mm;
        }
        #thermalReceipt .th-shop-sub {
            text-align: center;
            font-size: 9.5px;
            color: #333;
            margin-bottom: 0.5mm;
        }
        #thermalReceipt .th-divider {
            border: none;
            border-top: 1px dashed #000;
            margin: 2mm 0;
        }
        #thermalReceipt .th-divider-solid {
            border: none;
            border-top: 1px solid #000;
            margin: 2mm 0;
        }

        /* Receipt meta */
        #thermalReceipt .th-meta {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            margin-bottom: 0.5mm;
        }
        #thermalReceipt .th-meta span:last-child {
            text-align: right;
        }

        /* Customer info */
        #thermalReceipt .th-customer {
            font-size: 10.5px;
            font-weight: 700;
            margin: 1mm 0 0.5mm;
        }
        #thermalReceipt .th-customer-phone {
            font-size: 10px;
            color: #333;
        }

        /* Items table */
        #thermalReceipt .th-items {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
            margin: 1.5mm 0;
        }
        #thermalReceipt .th-items thead tr {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }
        #thermalReceipt .th-items th {
            padding: 1mm 0.5mm;
            text-align: left;
            font-size: 9.5px;
            text-transform: uppercase;
            font-weight: 900;
            letter-spacing: .3px;
        }
        #thermalReceipt .th-items th.r,
        #thermalReceipt .th-items td.r { text-align: right; }

        #thermalReceipt .th-items td {
            padding: 1.2mm 0.5mm;
            vertical-align: top;
            border-bottom: 1px dashed #ccc;
        }
        #thermalReceipt .th-items tr:last-child td {
            border-bottom: none;
        }

        /* Item name wraps, price/qty stay on one line */
        #thermalReceipt .th-item-name {
            font-weight: 600;
            word-break: break-word;
            max-width: 30mm;
        }

        /* Totals section */
        #thermalReceipt .th-total-row {
            display: flex;
            justify-content: space-between;
            font-size: 10.5px;
            padding: 0.5mm 0;
        }
        #thermalReceipt .th-total-row.grand {
            font-size: 13px;
            font-weight: 900;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 1.5mm 0;
            margin: 1mm 0;
        }
        #thermalReceipt .th-total-row.discount span:last-child {
            color: #000;
            font-weight: 700;
        }

        /* Footer */
        #thermalReceipt .th-footer {
            text-align: center;
            font-size: 9.5px;
            color: #333;
            margin-top: 2mm;
            line-height: 1.6;
        }
        #thermalReceipt .th-thankyou {
            text-align: center;
            font-size: 11.5px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin: 2mm 0 1mm;
        }

        /* Barcode-style bottom line (decorative) */
        #thermalReceipt .th-bottom-space {
            margin-top: 8mm; /* extra space so paper cuts cleanly */
        }
    </style>

    <!-- SHOP HEADER -->
    <div class="th-shop-name">Maken Solar Energy</div>
    <div class="th-shop-sub">Electronic Market, Habib Bank Street, Block No. 5</div>
    <div class="th-shop-sub">Tel: 0314-4949491 / 0308-8288461</div>

    <hr class="th-divider-solid">

    <!-- RECEIPT META -->
    <div class="th-meta">
        <span>INV#: <strong id="th-inv-id">---</strong></span>
        <span id="th-date">---</span>
    </div>
    <div class="th-meta">
        <span>STATUS:</span>
        <span><strong style="color:#000;">PAID</strong></span>
    </div>

    <hr class="th-divider">

    <!-- CUSTOMER -->
    <div style="font-size:9px; text-transform:uppercase; color:#555; letter-spacing:.5px;">Customer</div>
    <div class="th-customer" id="th-customer">---</div>
    <div class="th-customer-phone" id="th-phone"></div>

    <hr class="th-divider">

    <!-- ITEMS TABLE -->
    <table class="th-items">
        <thead>
            <tr>
                <th style="width:38mm;">Item</th>
                <th class="r" style="width:8mm;">Qty</th>
                <th class="r" style="width:20mm;">Total</th>
            </tr>
        </thead>
        <tbody id="th-items-body">
            <!-- filled by printSale() -->
        </tbody>
    </table>

    <hr class="th-divider-solid">

    <!-- TOTALS -->
    <div id="th-subtotal-row" class="th-total-row">
        <span>Subtotal</span>
        <span id="th-subtotal">Rs. 0</span>
    </div>
    <div id="th-discount-row" class="th-total-row discount" style="display:none;">
        <span>Discount</span>
        <span id="th-discount">- Rs. 0</span>
    </div>
    <div class="th-total-row grand">
        <span>TOTAL</span>
        <span id="th-grand">Rs. 0</span>
    </div>

    <!-- THANK YOU -->
    <div class="th-thankyou">Thank You!</div>
    <div class="th-footer">
        Items once sold are not returnable<br>
        without original receipt.<br>
        <strong>Maken Solar Energy</strong>
    </div>

    <div class="th-bottom-space"></div>
</div>


<!-- Bootstrap confirm modal -->
<div class="modal fade" id="appModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div id="modalIcon" style="font-size:40px;"></div>
                <h6 class="mt-3" id="modalMessage"></h6>
            </div>
            <div class="modal-footer justify-content-center" id="modalActions"></div>
        </div>
    </div>
</div>


<script>
/* ══════════════════════════════════════════════════════
   printSale(id)
   Fetches the sale from /api/sales/{id}, fills the
   80mm thermal receipt template, then calls print().
   The @media print CSS hides everything except
   #thermalReceipt so only the narrow receipt prints.
══════════════════════════════════════════════════════ */
function printSale(id) {
    if (!id) return;

    axios.get('/api/sales/' + id).then(r => {
        const s = r.data.data;

        /* ── Fill header fields ── */
        document.getElementById('th-inv-id').textContent =
            'INV-' + String(s.id).padStart(5, '0');

        document.getElementById('th-date').textContent = s.date;

        document.getElementById('th-customer').textContent =
            s.customer ? s.customer.name : 'Walking Customer';

        const phoneEl = document.getElementById('th-phone');
        phoneEl.textContent = s.customer && s.customer.phone ? s.customer.phone : '';

        /* ── Build items rows ── */
        const subtotal = parseFloat(s.total_bill);
        const discount = parseFloat(s.discount || 0);
        const grand    = Math.max(0, subtotal - discount);

        let rows = '';
        s.details.forEach(d => {
            const name      = d.product ? d.product.name : 'Unknown';
            const qty       = d.qty;
            const lineTotal = parseFloat(d.total).toLocaleString('en');

            // Unit price shown as small line under name to save width
            rows += `
                <tr>
                    <td>
                        <div class="th-item-name">${name}</div>
                        <div style="font-size:9.5px; color:#444;">
                            ${qty} x Rs.${parseFloat(d.price).toLocaleString('en')}
                        </div>
                    </td>
                    <td class="r">${qty}</td>
                    <td class="r" style="font-weight:700;">Rs.${lineTotal}</td>
                </tr>
            `;
        });
        document.getElementById('th-items-body').innerHTML = rows;

        /* ── Totals ── */
        document.getElementById('th-subtotal').textContent =
            'Rs. ' + subtotal.toLocaleString('en');

        const discountRow = document.getElementById('th-discount-row');
        if (discount > 0) {
            document.getElementById('th-discount').textContent =
                '- Rs. ' + discount.toLocaleString('en');
            discountRow.style.display = 'flex';
        } else {
            discountRow.style.display = 'none';
        }

        document.getElementById('th-grand').textContent =
            'Rs. ' + grand.toLocaleString('en');

        /* ── Print ── */
        setTimeout(() => window.print(), 300);

    }).catch(err => {
        console.error(err);
        showPopup('Failed to generate receipt', 'error');
    });
}

/* ══════════════════════════════════
   SIDEBAR TOGGLE
══════════════════════════════════ */
document.addEventListener('DOMContentLoaded', function () {
    lucide.createIcons();

    const sidebar     = document.getElementById('sidebar');
    const mainWrapper = document.getElementById('main-wrapper');
    const overlay     = document.getElementById('overlay');
    // const innerToggle = document.getElementById('sidebarInnerToggle');
    const globalToggle= document.getElementById('globalToggleBtn');

    function toggleSidebar() {
        if (window.innerWidth <= 991) {
            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('active');
        } else {
            sidebar.classList.toggle('collapsed');
            mainWrapper.classList.toggle('expanded');
        }
    }

    globalToggle.addEventListener('click', toggleSidebar);
    innerToggle.addEventListener('click', toggleSidebar);
    overlay.addEventListener('click', toggleSidebar);
});

/* ══════════════════════════════════
   MODAL / POPUP
══════════════════════════════════ */
let modal;
window.addEventListener('DOMContentLoaded', () => {
    modal = new bootstrap.Modal(document.getElementById('appModal'));
});

function showPopup(message, type = 'success', confirmCallback = null) {
    document.getElementById('modalMessage').innerText = message;

    const icon    = document.getElementById('modalIcon');
    const actions = document.getElementById('modalActions');

    if (type === 'success')      icon.innerHTML = '✅';
    else if (type === 'error')   icon.innerHTML = '❌';
    else if (type === 'confirm') icon.innerHTML = '⚠️';

    actions.innerHTML = '';

    if (type === 'confirm') {
        actions.innerHTML = `
            <button class="btn btn-secondary" onclick="modal.hide()">Cancel</button>
            <button class="btn btn-danger" id="confirmBtn">Confirm</button>
        `;
        setTimeout(() => {
            document.getElementById('confirmBtn').onclick = () => {
                modal.hide();
                confirmCallback && confirmCallback();
            };
        }, 0);
    } else {
        actions.innerHTML = `<button class="btn btn-warning" onclick="modal.hide()">OK</button>`;
    }

    modal.show();
}
</script>
</body>
</html>
