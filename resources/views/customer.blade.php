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

    /* ── PAGE ─────────────────────────────────────── */
    .mk-page {
        
        max-width: 1320px;
        margin: 0 auto;
        animation: mkIn .4s ease both;
    }
    @keyframes mkIn { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }

    /* ── SECTION TITLE ────────────────────────────── */
    .mk-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 16px;
        font-weight: 700;
        color: var(--maken-slate);
        margin-bottom: 16px;
    }
    .mk-title::before {
        content: '';
        width: 4px; height: 20px;
        background: var(--maken-amber);
        border-radius: 4px;
        display: block;
        flex-shrink: 0;
    }

    /* ── CARD ─────────────────────────────────────── */
    .mk-card {
        background: var(--maken-white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-card);
        border: 1px solid var(--maken-line);
        margin-bottom: 28px;
        overflow: hidden;
    }
    .mk-card-body { padding: 26px 28px 22px; }

    /* ── FORM GRID ────────────────────────────────── */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px 20px;
    }
    .col-span-2 { grid-column: span 2; }
    @media(max-width:900px) { .form-grid { grid-template-columns: 1fr 1fr; } }
    @media(max-width:580px) { .form-grid { grid-template-columns: 1fr; } .col-span-2 { grid-column: span 1; } }

    /* ── FIELD ────────────────────────────────────── */
    .mk-field label {
        display: block;
        font-size: 12.5px;
        font-weight: 600;
        color: var(--maken-slate-3);
        margin-bottom: 6px;
    }
    .mk-field label .req { color: var(--maken-danger); margin-left: 2px; }

    .mk-input-wrap { position: relative; }
    .mk-input-wrap .ico {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 13px;
        pointer-events: none;
        z-index: 1;
    }
    .mk-input-wrap .ico.top { top: 14px; transform: none; }

    .mk-input {
        width: 100%;
        height: 42px;
        border: 1.5px solid var(--maken-line);
        border-radius: var(--radius);
        background: var(--maken-white);
        padding: 0 14px 0 36px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 13.5px;
        color: var(--maken-slate);
        transition: border-color .2s, box-shadow .2s;
        outline: none;
    }
    .mk-input::placeholder { color: #b0bec5; }
    .mk-input:focus { border-color: var(--maken-amber); box-shadow: 0 0 0 3px var(--maken-amber-glow); }
    textarea.mk-input { height: auto; padding-top: 10px; padding-bottom: 10px; resize: none; }

    /* ── FORM ACTIONS ─────────────────────────────── */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
        padding-top: 18px;
        border-top: 1px solid var(--maken-line);
    }

    .btn-ghost {
        height: 42px;
        padding: 0 20px;
        border-radius: var(--radius);
        border: 1.5px solid var(--maken-line);
        background: transparent;
        color: var(--maken-slate-4);
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 13.5px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex; align-items: center; gap: 7px;
        transition: all .2s;
    }
    .btn-ghost:hover { border-color: var(--maken-slate-3); color: var(--maken-slate); }

    .btn-amber {
        height: 42px;
        padding: 0 22px;
        border-radius: var(--radius);
        border: none;
        background: var(--maken-amber);
        color: var(--maken-slate);
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 13.5px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex; align-items: center; gap: 8px;
        transition: all .22s;
        box-shadow: 0 2px 8px var(--maken-amber-glow);
    }
    .btn-amber:hover { background: var(--maken-amber-dark); color: #fff; box-shadow: var(--shadow-amber); transform: translateY(-1px); }
    .btn-amber.slate { background: var(--maken-slate-2); color: #fff; box-shadow: none; }
    .btn-amber.slate:hover { background: var(--maken-slate); box-shadow: 0 4px 16px rgba(15,23,42,.3); }

    /* ── TABLE HEADER ─────────────────────────────── */
    .table-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding: 16px 24px;
        border-bottom: 1px solid var(--maken-line);
    }

    .search-wrap { position: relative; display: flex; align-items: center; }
    .search-wrap i { position: absolute; left: 12px; color: #94a3b8; font-size: 13px; pointer-events: none; }
    .search-inp {
        height: 38px;
        width: 230px;
        border: 1.5px solid var(--maken-line);
        border-radius: 30px;
        background: var(--maken-surface);
        padding: 0 16px 0 36px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 13px;
        color: var(--maken-slate);
        outline: none;
        transition: all .2s;
    }
    .search-inp::placeholder { color: #94a3b8; }
    .search-inp:focus { border-color: var(--maken-amber); background: #fff; box-shadow: 0 0 0 3px var(--maken-amber-glow); }

    .tbl-actions { display: flex; gap: 8px; }

    .btn-filter {
        height: 38px;
        padding: 0 15px;
        border-radius: var(--radius);
        border: 1.5px solid var(--maken-line);
        background: #fff;
        color: var(--maken-slate-4);
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex; align-items: center; gap: 6px;
        transition: all .2s;
    }
    .btn-filter:hover { border-color: var(--maken-slate-3); color: var(--maken-slate); }

    .btn-add-sm {
        height: 38px;
        padding: 0 16px;
        border-radius: var(--radius);
        border: none;
        background: var(--maken-amber);
        color: var(--maken-slate);
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex; align-items: center; gap: 6px;
        transition: all .2s;
        box-shadow: 0 2px 8px var(--maken-amber-glow);
    }
    .btn-add-sm:hover { background: var(--maken-amber-dark); color: #fff; transform: translateY(-1px); }

    /* ── TABLE ────────────────────────────────────── */
    .mk-table { width: 100%; border-collapse: collapse; }

    .mk-table thead tr { background: var(--maken-slate); }
    .mk-table thead th {
        padding: 13px 14px;
        text-align: left;
        font-size: 11.5px;
        font-weight: 600;
        letter-spacing: .5px;
        text-transform: uppercase;
        color: #94a3b8;
        white-space: nowrap;
    }
    .mk-table thead th:first-child { padding-left: 22px; }
    .mk-table thead th:last-child { text-align: center; }

    .mk-table tbody tr {
        border-bottom: 1px solid var(--maken-line);
        transition: background .15s;
    }
    .mk-table tbody tr:last-child { border-bottom: none; }
    .mk-table tbody tr:hover { background: #f8fafc; }

    .mk-table td {
        padding: 13px 14px;
        vertical-align: middle;
        font-size: 13.5px;
        color: var(--maken-slate);
    }
    .mk-table td:first-child { padding-left: 22px; color: var(--maken-slate-4); font-weight: 600; font-size: 13px; }
    .mk-table td:last-child { text-align: center; }

    /* row animation */
    .mk-table tbody tr { animation: rowIn .28s ease both; }
    .mk-table tbody tr:nth-child(1) { animation-delay:.03s; }
    .mk-table tbody tr:nth-child(2) { animation-delay:.06s; }
    .mk-table tbody tr:nth-child(3) { animation-delay:.09s; }
    .mk-table tbody tr:nth-child(4) { animation-delay:.12s; }
    .mk-table tbody tr:nth-child(5) { animation-delay:.15s; }
    @keyframes rowIn { from { opacity:0; transform:translateX(-5px); } to { opacity:1; transform:none; } }

    /* status */
    .sbadge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }
    .sbadge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; display: block; }
    .sbadge.active   { background: var(--maken-success-bg); color: #065f46; }
    .sbadge.active::before { background: var(--maken-success); }
    .sbadge.inactive { background: var(--maken-warn-bg); color: var(--maken-warn); }
    .sbadge.inactive::before { background: var(--maken-amber); }

    /* action buttons */
    .act-grp { display: inline-flex; align-items: center; gap: 4px; justify-content: center; }
    .act-btn {
        width: 30px; height: 30px;
        border-radius: 7px;
        border: 1px solid var(--maken-line);
        background: var(--maken-white);
        cursor: pointer;
        font-size: 12px;
        color: var(--maken-slate-4);
        display: inline-flex; align-items: center; justify-content: center;
        transition: all .18s;
    }
    .act-btn.edit:hover  { background: var(--maken-amber); color: var(--maken-slate); border-color: var(--maken-amber); transform: translateY(-2px); }
    .act-btn.view:hover  { background: #0ea5e9; color: #fff; border-color: #0ea5e9; transform: translateY(-2px); }
    .act-btn.del:hover   { background: var(--maken-danger); color: #fff; border-color: var(--maken-danger); transform: translateY(-2px); }

    /* ── TABLE FOOTER ─────────────────────────────── */
    .mk-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding: 14px 24px;
        border-top: 1px solid var(--maken-line);
    }
    .foot-info { font-size: 13px; color: var(--maken-slate-4); }
    .foot-right { display: flex; align-items: center; gap: 12px; }

    .pagination { display: flex; align-items: center; gap: 4px; }
    .pg {
        width: 32px; height: 32px;
        border-radius: 8px;
        border: 1.5px solid var(--maken-line);
        background: var(--maken-white);
        color: var(--maken-slate-4);
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: all .18s;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .pg:hover, .pg.act { background: var(--maken-amber); color: var(--maken-slate); border-color: var(--maken-amber); }
    .pg:disabled { opacity: .35; cursor: not-allowed; }

    .per-page {
        height: 32px;
        border: 1.5px solid var(--maken-line);
        border-radius: 8px;
        padding: 0 10px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 13px;
        color: var(--maken-slate);
        background: var(--maken-white);
        outline: none;
        cursor: pointer;
    }
    .per-page:focus { border-color: var(--maken-amber); }

    /* empty */
    .empty-cell { text-align: center; padding: 52px 20px; }
    .empty-ico {
        width: 52px; height: 52px;
        border-radius: 50%;
        background: var(--maken-surface);
        display: flex; align-items: center; justify-content: center;
        font-size: 20px;
        color: #94a3b8;
        margin: 0 auto 12px;
    }
    .empty-cell p { color: #94a3b8; font-size: 13.5px; }
</style>

<div class="mk-page">

    <!-- ══ FORM ══════════════════════════════════ -->
    <div class="mk-title">Customer Form</div>
    <div class="mk-card">
        <div class="mk-card-body">
            <form id="customerForm">
                <input type="hidden" id="customer_id">
                <div class="form-grid">

                    <div class="mk-field">
                        <label>Customer Name <span class="req">*</span></label>
                        <div class="mk-input-wrap">
                            <i class="fas fa-user ico"></i>
                            <input type="text" id="name" class="mk-input" placeholder="Enter customer name" required>
                        </div>
                    </div>

                    <div class="mk-field">
                        <label>Email <span class="req">*</span></label>
                        <div class="mk-input-wrap">
                            <i class="fas fa-envelope ico"></i>
                            <input type="email" id="email" class="mk-input" placeholder="Enter email address">
                        </div>
                    </div>

                    <div class="mk-field">
                        <label>Phone Number <span class="req">*</span></label>
                        <div class="mk-input-wrap">
                            <i class="fas fa-phone ico"></i>
                            <input type="text" id="phone" class="mk-input" placeholder="Enter phone number" required>
                        </div>
                    </div>

                    <div class="mk-field">
                        <label>Opening Balance</label>
                        <div class="mk-input-wrap">
                            <i class="fas fa-dollar ico"></i>
                            <input type="text" id="company" class="mk-input" placeholder="Enter company name">
                        </div>
                    </div>

                    <div class="mk-field col-span-2">
                        <label>Address <span class="req">*</span></label>
                        <div class="mk-input-wrap">
                            <i class="fas fa-location-dot ico top"></i> 
                            <textarea id="address" class="mk-input" rows="2" placeholder="Enter full address"></textarea>
                        </div>
                    </div>

                </div>
                <div class="form-actions">
                    <button type="button" class="btn-ghost" onclick="resetForm()">
                        <i class="fas fa-rotate-left"></i> Reset
                    </button>
                    <button type="submit" class="btn-amber" id="saveBtn">
                        <i class="fas fa-floppy-disk"></i>
                        <span id="saveBtnText">Save Customer</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ══ LIST ══════════════════════════════════ -->
    <div class="mk-title">Customer List</div>
    <div class="mk-card">
        <div class="table-header">
            <div class="search-wrap">
                <i class="fas fa-magnifying-glass"></i>
                <input type="text" id="tableSearch" class="search-inp" placeholder="Search customers...">
            </div>
            <div class="tbl-actions">
                <button class="btn-filter">
                    <i class="fas fa-sliders"></i> Filter
                </button>
                <button class="btn-add-sm" onclick="scrollTop()">
                    <i class="fas fa-plus"></i> Add Customer
                </button>
            </div>
        </div>

        <div style="overflow-x:auto;">
            <table class="mk-table">
                <thead>
                    <tr>
                        <th style="width:48px;">#</th>
                        <th>Customer Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Company</th>
                        <th>Address</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th style="width:100px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="customerTableBody">
                    <tr>
                        <td colspan="9" class="empty-cell">
                            <div class="empty-ico"><i class="fas fa-users"></i></div>
                            <p>Loading customers…</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mk-footer">
            <div class="foot-info" id="paginationInfo">—</div>
            <div class="foot-right">
                <div class="pagination" id="paginationBtns"></div>
                <select class="per-page" id="perPage">
                    <option value="10" selected>10 / page</option>
                    <option value="25">25 / page</option>
                    <option value="50">50 / page</option>
                </select>
            </div>
        </div>
    </div>

</div>

<script>
const apiBase = '/api/customers';
let all = [], page = 1;

/* FETCH */
function fetchCustomers(search = '') {
    axios.get(`${apiBase}?search=${encodeURIComponent(search)}&limit=500`)
        .then(r => { all = r.data.data || []; page = 1; render(); })
        .catch(() => {
            document.getElementById('customerTableBody').innerHTML =
                `<tr><td colspan="9" class="empty-cell">
                    <div class="empty-ico" style="background:#fee2e2"><i class="fas fa-circle-exclamation" style="color:#ef4444"></i></div>
                    <p>Failed to load customers.</p>
                </td></tr>`;
        });
}

/* RENDER */
function filtered() {
    const q = (document.getElementById('tableSearch').value || '').toLowerCase();
    return q ? all.filter(c => [c.name,c.email,c.phone].some(v => (v||'').toLowerCase().includes(q))) : all;
}
function render() {
    const per = +document.getElementById('perPage').value;
    const rows = filtered();
    const pages = Math.ceil(rows.length / per) || 1;
    if (page > pages) page = pages;
    const s = (page - 1) * per, slice = rows.slice(s, s + per);

    document.getElementById('paginationInfo').textContent =
        `Showing ${rows.length ? s+1 : 0} to ${Math.min(s+per, rows.length)} of ${rows.length} entries`;

    if (!slice.length) {
        document.getElementById('customerTableBody').innerHTML =
            `<tr><td colspan="9" class="empty-cell">
                <div class="empty-ico"><i class="fas fa-user-slash"></i></div>
                <p>No customers found</p>
            </td></tr>`;
        renderPg(0,1); return;
    }

    document.getElementById('customerTableBody').innerHTML = slice.map((c, i) =>
        `<tr>
            <td>${s+i+1}</td>
            <td style="font-weight:600">${c.name}</td>
            <td>${c.email||'—'}</td>
            <td>${c.phone}</td>
            <td>${c.company||'—'}</td>
            <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="${c.address||''}">${c.address||'—'}</td>
            <td style="font-weight:600">Rs.&nbsp;${parseFloat(c.opening_balance||0).toLocaleString()}</td>
            <td><span class="sbadge ${c.status==='inactive'?'inactive':'active'}">${c.status==='inactive'?'Inactive':'Active'}</span></td>
            <td>
                <div class="act-grp">
                    <button class="act-btn edit" onclick='editCustomer(${JSON.stringify(c)})' title="Edit"><i class="fas fa-pen-to-square"></i></button>
                    <button class="act-btn view" title="View"><i class="fas fa-eye"></i></button>
                    <button class="act-btn del"  onclick="deleteCustomer(${c.id})" title="Delete"><i class="fas fa-trash-can"></i></button>
                </div>
            </td>
        </tr>`
    ).join('');

    renderPg(rows.length, pages);
}

function renderPg(total, pages) {

    const w = document.getElementById('paginationBtns');
    if (pages <= 1) { w.innerHTML=''; return; }
    let h = `<button class="pg" onclick="goPage(${page-1})" ${page===1?'disabled':''}><i class="fas fa-chevron-left" style="font-size:10px"></i></button>`;
    for (let p=1; p<=pages && p<=5; p++) h += `<button class="pg ${p===page?'act':''}" onclick="goPage(${p})">${p}</button>`;
    if (pages>5) h += `<span style="color:#94a3b8;padding:0 2px">…</span><button class="pg ${pages===page?'act':''}" onclick="goPage(${pages})">${pages}</button>`;
    h += `<button class="pg" onclick="goPage(${page+1})" ${page===pages?'disabled':''}><i class="fas fa-chevron-right" style="font-size:10px"></i></button>`;
    w.innerHTML = h;
}
function goPage(p) {
    const pages = Math.ceil(filtered().length / +document.getElementById('perPage').value) || 1;
    if (p<1||p>pages) return;
    page = p; render();
}

/* FORM SUBMIT */
document.getElementById('customerForm').onsubmit = function(e) {
    e.preventDefault();
    const id = document.getElementById('customer_id').value;
    const data = {
        name:            document.getElementById('name').value,
        email:           document.getElementById('email').value,
        phone:           document.getElementById('phone').value,
        opening_balance: 0,
        address:         document.getElementById('address').value,
    };
    (id ? axios.put(`${apiBase}/${id}`, data) : axios.post(apiBase, data))
        .then(r => { alert(r.data.message || 'Saved!'); resetForm(); fetchCustomers(); })
        .catch(err => alert(err.response?.data?.message || 'Error saving.'));
};

/* EDIT */
function editCustomer(c) {
    document.getElementById('customer_id').value = c.id;
    document.getElementById('name').value         = c.name;
    document.getElementById('email').value        = c.email||'';
    document.getElementById('phone').value        = c.phone;
    document.getElementById('address').value      = c.address||'';
    document.getElementById('saveBtnText').textContent = 'Update Customer';
    document.getElementById('saveBtn').classList.add('slate');
    scrollTop();
}

/* DELETE */
function deleteCustomer(id) {
    if (!confirm('Delete this customer?')) return;
    axios.delete(`${apiBase}?ids=${id}`)
        .then(r => { alert(r.data.message||'Deleted.'); fetchCustomers(); })
        .catch(() => alert('Failed to delete.'));
}

/* RESET */
function resetForm() {
    document.getElementById('customerForm').reset();
    document.getElementById('customer_id').value = '';
    document.getElementById('saveBtnText').textContent = 'Save Customer';
    document.getElementById('saveBtn').classList.remove('slate');
}
function scrollTop() { window.scrollTo({ top: 0, behavior: 'smooth' }); }

/* SEARCH */
let st;
document.getElementById('tableSearch').oninput = function() { clearTimeout(st); st = setTimeout(()=>{ page=1; render(); }, 280); };
document.getElementById('perPage').onchange = function() { page=1; render(); };

window.onload = () => fetchCustomers();
</script>

@endsection