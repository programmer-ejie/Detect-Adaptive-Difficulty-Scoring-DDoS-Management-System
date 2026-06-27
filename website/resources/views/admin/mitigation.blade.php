@extends('admin.layout', ['page' => 'mitigation'])

@section('title', 'Mitigation')
@section('page-title', 'Mitigation')

@section('content')
<style>
    .stat-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        position: relative;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        border-color: rgba(0, 0, 0, 0.1);
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #5864FF, #4356E6);
    }
    .stat-card .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 12px;
        background: linear-gradient(135deg, #5864FF 0%, #4356E6 100%);
        color: white;
    }
    .stat-label {
        font-size: 12px;
        font-weight: 600;
        color: #8c92a4;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 8px;
    }
    .stat-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
    }
    .stat-description {
        font-size: 12px;
        color: #9ca3af;
        margin-top: 10px;
    }
    .stat-value.text-blocked { color: #dc2626; }
    .stat-value.text-rate { color: #d97706; }
    .stat-value.text-acl { color: #2563eb; }
    .stat-value.text-auto { color: #16a34a; }

    .filter-bar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        background: #f8f9fa;
        border-radius: 10px;
        margin-bottom: 20px;
        border: 1px solid #e5e7eb;
    }
    .filter-bar .filter-group {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .filter-bar label {
        font-size: 11px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin: 0;
        white-space: nowrap;
    }
    .filter-bar select,
    .filter-bar input {
        padding: 5px 10px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 12px;
        background: white;
        color: #374151;
        min-width: 100px;
    }
    .filter-bar select:focus,
    .filter-bar input:focus {
        border-color: #5864FF;
        outline: none;
        box-shadow: 0 0 0 3px rgba(88, 100, 255, 0.1);
    }
    .filter-bar .btn-filter {
        padding: 5px 14px;
        background: #5864FF;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }
    .filter-bar .btn-filter:hover { background: #4356E6; }
    .filter-bar .btn-reset {
        padding: 5px 14px;
        background: transparent;
        color: #6b7280;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .filter-bar .btn-reset:hover { background: #f3f4f6; }
    .filter-bar .filter-spacer { flex: 1; }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 3px 10px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }
    .status-badge .status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        display: inline-block;
    }
    .status-badge.active { background: #dcfce7; color: #166534; }
    .status-badge.active .status-dot { background: #16a34a; }
    .status-badge.review { background: #fef3c7; color: #92400e; }
    .status-badge.review .status-dot { background: #d97706; }
    .status-badge.queued { background: #dbeafe; color: #0c4a6e; }
    .status-badge.queued .status-dot { background: #2563eb; }
    .status-badge.pending { background: #fee2e2; color: #7f1d1d; }
    .status-badge.pending .status-dot { background: #dc2626; }
    .status-badge.expired { background: #f3f4f6; color: #6b7280; }
    .status-badge.expired .status-dot { background: #9ca3af; }

    .btn-mitigate-action {
        padding: 3px 10px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        margin: 1px;
    }
    .btn-mitigate-action.apply { background: #dcfce7; color: #166534; }
    .btn-mitigate-action.apply:hover { background: #bbf7d0; }
    .btn-mitigate-action.undo { background: #fee2e2; color: #7f1d1d; }
    .btn-mitigate-action.undo:hover { background: #fecaca; }
    .btn-mitigate-action.edit { background: #dbeafe; color: #0c4a6e; }
    .btn-mitigate-action.edit:hover { background: #bfdbfe; }
    .btn-mitigate-action.view { background: #f3f4f6; color: #374151; }
    .btn-mitigate-action.view:hover { background: #e5e7eb; }

    .table-mitigation {
        width: 100%;
        border-collapse: collapse;
    }
    .table-mitigation th {
        padding: 10px 12px;
        font-weight: 600;
        font-size: 11px;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e5e7eb;
        white-space: nowrap;
        text-align: left;
    }
    .table-mitigation td {
        padding: 10px 12px;
        vertical-align: middle;
        font-size: 13px;
        border-bottom: 1px solid #f3f4f6;
        white-space: nowrap;
    }
    .table-mitigation tbody tr:hover { background: #f8fafc; }
    .table-mitigation .ip-address { font-family: monospace; font-size: 12px; }
    .table-mitigation .text-center { text-align: center; }

    .bulk-checkbox {
        width: 16px;
        height: 16px;
        cursor: pointer;
        accent-color: #5864FF;
    }
    .bulk-actions-bar {
        display: none;
        align-items: center;
        gap: 12px;
        padding: 10px 16px;
        background: #eef2ff;
        border-radius: 8px;
        margin-bottom: 16px;
        border: 1px solid #c7d2fe;
        flex-wrap: wrap;
    }
    .bulk-actions-bar.show { display: flex; }
    .bulk-actions-bar .bulk-count {
        font-size: 13px;
        font-weight: 600;
        color: #1f2937;
    }
    .bulk-actions-bar .bulk-btn {
        padding: 4px 14px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    .bulk-actions-bar .bulk-btn.delete { background: #fee2e2; color: #7f1d1d; }
    .bulk-actions-bar .bulk-btn.delete:hover { background: #fecaca; }
    .bulk-actions-bar .bulk-btn.export { background: #5864FF; color: white; }
    .bulk-actions-bar .bulk-btn.export:hover { background: #4356E6; }
    .bulk-actions-bar .bulk-btn.apply-bulk { background: #dcfce7; color: #166534; }
    .bulk-actions-bar .bulk-btn.apply-bulk:hover { background: #bbf7d0; }

    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid #e5e7eb;
        flex-wrap: wrap;
        gap: 12px;
    }
    .pagination-wrapper .page-info { font-size: 12px; color: #9ca3af; }
    .pagination-wrapper .page-buttons { display: flex; gap: 6px; }
    .pagination-wrapper .page-buttons button {
        padding: 4px 12px;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        background: white;
        color: #374151;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .pagination-wrapper .page-buttons button:hover:not(:disabled) { background: #f3f4f6; }
    .pagination-wrapper .page-buttons button:disabled { opacity: 0.4; cursor: not-allowed; }
    .pagination-wrapper .page-buttons button.active { background: #5864FF; color: white; border-color: #5864FF; }

    .quick-action-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }
    .quick-action-grid .btn-quick {
        padding: 14px 16px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        background: white;
        text-align: left;
        transition: all 0.2s;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 12px;
        width: 100%;
    }
    .quick-action-grid .btn-quick:hover {
        border-color: #5864FF;
        background: #f8f9ff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .quick-action-grid .btn-quick .icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .quick-action-grid .btn-quick .icon.danger { background: #fee2e2; color: #dc2626; }
    .quick-action-grid .btn-quick .icon.warning { background: #fef3c7; color: #d97706; }
    .quick-action-grid .btn-quick .icon.primary { background: #dbeafe; color: #2563eb; }
    .quick-action-grid .btn-quick .icon.success { background: #dcfce7; color: #16a34a; }
    .quick-action-grid .btn-quick .icon.purple { background: #ede9fe; color: #7c3aed; }
    .quick-action-grid .btn-quick .sub { font-size: 11px; font-weight: 400; color: #9ca3af; display: block; }

    .table-header-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
        flex-wrap: wrap;
        gap: 8px;
    }
    .table-header-toolbar h5 { margin: 0; }
    .table-header-toolbar .toolbar-right {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }
    .table-header-toolbar .toolbar-right .count-badge {
        font-size: 13px;
        color: #6b7280;
    }

    .toast-notification {
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        z-index: 99999;
        animation: slideIn 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .toast-notification.success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .toast-notification.warning { background: #fee2e2; color: #7f1d1d; border: 1px solid #fecaca; }
    .toast-notification.info { background: #dbeafe; color: #0c4a6e; border: 1px solid #bfdbfe; }
    @keyframes slideIn {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }
    .modal-overlay.show { display: flex; }
    .modal-box {
        background: white;
        border-radius: 16px;
        max-width: 500px;
        width: 95%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 24px 48px rgba(0,0,0,0.2);
        animation: modalSlideIn 0.25s ease;
    }
    @keyframes modalSlideIn {
        from { transform: translateY(30px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 24px;
        border-bottom: 1px solid #e5e7eb;
    }
    .modal-header h4 { margin: 0; font-weight: 700; color: #1f2937; }
    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #9ca3af;
        transition: color 0.2s;
        padding: 0 4px;
    }
    .modal-close:hover { color: #374151; }
    .modal-body { padding: 24px; }
    .modal-body .form-group { margin-bottom: 16px; }
    .modal-body .form-group label {
        font-size: 12px;
        font-weight: 600;
        color: #374151;
        display: block;
        margin-bottom: 4px;
    }
    .modal-body .form-group input,
    .modal-body .form-group select,
    .modal-body .form-group textarea {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 13px;
    }
    .modal-body .form-group input:focus,
    .modal-body .form-group select:focus,
    .modal-body .form-group textarea:focus {
        border-color: #5864FF;
        outline: none;
        box-shadow: 0 0 0 3px rgba(88, 100, 255, 0.1);
    }
    .modal-body .modal-actions {
        display: flex;
        gap: 12px;
        margin-top: 20px;
        flex-wrap: wrap;
    }
    .modal-body .modal-actions .btn-primary {
        padding: 8px 24px;
        background: #5864FF;
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }
    .modal-body .modal-actions .btn-primary:hover { background: #4356E6; }
    .modal-body .modal-actions .btn-secondary {
        padding: 8px 24px;
        background: transparent;
        color: #6b7280;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .modal-body .modal-actions .btn-secondary:hover { background: #f3f4f6; }

    .row-separator {
        margin-top: 20px;
    }

    @media (max-width: 992px) {
        .quick-action-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 768px) {
        .filter-bar { flex-direction: column; align-items: stretch; }
        .filter-bar .filter-group { flex-wrap: wrap; }
        .filter-bar .filter-spacer { display: none; }
        .quick-action-grid { grid-template-columns: 1fr 1fr; }
        .table-mitigation { font-size: 12px; }
        .table-mitigation th, .table-mitigation td { padding: 6px 8px; white-space: normal; }
        .pagination-wrapper { flex-direction: column; align-items: stretch; text-align: center; }
        .pagination-wrapper .page-buttons { justify-content: center; flex-wrap: wrap; }
        .bulk-actions-bar { flex-wrap: wrap; }
        .table-header-toolbar { flex-direction: column; align-items: stretch; gap: 8px; }
        .table-header-toolbar .toolbar-right { justify-content: space-between; flex-wrap: wrap; }
        .modal-box { max-width: 100%; margin: 10px; }
    }
    @media (max-width: 576px) {
        .filter-bar select, .filter-bar input { min-width: 70px; font-size: 11px; }
        .filter-bar label { font-size: 10px; }
        .table-mitigation td { font-size: 11px; padding: 4px 6px; }
        .table-mitigation th { font-size: 10px; padding: 4px 6px; }
        .quick-action-grid { grid-template-columns: 1fr; }
        .quick-action-grid .btn-quick { font-size: 12px; padding: 10px 12px; }
    }
</style>

<div class="row">
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="stat-icon"><i class="ti ti-ban"></i></div>
                <div class="stat-label">Blocked IPs</div>
                <div class="stat-value text-blocked" id="blocked-count">248</div>
                <span class="stat-badge" style="background: #dcfce7; color: #166534;">Active</span>
                <div class="stat-description">Deny list entries</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="stat-icon"><i class="ti ti-adjustments"></i></div>
                <div class="stat-label">Rate Limit Rules</div>
                <div class="stat-value text-rate" id="rate-count">12</div>
                <span class="stat-badge" style="background: #dcfce7; color: #166534;">Enabled</span>
                <div class="stat-description">Applied to edge router</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="stat-icon"><i class="ti ti-shield-check"></i></div>
                <div class="stat-label">ACL Updates</div>
                <div class="stat-value text-acl" id="acl-count">6</div>
                <span class="stat-badge" style="background: #fef3c7; color: #92400e;">Pending</span>
                <div class="stat-description">Awaiting confirmation</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="stat-icon"><i class="ti ti-git-branch"></i></div>
                <div class="stat-label">Mitigation Mode</div>
                <div class="stat-value text-auto" id="mode-count">Auto</div>
                <span class="stat-badge" style="background: #dcfce7; color: #166534;">On</span>
                <div class="stat-description">Rules can be reviewed</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="filter-bar">
            <div class="filter-group">
                <label for="status-filter">Status</label>
                <select id="status-filter">
                    <option value="all">All</option>
                    <option value="active">Active</option>
                    <option value="review">Review</option>
                    <option value="queued">Queued</option>
                    <option value="pending">Pending</option>
                    <option value="expired">Expired</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="action-filter">Action</label>
                <select id="action-filter">
                    <option value="all">All</option>
                    <option value="deny">Deny</option>
                    <option value="rate_limit">Rate Limit</option>
                    <option value="acl_update">ACL Update</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="search-input">Search</label>
                <input type="text" id="search-input" placeholder="IP or rule..." style="min-width: 130px;">
            </div>
            <div class="filter-spacer"></div>
            <div style="display: flex; gap: 6px;">
                <button class="btn-filter" onclick="applyFilters()">Apply</button>
                <button class="btn-reset" onclick="resetFilters()">Reset</button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="bulk-actions-bar" id="bulk-actions-bar">
            <span class="bulk-count"><span id="bulk-count">0</span> selected</span>
            <button class="bulk-btn apply-bulk" onclick="bulkApply()">Apply Selected</button>
            <button class="bulk-btn delete" onclick="bulkDelete()">Delete</button>
            <button class="bulk-btn export" onclick="exportCSV()">Export CSV</button>
            <button class="bulk-btn" style="background:#f3f4f6;color:#6b7280;" onclick="clearBulkSelection()">Clear</button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
            <h5 class="mb-0">Quick Mitigation</h5>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="quick-action-grid">
                    <button class="btn-quick" onclick="openBlockModal()">
                        <span class="icon danger"><i class="ti ti-ban"></i></span>
                        <div><div>Block IP</div><span class="sub">Add to deny list</span></div>
                    </button>
                    <button class="btn-quick" onclick="openRateLimitModal()">
                        <span class="icon warning"><i class="ti ti-adjustments"></i></span>
                        <div><div>Rate Limit</div><span class="sub">Apply throttling</span></div>
                    </button>
                    <button class="btn-quick" onclick="openACLModal()">
                        <span class="icon primary"><i class="ti ti-shield-check"></i></span>
                        <div><div>Deploy ACL</div><span class="sub">Update access rules</span></div>
                    </button>
                    <button class="btn-quick" onclick="openWhitelistModal()">
                        <span class="icon purple"><i class="ti ti-lock-open"></i></span>
                        <div><div>Whitelist IP</div><span class="sub">Allow trusted sources</span></div>
                    </button>
                    <button class="btn-quick" onclick="recheckRouter()">
                        <span class="icon success"><i class="ti ti-refresh-alert"></i></span>
                        <div><div>Recheck Router</div><span class="sub">Verify status</span></div>
                    </button>
                    <button class="btn-quick" onclick="openBulkBlockModal()">
                        <span class="icon danger"><i class="ti ti-list"></i></span>
                        <div><div>Bulk Block</div><span class="sub">Multiple IPs at once</span></div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row row-separator">
    <div class="col-12">
        <div class="table-header-toolbar">
            <h5>Mitigation Rules</h5>
            <div class="toolbar-right">
                <span class="count-badge">Showing <span id="rule-count">4</span> rules</span>
                <button class="btn btn-sm btn-outline-primary" onclick="refreshRules()" style="padding: 3px 10px; font-size: 11px;">
                    <i class="ti ti-refresh"></i> Refresh
                </button>
            </div>
        </div>

        <div class="card tbl-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-mitigation mb-0" id="rule-table">
                        <thead>
                            <tr>
                                <th style="width:30px;"><input type="checkbox" class="bulk-checkbox" id="select-all" onchange="toggleAllCheckboxes(this)"></th>
                                <th style="min-width:180px;">Rule</th>
                                <th style="min-width:120px;">Target</th>
                                <th style="min-width:100px;">Action</th>
                                <th style="min-width:100px;text-align:center;">Status</th>
                                <th style="min-width:100px;text-align:center;">Updated</th>
                                <th style="min-width:200px;text-align:center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="rule-tbody">
                            <tr class="rule-row" data-status="active" data-action="deny" data-id="1">
                                <td onclick="event.stopPropagation();"><input type="checkbox" class="bulk-checkbox row-checkbox" onchange="updateBulkBar()"></td>
                                <td>Block UDP amplification source</td>
                                <td><span class="ip-address">10.23.1.18</span></td>
                                <td>Deny</td>
                                <td style="text-align:center;"><span class="status-badge active"><span class="status-dot"></span> Active</span></td>
                                <td style="text-align:center;">2 min ago</td>
                                <td style="text-align:center;">
                                    <button class="btn-mitigate-action undo" onclick="undoRule(this)">Undo</button>
                                    <button class="btn-mitigate-action edit" onclick="openEditModal(this)">Edit</button>
                                    <button class="btn-mitigate-action view" onclick="viewRule(this)">View</button>
                                </td>
                            </tr>
                            <tr class="rule-row" data-status="review" data-action="rate_limit" data-id="2">
                                <td onclick="event.stopPropagation();"><input type="checkbox" class="bulk-checkbox row-checkbox" onchange="updateBulkBar()"></td>
                                <td>HTTP flood throttling</td>
                                <td><span class="ip-address">172.16.0.12</span></td>
                                <td>Rate Limit</td>
                                <td style="text-align:center;"><span class="status-badge review"><span class="status-dot"></span> Review</span></td>
                                <td style="text-align:center;">8 min ago</td>
                                <td style="text-align:center;">
                                    <button class="btn-mitigate-action apply" onclick="applyRule(this)">Apply</button>
                                    <button class="btn-mitigate-action undo" onclick="undoRule(this)">Undo</button>
                                    <button class="btn-mitigate-action edit" onclick="openEditModal(this)">Edit</button>
                                </td>
                            </tr>
                            <tr class="rule-row" data-status="queued" data-action="acl_update" data-id="3">
                                <td onclick="event.stopPropagation();"><input type="checkbox" class="bulk-checkbox row-checkbox" onchange="updateBulkBar()"></td>
                                <td>Temporary SYN ACL</td>
                                <td><span class="ip-address">172.16.0.18</span></td>
                                <td>ACL Update</td>
                                <td style="text-align:center;"><span class="status-badge queued"><span class="status-dot"></span> Queued</span></td>
                                <td style="text-align:center;">14 min ago</td>
                                <td style="text-align:center;">
                                    <button class="btn-mitigate-action apply" onclick="applyRule(this)">Apply</button>
                                    <button class="btn-mitigate-action undo" onclick="undoRule(this)">Undo</button>
                                    <button class="btn-mitigate-action edit" onclick="openEditModal(this)">Edit</button>
                                </td>
                            </tr>
                            <tr class="rule-row" data-status="pending" data-action="deny" data-id="4">
                                <td onclick="event.stopPropagation();"><input type="checkbox" class="bulk-checkbox row-checkbox" onchange="updateBulkBar()"></td>
                                <td>ICMP flood protection</td>
                                <td><span class="ip-address">192.0.2.88</span></td>
                                <td>Deny</td>
                                <td style="text-align:center;"><span class="status-badge pending"><span class="status-dot"></span> Pending</span></td>
                                <td style="text-align:center;">22 min ago</td>
                                <td style="text-align:center;">
                                    <button class="btn-mitigate-action apply" onclick="applyRule(this)">Apply</button>
                                    <button class="btn-mitigate-action undo" onclick="undoRule(this)">Undo</button>
                                    <button class="btn-mitigate-action edit" onclick="openEditModal(this)">Edit</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="pagination-wrapper">
            <span class="page-info">Page <span id="current-page">1</span> of <span id="total-pages">1</span></span>
            <div class="page-buttons">
                <button id="prev-page" onclick="changePage(-1)">Previous</button>
                <button id="page-1" class="active" onclick="goToPage(1)">1</button>
                <button id="next-page" onclick="changePage(1)">Next</button>
            </div>
        </div>
    </div>
</div>

<div class="modal-overlay" id="block-modal" onclick="if(event.target===this) closeModal('block-modal')">
    <div class="modal-box">
        <div class="modal-header">
            <h4>Block IP Address</h4>
            <button class="modal-close" onclick="closeModal('block-modal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>IP Address</label>
                <input type="text" id="block-ip" placeholder="e.g. 192.168.1.100">
            </div>
            <div class="form-group">
                <label>Reason</label>
                <select id="block-reason">
                    <option value="DDoS Attack">DDoS Attack</option>
                    <option value="Suspicious Activity">Suspicious Activity</option>
                    <option value="Malicious Scan">Malicious Scan</option>
                    <option value="Policy Violation">Policy Violation</option>
                </select>
            </div>
            <div class="form-group">
                <label>Duration</label>
                <select id="block-duration">
                    <option value="1h">1 Hour</option>
                    <option value="24h">24 Hours</option>
                    <option value="7d">7 Days</option>
                    <option value="permanent">Permanent</option>
                </select>
            </div>
            <div class="form-group">
                <label>Notes (optional)</label>
                <textarea id="block-notes" rows="2" placeholder="Additional notes..."></textarea>
            </div>
            <div class="modal-actions">
                <button class="btn-primary" onclick="executeBlock()">Block IP</button>
                <button class="btn-secondary" onclick="closeModal('block-modal')">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal-overlay" id="ratelimit-modal" onclick="if(event.target===this) closeModal('ratelimit-modal')">
    <div class="modal-box">
        <div class="modal-header">
            <h4>Apply Rate Limit</h4>
            <button class="modal-close" onclick="closeModal('ratelimit-modal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Target IP</label>
                <input type="text" id="rate-ip" placeholder="e.g. 172.16.0.12">
            </div>
            <div class="form-group">
                <label>Rate Limit (packets/sec)</label>
                <select id="rate-limit">
                    <option value="10">10 pps</option>
                    <option value="50">50 pps</option>
                    <option value="100">100 pps</option>
                    <option value="500">500 pps</option>
                    <option value="1000">1000 pps</option>
                </select>
            </div>
            <div class="form-group">
                <label>Protocol</label>
                <select id="rate-protocol">
                    <option value="all">All Protocols</option>
                    <option value="tcp">TCP</option>
                    <option value="udp">UDP</option>
                    <option value="icmp">ICMP</option>
                </select>
            </div>
            <div class="modal-actions">
                <button class="btn-primary" onclick="executeRateLimit()">Apply Rate Limit</button>
                <button class="btn-secondary" onclick="closeModal('ratelimit-modal')">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal-overlay" id="acl-modal" onclick="if(event.target===this) closeModal('acl-modal')">
    <div class="modal-box">
        <div class="modal-header">
            <h4>Deploy ACL Update</h4>
            <button class="modal-close" onclick="closeModal('acl-modal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Target IP</label>
                <input type="text" id="acl-ip" placeholder="e.g. 172.16.0.18">
            </div>
            <div class="form-group">
                <label>ACL Rule Type</label>
                <select id="acl-type">
                    <option value="deny">Deny All Traffic</option>
                    <option value="allow">Allow Specific</option>
                    <option value="rate_limit">Rate Limit</option>
                </select>
            </div>
            <div class="form-group">
                <label>Port (optional)</label>
                <input type="text" id="acl-port" placeholder="e.g. 80, 443, or leave blank for all">
            </div>
            <div class="modal-actions">
                <button class="btn-primary" onclick="executeACL()">Deploy ACL</button>
                <button class="btn-secondary" onclick="closeModal('acl-modal')">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal-overlay" id="whitelist-modal" onclick="if(event.target===this) closeModal('whitelist-modal')">
    <div class="modal-box">
        <div class="modal-header">
            <h4>Whitelist IP Address</h4>
            <button class="modal-close" onclick="closeModal('whitelist-modal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>IP Address</label>
                <input type="text" id="whitelist-ip" placeholder="e.g. 192.168.1.100">
            </div>
            <div class="form-group">
                <label>Reason</label>
                <input type="text" id="whitelist-reason" placeholder="e.g. Trusted partner">
            </div>
            <div class="modal-actions">
                <button class="btn-primary" onclick="executeWhitelist()">Add to Whitelist</button>
                <button class="btn-secondary" onclick="closeModal('whitelist-modal')">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal-overlay" id="bulk-block-modal" onclick="if(event.target===this) closeModal('bulk-block-modal')">
    <div class="modal-box">
        <div class="modal-header">
            <h4>Bulk Block IPs</h4>
            <button class="modal-close" onclick="closeModal('bulk-block-modal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>IP Addresses (one per line)</label>
                <textarea id="bulk-ips" rows="6" placeholder="192.168.1.100&#10;10.0.0.50&#10;172.16.0.25"></textarea>
            </div>
            <div class="form-group">
                <label>Reason</label>
                <select id="bulk-reason">
                    <option value="DDoS Attack">DDoS Attack</option>
                    <option value="Suspicious Activity">Suspicious Activity</option>
                    <option value="Malicious Scan">Malicious Scan</option>
                    <option value="Policy Violation">Policy Violation</option>
                </select>
            </div>
            <div class="modal-actions">
                <button class="btn-primary" onclick="executeBulkBlock()">Block All</button>
                <button class="btn-secondary" onclick="closeModal('bulk-block-modal')">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal-overlay" id="edit-modal" onclick="if(event.target===this) closeModal('edit-modal')">
    <div class="modal-box">
        <div class="modal-header">
            <h4>Edit Rule</h4>
            <button class="modal-close" onclick="closeModal('edit-modal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Rule Name</label>
                <input type="text" id="edit-rule-name" value="">
            </div>
            <div class="form-group">
                <label>Target IP</label>
                <input type="text" id="edit-target-ip" value="">
            </div>
            <div class="form-group">
                <label>Action</label>
                <select id="edit-action">
                    <option value="deny">Deny</option>
                    <option value="rate_limit">Rate Limit</option>
                    <option value="acl_update">ACL Update</option>
                </select>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select id="edit-status">
                    <option value="active">Active</option>
                    <option value="review">Review</option>
                    <option value="queued">Queued</option>
                    <option value="pending">Pending</option>
                    <option value="expired">Expired</option>
                </select>
            </div>
            <div class="modal-actions">
                <button class="btn-primary" onclick="executeEdit()">Save Changes</button>
                <button class="btn-secondary" onclick="closeModal('edit-modal')">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal-overlay" id="view-modal" onclick="if(event.target===this) closeModal('view-modal')">
    <div class="modal-box">
        <div class="modal-header">
            <h4>Rule Details</h4>
            <button class="modal-close" onclick="closeModal('view-modal')">&times;</button>
        </div>
        <div class="modal-body" id="view-modal-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div><div style="font-size:11px;color:#9ca3af;text-transform:uppercase;font-weight:600;">Rule Name</div><div style="font-size:14px;font-weight:500;color:#1f2937;" id="view-rule">—</div></div>
                <div><div style="font-size:11px;color:#9ca3af;text-transform:uppercase;font-weight:600;">Target IP</div><div style="font-size:14px;font-weight:500;color:#1f2937;" id="view-target">—</div></div>
                <div><div style="font-size:11px;color:#9ca3af;text-transform:uppercase;font-weight:600;">Action</div><div style="font-size:14px;font-weight:500;color:#1f2937;" id="view-action">—</div></div>
                <div><div style="font-size:11px;color:#9ca3af;text-transform:uppercase;font-weight:600;">Status</div><div style="font-size:14px;font-weight:500;color:#1f2937;" id="view-status">—</div></div>
                <div><div style="font-size:11px;color:#9ca3af;text-transform:uppercase;font-weight:600;">Updated</div><div style="font-size:14px;font-weight:500;color:#1f2937;" id="view-updated">—</div></div>
                <div><div style="font-size:11px;color:#9ca3af;text-transform:uppercase;font-weight:600;">Rule ID</div><div style="font-size:14px;font-weight:500;color:#1f2937;" id="view-id">—</div></div>
            </div>
            <div style="margin-top:16px;padding-top:16px;border-top:1px solid #f3f4f6;">
                <div style="font-size:11px;color:#9ca3af;text-transform:uppercase;font-weight:600;">Description</div>
                <div style="font-size:14px;color:#374151;margin-top:4px;" id="view-desc">This rule was created to mitigate a detected attack threat.</div>
            </div>
            <div class="modal-actions">
                <button class="btn-secondary" onclick="closeModal('view-modal')">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentPage = 1;
    const rowsPerPage = 5;
    let currentModal = null;
    let editRowRef = null;

    document.addEventListener('DOMContentLoaded', function() {
        updatePagination();
        updateRuleCount();
        updateStatCounts();
    });

    function applyFilters() {
        const status = document.getElementById('status-filter').value;
        const action = document.getElementById('action-filter').value;
        const search = document.getElementById('search-input').value.toLowerCase();

        const rows = document.querySelectorAll('.rule-row');
        rows.forEach(row => {
            const rowStatus = row.dataset.status;
            const rowAction = row.dataset.action;
            const rowText = row.textContent.toLowerCase();
            let show = true;
            if (status !== 'all' && rowStatus !== status) show = false;
            if (action !== 'all' && rowAction !== action) show = false;
            if (search && !rowText.includes(search)) show = false;
            row.style.display = show ? '' : 'none';
        });
        currentPage = 1;
        updatePagination();
        updateRuleCount();
        updateBulkBar();
        updateStatCounts();
    }

    function resetFilters() {
        document.getElementById('status-filter').value = 'all';
        document.getElementById('action-filter').value = 'all';
        document.getElementById('search-input').value = '';
        document.querySelectorAll('.rule-row').forEach(row => row.style.display = '');
        currentPage = 1;
        updatePagination();
        updateRuleCount();
        updateBulkBar();
        updateStatCounts();
    }

    function updatePagination() {
        const rows = document.querySelectorAll('.rule-row:not([style*="display: none"])');
        const totalPages = Math.ceil(rows.length / rowsPerPage) || 1;
        if (currentPage > totalPages) currentPage = totalPages;
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        rows.forEach((row, index) => {
            row.style.display = (index >= start && index < end) ? '' : 'none';
        });
        document.getElementById('current-page').textContent = currentPage;
        document.getElementById('total-pages').textContent = totalPages;
        document.getElementById('prev-page').disabled = currentPage === 1;
        document.getElementById('next-page').disabled = currentPage === totalPages;

        const btnContainer = document.querySelector('.page-buttons');
        let buttons = [];
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || Math.abs(i - currentPage) <= 1) {
                buttons.push(i);
            } else if (buttons[buttons.length-1] !== '...') {
                buttons.push('...');
            }
        }
        btnContainer.innerHTML = '';
        buttons.forEach(p => {
            if (p === '...') {
                const span = document.createElement('span');
                span.textContent = '...';
                span.style.padding = '4px 8px';
                span.style.color = '#9ca3af';
                btnContainer.appendChild(span);
            } else {
                const btn = document.createElement('button');
                btn.textContent = p;
                btn.className = p === currentPage ? 'active' : '';
                btn.onclick = () => goToPage(p);
                btnContainer.appendChild(btn);
            }
        });
        const prevBtn = document.createElement('button');
        prevBtn.textContent = 'Previous';
        prevBtn.onclick = () => changePage(-1);
        prevBtn.disabled = currentPage === 1;
        btnContainer.prepend(prevBtn);
        const nextBtn = document.createElement('button');
        nextBtn.textContent = 'Next';
        nextBtn.onclick = () => changePage(1);
        nextBtn.disabled = currentPage === totalPages;
        btnContainer.appendChild(nextBtn);
    }

    function goToPage(page) {
        currentPage = page;
        updatePagination();
        updateRuleCount();
    }

    function changePage(delta) {
        const total = parseInt(document.getElementById('total-pages').textContent);
        const newPage = currentPage + delta;
        if (newPage >= 1 && newPage <= total) {
            currentPage = newPage;
            updatePagination();
            updateRuleCount();
        }
    }

    function updateRuleCount() {
        const visible = document.querySelectorAll('.rule-row:not([style*="display: none"])').length;
        document.getElementById('rule-count').textContent = visible;
    }

    function updateStatCounts() {
        const rows = document.querySelectorAll('.rule-row');
        let active = 0, review = 0, queued = 0, pending = 0;
        rows.forEach(row => {
            const status = row.dataset.status;
            if (status === 'active') active++;
            else if (status === 'review') review++;
            else if (status === 'queued') queued++;
            else if (status === 'pending') pending++;
        });
        document.getElementById('blocked-count').textContent = active + pending;
        document.getElementById('rate-count').textContent = review;
        document.getElementById('acl-count').textContent = queued;
    }

    function toggleAllCheckboxes(master) {
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = master.checked);
        updateBulkBar();
    }

    function updateBulkBar() {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        const count = checked.length;
        const bar = document.getElementById('bulk-actions-bar');
        document.getElementById('bulk-count').textContent = count;
        if (count > 0) {
            bar.classList.add('show');
        } else {
            bar.classList.remove('show');
        }
    }

    function clearBulkSelection() {
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = false);
        document.getElementById('select-all').checked = false;
        updateBulkBar();
    }

    function bulkApply() {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        if (checked.length === 0) return;
        checked.forEach(cb => {
            const row = cb.closest('.rule-row');
            if (row) {
                const statusCell = row.querySelector('td:nth-child(5)');
                statusCell.innerHTML = `<span class="status-badge active"><span class="status-dot"></span> Active</span>`;
                row.dataset.status = 'active';
                const actionCell = row.querySelector('td:nth-child(7)');
                if (actionCell) {
                    actionCell.innerHTML = `
                        <button class="btn-mitigate-action undo" onclick="undoRule(this)">Undo</button>
                        <button class="btn-mitigate-action edit" onclick="openEditModal(this)">Edit</button>
                        <button class="btn-mitigate-action view" onclick="viewRule(this)">View</button>
                    `;
                }
            }
        });
        clearBulkSelection();
        applyFilters();
        updateStatCounts();
        showToast(`${checked.length} rules applied`, 'success');
    }

    function bulkDelete() {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        if (checked.length === 0) return;
        if (confirm(`Delete ${checked.length} selected rule(s)?`)) {
            checked.forEach(cb => {
                const row = cb.closest('.rule-row');
                if (row) row.remove();
            });
            clearBulkSelection();
            applyFilters();
            updateStatCounts();
            showToast(`${checked.length} rules deleted`, 'warning');
        }
    }

    function exportCSV() {
        const rows = document.querySelectorAll('.rule-row:not([style*="display: none"])');
        if (rows.length === 0) {
            showToast('No rules to export', 'warning');
            return;
        }
        let csv = 'Rule,Target,Action,Status,Updated\n';
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length >= 6) {
                const rule = cells[1]?.textContent?.trim() || '';
                const target = cells[2]?.textContent?.trim() || '';
                const action = cells[3]?.textContent?.trim() || '';
                const status = cells[4]?.textContent?.trim() || '';
                const updated = cells[5]?.textContent?.trim() || '';
                csv += `"${rule}","${target}","${action}","${status}","${updated}"\n`;
            }
        });
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `mitigation_rules_${new Date().toISOString().slice(0,10)}.csv`;
        a.click();
        URL.revokeObjectURL(url);
        showToast('CSV exported successfully', 'success');
    }

    function applyRule(button) {
        const row = button.closest('.rule-row');
        const statusCell = row.querySelector('td:nth-child(5)');
        statusCell.innerHTML = `<span class="status-badge active"><span class="status-dot"></span> Active</span>`;
        row.dataset.status = 'active';
        const actionCell = row.querySelector('td:nth-child(7)');
        if (actionCell) {
            actionCell.innerHTML = `
                <button class="btn-mitigate-action undo" onclick="undoRule(this)">Undo</button>
                <button class="btn-mitigate-action edit" onclick="openEditModal(this)">Edit</button>
                <button class="btn-mitigate-action view" onclick="viewRule(this)">View</button>
            `;
        }
        updateStatCounts();
        showToast('Rule applied successfully', 'success');
    }

    function undoRule(button) {
        const row = button.closest('.rule-row');
        const statusCell = row.querySelector('td:nth-child(5)');
        statusCell.innerHTML = `<span class="status-badge pending"><span class="status-dot"></span> Pending</span>`;
        row.dataset.status = 'pending';
        const actionCell = row.querySelector('td:nth-child(7)');
        if (actionCell) {
            actionCell.innerHTML = `
                <button class="btn-mitigate-action apply" onclick="applyRule(this)">Apply</button>
                <button class="btn-mitigate-action undo" onclick="undoRule(this)">Undo</button>
                <button class="btn-mitigate-action edit" onclick="openEditModal(this)">Edit</button>
            `;
        }
        updateStatCounts();
        showToast('Rule undone', 'warning');
    }

    function viewRule(button) {
        const row = button.closest('.rule-row');
        const cells = row.querySelectorAll('td');
        document.getElementById('view-rule').textContent = cells[1]?.textContent?.trim() || '—';
        document.getElementById('view-target').textContent = cells[2]?.textContent?.trim() || '—';
        document.getElementById('view-action').textContent = cells[3]?.textContent?.trim() || '—';
        document.getElementById('view-status').textContent = cells[4]?.textContent?.trim() || '—';
        document.getElementById('view-updated').textContent = cells[5]?.textContent?.trim() || '—';
        document.getElementById('view-id').textContent = row.dataset.id || '—';
        document.getElementById('view-desc').textContent = 'This rule was created to mitigate a detected attack threat.';
        openModal('view-modal');
    }

    function openEditModal(button) {
        const row = button.closest('.rule-row');
        editRowRef = row;
        const cells = row.querySelectorAll('td');
        document.getElementById('edit-rule-name').value = cells[1]?.textContent?.trim() || '';
        document.getElementById('edit-target-ip').value = cells[2]?.textContent?.trim() || '';
        const actionMap = { 'Deny': 'deny', 'Rate Limit': 'rate_limit', 'ACL Update': 'acl_update' };
        const actionVal = cells[3]?.textContent?.trim() || 'deny';
        document.getElementById('edit-action').value = actionMap[actionVal] || 'deny';
        document.getElementById('edit-status').value = row.dataset.status || 'pending';
        openModal('edit-modal');
    }

    function executeEdit() {
        if (!editRowRef) return;
        const name = document.getElementById('edit-rule-name').value.trim();
        const target = document.getElementById('edit-target-ip').value.trim();
        const action = document.getElementById('edit-action').value;
        const status = document.getElementById('edit-status').value;
        if (!name || !target) { showToast('Please fill in all fields', 'warning'); return; }
        const cells = editRowRef.querySelectorAll('td');
        const actionLabels = { 'deny': 'Deny', 'rate_limit': 'Rate Limit', 'acl_update': 'ACL Update' };
        const statusMap = {
            'active': { class: 'active', label: 'Active' },
            'review': { class: 'review', label: 'Review' },
            'queued': { class: 'queued', label: 'Queued' },
            'pending': { class: 'pending', label: 'Pending' },
            'expired': { class: 'expired', label: 'Expired' }
        };
        cells[1].textContent = name;
        cells[2].innerHTML = `<span class="ip-address">${target}</span>`;
        cells[3].textContent = actionLabels[action] || 'Deny';
        const st = statusMap[status] || statusMap.pending;
        cells[4].innerHTML = `<span class="status-badge ${st.class}"><span class="status-dot"></span> ${st.label}</span>`;
        editRowRef.dataset.status = status;
        editRowRef.dataset.action = action;
        closeModal('edit-modal');
        editRowRef = null;
        updateStatCounts();
        showToast('Rule updated successfully', 'success');
    }

    function recheckRouter() {
        showToast('Router status rechecked - all services normal', 'success');
    }

    function openBlockModal() { openModal('block-modal'); }
    function openRateLimitModal() { openModal('ratelimit-modal'); }
    function openACLModal() { openModal('acl-modal'); }
    function openWhitelistModal() { openModal('whitelist-modal'); }
    function openBulkBlockModal() { openModal('bulk-block-modal'); }

    function openModal(id) {
        currentModal = id;
        document.getElementById(id).classList.add('show');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
        currentModal = null;
    }

    function executeBlock() {
        const ip = document.getElementById('block-ip').value.trim();
        const reason = document.getElementById('block-reason').value;
        const duration = document.getElementById('block-duration').value;
        const notes = document.getElementById('block-notes').value.trim();
        if (!ip) { showToast('Please enter an IP address', 'warning'); return; }
        closeModal('block-modal');
        addRule(`Block ${ip} - ${reason}`, ip, 'Deny', 'active', `${duration} - ${notes || 'No notes'}`);
        showToast(`IP ${ip} blocked successfully`, 'success');
        document.getElementById('block-ip').value = '';
        document.getElementById('block-notes').value = '';
        updateStatCounts();
    }

    function executeRateLimit() {
        const ip = document.getElementById('rate-ip').value.trim();
        const limit = document.getElementById('rate-limit').value;
        const protocol = document.getElementById('rate-protocol').value;
        if (!ip) { showToast('Please enter an IP address', 'warning'); return; }
        closeModal('ratelimit-modal');
        addRule(`Rate Limit ${protocol} ${limit}pps`, ip, 'Rate Limit', 'review', `${limit} pps`);
        showToast(`Rate limit applied to ${ip}`, 'success');
        document.getElementById('rate-ip').value = '';
        updateStatCounts();
    }

    function executeACL() {
        const ip = document.getElementById('acl-ip').value.trim();
        const type = document.getElementById('acl-type').value;
        const port = document.getElementById('acl-port').value.trim();
        if (!ip) { showToast('Please enter an IP address', 'warning'); return; }
        closeModal('acl-modal');
        const label = port ? `${type.toUpperCase()} on port ${port}` : type.toUpperCase();
        addRule(`ACL ${label}`, ip, 'ACL Update', 'queued', `Rule: ${type}`);
        showToast(`ACL deployed for ${ip}`, 'success');
        document.getElementById('acl-ip').value = '';
        document.getElementById('acl-port').value = '';
        updateStatCounts();
    }

    function executeWhitelist() {
        const ip = document.getElementById('whitelist-ip').value.trim();
        const reason = document.getElementById('whitelist-reason').value.trim();
        if (!ip) { showToast('Please enter an IP address', 'warning'); return; }
        closeModal('whitelist-modal');
        addRule(`Whitelist ${ip}`, ip, 'Allow', 'active', reason || 'Trusted source');
        showToast(`IP ${ip} whitelisted`, 'success');
        document.getElementById('whitelist-ip').value = '';
        document.getElementById('whitelist-reason').value = '';
        updateStatCounts();
    }

    function executeBulkBlock() {
        const ips = document.getElementById('bulk-ips').value.trim();
        const reason = document.getElementById('bulk-reason').value;
        if (!ips) { showToast('Please enter at least one IP address', 'warning'); return; }
        const ipList = ips.split('\n').map(ip => ip.trim()).filter(ip => ip);
        if (ipList.length === 0) { showToast('Please enter valid IP addresses', 'warning'); return; }
        closeModal('bulk-block-modal');
        ipList.forEach(ip => {
            addRule(`Block ${ip} - ${reason}`, ip, 'Deny', 'active', 'Bulk block');
        });
        showToast(`${ipList.length} IPs blocked successfully`, 'success');
        document.getElementById('bulk-ips').value = '';
        updateStatCounts();
    }

    function addRule(rule, target, action, status, detail) {
        const tbody = document.getElementById('rule-tbody');
        const statusMap = {
            'active': { class: 'active', label: 'Active' },
            'review': { class: 'review', label: 'Review' },
            'queued': { class: 'queued', label: 'Queued' },
            'pending': { class: 'pending', label: 'Pending' }
        };
        const st = statusMap[status] || statusMap.pending;
        const row = document.createElement('tr');
        row.className = 'rule-row';
        row.dataset.status = status;
        row.dataset.action = action === 'Deny' ? 'deny' : action === 'Rate Limit' ? 'rate_limit' : action === 'ACL Update' ? 'acl_update' : 'allow';
        row.dataset.id = Date.now();
        const actionLabels = { 'deny': 'Deny', 'rate_limit': 'Rate Limit', 'acl_update': 'ACL Update', 'allow': 'Allow' };
        const actionDisplay = actionLabels[row.dataset.action] || action;
        let actionsHtml = '';
        if (status === 'active') {
            actionsHtml = `
                <button class="btn-mitigate-action undo" onclick="undoRule(this)">Undo</button>
                <button class="btn-mitigate-action edit" onclick="openEditModal(this)">Edit</button>
                <button class="btn-mitigate-action view" onclick="viewRule(this)">View</button>
            `;
        } else {
            actionsHtml = `
                <button class="btn-mitigate-action apply" onclick="applyRule(this)">Apply</button>
                <button class="btn-mitigate-action undo" onclick="undoRule(this)">Undo</button>
                <button class="btn-mitigate-action edit" onclick="openEditModal(this)">Edit</button>
            `;
        }
        row.innerHTML = `
            <td onclick="event.stopPropagation();"><input type="checkbox" class="bulk-checkbox row-checkbox" onchange="updateBulkBar()"></td>
            <td>${rule}</td>
            <td><span class="ip-address">${target}</span></td>
            <td>${actionDisplay}</td>
            <td style="text-align:center;"><span class="status-badge ${st.class}"><span class="status-dot"></span> ${st.label}</span></td>
            <td style="text-align:center;">Just now</td>
            <td style="text-align:center;">${actionsHtml}</td>
        `;
        tbody.prepend(row);
        applyFilters();
        updateRuleCount();
        updateStatCounts();
    }

    function refreshRules() {
        const btn = event?.target?.closest?.('button') || document.querySelector('.table-header-toolbar .btn-outline-primary');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="ti ti-loader ti-spin"></i>';
            setTimeout(() => {
                btn.innerHTML = '<i class="ti ti-refresh"></i> Refresh';
                btn.disabled = false;
                applyFilters();
                showToast('Rules refreshed', 'success');
            }, 600);
        } else {
            applyFilters();
        }
    }

    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `toast-notification ${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && currentModal) {
            closeModal(currentModal);
        }
    });

    document.getElementById('search-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') applyFilters();
    });
</script>
@endsection