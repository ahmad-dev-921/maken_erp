@extends('layout.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<style>
    :root {
        --maken-amber:   #fbbf24;
        --maken-amber-dk:#d97706;
        --maken-slate:   #0f172a;
        --maken-surface: #f1f5f9;
        --maken-white:   #ffffff;
        --maken-line:    #e2e8f0;
        --maken-danger:  #ef4444;
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
    .btn-action.restore:hover  { background: var(--maken-amber); border-color: var(--maken-amber); }
    .btn-action.print-btn:hover { background: #0f172a; color: #fff; border-color: #0f172a; }
    .btn-action.delete:hover   { background: #fee2e2; color: var(--maken-danger); border-color: #fecaca; }

    .badge { display: inline-block; padding: 2px 9px; border-radius: 5px; font-size: 11px; font-weight: 700; }
    .badge.valid   { background: #fef3c7; color: #92400e; }
    .badge.expired { background: #fee2e2; color: #b91c1c; }
    .badge.none    { background: #f1f5f9; color: #64748b; }
</style>

<div class="quotation-page">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <div>
            <h2 style="margin:0; font-size:22px;">Held Carts / Quotations</h2>
            <p style="margin:4px 0 0; color:#64748b; font-size:13px;">Manage saved carts and print quotation receipts</p>
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
   FETCH & RENDER TABLE
══════════════════════════════════ */
function fetchQuotations() {
    axios.get('/api/quotations').then(r => {
        const body  = document.getElementById('quotationTableBody');
        const data  = r.data.data;
        const today = new Date(); today.setHours(0,0,0,0);

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

        body.innerHTML = data.map(q => {
            let badge = `<span class="badge none"><i class="fas fa-minus"></i> No expiry</span>`;
            if (q.expiry_date) {
                const expired = new Date(q.expiry_date) < today;
                badge = expired
                    ? `<span class="badge expired"><i class="fas fa-exclamation-triangle"></i> Expired: ${q.expiry_date}</span>`
                    : `<span class="badge valid"><i class="fas fa-clock"></i> Expires: ${q.expiry_date}</span>`;
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
                    <td style="font-weight:800;">Rs. ${parseFloat(q.total).toLocaleString()}</td>
                    <td>${badge}</td>
                    <td style="text-align:center;">
                        <div style="display:flex; gap:6px; justify-content:center;">
                            <button class="btn-action print-btn" onclick="printQuotation(${q.id})">
                                <i class="fas fa-print"></i> Print
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

/* ══════════════════════════════════════════════════
   PRINT QUOTATION — opens its own A4 popup window.
   The thermal receipt in app.blade is for SALES only.
   Quotations are formal A4 documents for the customer.
══════════════════════════════════════════════════ */
function printQuotation(id) {
    axios.get('/api/quotations/' + id).then(r => {
        const q         = r.data.data;
        const today     = new Date(); today.setHours(0,0,0,0);
        const isExpired = q.expiry_date && new Date(q.expiry_date) < today;
        const customer  = q.customer ? q.customer.name : 'Walking Customer';
        const phone     = q.customer && q.customer.phone ? q.customer.phone : '';
        const subtotal  = parseFloat(q.total);
        const discount  = parseFloat(q.discount || 0);
        const grand     = Math.max(0, subtotal - discount);
        const qtNum     = 'QT-' + String(q.id).padStart(5, '0');

        /* ── Build item rows ── */
        let itemRows = '';
        q.items.forEach((item, i) => {
            const lineTotal = (item.qty * parseFloat(item.price)).toLocaleString('en', { minimumFractionDigits: 0 });
            itemRows += `
                <tr>
                    <td style="padding:9px 10px; border-bottom:1px solid #f0f0f0;">${i + 1}</td>
                    <td style="padding:9px 10px; border-bottom:1px solid #f0f0f0; font-weight:600;">${item.name}</td>
                    <td style="padding:9px 10px; border-bottom:1px solid #f0f0f0; text-align:center;">${item.qty}</td>
                    <td style="padding:9px 10px; border-bottom:1px solid #f0f0f0; text-align:right;">Rs. ${parseFloat(item.price).toLocaleString()}</td>
                    <td style="padding:9px 10px; border-bottom:1px solid #f0f0f0; text-align:right; font-weight:700;">Rs. ${lineTotal}</td>
                </tr>
            `;
        });

        /* ── Discount row ── */
        const discountRow = discount > 0 ? `
            <tr>
                <td colspan="4" style="padding:8px 10px; text-align:right; color:#dc2626; font-weight:600;">Discount</td>
                <td style="padding:8px 10px; text-align:right; color:#dc2626; font-weight:700;">
                    − Rs. ${discount.toLocaleString('en', { minimumFractionDigits: 0 })}
                </td>
            </tr>
        ` : '';

        /* ── Validity banner ── */
        const validityText = isExpired
            ? `⚠ This quotation expired on ${q.expiry_date}`
            : (q.expiry_date ? `✓ Valid until ${q.expiry_date}` : '✓ No expiry date set');

        const validityBg    = isExpired ? '#fee2e2' : '#fef3c7';
        const validityColor = isExpired ? '#b91c1c' : '#92400e';
        const statusText    = isExpired ? 'EXPIRED'   : 'QUOTATION';
        const statusColor   = isExpired ? '#b91c1c'   : '#d97706';

        /* ── Full A4 HTML document ── */
        const html = `<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quotation ${qtNum}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13px;
            color: #0f172a;
            background: #fff;
            padding: 30px 35px;
        }
        @media print {
            body { padding: 20px 25px; }
            @page { size: A4; margin: 0; }
        }

        /* Header */
        .doc-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 16px;
            border-bottom: 3px solid #0f172a;
            margin-bottom: 18px;
        }
        .shop-name   { font-size: 22px; font-weight: 800; letter-spacing: -.5px; }
        .shop-sub    { font-size: 11px; color: #64748b; margin-top: 3px; line-height: 1.6; }
        .doc-type    { font-size: 28px; font-weight: 800; color: #0f172a; text-align: right; letter-spacing: -1px; }
        .doc-num     { font-size: 13px; font-weight: 700; color: #d97706; text-align: right; margin-top: 3px; }

        /* Meta grid */
        .meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 20px;
        }
        .meta-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 15px;
        }
        .meta-label { font-size: 10px; text-transform: uppercase; letter-spacing: .8px; color: #94a3b8; margin-bottom: 5px; font-weight: 700; }
        .meta-value { font-size: 14px; font-weight: 700; }
        .meta-sub   { font-size: 11px; color: #64748b; margin-top: 2px; }

        /* Status badge */
        .status-badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .5px;
            background: ${isExpired ? '#fee2e2' : '#fef3c7'};
            color: ${statusColor};
        }

        /* Items table */
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #0f172a; color: #fff; }
        thead th { padding: 10px 10px; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: .5px; }
        thead th.r { text-align: right; }
        thead th.c { text-align: center; }

        /* Totals */
        .totals-wrap { margin-top: 6px; display: flex; justify-content: flex-end; }
        .totals-box  { width: 260px; }
        .total-row   { display: flex; justify-content: space-between; padding: 5px 0; font-size: 13px; color: #64748b; }
        .total-row.grand {
            font-size: 16px; font-weight: 800; color: #0f172a;
            border-top: 2px solid #0f172a;
            margin-top: 5px; padding-top: 8px;
        }

        /* Validity banner */
        .validity-banner {
            text-align: center;
            padding: 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            margin: 14px 0;
            background: ${validityBg};
            color: ${validityColor};
        }

        /* Signatures */
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }
        .sig-line {
            width: 160px;
            border-top: 1px solid #0f172a;
            text-align: center;
            padding-top: 6px;
            font-size: 11px;
            color: #64748b;
        }

        /* Footer */
        .doc-footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10.5px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 12px;
            line-height: 1.7;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="doc-header">
        <div>
            <div class="shop-name">Maken Solar Energy</div>
            <div class="shop-sub">
                Electronic Market, Habib Bank Street, Block No. 5<br>
                Tel: 0314-4949491 / 0308-8288461
            </div>
        </div>
        <div>
            <div class="doc-type">QUOTATION</div>
            <div class="doc-num">${qtNum}</div>
        </div>
    </div>

    <!-- Meta info -->
    <div class="meta-grid">
        <div class="meta-box">
            <div class="meta-label">Bill To</div>
            <div class="meta-value">${customer}</div>
            ${phone ? `<div class="meta-sub">${phone}</div>` : ''}
        </div>
        <div class="meta-box">
            <div class="meta-label">Details</div>
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <div class="meta-sub" style="margin-bottom:4px;">Date: <strong>${q.date}</strong></div>
                    ${q.reference_name ? `<div class="meta-sub">Ref: <strong>${q.reference_name}</strong></div>` : ''}
                </div>
                <span class="status-badge">${statusText}</span>
            </div>
        </div>
    </div>

    <!-- Items -->
    <table>
        <thead>
            <tr>
                <th style="width:30px;">#</th>
                <th>Product Description</th>
                <th class="c" style="width:60px;">Qty</th>
                <th class="r" style="width:120px;">Unit Price</th>
                <th class="r" style="width:120px;">Total</th>
            </tr>
        </thead>
        <tbody>
            ${itemRows}
            ${discountRow}
        </tbody>
    </table>

    <!-- Validity banner -->
    <div class="validity-banner">${validityText}</div>

    <!-- Totals -->
    <div class="totals-wrap">
        <div class="totals-box">
            <div class="total-row">
                <span>Subtotal</span>
                <span>Rs. ${subtotal.toLocaleString('en', { minimumFractionDigits: 0 })}</span>
            </div>
            ${discount > 0 ? `
            <div class="total-row" style="color:#dc2626;">
                <span>Discount</span>
                <span>− Rs. ${discount.toLocaleString('en', { minimumFractionDigits: 0 })}</span>
            </div>` : ''}
            <div class="total-row grand">
                <span>Grand Total</span>
                <span>Rs. ${grand.toLocaleString('en', { minimumFractionDigits: 0 })}</span>
            </div>
        </div>
    </div>

    <!-- Signatures -->
    <div class="signatures">
        <div class="sig-line">Customer Signature</div>
        <div class="sig-line">Authorized Signatory</div>
    </div>

    <!-- Footer -->
    <div class="doc-footer">
        This is a computer generated quotation and does not require a physical signature.<br>
        Prices are subject to change after the validity date. &nbsp;|&nbsp; <strong>Maken Solar Energy</strong>
    </div>

</body>
</html>`;

        /* ── Open print window ── */
        const win = window.open('', '_blank', 'width=800,height=900');
        win.document.write(html);
        win.document.close();
        win.addEventListener('load', () => {
            setTimeout(() => { win.focus(); win.print(); }, 500);
        });

    }).catch(err => {
        console.error(err);
        showPopup('Failed to load quotation', 'error');
    });
}

/* ══════════════════════════════════
   RESTORE & DELETE
══════════════════════════════════ */
function restore(id) {
    window.location.href = '/pos?restore=' + id;
}

function remove(id) {
    showPopup('Delete this quotation? This cannot be undone.', 'confirm', () => {
        axios.delete('/api/quotations/' + id)
            .then(() => { showPopup('Quotation deleted'); fetchQuotations(); })
            .catch(() => showPopup('Failed to delete', 'error'));
    });
}

window.onload = fetchQuotations;
</script>

@endsection
