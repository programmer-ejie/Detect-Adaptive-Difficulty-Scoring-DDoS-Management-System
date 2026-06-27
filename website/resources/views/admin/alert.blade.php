@extends('admin.layout', ['page' => 'alert'])

@section('title', 'Alerts')
@section('page-title', 'Alerts')

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
    .stat-value.text-critical { color: #dc2626; }
    .stat-value.text-high { color: #d97706; }
    .stat-value.text-acknowledged { color: #2563eb; }
    .stat-value.text-resolved { color: #16a34a; }

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
    .status-badge.open { background: #fee2e2; color: #7f1d1d; }
    .status-badge.open .status-dot { background: #dc2626; }
    .status-badge.investigating { background: #fef3c7; color: #92400e; }
    .status-badge.investigating .status-dot { background: #d97706; }
    .status-badge.acknowledged { background: #dbeafe; color: #0c4a6e; }
    .status-badge.acknowledged .status-dot { background: #2563eb; }
    .status-badge.resolved { background: #dcfce7; color: #166534; }
    .status-badge.resolved .status-dot { background: #16a34a; }

    .severity-badge {
        padding: 3px 10px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
        display: inline-block;
    }
    .severity-badge.critical { background: #fee2e2; color: #7f1d1d; border: 1px solid #fecaca; }
    .severity-badge.high { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .severity-badge.medium { background: #dbeafe; color: #0c4a6e; border: 1px solid #bfdbfe; }
    .severity-badge.low { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }

    .btn-mitigate {
        padding: 4px 14px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        background: #5864FF;
        color: white;
        text-decoration: none;
        display: inline-block;
    }
    .btn-mitigate:hover { background: #4356E6; color: white; text-decoration: none; }
    .btn-mitigate.resolved-btn { background: #9ca3af; cursor: default; }
    .btn-mitigate.resolved-btn:hover { background: #9ca3af; }

    .table-alerts th {
        padding: 10px 12px;
        font-weight: 600;
        font-size: 11px;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e5e7eb;
        white-space: nowrap;
    }
    .table-alerts td {
        padding: 10px 12px;
        vertical-align: middle;
        font-size: 13px;
        border-bottom: 1px solid #f3f4f6;
        white-space: nowrap;
    }
    .table-alerts tbody tr {
        cursor: pointer;
        transition: background 0.15s;
    }
    .table-alerts tbody tr:hover { background: #f8fafc; }
    .table-alerts .ip-address { font-family: monospace; font-size: 12px; }
    .table-alerts .time-cell { white-space: nowrap; min-width: 80px; }

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
    .bulk-actions-bar .bulk-btn.acknowledge { background: #dbeafe; color: #0c4a6e; }
    .bulk-actions-bar .bulk-btn.acknowledge:hover { background: #bfdbfe; }
    .bulk-actions-bar .bulk-btn.resolve { background: #dcfce7; color: #166534; }
    .bulk-actions-bar .bulk-btn.resolve:hover { background: #bbf7d0; }
    .bulk-actions-bar .bulk-btn.export { background: #5864FF; color: white; }
    .bulk-actions-bar .bulk-btn.export:hover { background: #4356E6; }

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

    .refresh-controls {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .refresh-controls select {
        padding: 3px 8px;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        font-size: 11px;
        background: white;
        color: #374151;
    }
    .refresh-indicator {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #16a34a;
        transition: background 0.3s;
    }
    .refresh-indicator.paused { background: #9ca3af; }

    .sla-timer {
        font-size: 11px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 4px;
        white-space: nowrap;
    }
    .sla-timer.safe { background: #dcfce7; color: #166534; }
    .sla-timer.warning { background: #fef3c7; color: #92400e; }
    .sla-timer.critical { background: #fee2e2; color: #7f1d1d; animation: pulse-sla 1s infinite; }

    @keyframes pulse-sla {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
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
        max-width: 700px;
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
    .modal-body .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    .modal-body .detail-item .label {
        font-size: 11px;
        color: #9ca3af;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.3px;
    }
    .modal-body .detail-item .value {
        font-size: 15px;
        color: #1f2937;
        font-weight: 500;
        margin-top: 2px;
        word-break: break-all;
    }
    .modal-body .detail-item .value .ip-address { font-family: monospace; }
    .modal-body .modal-description {
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid #f3f4f6;
    }
    .modal-body .modal-description .label {
        font-size: 11px;
        color: #9ca3af;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.3px;
    }
    .modal-body .modal-description .value {
        font-size: 14px;
        color: #374151;
        margin-top: 4px;
        line-height: 1.6;
    }

    .severity-bar-wrapper {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .severity-bar-item .bar-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 3px;
    }
    .severity-bar-item .bar-row .bar-label { font-size: 12px; font-weight: 600; }
    .severity-bar-item .bar-row .bar-count { font-size: 12px; color: #374151; font-weight: 600; }
    .severity-bar-item .bar-track {
        height: 6px;
        border-radius: 3px;
        background: #f3f4f6;
        overflow: hidden;
    }
    .severity-bar-item .bar-track .bar-fill {
        height: 100%;
        border-radius: 3px;
        transition: width 0.5s ease;
    }
    .bar-fill.critical { background: #dc2626; }
    .bar-fill.high { background: #d97706; }
    .bar-fill.medium { background: #2563eb; }
    .bar-fill.low { background: #16a34a; }

    .stats-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    .stats-grid .stat-box {
        text-align: center;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 8px;
    }
    .stats-grid .stat-box .number { font-size: 20px; font-weight: 700; }
    .stats-grid .stat-box .label { font-size: 10px; color: #9ca3af; font-weight: 500; margin-top: 2px; }

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

    .alert-badge-title {
        background: #dc2626;
        color: white;
        border-radius: 50%;
        padding: 1px 6px;
        font-size: 10px;
        font-weight: 700;
        margin-left: 4px;
        display: inline-block;
    }

    .table-header-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
        flex-wrap: wrap;
        gap: 8px;
    }
    .table-header-toolbar h5 {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .table-header-toolbar .toolbar-right {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }
    .table-header-toolbar .toolbar-right .alert-count-badge {
        font-size: 13px;
        color: #6b7280;
    }
    .table-header-toolbar .toolbar-right .refresh-controls {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .table-header-toolbar .toolbar-right .refresh-controls select {
        padding: 3px 6px;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        font-size: 11px;
        background: white;
        color: #374151;
    }
    .table-header-toolbar .toolbar-right .refresh-controls .refresh-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        background: #16a34a;
    }
    .table-header-toolbar .toolbar-right .refresh-controls .refresh-indicator.paused {
        background: #9ca3af;
    }

    .text-resolved-label {
        font-size: 11px;
        color: #9ca3af;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .filter-bar { flex-direction: column; align-items: stretch; }
        .filter-bar .filter-group { flex-wrap: wrap; }
        .filter-bar .filter-spacer { display: none; }
        .stats-grid { grid-template-columns: 1fr 1fr; }
        .table-alerts { font-size: 12px; }
        .table-alerts th, .table-alerts td { padding: 6px 8px; white-space: normal; }
        .modal-body .detail-grid { grid-template-columns: 1fr; }
        .pagination-wrapper { flex-direction: column; align-items: stretch; text-align: center; }
        .pagination-wrapper .page-buttons { justify-content: center; flex-wrap: wrap; }
        .bulk-actions-bar { flex-wrap: wrap; }
        .table-header-toolbar { flex-direction: column; align-items: stretch; gap: 8px; }
        .table-header-toolbar .toolbar-right { justify-content: space-between; flex-wrap: wrap; }
    }
    @media (max-width: 576px) {
        .stats-grid { grid-template-columns: 1fr 1fr; }
        .filter-bar select, .filter-bar input { min-width: 70px; font-size: 11px; }
        .filter-bar label { font-size: 10px; }
        .table-alerts td { font-size: 11px; padding: 4px 6px; }
        .table-alerts th { font-size: 10px; padding: 4px 6px; }
        .table-header-toolbar .toolbar-right .alert-count-badge { font-size: 11px; }
        .table-header-toolbar .toolbar-right .refresh-controls select { font-size: 10px; }
    }
</style>

<div class="row">
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="stat-icon"><i class="ti ti-alert-octagon"></i></div>
                <div class="stat-label">Critical Alerts</div>
                <div class="stat-value text-critical" id="critical-count">24</div>
                <span class="stat-badge" style="background: #fee2e2; color: #7f1d1d;">Needs review</span>
                <div class="stat-description">Highest risk incidents</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="stat-icon"><i class="ti ti-alert-triangle"></i></div>
                <div class="stat-label">High Severity</div>
                <div class="stat-value text-high" id="high-count">48</div>
                <span class="stat-badge" style="background: #fef3c7; color: #92400e;">Active</span>
                <div class="stat-description">Traffic spikes under watch</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="stat-icon"><i class="ti ti-check-circle"></i></div>
                <div class="stat-label">Acknowledged</div>
                <div class="stat-value text-acknowledged" id="acknowledged-count">342</div>
                <span class="stat-badge" style="background: #dbeafe; color: #0c4a6e;">Tracked</span>
                <div class="stat-description">Assigned to operator</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="stat-icon"><i class="ti ti-check"></i></div>
                <div class="stat-label">Resolved</div>
                <div class="stat-value text-resolved" id="resolved-count">1,847</div>
                <span class="stat-badge" style="background: #dcfce7; color: #166534;">Closed</span>
                <div class="stat-description">Successfully mitigated</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="filter-bar">
            <div class="filter-group">
                <label for="severity-filter">Severity</label>
                <select id="severity-filter">
                    <option value="all">All</option>
                    <option value="critical">Critical</option>
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="status-filter">Status</label>
                <select id="status-filter">
                    <option value="all">All</option>
                    <option value="open">Open</option>
                    <option value="investigating">Investigating</option>
                    <option value="acknowledged">Acknowledged</option>
                    <option value="resolved">Resolved</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="type-filter">Type</label>
                <select id="type-filter">
                    <option value="all">All</option>
                    <option value="udp">UDP</option>
                    <option value="http">HTTP</option>
                    <option value="syn">SYN</option>
                    <option value="probing">Probing</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="search-input">Search</label>
                <input type="text" id="search-input" placeholder="IP or keyword..." style="min-width: 130px;">
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
            <button class="bulk-btn acknowledge" onclick="bulkAction('acknowledged')">Acknowledge</button>
            <button class="bulk-btn resolve" onclick="bulkAction('resolved')">Resolve</button>
            <button class="bulk-btn export" onclick="exportCSV()">Export CSV</button>
            <button class="bulk-btn" style="background:#f3f4f6;color:#6b7280;" onclick="clearBulkSelection()">Clear</button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="table-header-toolbar">
            <h5>Detected Attack Alerts <span class="alert-badge-title" id="title-badge">4</span></h5>
            <div class="toolbar-right">
                <span class="alert-count-badge">Showing <span id="alert-count">4</span> alerts</span>
                <div class="refresh-controls">
                    <span class="refresh-indicator" id="refresh-indicator"></span>
                    <select id="refresh-interval">
                        <option value="0">Off</option>
                        <option value="10">10s</option>
                        <option value="30" selected>30s</option>
                        <option value="60">60s</option>
                    </select>
                    <button class="btn btn-sm btn-outline-primary" onclick="refreshAlerts()" style="padding: 3px 10px; font-size: 11px; white-space: nowrap;">
                        <i class="ti ti-refresh"></i> Refresh
                    </button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-alerts mb-0" id="alert-table">
                        <thead>
                            <tr>
                                <th style="width:30px;"><input type="checkbox" class="bulk-checkbox" id="select-all" onchange="toggleAllCheckboxes(this)"></th>
                                <th class="time-cell">Time</th>
                                <th>Type</th>
                                <th>Source IP</th>
                                <th>Target IP</th>
                                <th style="text-align:center;">Severity</th>
                                <th style="text-align:center;">Status</th>
                                <th style="text-align:center;">SLA</th>
                                <th style="text-align:center;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="alert-tbody">
                            <tr class="alert-row" data-severity="critical" data-status="open" data-type="udp" data-id="1" onclick="openDetailModal(this)">
                                <td onclick="event.stopPropagation();"><input type="checkbox" class="bulk-checkbox row-checkbox" onchange="updateBulkBar()"></td>
                                <td class="time-cell">2 min ago</td>
                                <td>UDP Amplification</td>
                                <td><span class="ip-address">10.23.1.18</span></td>
                                <td><span class="ip-address">172.16.0.12</span></td>
                                <td style="text-align:center;"><span class="severity-badge critical">Critical</span></td>
                                <td style="text-align:center;"><span class="status-badge open"><span class="status-dot"></span> Open</span></td>
                                <td style="text-align:center;"><span class="sla-timer warning" data-start="2026-06-27T14:23:45">2m 30s / 5m</span></td>
                                <td style="text-align:center;">
                                    <a href="{{ url('/mitigation') }}" class="btn-mitigate" onclick="event.stopPropagation();">Mitigate</a>
                                </td>
                            </tr>
                            <tr class="alert-row" data-severity="high" data-status="investigating" data-type="http" data-id="2" onclick="openDetailModal(this)">
                                <td onclick="event.stopPropagation();"><input type="checkbox" class="bulk-checkbox row-checkbox" onchange="updateBulkBar()"></td>
                                <td class="time-cell">7 min ago</td>
                                <td>HTTP Flood</td>
                                <td><span class="ip-address">203.0.113.44</span></td>
                                <td><span class="ip-address">172.16.0.12</span></td>
                                <td style="text-align:center;"><span class="severity-badge high">High</span></td>
                                <td style="text-align:center;"><span class="status-badge investigating"><span class="status-dot"></span> Investigating</span></td>
                                <td style="text-align:center;"><span class="sla-timer warning" data-start="2026-06-27T14:18:32">7m 10s / 10m</span></td>
                                <td style="text-align:center;">
                                    <a href="{{ url('/mitigation') }}" class="btn-mitigate" onclick="event.stopPropagation();">Mitigate</a>
                                </td>
                            </tr>
                            <tr class="alert-row" data-severity="medium" data-status="acknowledged" data-type="syn" data-id="3" onclick="openDetailModal(this)">
                                <td onclick="event.stopPropagation();"><input type="checkbox" class="bulk-checkbox row-checkbox" onchange="updateBulkBar()"></td>
                                <td class="time-cell">16 min ago</td>
                                <td>SYN Scan</td>
                                <td><span class="ip-address">198.51.100.77</span></td>
                                <td><span class="ip-address">172.16.0.18</span></td>
                                <td style="text-align:center;"><span class="severity-badge medium">Medium</span></td>
                                <td style="text-align:center;"><span class="status-badge acknowledged"><span class="status-dot"></span> Acknowledged</span></td>
                                <td style="text-align:center;"><span class="sla-timer safe" data-start="2026-06-27T14:12:10">16m / 30m</span></td>
                                <td style="text-align:center;">
                                    <a href="{{ url('/mitigation') }}" class="btn-mitigate" onclick="event.stopPropagation();">Mitigate</a>
                                </td>
                            </tr>
                            <tr class="alert-row" data-severity="low" data-status="resolved" data-type="probing" data-id="4" onclick="openDetailModal(this)">
                                <td onclick="event.stopPropagation();"><input type="checkbox" class="bulk-checkbox row-checkbox" onchange="updateBulkBar()"></td>
                                <td class="time-cell">31 min ago</td>
                                <td>Low-rate Probing</td>
                                <td><span class="ip-address">192.0.2.88</span></td>
                                <td><span class="ip-address">172.16.0.12</span></td>
                                <td style="text-align:center;"><span class="severity-badge low">Low</span></td>
                                <td style="text-align:center;"><span class="status-badge resolved"><span class="status-dot"></span> Resolved</span></td>
                                <td style="text-align:center;"><span class="sla-timer safe">31m / 30m</span></td>
                                <td style="text-align:center;"><span class="text-resolved-label">Resolved</span></td>
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

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-3">Alert Severity Distribution</h6>
                <div class="severity-bar-wrapper" id="severity-chart">
                    <div class="severity-bar-item">
                        <div class="bar-row">
                            <span class="bar-label" style="color: #7f1d1d;">Critical</span>
                            <span class="bar-count" id="chart-critical">24</span>
                        </div>
                        <div class="bar-track">
                            <div class="bar-fill critical" id="chart-critical-bar" style="width:15%;"></div>
                        </div>
                    </div>
                    <div class="severity-bar-item">
                        <div class="bar-row">
                            <span class="bar-label" style="color: #92400e;">High</span>
                            <span class="bar-count" id="chart-high">48</span>
                        </div>
                        <div class="bar-track">
                            <div class="bar-fill high" id="chart-high-bar" style="width:30%;"></div>
                        </div>
                    </div>
                    <div class="severity-bar-item">
                        <div class="bar-row">
                            <span class="bar-label" style="color: #0c4a6e;">Medium</span>
                            <span class="bar-count" id="chart-medium">342</span>
                        </div>
                        <div class="bar-track">
                            <div class="bar-fill medium" id="chart-medium-bar" style="width:60%;"></div>
                        </div>
                    </div>
                    <div class="severity-bar-item">
                        <div class="bar-row">
                            <span class="bar-label" style="color: #166534;">Low</span>
                            <span class="bar-count" id="chart-low">1,847</span>
                        </div>
                        <div class="bar-track">
                            <div class="bar-fill low" id="chart-low-bar" style="width:90%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-3">Alert Response Metrics</h6>
                <div class="stats-grid">
                    <div class="stat-box">
                        <div class="number" style="color: #5864FF;">92%</div>
                        <div class="label">Avg Response Rate</div>
                    </div>
                    <div class="stat-box">
                        <div class="number" style="color: #16a34a;">4.2m</div>
                        <div class="label">Mean Time to Acknowledge</div>
                    </div>
                    <div class="stat-box">
                        <div class="number" style="color: #d97706;">8.7m</div>
                        <div class="label">Mean Time to Resolve</div>
                    </div>
                    <div class="stat-box">
                        <div class="number" style="color: #6b7280;">3.2%</div>
                        <div class="label">False Positive Rate</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-overlay" id="detail-modal" onclick="if(event.target===this) closeDetailModal()">
    <div class="modal-box">
        <div class="modal-header">
            <h4 id="modal-title">Alert Details</h4>
            <button class="modal-close" onclick="closeDetailModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="detail-grid" id="modal-details">
                <div class="detail-item"><div class="label">Type</div><div class="value" id="modal-type">—</div></div>
                <div class="detail-item"><div class="label">Severity</div><div class="value" id="modal-severity">—</div></div>
                <div class="detail-item"><div class="label">Source IP</div><div class="value"><span class="ip-address" id="modal-source">—</span></div></div>
                <div class="detail-item"><div class="label">Target IP</div><div class="value"><span class="ip-address" id="modal-target">—</span></div></div>
                <div class="detail-item"><div class="label">Status</div><div class="value" id="modal-status">—</div></div>
                <div class="detail-item"><div class="label">Time</div><div class="value" id="modal-time">—</div></div>
                <div class="detail-item"><div class="label">SLA Timer</div><div class="value" id="modal-sla">—</div></div>
                <div class="detail-item"><div class="label">Alert ID</div><div class="value" id="modal-id">—</div></div>
            </div>
            <div class="modal-description">
                <div class="label">Description</div>
                <div class="value" id="modal-description">This alert was triggered by suspicious network activity matching the attack pattern. Immediate investigation recommended.</div>
            </div>
            <div style="margin-top:20px; display:flex; gap:12px; flex-wrap:wrap;">
                <button class="btn-mitigate" onclick="closeDetailModal(); window.location.href='{{ url('/mitigation') }}'">Go to Mitigation</button>
                <button class="bulk-btn acknowledge" onclick="closeDetailModal(); updateStatusFromModal('acknowledged')">Acknowledge</button>
                <button class="bulk-btn resolve" onclick="closeDetailModal(); updateStatusFromModal('resolved')">Resolve</button>
                <button class="bulk-btn" style="background:#f3f4f6;color:#6b7280;" onclick="closeDetailModal()">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    let allAlerts = [];
    let currentPage = 1;
    const rowsPerPage = 5;
    let refreshTimer = null;
    let selectedAlertId = null;

    document.addEventListener('DOMContentLoaded', function() {
        buildAlertData();
        updatePagination();
        updateAlertCount();
        updateTitleBadge();
        updateStatCounts();
        startAutoRefresh();
        updateSlaTimers();
        setInterval(updateSlaTimers, 5000);
    });

    function buildAlertData() {
        const rows = document.querySelectorAll('.alert-row');
        allAlerts = [];
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length >= 8) {
                allAlerts.push({
                    id: row.dataset.id || 'unknown',
                    time: cells[1]?.textContent?.trim() || '',
                    type: cells[2]?.textContent?.trim() || '',
                    source: cells[3]?.textContent?.trim() || '',
                    target: cells[4]?.textContent?.trim() || '',
                    severity: row.dataset.severity || 'low',
                    status: row.dataset.status || 'open',
                    typeLabel: row.dataset.type || 'unknown',
                    slaText: cells[7]?.textContent?.trim() || '',
                    element: row
                });
            }
        });
    }

    function applyFilters() {
        const severity = document.getElementById('severity-filter').value;
        const status = document.getElementById('status-filter').value;
        const type = document.getElementById('type-filter').value;
        const search = document.getElementById('search-input').value.toLowerCase();

        const rows = document.querySelectorAll('.alert-row');
        rows.forEach(row => {
            const rowSeverity = row.dataset.severity;
            const rowStatus = row.dataset.status;
            const rowType = row.dataset.type;
            const rowText = row.textContent.toLowerCase();
            let show = true;
            if (severity !== 'all' && rowSeverity !== severity) show = false;
            if (status !== 'all' && rowStatus !== status) show = false;
            if (type !== 'all' && rowType !== type) show = false;
            if (search && !rowText.includes(search)) show = false;
            row.style.display = show ? '' : 'none';
        });
        currentPage = 1;
        updatePagination();
        updateAlertCount();
        updateTitleBadge();
        updateStatCounts();
        updateBulkBar();
    }

    function resetFilters() {
        document.getElementById('severity-filter').value = 'all';
        document.getElementById('status-filter').value = 'all';
        document.getElementById('type-filter').value = 'all';
        document.getElementById('search-input').value = '';
        document.querySelectorAll('.alert-row').forEach(row => row.style.display = '');
        currentPage = 1;
        updatePagination();
        updateAlertCount();
        updateTitleBadge();
        updateStatCounts();
        updateBulkBar();
    }

    function updatePagination() {
        const rows = document.querySelectorAll('.alert-row:not([style*="display: none"])');
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
        updateAlertCount();
    }

    function changePage(delta) {
        const total = parseInt(document.getElementById('total-pages').textContent);
        const newPage = currentPage + delta;
        if (newPage >= 1 && newPage <= total) {
            currentPage = newPage;
            updatePagination();
            updateAlertCount();
        }
    }

    function updateAlertCount() {
        const visible = document.querySelectorAll('.alert-row:not([style*="display: none"])').length;
        document.getElementById('alert-count').textContent = visible;
    }

    function updateTitleBadge() {
        const visible = document.querySelectorAll('.alert-row:not([style*="display: none"])').length;
        const badge = document.getElementById('title-badge');
        if (badge) badge.textContent = visible;
    }

    function updateStatCounts() {
        const allRows = document.querySelectorAll('.alert-row');
        let critical = 0, high = 0, ack = 0, resolved = 0;
        allRows.forEach(row => {
            const status = row.dataset.status;
            const severity = row.dataset.severity;
            if (status === 'resolved') resolved++;
            else if (status === 'acknowledged') ack++;
            else if (severity === 'critical') critical++;
            else if (severity === 'high') high++;
        });
        document.getElementById('critical-count').textContent = critical;
        document.getElementById('high-count').textContent = high + critical;
        document.getElementById('acknowledged-count').textContent = ack;
        document.getElementById('resolved-count').textContent = resolved;
        const c = parseInt(document.getElementById('chart-critical')?.textContent || 0);
        const h = parseInt(document.getElementById('chart-high')?.textContent || 0);
        const m = parseInt(document.getElementById('chart-medium')?.textContent || 0);
        const l = parseInt(document.getElementById('chart-low')?.textContent || 0);
        const maxVal = Math.max(c, h, m, l, 1);
        document.getElementById('chart-critical-bar').style.width = Math.round((c/maxVal)*100) + '%';
        document.getElementById('chart-high-bar').style.width = Math.round((h/maxVal)*100) + '%';
        document.getElementById('chart-medium-bar').style.width = Math.round((m/maxVal)*100) + '%';
        document.getElementById('chart-low-bar').style.width = Math.round((l/maxVal)*100) + '%';
    }

    function startAutoRefresh() {
        const interval = parseInt(document.getElementById('refresh-interval').value);
        const indicator = document.getElementById('refresh-indicator');
        if (refreshTimer) { clearInterval(refreshTimer); refreshTimer = null; }
        if (interval > 0) {
            refreshTimer = setInterval(() => {
                refreshAlertsSilent();
            }, interval * 1000);
            indicator.className = 'refresh-indicator';
        } else {
            indicator.className = 'refresh-indicator paused';
        }
    }

    document.getElementById('refresh-interval').addEventListener('change', startAutoRefresh);

    function refreshAlertsSilent() {
        const indicator = document.getElementById('refresh-indicator');
        indicator.style.background = '#d97706';
        setTimeout(() => {
            applyFilters();
            indicator.style.background = '#16a34a';
        }, 300);
    }

    function refreshAlerts() {
        const btn = event?.target?.closest?.('button') || document.querySelector('.refresh-controls .btn-outline-primary');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="ti ti-loader ti-spin"></i>';
            setTimeout(() => {
                btn.innerHTML = '<i class="ti ti-refresh"></i> Refresh';
                btn.disabled = false;
                applyFilters();
                showToast('Alerts refreshed', 'success');
            }, 600);
        } else {
            applyFilters();
        }
    }

    function updateSlaTimers() {
        document.querySelectorAll('.sla-timer[data-start]').forEach(el => {
            try {
                const start = new Date(el.dataset.start);
                const now = new Date();
                const diff = Math.floor((now - start) / 1000);
                const mins = Math.floor(diff / 60);
                const secs = diff % 60;
                const text = `${mins}m ${secs}s`;
                const threshold = parseInt(el.textContent.split('/')[1]?.trim()?.replace(/[^0-9]/g,'') || 10);
                const currentMins = mins + (secs/60);
                if (currentMins > threshold) {
                    el.className = 'sla-timer critical';
                } else if (currentMins > threshold * 0.7) {
                    el.className = 'sla-timer warning';
                } else {
                    el.className = 'sla-timer safe';
                }
                el.textContent = `${text} / ${threshold}m`;
            } catch(e) { /* ignore */ }
        });
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

    function bulkAction(newStatus) {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        if (checked.length === 0) return;
        const ids = [];
        checked.forEach(cb => {
            const row = cb.closest('.alert-row');
            if (row) {
                ids.push(row.dataset.id);
                updateRowStatus(row, newStatus);
            }
        });
        clearBulkSelection();
        applyFilters();
        showToast(`${ids.length} alerts ${newStatus}`, 'success');
    }

    function updateRowStatus(row, newStatus) {
        const statusMap = {
            'open': { class: 'open', label: 'Open' },
            'investigating': { class: 'investigating', label: 'Investigating' },
            'acknowledged': { class: 'acknowledged', label: 'Acknowledged' },
            'resolved': { class: 'resolved', label: 'Resolved' }
        };
        const status = statusMap[newStatus];
        if (!status) return;
        const cells = row.querySelectorAll('td');
        if (cells.length >= 8) {
            const statusCell = cells[6];
            statusCell.innerHTML = `<span class="status-badge ${status.class}"><span class="status-dot"></span> ${status.label}</span>`;
            row.dataset.status = newStatus;
            const actionCell = cells[8];
            if (newStatus === 'resolved') {
                actionCell.innerHTML = '<span class="text-resolved-label">Resolved</span>';
            } else {
                actionCell.innerHTML = `<a href="{{ url('/mitigation') }}" class="btn-mitigate" onclick="event.stopPropagation();">Mitigate</a>`;
            }
        }
        updateStatCounts();
        updateAlertCount();
        updateTitleBadge();
    }

    function updateStatusFromModal(newStatus) {
        if (!selectedAlertId) return;
        const row = document.querySelector(`.alert-row[data-id="${selectedAlertId}"]`);
        if (row) {
            updateRowStatus(row, newStatus);
            showToast(`Alert ${selectedAlertId} ${newStatus}`, 'success');
            selectedAlertId = null;
        }
    }

    function openDetailModal(row) {
        const cells = row.querySelectorAll('td');
        if (cells.length < 8) return;
        selectedAlertId = row.dataset.id;
        document.getElementById('modal-title').textContent = `Alert #${row.dataset.id}`;
        document.getElementById('modal-type').textContent = cells[2]?.textContent?.trim() || '—';
        document.getElementById('modal-source').textContent = cells[3]?.textContent?.trim() || '—';
        document.getElementById('modal-target').textContent = cells[4]?.textContent?.trim() || '—';
        document.getElementById('modal-time').textContent = cells[1]?.textContent?.trim() || '—';
        document.getElementById('modal-id').textContent = row.dataset.id || '—';
        const sev = row.dataset.severity || 'low';
        const sevLabel = sev.charAt(0).toUpperCase() + sev.slice(1);
        document.getElementById('modal-severity').innerHTML = `<span class="severity-badge ${sev}">${sevLabel}</span>`;
        const stat = row.dataset.status || 'open';
        const statMap = { 'open':'Open', 'investigating':'Investigating', 'acknowledged':'Acknowledged', 'resolved':'Resolved' };
        document.getElementById('modal-status').innerHTML = `<span class="status-badge ${stat}"><span class="status-dot"></span> ${statMap[stat] || stat}</span>`;
        const slaEl = cells[7]?.querySelector('.sla-timer');
        document.getElementById('modal-sla').textContent = slaEl ? slaEl.textContent : '—';
        const desc = {
            'udp': 'This alert was triggered by a UDP amplification attack. The source IP is flooding the target with large UDP packets, overwhelming network bandwidth.',
            'http': 'This alert was triggered by an HTTP flood attack. Multiple HTTP requests are being sent to the target server, exhausting application resources.',
            'syn': 'This alert was triggered by a SYN scan attack. The source IP is sending SYN packets to multiple ports, attempting to discover open services.',
            'probing': 'This alert was triggered by low-rate probing. The source IP is sending periodic requests to map network topology and identify vulnerabilities.'
        };
        document.getElementById('modal-description').textContent = desc[row.dataset.type] || 'This alert was triggered by suspicious network activity matching the attack pattern. Immediate investigation recommended.';
        document.getElementById('detail-modal').classList.add('show');
    }

    function closeDetailModal() {
        document.getElementById('detail-modal').classList.remove('show');
        selectedAlertId = null;
    }

    function exportCSV() {
        const rows = document.querySelectorAll('.alert-row:not([style*="display: none"])');
        if (rows.length === 0) {
            showToast('No alerts to export', 'warning');
            return;
        }
        let csv = 'Time,Type,Source IP,Target IP,Severity,Status\n';
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length >= 6) {
                const time = cells[1]?.textContent?.trim() || '';
                const type = cells[2]?.textContent?.trim() || '';
                const src = cells[3]?.textContent?.trim() || '';
                const tgt = cells[4]?.textContent?.trim() || '';
                const sev = cells[5]?.textContent?.trim() || '';
                const stat = cells[6]?.textContent?.trim() || '';
                csv += `"${time}","${type}","${src}","${tgt}","${sev}","${stat}"\n`;
            }
        });
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `alerts_${new Date().toISOString().slice(0,10)}.csv`;
        a.click();
        URL.revokeObjectURL(url);
        showToast('CSV exported successfully', 'success');
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
        if (e.key === 'Escape') {
            if (document.getElementById('detail-modal').classList.contains('show')) {
                closeDetailModal();
            }
        }
    });

    document.getElementById('search-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') applyFilters();
    });
</script>
@endsection