@extends('admin.layout', ['page' => 'settings'])

@section('title', 'Settings')
@section('page-title', 'Settings')

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
    .stat-card .stat-icon.green { background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); }
    .stat-card .stat-icon.orange { background: linear-gradient(135deg, #d97706 0%, #b45309 100%); }
    .stat-card .stat-icon.blue { background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); }
    .stat-card .stat-icon.purple { background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); }

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
    .stat-value.text-connected { color: #16a34a; }
    .stat-value.text-pending { color: #d97706; }
    .stat-value.text-rules { color: #2563eb; }
    .stat-value.text-health { color: #7c3aed; }

    .settings-section {
        margin-bottom: 24px;
    }
    .settings-section .section-title {
        font-size: 14px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .settings-section .section-title .title-icon {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        background: #eef2ff;
        color: #5864FF;
    }

    .form-group {
        margin-bottom: 18px;
    }
    .form-group .form-label {
        font-size: 12px;
        font-weight: 600;
        color: #374151;
        display: block;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .form-group .form-label .required {
        color: #dc2626;
        margin-left: 2px;
    }
    .form-group .form-control,
    .form-group .form-select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 13px;
        color: #1f2937;
        background: white;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-group .form-control:focus,
    .form-group .form-select:focus {
        border-color: #5864FF;
        outline: none;
        box-shadow: 0 0 0 3px rgba(88, 100, 255, 0.1);
    }
    .form-group .form-control::placeholder {
        color: #9ca3af;
    }
    .form-group .form-hint {
        font-size: 11px;
        color: #9ca3af;
        margin-top: 4px;
    }

    .form-check-custom {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 14px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        transition: all 0.2s;
        cursor: pointer;
    }
    .form-check-custom:hover {
        background: #f1f2f4;
        border-color: #d1d5db;
    }
    .form-check-custom .form-check-input {
        width: 40px;
        height: 20px;
        cursor: pointer;
        accent-color: #5864FF;
        flex-shrink: 0;
    }
    .form-check-custom .form-check-label {
        cursor: pointer;
        flex: 1;
    }
    .form-check-custom .form-check-label .label-title {
        font-size: 13px;
        font-weight: 600;
        color: #1f2937;
    }
    .form-check-custom .form-check-label .label-desc {
        font-size: 12px;
        color: #9ca3af;
        display: block;
    }

    .btn-save {
        padding: 8px 24px;
        background: #5864FF;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-save:hover { background: #4356E6; }
    .btn-save:disabled { opacity: 0.6; cursor: not-allowed; }

    .btn-secondary-outline {
        padding: 8px 20px;
        background: transparent;
        color: #6b7280;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-secondary-outline:hover { background: #f3f4f6; }

    .btn-danger-outline {
        padding: 8px 20px;
        background: transparent;
        color: #dc2626;
        border: 1px solid #fecaca;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-danger-outline:hover { background: #fee2e2; }

    .settings-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
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
        max-width: 450px;
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
    .modal-body .modal-icon {
        font-size: 48px;
        text-align: center;
        margin-bottom: 16px;
    }
    .modal-body .modal-text {
        text-align: center;
        color: #6b7280;
        font-size: 14px;
        line-height: 1.6;
    }
    .modal-body .modal-text strong { color: #1f2937; }
    .modal-actions {
        display: flex;
        gap: 12px;
        margin-top: 20px;
        justify-content: center;
        flex-wrap: wrap;
    }
    .modal-actions .btn-primary {
        padding: 8px 24px;
        background: #5864FF;
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }
    .modal-actions .btn-primary:hover { background: #4356E6; }
    .modal-actions .btn-secondary {
        padding: 8px 24px;
        background: transparent;
        color: #6b7280;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .modal-actions .btn-secondary:hover { background: #f3f4f6; }
    .modal-actions .btn-danger {
        padding: 8px 24px;
        background: #dc2626;
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }
    .modal-actions .btn-danger:hover { background: #b91c1c; }

    @media (max-width: 992px) {
        .settings-grid {
            grid-template-columns: 1fr;
        }
    }
    @media (max-width: 768px) {
        .settings-grid {
            grid-template-columns: 1fr;
        }
        .modal-box { max-width: 100%; margin: 10px; }
        .form-check-custom { flex-wrap: wrap; }
    }
</style>

<div class="row">
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="stat-icon green"><i class="ti ti-wifi-2"></i></div>
                <div class="stat-label">Connection Status</div>
                <div class="stat-value text-connected" id="connection-status">Online</div>
                <span class="stat-badge" style="background: #dcfce7; color: #166534;">Connected</span>
                <div class="stat-description">Router reachable</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="stat-icon orange"><i class="ti ti-clock"></i></div>
                <div class="stat-label">Log Retention</div>
                <div class="stat-value text-pending" id="retention-days">30</div>
                <span class="stat-badge" style="background: #fef3c7; color: #92400e;">Days</span>
                <div class="stat-description">Logs stored</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="stat-icon blue"><i class="ti ti-shield-check"></i></div>
                <div class="stat-label">Active Rules</div>
                <div class="stat-value text-rules" id="active-rules">12</div>
                <span class="stat-badge" style="background: #dbeafe; color: #0c4a6e;">Enforced</span>
                <div class="stat-description">Mitigation rules active</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="stat-icon purple"><i class="ti ti-heart"></i></div>
                <div class="stat-label">System Health</div>
                <div class="stat-value text-health" id="system-health">98%</div>
                <span class="stat-badge" style="background: #dcfce7; color: #166534;">Healthy</span>
                <div class="stat-description">All systems operational</div>
            </div>
        </div>
    </div>
</div>

<div class="settings-grid">
    <div class="settings-section">
        <div class="section-title">
            <span class="title-icon"><i class="ti ti-shield"></i></span>
            Detection Settings
        </div>
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Attack Score Threshold <span class="required">*</span></label>
                    <input type="number" class="form-control" id="score-threshold" value="85" min="0" max="100">
                    <div class="form-hint">Scores above this threshold trigger mitigation actions (0-100)</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Auto-Refresh Interval</label>
                    <select class="form-select" id="refresh-interval">
                        <option value="5">5 seconds</option>
                        <option value="10">10 seconds</option>
                        <option value="30" selected>30 seconds</option>
                        <option value="60">60 seconds</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Time Window for Analysis</label>
                    <select class="form-select" id="time-window">
                        <option value="15">15 minutes</option>
                        <option value="60" selected>1 hour</option>
                        <option value="360">6 hours</option>
                        <option value="1440">24 hours</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Alert Sensitivity</label>
                    <select class="form-select" id="alert-sensitivity">
                        <option value="low">Low (fewer alerts)</option>
                        <option value="medium" selected>Medium (balanced)</option>
                        <option value="high">High (more alerts)</option>
                    </select>
                </div>
                <div class="form-check-custom">
                    <input class="form-check-input" type="checkbox" id="auto-mitigation" checked>
                    <label class="form-check-label" for="auto-mitigation">
                        <span class="label-title">Automatic Mitigation</span>
                        <span class="label-desc">Enable automatic mitigation when score is critical</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="settings-section">
        <div class="section-title">
            <span class="title-icon"><i class="ti ti-router"></i></span>
            Router Connection
        </div>
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Router Host <span class="required">*</span></label>
                    <input type="text" class="form-control" id="router-host" value="172.16.0.1">
                    <div class="form-hint">IP address or hostname of the router</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Router Port</label>
                    <input type="number" class="form-control" id="router-port" value="22" min="1" max="65535">
                </div>
                <div class="form-group">
                    <label class="form-label">Log Source</label>
                    <select class="form-select" id="log-source">
                        <option value="both" selected>Router logs and flow records</option>
                        <option value="logs">Router logs only</option>
                        <option value="flows">Flow records only</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Notification Email</label>
                    <input type="email" class="form-control" id="notification-email" value="keanosy0319@gmail.com">
                    <div class="form-hint">Alerts will be sent to this email address</div>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <button class="btn-save" onclick="saveSettings()"><i class="ti ti-device-floppy"></i> Save Settings</button>
                    <button class="btn-secondary-outline" onclick="testConnection()"><i class="ti ti-plug-connected"></i> Test Connection</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="settings-section">
            <div class="section-title">
                <span class="title-icon"><i class="ti ti-settings"></i></span>
                System Preferences
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="form-check-custom">
                        <input class="form-check-input" type="checkbox" id="notify-high" checked>
                        <label class="form-check-label" for="notify-high">
                            <span class="label-title">Notify on High Severity Attacks</span>
                            <span class="label-desc">Send alert when attacks are classified as high or critical</span>
                        </label>
                    </div>
                    <div class="form-check-custom" style="margin-top: 10px;">
                        <input class="form-check-input" type="checkbox" id="keep-history" checked>
                        <label class="form-check-label" for="keep-history">
                            <span class="label-title">Keep Mitigation History</span>
                            <span class="label-desc">Store applied rule records for later review</span>
                        </label>
                    </div>
                    <div class="form-check-custom" style="margin-top: 10px;">
                        <input class="form-check-input" type="checkbox" id="manual-acl">
                        <label class="form-check-label" for="manual-acl">
                            <span class="label-title">Manual Approval for ACL Updates</span>
                            <span class="label-desc">Queue firewall rule changes before deployment</span>
                        </label>
                    </div>
                    <div class="form-check-custom" style="margin-top: 10px;">
                        <input class="form-check-input" type="checkbox" id="ml-auto-train" checked>
                        <label class="form-check-label" for="ml-auto-train">
                            <span class="label-title">Auto-Train ML Models</span>
                            <span class="label-desc">Automatically retrain detection models with new attack data</span>
                        </label>
                    </div>
                    <div class="form-check-custom" style="margin-top: 10px;">
                        <input class="form-check-input" type="checkbox" id="detailed-logs">
                        <label class="form-check-label" for="detailed-logs">
                            <span class="label-title">Enable Detailed Logging</span>
                            <span class="label-desc">Record verbose system events for debugging</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="settings-section">
            <div class="section-title">
                <span class="title-icon"><i class="ti ti-database"></i></span>
                Data & Storage
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Log Retention Period</label>
                        <select class="form-select" id="log-retention">
                            <option value="7">7 days</option>
                            <option value="14">14 days</option>
                            <option value="30" selected>30 days</option>
                            <option value="60">60 days</option>
                            <option value="90">90 days</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Storage Location</label>
                        <input type="text" class="form-control" id="storage-location" value="/var/log/ddos-detector" readonly>
                        <div class="form-hint">Current storage path for all system logs</div>
                    </div>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <button class="btn-secondary-outline" onclick="clearCache()"><i class="ti ti-trash"></i> Clear Cache</button>
                        <button class="btn-secondary-outline" onclick="exportConfig()"><i class="ti ti-download"></i> Export Config</button>
                        <button class="btn-danger-outline" onclick="resetDefaults()"><i class="ti ti-refresh"></i> Reset to Defaults</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-overlay" id="confirm-modal" onclick="if(event.target===this) closeModal('confirm-modal')">
    <div class="modal-box">
        <div class="modal-header">
            <h4 id="confirm-title">Confirm Action</h4>
            <button class="modal-close" onclick="closeModal('confirm-modal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="modal-icon" id="confirm-icon">⚠️</div>
            <div class="modal-text" id="confirm-text">Are you sure you want to proceed?</div>
            <div class="modal-actions">
                <button class="btn-primary" id="confirm-yes" onclick="confirmAction()">Yes</button>
                <button class="btn-secondary" onclick="closeModal('confirm-modal')">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal-overlay" id="result-modal" onclick="if(event.target===this) closeModal('result-modal')">
    <div class="modal-box">
        <div class="modal-header">
            <h4 id="result-title">Result</h4>
            <button class="modal-close" onclick="closeModal('result-modal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="modal-icon" id="result-icon">✅</div>
            <div class="modal-text" id="result-text">Operation completed successfully.</div>
            <div class="modal-actions">
                <button class="btn-primary" onclick="closeModal('result-modal')">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
    let confirmCallback = null;

    function saveSettings() {
        const data = {
            scoreThreshold: document.getElementById('score-threshold').value,
            refreshInterval: document.getElementById('refresh-interval').value,
            timeWindow: document.getElementById('time-window').value,
            sensitivity: document.getElementById('alert-sensitivity').value,
            autoMitigation: document.getElementById('auto-mitigation').checked,
            routerHost: document.getElementById('router-host').value,
            routerPort: document.getElementById('router-port').value,
            logSource: document.getElementById('log-source').value,
            notificationEmail: document.getElementById('notification-email').value,
            notifyHigh: document.getElementById('notify-high').checked,
            keepHistory: document.getElementById('keep-history').checked,
            manualAcl: document.getElementById('manual-acl').checked,
            mlAutoTrain: document.getElementById('ml-auto-train').checked,
            detailedLogs: document.getElementById('detailed-logs').checked,
            logRetention: document.getElementById('log-retention').value
        };
        console.log('Settings saved:', data);
        showResultToast('Settings saved successfully!', 'success');
        updateStatCards();
    }

    function testConnection() {
        const host = document.getElementById('router-host').value || '172.16.0.1';
        const port = document.getElementById('router-port').value || 22;
        showResultModal(
            'Testing Connection',
            '🔄',
            `Connecting to <strong>${host}:${port}</strong>...<br><br>Checking router availability...`
        );
        setTimeout(() => {
            closeModal('result-modal');
            const success = Math.random() > 0.2;
            if (success) {
                showResultModal(
                    'Connection Successful',
                    '✅',
                    `Successfully connected to <strong>${host}:${port}</strong>.<br><br>Router is online and responding to requests.`
                );
                document.getElementById('connection-status').textContent = 'Online';
                const badge = document.querySelector('.stat-card:first-child .stat-badge');
                if (badge) {
                    badge.textContent = 'Connected';
                    badge.style.background = '#dcfce7';
                    badge.style.color = '#166534';
                }
            } else {
                showResultModal(
                    'Connection Failed',
                    '❌',
                    `Failed to connect to <strong>${host}:${port}</strong>.<br><br>Please check the router host and port settings.`
                );
                document.getElementById('connection-status').textContent = 'Offline';
                const badge = document.querySelector('.stat-card:first-child .stat-badge');
                if (badge) {
                    badge.textContent = 'Failed';
                    badge.style.background = '#fee2e2';
                    badge.style.color = '#7f1d1d';
                }
            }
        }, 2000);
    }

    function clearCache() {
        showConfirmModal(
            'Clear Cache',
            '🗑️',
            'This will delete all temporary cache files and system logs.<br><br><strong>This action cannot be undone.</strong>',
            function() {
                showResultToast('Cache cleared successfully!', 'success');
                closeModal('confirm-modal');
            }
        );
    }

    function exportConfig() {
        const config = {
            version: '1.0',
            timestamp: new Date().toISOString(),
            settings: {
                scoreThreshold: document.getElementById('score-threshold').value,
                refreshInterval: document.getElementById('refresh-interval').value,
                timeWindow: document.getElementById('time-window').value,
                sensitivity: document.getElementById('alert-sensitivity').value,
                autoMitigation: document.getElementById('auto-mitigation').checked,
                routerHost: document.getElementById('router-host').value,
                routerPort: document.getElementById('router-port').value,
                logSource: document.getElementById('log-source').value,
                notificationEmail: document.getElementById('notification-email').value,
                notifyHigh: document.getElementById('notify-high').checked,
                keepHistory: document.getElementById('keep-history').checked,
                manualAcl: document.getElementById('manual-acl').checked,
                mlAutoTrain: document.getElementById('ml-auto-train').checked,
                detailedLogs: document.getElementById('detailed-logs').checked,
                logRetention: document.getElementById('log-retention').value
            }
        };
        const blob = new Blob([JSON.stringify(config, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `ddos_detector_config_${new Date().toISOString().slice(0,10)}.json`;
        a.click();
        URL.revokeObjectURL(url);
        showResultToast('Configuration exported successfully!', 'success');
    }

    function resetDefaults() {
        showConfirmModal(
            'Reset to Defaults',
            '⚠️',
            'This will reset all settings to their default values.<br><br><strong>This action cannot be undone.</strong>',
            function() {
                document.getElementById('score-threshold').value = '85';
                document.getElementById('refresh-interval').value = '30';
                document.getElementById('time-window').value = '60';
                document.getElementById('alert-sensitivity').value = 'medium';
                document.getElementById('auto-mitigation').checked = true;
                document.getElementById('router-host').value = '172.16.0.1';
                document.getElementById('router-port').value = '22';
                document.getElementById('log-source').value = 'both';
                document.getElementById('notification-email').value = 'keanosy0319@gmail.com';
                document.getElementById('notify-high').checked = true;
                document.getElementById('keep-history').checked = true;
                document.getElementById('manual-acl').checked = false;
                document.getElementById('ml-auto-train').checked = true;
                document.getElementById('detailed-logs').checked = false;
                document.getElementById('log-retention').value = '30';
                showResultToast('Settings reset to defaults!', 'success');
                updateStatCards();
                closeModal('confirm-modal');
            }
        );
    }

    function showConfirmModal(title, icon, text, callback) {
        document.getElementById('confirm-title').textContent = title;
        document.getElementById('confirm-icon').textContent = icon;
        document.getElementById('confirm-text').innerHTML = text;
        confirmCallback = callback;
        openModal('confirm-modal');
    }

    function confirmAction() {
        if (typeof confirmCallback === 'function') {
            confirmCallback();
        }
        confirmCallback = null;
    }

    function showResultModal(title, icon, text) {
        document.getElementById('result-title').textContent = title;
        document.getElementById('result-icon').textContent = icon;
        document.getElementById('result-text').innerHTML = text;
        openModal('result-modal');
    }

    function showResultToast(message, type) {
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

    function openModal(id) {
        document.getElementById(id).classList.add('show');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
        if (id === 'confirm-modal') {
            confirmCallback = null;
        }
    }

    function updateStatCards() {
        const retention = document.getElementById('log-retention').value;
        document.getElementById('retention-days').textContent = retention;
        const rules = Math.floor(Math.random() * 20) + 5;
        document.getElementById('active-rules').textContent = rules;
        const health = Math.floor(Math.random() * 10) + 90;
        document.getElementById('system-health').textContent = health + '%';
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (document.getElementById('confirm-modal').classList.contains('show')) {
                closeModal('confirm-modal');
            }
            if (document.getElementById('result-modal').classList.contains('show')) {
                closeModal('result-modal');
            }
        }
    });

    document.querySelectorAll('.form-control, .form-select, .form-check-input').forEach(function(el) {
        el.addEventListener('change', function() {
            if (this.closest('.form-check-custom')) {
                const isChecked = this.checked;
                const parent = this.closest('.form-check-custom');
                if (isChecked) {
                    parent.style.borderColor = '#5864FF';
                    parent.style.background = '#f0f1ff';
                } else {
                    parent.style.borderColor = '#e5e7eb';
                    parent.style.background = '#f8f9fa';
                }
            }
        });
    });

    document.querySelectorAll('.form-check-custom .form-check-input').forEach(function(el) {
        if (el.checked) {
            el.closest('.form-check-custom').style.borderColor = '#5864FF';
            el.closest('.form-check-custom').style.background = '#f0f1ff';
        }
    });
</script>
@endsection