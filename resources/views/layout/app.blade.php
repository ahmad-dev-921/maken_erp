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
            --maken-amber-darFk: #d97706;
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
  #123a6f 0%,
  #0a2550 40%,
  #07162f 75%,
  #050b1a 100%);
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            transition: var(--transition);
            z-index: 1000;
            overflow: hidden;
        }

        #sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

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

        .brand-icon {
            width: 36px; height: 36px;
            background: var(--maken-amber);
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #fff;
        }
        .brand-icon svg { width: 18px; height: 18px; }

        .brand-name {
            font-size: 1rem;
            font-weight: 800;
            letter-spacing: 2px;
            /* text-transform: uppercase; */
            color: var(--maken-light);
            transition: opacity 0.2s, width 0.3s;
        }

        #sidebar.collapsed .brand-name { opacity: 0; width: 0; }

        /* Inner Sidebar Toggle - Optional but kept for design */
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
            color: #fff; font-weight: 600; font-size: 0.875rem;
            text-decoration: none; transition: var(--transition);
        }
        .nav-link svg { width: 18px; height: 18px; flex-shrink: 0; }
        #sidebar.collapsed .nav-link { justify-content: center; gap: 0; padding: 0.7rem; }
        #sidebar.collapsed .nav-text { display: none; }

.sidebar-nav .nav-link:hover,
.sidebar-nav .nav-link.active {
    background: var(--maken-amber-soft);
    color: var(--maken-amber);
}
.sidebar-nav .nav-link {
    background: transparent;
    color: #cbd5e1;
    transition: 0.2s ease;
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

        /* FIXED TOGGLE BUTTON IN HEADER */
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

        /* Mobile Adjustments */
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
    </style>
</head>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<body>

<div id="overlay"></div>

<aside id="sidebar">
    <div class="sidebar-header">
        {{-- <div class="brand-icon"><i class="fa-solid fa-solar-panel"></i></div> --}}
        <span class="brand-name">Maken Electronics</span>
        <div class="sidebar-toggle-btn-inner d-none d-lg-flex" id="sidebarInnerToggle">
            <i data-lucide="chevron-left" id="innerToggleIcon"></i>
        </div>
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
        <!-- GLOBAL TOGGLE BUTTON (Always Visible) -->
        <button class="header-toggle-btn" id="globalToggleBtn">
            <i data-lucide="menu" id="globalToggleIcon"></i>
        </button>

        <span class="page-title d-none d-sm-inline fw-bold text-muted text-uppercase small tracking-widest">
            Premium Solar Solutions
        </span>

        <div class="topbar-right">
           <div class="user-avatar" style="width: auto; padding: 0 10px; height: 32px; font-size: 12px; display:flex; align-items:center;">
    {{ \Carbon\Carbon::now()->format('l, d F Y') }}
</div>
        </div>
    </header>

    <div class="scroll-area">
      @yield('content')
    </div>
</div>

<!-- Global Printable Invoice (Hidden from Screen) -->
<div id="printableInvoice" style="position: absolute; left: -9999px; top: 0; width: 800px;">
    <style>
        @media screen {
            #printableInvoice { display: none; }
        }
        @media print {
            header, footer, aside, nav, .sidebar, .top-bar, .scroll-area, #overlay, #main-wrapper { display: none !important; }
            body { background: white !important; margin: 0 !important; padding: 0 !important; overflow: visible !important; height: auto !important; }
            #printableInvoice { 
                display: block !important; 
                position: absolute !important; 
                left: 0 !important; 
                top: 0 !important; 
                width: 100% !important; 
                visibility: visible !important;
                margin: 0 !important;
                padding: 20px !important;
            }
            #printableInvoice * { visibility: visible !important; }
        }
        .inv-header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 15px; }
        .inv-title { font-size: 24px; font-weight: 800; text-transform: uppercase; margin: 0; }
        .inv-meta { display: flex; justify-content: space-between; margin-bottom: 20px; font-size: 14px; }
        .inv-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .inv-table th { border-bottom: 2px solid #000; padding: 10px; text-align: left; }
        .inv-table td { border-bottom: 1px solid #eee; padding: 10px; }
        .inv-total { text-align: right; font-size: 18px; font-weight: 800; border-top: 2px solid #000; padding-top: 10px; }
    </style>
    <div class="inv-header">
        <h1 class="inv-title">Maken Electronics</h1>
        <p style="margin:5px 0; font-weight:600;">Premium Solar & Electronic Solutions</p>
        <p style="margin:0; font-size:12px;">Shop #12, Electronic Market, Karachi | Tel: 0321-1234567</p>
    </div>
    <div class="inv-meta">
        <div>
            <strong style="text-decoration:underline;">CUSTOMER INFO:</strong><br>
            <span style="font-size:16px; font-weight:700;" id="printCustomer">---</span><br>
            <span id="printPhone">---</span>
        </div>
        <div style="text-align:right;">
            <strong>INVOICE #:</strong> <span id="printInvoiceId" style="font-weight:700;">---</span><br>
            <strong>DATE:</strong> <span id="printDate">---</span><br>
            <strong>STATUS:</strong> <span style="color:green; font-weight:700;">PAID</span>
        </div>
    </div>
    <table class="inv-table">
        <thead>
            <tr style="background:#f0f0f0;">
                <th>PRODUCT DESCRIPTION</th>
                <th style="text-align:center;">QTY</th>
                <th style="text-align:right;">UNIT PRICE</th>
                <th style="text-align:right;">TOTAL</th>
            </tr>
        </thead>
        <tbody id="printBody"></tbody>
    </table>
    <div class="inv-total">
        <span style="font-size:14px; font-weight:400;">NET AMOUNT PAYABLE:</span><br>
        Rs. <span id="printGrandTotal">0.00</span>
    </div>
    <div style="margin-top:50px; display:flex; justify-content:space-between; font-size:12px;">
        <div style="border-top:1px solid #000; width:150px; text-align:center;">Customer Signature</div>
        <div style="border-top:1px solid #000; width:150px; text-align:center;">Authorized Signatory</div>
    </div>
    <div style="margin-top:40px; text-align:center; font-size:11px; color:#666; border-top:1px solid #eee; padding-top:10px;">
        This is a computer generated invoice and does not require a physical signature.<br>
        Thank you for your business!
    </div>
</div>
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
    function printSale(id) {
        if (!id) return;
        const btn = event?.target;
        const oldText = btn ? btn.innerHTML : '';
        if (btn && btn.tagName === 'BUTTON') btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        axios.get('/api/sales/' + id).then(r => {
            if (btn && btn.tagName === 'BUTTON') btn.innerHTML = oldText;
            const s = r.data.data;
            document.getElementById('printCustomer').textContent = s.customer ? s.customer.name : 'Walking Customer';
            document.getElementById('printPhone').textContent = s.customer ? s.customer.phone : '---';
            document.getElementById('printInvoiceId').textContent = 'INV-' + s.id;
            document.getElementById('printDate').textContent = s.date;
            document.getElementById('printGrandTotal').textContent = parseFloat(s.total_bill).toLocaleString();
            
            let html = '';
            s.details.forEach(d => {
                html += `
                    <tr>
                        <td>${d.product ? d.product.name : 'Unknown'}</td>
                        <td style="text-align:center;">${d.qty}</td>
                        <td style="text-align:right;">Rs. ${parseFloat(d.price).toLocaleString()}</td>
                        <td style="text-align:right;">Rs. ${parseFloat(d.total).toLocaleString()}</td>
                    </tr>
                `;
            });
            document.getElementById('printBody').innerHTML = html;
            
            // Wait for DOM update before printing
            setTimeout(() => {
                window.print();
            }, 300);
        }).catch(err => {
            console.error(err);
            alert('Failed to generate invoice. Please check console.');
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();

        const sidebar = document.getElementById('sidebar');
        const mainWrapper = document.getElementById('main-wrapper');
        const overlay = document.getElementById('overlay');
        const innerToggle = document.getElementById('sidebarInnerToggle');
        const globalToggle = document.getElementById('globalToggleBtn');

        function toggleSidebar() {
            const isMobile = window.innerWidth <= 991;

            if (isMobile) {
                // Mobile behavior: Slide in/out
                sidebar.classList.toggle('mobile-open');
                overlay.classList.toggle('active');
            } else {
                // Desktop behavior: Collapse/Expand
                sidebar.classList.toggle('collapsed');
                mainWrapper.classList.toggle('expanded');
            }
        }

        // Global Header Toggle
        globalToggle.addEventListener('click', toggleSidebar);

        // Sidebar Inner Toggle (Optional shortcut)
        innerToggle.addEventListener('click', toggleSidebar);

        // Close mobile sidebar on overlay click
        overlay.addEventListener('click', toggleSidebar);
    });
    let modal;

window.addEventListener('DOMContentLoaded', () => {
    modal = new bootstrap.Modal(document.getElementById('appModal'));
});

function showPopup(message, type = 'success', confirmCallback = null) {
    document.getElementById('modalMessage').innerText = message;

    const icon = document.getElementById('modalIcon');
    const actions = document.getElementById('modalActions');

    if (type === 'success') icon.innerHTML = '✅';
    else if (type === 'error') icon.innerHTML = '❌';
    else if (type === 'confirm') icon.innerHTML = '⚠️';

    actions.innerHTML = '';

    if (type === 'confirm') {
        actions.innerHTML = `
            <button class="btn btn-secondary" onclick="modal.hide()">Cancel</button>
            <button class="btn btn-danger" id="confirmBtn">Delete</button>
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
