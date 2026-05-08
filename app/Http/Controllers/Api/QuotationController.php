@extends('layout.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<style>
    :root {
        --maken-amber:     #fbbf24;
        --maken-amber-dk:  #d97706;
        --maken-slate:     #0f172a;
        --maken-surface:   #f1f5f9;
        --maken-white:     #ffffff;
        --maken-line:      #e2e8f0;
        --maken-danger:    #ef4444;
        --maken-success:   #22c55e;
    }

    body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--maken-surface); color: var(--maken-slate); }
    .quotation-page { max-width: 1200px; margin: 0 auto; padding: 20px; }

    .mk-card { background: var(--maken-white); border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,.05); border: 1px solid var(--maken-line); margin-bottom: 20px; }
    .card-body { padding: 20px; }

    .mk-table { width: 100%; border-collapse: collapse; }
    .mk-table thead tr { background: #f8fafc; border-bottom: 2px solid var(--maken-line); }
    .mk-table th { padding: 12px 15px; text-align: left; font-size: 12px; text-transform: uppercase; color: #64748b; letter-spacing: .5px; }
    .mk-table td { padding: 13px 15px; border-bottom: 1px solid var(--maken-line); font-size: 14px; vertical-align: middle; }
    .mk-table tbody tr:last-child td { border-bottom: none; }
    .mk-table tbody tr:hover { background: #fafafa; }

    .btn-action { padding: 6px 13px; border-radius: 7px; border: 1px solid var(--maken-line); background: #fff; cursor: pointer; font-size: 12px; font-weight: 700; font-family: inherit; transition: all .2s; display: inline-flex; align-items: center; gap: 5px; }
    .btn-action.restore:hover { background: var(--maken-amber); border-color: var(--maken-amber); }
    .btn-action.receipt:hover { background: #eff6ff; border-color: #93c5fd; color: #1d4ed8; }
    .btn-action.delete:hover  { background: #fee2e2; color: var(--maken-danger); border-color: #fecaca; }

    /* Expiry badge */
    .badge { display: inline-block; padding: 2px 9px; border-radius: 5px; font-size: 11px; font-weight: 700; margin-top: 4px; }
    .badge.valid   { background: #fef3c7; color: #92400e; }
    .badge.expired { background: #fee2e2; color: #b91c1c; }
    .badge.none    { background: #f1f5f9; color: #64748b; }

    /* ── Receipt overlay ── */
    .receipt-overlay {
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.55);
        z-index: 3000;
        display: flex; align-items: center; justify-content: center;
        padding: 20px;
    }

    .receipt-modal {
        background: #fff;
        width: 540px;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 25px 60px rgba(0,0,0,.25);
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        animation: slideUp .2s ease;
    }

    @keyframes slideUp {
        from { transform: translateY(20px); opacity: 0; }
        to   { transform: translateY(0);    opacity: 1; }
    }

    .receipt-header {
        background: var(--maken-slate);
        color: #fff;
        padding: 18px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
    }

    .receipt-meta {
        padding: 14px 24px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        border-bottom: 1px solid var(--maken-line);
        flex-shrink: 0;
    }

    .receipt-items {
        padding: 0 24px;
        overflow-y: auto;
        flex: 1;
    }

    .receipt-items table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .receipt-items th {
        padding: 10px 0;
        text-align: left;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: #94a3b8;
        border-bottom: 2px solid var(--maken-line);
    }

    .receipt-items th.right,
    .receipt-items td.right { text-align: right; }
    .receipt-items th.center,
    .receipt-items td.center { text-align: center; }

    .receipt-items td {
        padding: 9px 0;
        border-bottom: 1px solid #f8fafc;
    }

    .receipt-totals {
        padding: 14px 24px;
        background: #f8fafc;
        border-top: 1px solid var(--maken-line);
        flex-shrink: 0;
    }

    .receipt-total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
        margin-bottom: 7px;
        color: #64748b;
    }

    .receipt-total-row.grand {
        font-size: 17px;
        font-weight: 800;
        color: var(--maken-slate);
        border-top: 2px dashed var(--maken-line);
        padding-top: 10px;
        margin-top: 5px;
        margin-bottom: 0;
    }

    .receipt-footer {
        padding: 14px 24px;
        border-top: 1px solid var(--maken-line);
        display: flex;
        gap: 10px;
        flex-shrink: 0;
    }

    /* ── Popup / toast ── */
    .mk-popup { position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 8px; }
    .mk-toast { padding: 12px 18px; border-radius: 10px; font-size: 13px; font-weight: 600; box-shadow: 0 4px 15px rgba(0,0,0,.12); animation: fadeIn .3s; }
    .mk-toast.success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    .mk-toast.error   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
    @keyframes fadeIn { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }
</style>

<div id="popupContainer" class="mk-popup"></div>

<div class="quotation-page">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <div>
            <h2 style="margin:0; font-size:22px;">Held Carts / Quotations</h2>
            <p style="margin:4px 0 0; color:#64748b; font-size:13px;">Manage saved carts and generate digital receipts</p>
        </div>
        <a href="/pos" class="btn-action restore" style="text-decoration:none; height:40px; padding:0 18px; font-size:13px;">
            <i class="fas fa-plus"></i> NEW SALE
        </a>
    </div>

    <div class="mk-card">
        <div class="card-body">
            <table class="mk-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Reference</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Validity</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody id="quotationTableBody">
                    <tr>
                        <td colspan="7" style="text-align:center; padding:40px; color:#94a3b8;">
                            <i class="fas fa-spinner fa-spin"></i> Loading...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
/* ══════════════════════════════════
   POPUP / TOAST
══════════════════════════════════ */
function showPopup(msg, type = 'success') {
    const container = document.getElementById('popupContainer');
    const toast = document.createElement('div');
    toast.className = 'mk-toast ' + type;
    toast.innerHTML = (type === 'success' ? '<i class="fas fa-check-circle"></i> ' : '<i class="fas fa-exclamation-circle"></i> ') + msg;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

/* ══════════════════════════════════
   FETCH & RENDER TABLE
══════════════════════════════════ */
function fetchQuotations() {
    axios.get('/api/quotations').then(r => {
        const body = document.getElementById('quotationTableBody');
        const data = r.data.data;

        if (data.length === 0) {
            body.innerHTML = `
                <tr>
                    <td colspan="7" style="text-align:center; padding:50px; color:#94a3b8;">
                        <i class="fas fa-inbox" style="font-size:32px; display:block; margin-bottom:10px;"></i>
                        No held carts found.
                    </td>
                </tr>`;
            return;
        }

        const today = new Date(); today.setHours(0,0,0,0);

        body.innerHTML = data.map(q => {
            let badgeHtml = `<span class="badge none"><i class="fas fa-minus"></i> No expiry</span>`;

            if (q.expiry_date) {
                const exp     = new Date(q.expiry_date);
                const expired = exp < today;
                const label   = expired
                    ? `<i class="fas fa-exclamation-triangle"></i> Expired: ${q.expiry_date}`
                    : `<i class="fas fa-clock"></i> Expires: ${q.expiry_date}`;
                badgeHtml = `<span class="badge ${expired ? 'expired' : 'valid'}">${label}</span>`;
            }

            return `
                <tr>
                    <td style="color:#64748b; font-size:13px;">${q.date}</td>
                    <td style="font-weight:700;">${q.reference_name || 'Unnamed'}</td>
                    <td>${q.customer ? q.customer.name : '<span style="color:#94a3b8;">Walking Customer</span>'}</td>
                    <td>
                        <span style="background:#f1f5f9; padding:3px 10px; border-radius:5px; font-size:12px; font-weight:700;">
                            ${q.items.length} item${q.items.length !== 1 ? 's' : ''}
                        </span>
                    </td>
                    <td style="font-weight:800; color:var(--maken-slate);">Rs. ${parseFloat(q.total).toLocaleString()}</td>
                    <td>${badgeHtml}</td>
                    <td style="text-align:center;">
                        <div style="display:flex; gap:6px; justify-content:center;">
                            <button class="btn-action receipt" onclick="viewReceipt(${q.id})">
                                <i class="fas fa-receipt"></i> Receipt
                            </button>
                            <button class="btn-action restore" onclick="restore(${q.id})">
                                <i class="fas fa-undo"></i> Restore
                            </button>
                            <button class="btn-action delete" onclick="remove(${q.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    }).catch(() => showPopup('Failed to load quotations', 'error'));
}

/* ══════════════════════════════════
   RESTORE TO POS
══════════════════════════════════ */
function restore(id) {
    window.location.href = '/pos?restore=' + id;
}

/* ══════════════════════════════════
   DELETE
══════════════════════════════════ */
function remove(id) {
    if (!confirm('Delete this quotation? This cannot be undone.')) return;
    axios.delete('/api/quotations/' + id)
        .then(() => { showPopup('Quotation deleted'); fetchQuotations(); })
        .catch(() => showPopup('Failed to delete', 'error'));
}

/* ══════════════════════════════════
   VIEW RECEIPT MODAL
══════════════════════════════════ */
function viewReceipt(id) {
    axios.get('/api/quotations/' + id).then(r => {
        const q        = r.data.data;
        const customer = q.customer ? q.customer.name : 'Walking Customer';
        const phone    = q.customer && q.customer.phone ? q.customer.phone : '';
        const today    = new Date(); today.setHours(0,0,0,0);
        const expired  = q.expiry_date && new Date(q.expiry_date) < today;

        /* ── Items rows ── */
        const rows = q.items.map((item, i) => {
            const lineTotal = (item.qty * item.price).toLocaleString('en', { minimumFractionDigits: 2 });
            return `
                <tr>
                    <td style="color:#94a3b8; width:28px;">${i + 1}</td>
                    <td style="font-weight:600;">${item.name}</td>
                    <td class="center">${item.qty}</td>
                    <td class="right">Rs. ${parseFloat(item.price).toLocaleString()}</td>
                    <td class="right" style="font-weight:700;">Rs. ${lineTotal}</td>
                </tr>
            `;
        }).join('');

        /* ── Totals ── */
        const subtotal = parseFloat(q.total);
        const discount = parseFloat(q.discount || 0);
        const grand    = Math.max(0, subtotal - discount);

        const discountRow = discount > 0 ? `
            <div class="receipt-total-row">
                <span>Discount</span>
                <span style="color:#dc2626; font-weight:700;">− Rs. ${discount.toLocaleString('en', { minimumFractionDigits: 2 })}</span>
            </div>
        ` : '';

        /* ── Expiry badge ── */
        let expiryHtml = `<span style="font-size:12px; color:#94a3b8;">No expiry set</span>`;
        if (q.expiry_date) {
            expiryHtml = `
                <span style="display:inline-block; padding:3px 10px; border-radius:5px; font-size:12px; font-weight:700;
                    background:${expired ? '#fee2e2' : '#fef3c7'};
                    color:${expired ? '#b91c1c' : '#92400e'};">
                    ${expired ? '⚠ Expired: ' : '✓ Expires: '}${q.expiry_date}
                </span>
            `;
        }

        /* ── Build modal HTML ── */
        const html = `
            <div class="receipt-overlay" id="receiptOverlay" onclick="closeReceiptOnBg(event)">
                <div class="receipt-modal" id="receiptModal">

                    {{-- Header --}}
                    <div class="receipt-header">
                        <div>
                            <div style="font-size:18px; font-weight:800; letter-spacing:-.3px;">Maken POS</div>
                            <div style="font-size:11px; opacity:.5; margin-top:2px;">Quotation Receipt</div>
                        </div>
                        <div style="text-align:right;">
                            <div style="color:#fbbf24; font-weight:800; font-size:15px; letter-spacing:.5px;">
                                #QT-${String(q.id).padStart(5, '0')}
                            </div>
                            <div style="font-size:11px; opacity:.5; margin-top:2px;">${q.date}</div>
                        </div>
                    </div>

                    {{-- Customer + Expiry --}}
                    <div class="receipt-meta">
                        <div>
                            <div style="font-size:10px; text-transform:uppercase; letter-spacing:.5px; color:#94a3b8; margin-bottom:4px;">Bill To</div>
                            <div style="font-weight:700; font-size:14px;">${customer}</div>
                            ${phone ? `<div style="font-size:12px; color:#64748b; margin-top:2px;"><i class="fas fa-phone" style="font-size:10px;"></i> ${phone}</div>` : ''}
                        </div>
                        <div style="text-align:right;">
                            <div style="font-size:10px; text-transform:uppercase; letter-spacing:.5px; color:#94a3b8; margin-bottom:4px;">Validity</div>
                            ${expiryHtml}
                            ${q.reference_name
                                ? `<div style="font-size:11px; color:#94a3b8; margin-top:6px;">
                                       <i class="fas fa-tag" style="font-size:10px;"></i> ${q.reference_name}
                                   </div>`
                                : ''}
                        </div>
                    </div>

                    {{-- Items table --}}
                    <div class="receipt-items">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width:28px;">#</th>
                                    <th>Item</th>
                                    <th class="center" style="width:50px;">Qty</th>
                                    <th class="right" style="width:100px;">Unit Price</th>
                                    <th class="right" style="width:100px;">Total</th>
                                </tr>
                            </thead>
                            <tbody>${rows}</tbody>
                        </table>
                    </div>

                    {{-- Totals --}}
                    <div class="receipt-totals">
                        <div class="receipt-total-row">
                            <span>Subtotal (${q.items.length} item${q.items.length !== 1 ? 's' : ''})</span>
                            <span>Rs. ${subtotal.toLocaleString('en', { minimumFractionDigits: 2 })}</span>
                        </div>
                        ${discountRow}
                        <div class="receipt-total-row grand">
                            <span>Grand Total</span>
                            <span>Rs. ${grand.toLocaleString('en', { minimumFractionDigits: 2 })}</span>
                        </div>
                    </div>

                    {{-- Footer actions --}}
                    <div class="receipt-footer">
                        <button
                            onclick="printReceipt()"
                            style="flex:1; height:44px; background:var(--maken-slate); color:#fff; border:none; border-radius:9px; font-weight:700; cursor:pointer; font-size:13px; font-family:inherit; display:flex; align-items:center; justify-content:center; gap:7px; transition:background .2s;"
                            onmouseover="this.style.background='#1e293b'"
                            onmouseout="this.style.background='var(--maken-slate)'">
                            <i class="fas fa-print"></i> Print Receipt
                        </button>
                        <button
                            onclick="document.getElementById('receiptOverlay').remove()"
                            style="height:44px; padding:0 20px; border:1.5px solid var(--maken-line); background:#fff; border-radius:9px; font-weight:700; cursor:pointer; font-size:13px; font-family:inherit; transition:all .2s;"
                            onmouseover="this.style.background='#f8fafc'"
                            onmouseout="this.style.background='#fff'">
                            Close
                        </button>
                    </div>

                </div>
            </div>
        `;

        /* Remove any existing overlay, then inject */
        const existing = document.getElementById('receiptOverlay');
        if (existing) existing.remove();
        document.body.insertAdjacentHTML('beforeend', html);

    }).catch(() => showPopup('Failed to load receipt', 'error'));
}

/* Close overlay when clicking the dark background */
function closeReceiptOnBg(e) {
    if (e.target.id === 'receiptOverlay') {
        document.getElementById('receiptOverlay').remove();
    }
}

/* ══════════════════════════════════
   PRINT RECEIPT
══════════════════════════════════ */
function printReceipt() {
    const modal = document.getElementById('receiptModal');
    if (!modal) return;

    const win = window.open('', '_blank', 'width=600,height=800');
    win.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Quotation Receipt</title>
            <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body {
                    font-family: 'Plus Jakarta Sans', sans-serif;
                    background: #fff;
                    color: #0f172a;
                }
                .receipt-modal {
                    width: 100%;
                    max-width: 540px;
                    margin: 0 auto;
                }
                .receipt-header {
                    background: #0f172a;
                    color: #fff;
                    padding: 18px 24px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }
                .receipt-meta {
                    padding: 14px 24px;
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 12px;
                    border-bottom: 1px solid #e2e8f0;
                }
                .receipt-items { padding: 0 24px; }
                .receipt-items table { width: 100%; border-collapse: collapse; font-size: 13px; }
                .receipt-items th {
                    padding: 10px 0;
                    text-align: left;
                    font-size: 10px;
                    text-transform: uppercase;
                    letter-spacing: .5px;
                    color: #94a3b8;
                    border-bottom: 2px solid #e2e8f0;
                }
                .receipt-items td { padding: 9px 0; border-bottom: 1px solid #f8fafc; font-size: 13px; }
                .right { text-align: right; }
                .center { text-align: center; }
                .receipt-totals { padding: 14px 24px; background: #f8fafc; border-top: 1px solid #e2e8f0; }
                .receipt-total-row { display: flex; justify-content: space-between; font-size: 13px; color: #64748b; margin-bottom: 7px; }
                .receipt-total-row.grand { font-size: 17px; font-weight: 800; color: #0f172a; border-top: 2px dashed #e2e8f0; padding-top: 10px; margin-top: 5px; margin-bottom: 0; }
                .receipt-footer { display: none; }
                @media print {
                    body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                }
            </style>
        </head>
        <body>
            ${modal.outerHTML}
        </body>
        </html>
    `);
    win.document.close();

    /* Wait for fonts/icons then print */
    win.addEventListener('load', () => {
        setTimeout(() => { win.focus(); win.print(); }, 600);
    });
}

/* ══════════════════════════════════
   INIT
══════════════════════════════════ */
window.onload = fetchQuotations;
</script>

@endsection
