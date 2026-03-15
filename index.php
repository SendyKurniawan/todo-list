<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>remind·do — subtask detail view</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
        }

        body {
            background: linear-gradient(145deg, #e8eef7 0%, #cddaea 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            margin: 0;
        }

        .card {
            max-width: 900px;
            width: 100%;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 2.8rem;
            box-shadow: 0 25px 45px -12px rgba(18, 38, 63, 0.35);
            padding: 2rem 2.2rem 2.8rem;
            border: 1px solid rgba(255, 255, 255, 0.7);
        }

        h1 {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            font-weight: 640;
            font-size: 2.1rem;
            letter-spacing: -0.02em;
            color: #14273e;
            margin-bottom: 0.2rem;
        }

        h1 .badge {
            background: #1e3b5c;
            color: white;
            font-size: 1.2rem;
            padding: 0.2rem 1.2rem;
            border-radius: 40px;
        }

        .sub {
            color: #25415f;
            font-weight: 400;
            font-size: 1rem;
            margin-left: 0.3rem;
            margin-bottom: 1.8rem;
            border-left: 4px solid #6f94c0;
            padding-left: 1.2rem;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 0 20px 20px 0;
        }

        /* stats panel */
        .stats-panel {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: space-between;
            background: rgba(255, 255, 255, 0.6);
            padding: 0.9rem 1.8rem;
            border-radius: 60px;
            margin-bottom: 2rem;
            backdrop-filter: blur(4px);
            font-weight: 500;
            color: #142f4b;
            border: 1px solid rgba(255, 255, 255, 0.9);
        }

        .stat-badge {
            background: #ffffffba;
            padding: 0.3rem 1.2rem;
            border-radius: 40px;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .stat-badge .count {
            background: #1e3b5c;
            color: white;
            border-radius: 30px;
            padding: 0.1rem 0.8rem;
        }

        /* ---- input row (common) ---- */
        .input-row {
            display: flex;
            gap: 0.8rem;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }

        .input-row input {
            flex: 1 1 250px;
            background: white;
            border: none;
            padding: 1rem 1.5rem;
            border-radius: 60px;
            font-size: 1rem;
            box-shadow: inset 0 3px 10px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.9);
        }

        .input-row input:focus {
            outline: 2px solid #7a9bcb;
        }

        .input-row button {
            background: #1e3b5c;
            border: none;
            color: white;
            font-weight: 600;
            padding: 0 2.5rem;
            border-radius: 60px;
            font-size: 1.05rem;
            cursor: pointer;
            box-shadow: 0 8px 16px -6px #1e3b5c;
            transition: 0.15s;
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }

        .input-row button:hover {
            background: #0f2940;
            transform: scale(1.01);
        }

        /* filter bar */
        .filter-bar {
            display: flex;
            gap: 0.6rem;
            margin: 1rem 0 1.8rem;
            flex-wrap: wrap;
        }

        .filter-btn {
            background: rgba(255, 255, 255, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.8);
            padding: 0.5rem 1.6rem;
            border-radius: 40px;
            font-weight: 500;
            color: #1a3652;
            cursor: pointer;
            backdrop-filter: blur(4px);
        }

        .filter-btn.active {
            background: #1e3b5c;
            color: white;
            border-color: white;
            box-shadow: 0 6px 12px #1e3b5c70;
        }

        .filter-btn.danger {
            background: #a13d3d;
            color: white;
        }

        /* ----- todo list (master & sub) ----- */
        .todo-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.7rem;
            margin-top: 0.8rem;
            margin-bottom: 1.5rem;
            max-height: 350px;
            overflow-y: auto;
            padding-right: 5px;
        }

        .todo-item {
            background: white;
            border-radius: 50px;
            padding: 0.1rem 0.1rem 0.1rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 5px 12px rgba(0, 0, 0, 0.03);
            border: 2px solid rgba(255, 255, 255, 1);
            transition: all 0.1s;
            cursor: pointer;
        }

        .todo-item:hover {
            background: #f3f9ff;
            border-color: #b3c9e9;
        }

        .todo-check {
            width: 26px;
            height: 26px;
            accent-color: #1e3b5c;
            cursor: pointer;
            pointer-events: auto;
            z-index: 2;
        }

        .todo-text {
            flex: 1;
            font-size: 1.1rem;
            padding: 0.7rem 0;
            color: #0b233c;
            word-break: break-word;
            pointer-events: none;
        }

        .todo-item.completed .todo-text {
            text-decoration: line-through;
            color: #7b8da8;
        }

        .delete-btn {
            background: none;
            border: none;
            font-size: 1.7rem;
            color: #96a9c2;
            cursor: pointer;
            padding: 0.2rem 1.2rem 0.2rem 0.3rem;
            border-radius: 0 50px 50px 0;
            transition: 0.1s;
            font-weight: 300;
            pointer-events: auto;
        }

        .delete-btn:hover {
            color: #c03c3c;
            background: #ffe7e7;
        }

        .empty-message {
            text-align: center;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 100px;
            color: #30557a;
            font-style: italic;
            border: 2px dashed #b1c9e6;
        }

        /* back link */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            background: #1e3b5c;
            color: white;
            padding: 0.5rem 2rem;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.6);
        }

        .back-link:hover {
            background: #102b41;
        }

        /* detail header for sublist */
        .sublist-header {
            background: #e5effb;
            border-radius: 50px;
            padding: 0.8rem 2rem;
            margin: 1.2rem 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.8rem;
            border: 1px solid white;
        }

        .master-task-title {
            font-weight: 600;
            color: #03223f;
            background: white;
            padding: 0.3rem 1.5rem;
            border-radius: 40px;
        }

        .clear-sub-btn {
            background: transparent;
            border: 2px solid #a3b9d6;
            padding: 0.3rem 1.5rem;
            border-radius: 40px;
            font-weight: 500;
            color: #1e3b5c;
            cursor: pointer;
        }

        .clear-sub-btn:hover {
            background: #1e3b5c;
            color: white;
            border-color: #1e3b5c;
        }

        /* detail view for subtask - ENHANCED */
        .detail-card {
            background: rgba(255, 255, 255, 0.7);
            border-radius: 50px;
            padding: 2rem;
            margin: 1.5rem 0;
            border: 2px solid white;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }

        .detail-field {
            display: flex;
            align-items: baseline;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px dashed #a0b8d5;
        }

        .detail-field:last-of-type {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #1d3b60;
            min-width: 120px;
            font-size: 1.1rem;
        }

        .detail-value {
            background: white;
            padding: 0.5rem 1.8rem;
            border-radius: 40px;
            color: #0a253f;
            word-break: break-word;
            flex: 1;
            box-shadow: inset 0 1px 4px #0000000d;
        }

        .detail-value.description {
            white-space: pre-wrap;
            line-height: 1.5;
        }

        .detail-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .edit-form {
            background: white;
            padding: 2rem;
            border-radius: 40px;
            margin-top: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #1d3b60;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.8rem 1.5rem;
            border: 2px solid #e2e8f0;
            border-radius: 30px;
            font-size: 1rem;
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .footer-note {
            margin-top: 1.5rem;
            text-align: right;
            color: #3b5e83;
            font-size: 0.85rem;
        }

        .priority-badge {
            font-size: 0.8rem;
            padding: 0.2rem 0.8rem;
            border-radius: 30px;
            background: #e2e8f0;
            margin-left: 0.5rem;
        }

        .priority-high {
            background: #fecaca;
            color: #991b1b;
        }

        .priority-medium {
            background: #fed7aa;
            color: #92400e;
        }

        .priority-low {
            background: #d1fae5;
            color: #065f46;
        }
    </style>
</head>

<body>
    <div id="app" class="card"></div>

    <script>
        (function() {
            // ---------- data model with detailed subtasks ----------
            let masterTasks = [];

            // load & save
            function loadData() {
                const stored = localStorage.getItem('nestedTodoDetailViewEnhanced');
                if (stored) {
                    try {
                        masterTasks = JSON.parse(stored);
                        // ensure structure with new fields
                        masterTasks = masterTasks.map(t => ({
                            id: t.id || Date.now() + Math.random(),
                            text: t.text || 'untitled',
                            completed: !!t.completed,
                            subtasks: Array.isArray(t.subtasks) ? t.subtasks.map(st => ({
                                id: st.id || Date.now() + Math.random(),
                                text: st.text || 'subtask',
                                completed: !!st.completed,
                                description: st.description || '',
                                dueDate: st.dueDate || '',
                                priority: st.priority || 'medium',
                                notes: st.notes || '',
                                createdAt: st.createdAt || new Date().toISOString()
                            })) : []
                        }));
                    } catch (e) {
                        masterTasks = [];
                    }
                }
            }

            function saveData() {
                localStorage.setItem('nestedTodoDetailViewEnhanced', JSON.stringify(masterTasks));
            }

            // ----- routing -----
            let currentRoute = 'list';
            let selectedMasterId = null;
            let selectedSubId = null;

            function navigateToList() {
                currentRoute = 'list';
                selectedMasterId = null;
                selectedSubId = null;
                window.location.hash = '';
                renderApp();
            }

            function navigateToSubList(masterId) {
                const master = masterTasks.find(m => m.id == masterId);
                if (!master) {
                    navigateToList();
                    return;
                }
                currentRoute = 'detail';
                selectedMasterId = master.id;
                selectedSubId = null;
                window.location.hash = `sub-${master.id}`;
                renderApp();
            }

            function navigateToSubtaskDetail(masterId, subId) {
                const master = masterTasks.find(m => m.id == masterId);
                if (!master) {
                    navigateToList();
                    return;
                }
                const sub = master.subtasks.find(s => s.id == subId);
                if (!sub) {
                    navigateToSubList(masterId);
                    return;
                }

                currentRoute = 'subtaskDetail';
                selectedMasterId = master.id;
                selectedSubId = sub.id;
                window.location.hash = `subtask-${masterId}-${subId}`;
                renderApp();
            }

            // hash change handling
            function handleHash() {
                const hash = window.location.hash.slice(1);

                if (hash.startsWith('subtask-')) {
                    const parts = hash.replace('subtask-', '').split('-');
                    if (parts.length >= 2) {
                        const masterId = parts[0];
                        const subId = parts[1];
                        const master = masterTasks.find(m => m.id == masterId);
                        if (master && master.subtasks.find(s => s.id == subId)) {
                            currentRoute = 'subtaskDetail';
                            selectedMasterId = masterId;
                            selectedSubId = subId;
                            renderApp();
                            return;
                        }
                    }
                    currentRoute = 'list';
                    selectedMasterId = null;
                    selectedSubId = null;
                } else if (hash.startsWith('sub-')) {
                    const idPart = hash.replace('sub-', '');
                    const master = masterTasks.find(m => m.id == idPart);
                    if (master) {
                        currentRoute = 'detail';
                        selectedMasterId = master.id;
                        selectedSubId = null;
                    } else {
                        currentRoute = 'list';
                        selectedMasterId = null;
                        selectedSubId = null;
                    }
                } else {
                    currentRoute = 'list';
                    selectedMasterId = null;
                    selectedSubId = null;
                }
                renderApp();
            }

            // ----- filter for main list -----
            let mainFilter = 'all';

            // ----- helper: escape html -----
            function escapeHtml(unsafe) {
                return unsafe.replace(/[&<>"']/g, function(m) {
                    if (m === '&') return '&amp;';
                    if (m === '<') return '&lt;';
                    if (m === '>') return '&gt;';
                    if (m === '"') return '&quot;';
                    return '&#039;';
                });
            }

            // ----- helper: format date -----
            function formatDate(dateString) {
                if (!dateString) return 'Not set';
                try {
                    const date = new Date(dateString);
                    return date.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });
                } catch {
                    return dateString;
                }
            }

            // ----- helper: get priority class -----
            function getPriorityClass(priority) {
                switch (priority) {
                    case 'high':
                        return 'priority-high';
                    case 'medium':
                        return 'priority-medium';
                    case 'low':
                        return 'priority-low';
                    default:
                        return '';
                }
            }

            // ----- render master list page -----
            function renderMasterPage() {
                let filtered = masterTasks;
                if (mainFilter === 'active') filtered = masterTasks.filter(t => !t.completed);
                if (mainFilter === 'completed') filtered = masterTasks.filter(t => t.completed);

                const total = masterTasks.length;
                const done = masterTasks.filter(t => t.completed).length;
                const left = total - done;

                let listHtml = '';
                if (filtered.length === 0) {
                    listHtml = `<li class="empty-message">➕ create a master task</li>`;
                } else {
                    filtered.forEach(m => {
                        const subtaskPreview = m.subtasks.length ? ` (${m.subtasks.filter(s => s.completed).length}/${m.subtasks.length} sub)` : '';
                        listHtml += `
                            <li class="todo-item ${m.completed ? 'completed' : ''}" data-id="${m.id}" data-master>
                                <input type="checkbox" class="todo-check" ${m.completed ? 'checked' : ''} data-id="${m.id}">
                                <span class="todo-text">${escapeHtml(m.text)}${escapeHtml(subtaskPreview)}</span>
                                <button class="delete-btn" data-id="${m.id}">✕</button>
                            </li>
                        `;
                    });
                }

                return `
                    <div>
                        <h1>📋 remind·do <span class="badge">master</span></h1>
                        <div class="sub">main tasks — each with 12 detailed subtasks</div>

                        <div class="stats-panel">
                            <span class="stat-badge">🧾 total <span class="count">${total}</span></span>
                            <span class="stat-badge">✅ done <span class="count">${done}</span></span>
                            <span class="stat-badge">⏳ left <span class="count">${left}</span></span>
                        </div>

                        <div class="input-row">
                            <input type="text" id="master-input" placeholder="e.g. 'vacation planning'" autofocus>
                            <button id="add-master-btn">➕ Add main + 12 subtasks</button>
                        </div>

                        <div class="filter-bar">
                            <button class="filter-btn ${mainFilter === 'all' ? 'active' : ''}" id="filter-all">All</button>
                            <button class="filter-btn ${mainFilter === 'active' ? 'active' : ''}" id="filter-active">Active</button>
                            <button class="filter-btn ${mainFilter === 'completed' ? 'active' : ''}" id="filter-completed">Completed</button>
                        </div>

                        <ul class="todo-list" id="master-list">
                            ${listHtml}
                        </ul>

                        <div style="display:flex; gap:1rem; justify-content:flex-end; margin-top:1rem;">
                            <button id="clear-completed-master" class="filter-btn" style="background:transparent;">✔ Clear completed main</button>
                            <button id="clear-all-master" class="filter-btn" style="background:transparent;">🗑️ Clear all main</button>
                        </div>
                        <footer>⬇️ click a main task to see its 12 detailed subtasks</footer>
                    </div>
                `;
            }

            // ----- render subtask list page -----
            function renderSubListPage(master) {
                const subTasks = master.subtasks || [];

                const subTotal = subTasks.length;
                const subDone = subTasks.filter(s => s.completed).length;
                const subLeft = subTotal - subDone;

                let subListHtml = '';
                if (subTasks.length === 0) {
                    subListHtml = `<li class="empty-message">✨ no subtasks — you can add more</li>`;
                } else {
                    subTasks.forEach(s => {
                        const priorityClass = getPriorityClass(s.priority);
                        subListHtml += `
                            <li class="todo-item ${s.completed ? 'completed' : ''}" data-subid="${s.id}">
                                <input type="checkbox" class="todo-check" ${s.completed ? 'checked' : ''} data-subid="${s.id}">
                                <span class="todo-text">
                                    ${escapeHtml(s.text)}
                                    <span class="priority-badge ${priorityClass}">${s.priority || 'medium'}</span>
                                </span>
                                <button class="delete-btn" data-subid="${s.id}">✕</button>
                            </li>
                        `;
                    });
                }

                return `
                    <div>
                        <a href="#" class="back-link" id="back-to-main">← back to master</a>
                        <h1>📋 sub tasks <span class="badge">12 detailed</span></h1>
                        <div class="sublist-header">
                            <span class="master-task-title">🗂️ ${escapeHtml(master.text)}</span>
                            <button id="clear-sub-completed" class="clear-sub-btn">✔ clear done subtasks</button>
                        </div>

                        <div class="stats-panel" style="margin-bottom:1.2rem;">
                            <span class="stat-badge">📋 sub total <span class="count" id="sub-total">${subTotal}</span></span>
                            <span class="stat-badge">✅ sub done <span class="count" id="sub-done">${subDone}</span></span>
                            <span class="stat-badge">⏳ sub left <span class="count" id="sub-left">${subLeft}</span></span>
                        </div>

                        <div class="input-row">
                            <input type="text" id="sub-input" placeholder="extra subtask">
                            <button id="add-sub-btn">➕ Add more</button>
                        </div>

                        <ul class="todo-list" id="sub-list">
                            ${subListHtml}
                        </ul>

                        <div style="display:flex; gap:1rem; justify-content:space-between; margin-top:1rem;">
                            <button id="delete-current-master" class="filter-btn danger">🗑️ delete main task</button>
                            <span style="color:#666;">⚡ click any subtask for full details</span>
                        </div>
                    </div>
                `;
            }

            // ----- render ENHANCED subtask DETAIL page with all fields -----
            function renderSubtaskDetailPage(master, sub) {
                const priorityClass = getPriorityClass(sub.priority);

                return `
                    <div>
                        <a href="#" class="back-link" id="back-to-sublist">← back to subtasks</a>
                        <h1>📋 subtask detail <span class="badge">${sub.text.substring(0, 20)}</span></h1>
                        
                        <div class="detail-card">
                            <div class="detail-field">
                                <span class="detail-label">Title</span>
                                <span class="detail-value">${escapeHtml(sub.text)}</span>
                            </div>
                            
                            <div class="detail-field">
                                <span class="detail-label">Description</span>
                                <span class="detail-value description">${escapeHtml(sub.description) || 'No description'}</span>
                            </div>
                            
                            <div class="detail-field">
                                <span class="detail-label">Status</span>
                                <span class="detail-value" id="subtask-status">
                                    ${sub.completed ? '✅ completed' : '⏳ active'}
                                </span>
                            </div>
                            
                            <div class="detail-field">
                                <span class="detail-label">Priority</span>
                                <span class="detail-value">
                                    <span class="priority-badge ${priorityClass}">${sub.priority || 'medium'}</span>
                                </span>
                            </div>
                            
                            <div class="detail-field">
                                <span class="detail-label">Due Date</span>
                                <span class="detail-value">${formatDate(sub.dueDate)}</span>
                            </div>
                            
                            <div class="detail-field">
                                <span class="detail-label">Notes</span>
                                <span class="detail-value description">${escapeHtml(sub.notes) || 'No additional notes'}</span>
                            </div>
                            
                            <div class="detail-field">
                                <span class="detail-label">Created</span>
                                <span class="detail-value">${formatDate(sub.createdAt)}</span>
                            </div>
                            
                            <div class="detail-field">
                                <span class="detail-label">Parent Task</span>
                                <span class="detail-value">${escapeHtml(master.text)}</span>
                            </div>
                            
                            <div class="detail-field">
                                <span class="detail-label">Subtask ID</span>
                                <span class="detail-value"><small>${sub.id}</small></span>
                            </div>
                            
                            <div class="detail-actions">
                                <button id="toggle-subtask-btn" class="filter-btn" style="background:#1e3b5c; color:white; border:none;">
                                    ${sub.completed ? '↩️ mark as not done' : '✅ mark as done'}
                                </button>
                                <button id="edit-subtask-btn" class="filter-btn" style="background:#4a5568; color:white; border:none;">
                                    ✏️ edit details
                                </button>
                                <button id="delete-subtask-from-detail" class="filter-btn danger" style="border:none;">
                                    🗑️ delete subtask
                                </button>
                            </div>
                        </div>

                        <!-- Edit form -->
                        <div id="edit-form" class="edit-form" style="display:none;">
                            <h3 style="margin-bottom:1.5rem; color:#1d3b60;">Edit Subtask Details</h3>
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" id="edit-title" value="${escapeHtml(sub.text)}">
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea id="edit-description">${escapeHtml(sub.description || '')}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Priority</label>
                                <select id="edit-priority">
                                    <option value="low" ${sub.priority === 'low' ? 'selected' : ''}>Low</option>
                                    <option value="medium" ${sub.priority === 'medium' ? 'selected' : ''}>Medium</option>
                                    <option value="high" ${sub.priority === 'high' ? 'selected' : ''}>High</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Due Date</label>
                                <input type="date" id="edit-duedate" value="${sub.dueDate || ''}">
                            </div>
                            <div class="form-group">
                                <label>Notes</label>
                                <textarea id="edit-notes">${escapeHtml(sub.notes || '')}</textarea>
                            </div>
                            <div class="detail-actions">
                                <button id="save-edit-btn" class="filter-btn" style="background:#2d6a4f; color:white; border:none;">💾 save changes</button>
                                <button id="cancel-edit-btn" class="filter-btn" style="background:#718096; color:white; border:none;">✕ cancel</button>
                            </div>
                        </div>
                        
                        <footer>⚡ edit to modify subtask details</footer>
                    </div>
                `;
            }

            // ----- main render switch -----
            function renderApp() {
                const appDiv = document.getElementById('app');

                if (currentRoute === 'list') {
                    appDiv.innerHTML = renderMasterPage();
                    attachMasterEvents();
                } else if (currentRoute === 'detail') {
                    const master = masterTasks.find(m => m.id == selectedMasterId);
                    if (!master) {
                        navigateToList();
                        return;
                    }
                    appDiv.innerHTML = renderSubListPage(master);
                    attachSubListEvents(master);
                } else if (currentRoute === 'subtaskDetail') {
                    const master = masterTasks.find(m => m.id == selectedMasterId);
                    if (!master) {
                        navigateToList();
                        return;
                    }
                    const sub = master.subtasks.find(s => s.id == selectedSubId);
                    if (!sub) {
                        navigateToSubList(master.id);
                        return;
                    }

                    appDiv.innerHTML = renderSubtaskDetailPage(master, sub);
                    attachSubtaskDetailEvents(master, sub);
                }
            }

            // ----- master page events (with 12 pre-filled subtasks) -----
            function attachMasterEvents() {
                document.getElementById('add-master-btn')?.addEventListener('click', () => {
                    const inp = document.getElementById('master-input');
                    const text = inp.value.trim();
                    if (!text) return alert('enter main task');

                    // create new master with 12 detailed subtasks
                    const newMaster = {
                        id: Date.now() + Math.random(),
                        text: text,
                        completed: false,
                        subtasks: [{
                                id: Date.now() + 1 + Math.random(),
                                text: '🔸 BOOKING HOTEL',
                                completed: false,
                                description: 'TANYA KAK ITA',
                                priority: 'high',
                                notes: '1 。kak ita tolong cek room hotel ..... tanggal .. jan/des - .. jan/des (..N)\n.. room ( .. twin + .. king )\n 2 。 李总有空房，需要控吗？\n 3 。团号是什么？\n 4 。tanya kak ita send email cc nya kemana jika 李总 bilang oke',
                                createdAt: new Date().toISOString()
                            },
                            {
                                id: Date.now() + 2 + Math.random(),
                                text: '🔹 TUNGGU COD = 酒店确认期',
                                completed: false,
                                description: 'KASIH SS BOOKING KE KAK ITA',
                                priority: 'high',
                                notes: 'pas udah booking kasih ss ke kak ita',
                                createdAt: new Date().toISOString()
                            },
                            {
                                id: Date.now() + 3 + Math.random(),
                                text: '🔸 BUAT ARRIVAL PLAN',
                                completed: false,
                                description: '1 。tanya flight detail = 航班信息\n 2 。 brp PAX = 几位 ',
                                priority: 'high',
                                notes: 'Coordinate with airport transfers',
                                createdAt: new Date().toISOString()
                            },
                            {
                                id: Date.now() + 4 + Math.random(),
                                text: '🔹 DAPAT COD',
                                completed: false,
                                description: 'CEK EMAIL BALI GLOW UNTUK COD HOTEL',
                                dueDate: new Date(Date.now() + 17 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
                                priority: 'high',
                                notes: '1 。sudah hotel kirimkan email kasih tau 李总这个团的酒店确认期是。。月。。号\n2 。isi COD ARRIVAL PLAN',
                                createdAt: new Date().toISOString()
                            },
                            {
                                id: Date.now() + 5 + Math.random(),
                                text: '🔸 AGREEMENT = 合约书',
                                completed: false,
                                description: 'TUNGGU 李总 ATAU KASIH TAU SIAPA TAU LUPA',
                                dueDate: new Date(Date.now() + 21 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
                                priority: 'high',
                                notes: '1 。李总 dikte/ cek ',
                                createdAt: new Date().toISOString()
                            },
                            {
                                id: Date.now() + 6 + Math.random(),
                                text: '🔸 TANGGAL COD = 今天确认期',
                                completed: false,
                                description: 'LEBIH BAGUS 3 HARI SEBELUM COD TANYA 李总',
                                dueDate: new Date(Date.now() + 24 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
                                priority: 'high',
                                notes: '1 。这个团酒店确认了吗？',
                                createdAt: new Date().toISOString()
                            },
                            {
                                id: Date.now() + 7 + Math.random(),
                                text: '🔸 SEND HOTEL EMAIL FINAL',
                                completed: false,
                                description: 'Send final confirmation to hotel',
                                dueDate: new Date(Date.now() + 28 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
                                priority: 'medium',
                                notes: 'Include special requests',
                                createdAt: new Date().toISOString()
                            },
                            {
                                id: Date.now() + 8 + Math.random(),
                                text: '🔸 INVOICE',
                                completed: false,
                                description: 'Prepare and send invoices',
                                dueDate: new Date(Date.now() + 31 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
                                priority: 'high',
                                notes: 'Include payment terms',
                                createdAt: new Date().toISOString()
                            },
                            {
                                id: Date.now() + 9 + Math.random(),
                                text: '🔸 ITINENARY GUIDE',
                                completed: false,
                                description: 'Create detailed itinerary',
                                dueDate: new Date(Date.now() + 35 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
                                priority: 'medium',
                                notes: 'Include maps and contacts',
                                createdAt: new Date().toISOString()
                            },
                            {
                                id: Date.now() + 10 + Math.random(),
                                text: '🔸 NAME LIST = 名单',
                                completed: false,
                                description: 'Compile final name list',
                                dueDate: new Date(Date.now() + 38 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
                                priority: 'medium',
                                notes: 'Double-check spellings',
                                createdAt: new Date().toISOString()
                            },
                            {
                                id: Date.now() + 11 + Math.random(),
                                text: '🔸 PAPAN NAMA = 接机牌',
                                completed: false,
                                description: 'Prepare name signs for pickup',
                                dueDate: new Date(Date.now() + 42 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
                                priority: 'low',
                                notes: 'Clear printing',
                                createdAt: new Date().toISOString()
                            },
                            {
                                id: Date.now() + 12 + Math.random(),
                                text: '🔸 NAMA TL = 领队名号',
                                completed: false,
                                description: 'Assign team leaders and badges',
                                dueDate: new Date(Date.now() + 45 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
                                priority: 'medium',
                                notes: 'Select group leaders',
                                createdAt: new Date().toISOString()
                            }
                        ]
                    };
                    masterTasks.push(newMaster);
                    inp.value = '';
                    saveData();
                    renderApp();
                });

                document.getElementById('filter-all')?.addEventListener('click', () => {
                    mainFilter = 'all';
                    renderApp();
                });
                document.getElementById('filter-active')?.addEventListener('click', () => {
                    mainFilter = 'active';
                    renderApp();
                });
                document.getElementById('filter-completed')?.addEventListener('click', () => {
                    mainFilter = 'completed';
                    renderApp();
                });

                document.getElementById('clear-completed-master')?.addEventListener('click', () => {
                    masterTasks = masterTasks.filter(t => !t.completed);
                    saveData();
                    renderApp();
                });
                document.getElementById('clear-all-master')?.addEventListener('click', () => {
                    if (masterTasks.length && confirm('Delete all main tasks?')) {
                        masterTasks = [];
                        saveData();
                        renderApp();
                    }
                });

                document.querySelectorAll('#master-list .todo-item[data-master]').forEach(item => {
                    const id = item.dataset.id;
                    const chk = item.querySelector('.todo-check');
                    if (chk) {
                        chk.addEventListener('change', (e) => {
                            e.stopPropagation();
                            const master = masterTasks.find(m => m.id == id);
                            if (master) {
                                master.completed = chk.checked;
                                saveData();
                                renderApp();
                            }
                        });
                    }
                    const del = item.querySelector('.delete-btn');
                    if (del) {
                        del.addEventListener('click', (e) => {
                            e.stopPropagation();
                            masterTasks = masterTasks.filter(m => m.id != id);
                            saveData();
                            renderApp();
                        });
                    }
                    item.addEventListener('click', (e) => {
                        if (e.target.closest('.todo-check') || e.target.closest('.delete-btn')) return;
                        e.preventDefault();
                        navigateToSubList(id);
                    });
                });
            }

            // ----- subtask list events -----
            function attachSubListEvents(master) {
                document.getElementById('back-to-main')?.addEventListener('click', (e) => {
                    e.preventDefault();
                    navigateToList();
                });

                document.getElementById('add-sub-btn')?.addEventListener('click', () => {
                    const inp = document.getElementById('sub-input');
                    if (!inp.value.trim()) return alert('enter subtask');
                    master.subtasks.push({
                        id: Date.now() + Math.random(),
                        text: inp.value.trim(),
                        completed: false,
                        description: '',
                        dueDate: '',
                        priority: 'medium',
                        notes: '',
                        createdAt: new Date().toISOString()
                    });
                    inp.value = '';
                    saveData();
                    renderApp();
                });

                document.getElementById('clear-sub-completed')?.addEventListener('click', () => {
                    master.subtasks = master.subtasks.filter(s => !s.completed);
                    saveData();
                    renderApp();
                });

                document.getElementById('delete-current-master')?.addEventListener('click', () => {
                    if (confirm(`delete main task "${master.text}"?`)) {
                        masterTasks = masterTasks.filter(m => m.id != master.id);
                        saveData();
                        navigateToList();
                    }
                });

                document.querySelectorAll('#sub-list .todo-item').forEach(item => {
                    const subId = item.dataset.subid;
                    const chk = item.querySelector('.todo-check');
                    if (chk) {
                        chk.addEventListener('change', (e) => {
                            e.stopPropagation();
                            const sub = master.subtasks.find(s => s.id == subId);
                            if (sub) {
                                sub.completed = chk.checked;
                                saveData();
                                renderApp();
                            }
                        });
                    }
                    const del = item.querySelector('.delete-btn');
                    if (del) {
                        del.addEventListener('click', (e) => {
                            e.stopPropagation();
                            master.subtasks = master.subtasks.filter(s => s.id != subId);
                            saveData();
                            renderApp();
                        });
                    }

                    item.addEventListener('click', (e) => {
                        if (e.target.closest('.todo-check') || e.target.closest('.delete-btn')) return;
                        e.preventDefault();
                        navigateToSubtaskDetail(master.id, subId);
                    });
                });
            }

            // ----- subtask detail events with edit functionality -----
            function attachSubtaskDetailEvents(master, sub) {
                document.getElementById('back-to-sublist')?.addEventListener('click', (e) => {
                    e.preventDefault();
                    navigateToSubList(master.id);
                });

                document.getElementById('toggle-subtask-btn')?.addEventListener('click', () => {
                    sub.completed = !sub.completed;
                    saveData();
                    renderApp();
                });

                document.getElementById('delete-subtask-from-detail')?.addEventListener('click', () => {
                    if (confirm(`delete subtask "${sub.text}"?`)) {
                        master.subtasks = master.subtasks.filter(s => s.id != sub.id);
                        saveData();
                        navigateToSubList(master.id);
                    }
                });

                // Edit functionality
                const editBtn = document.getElementById('edit-subtask-btn');
                const editForm = document.getElementById('edit-form');
                const cancelBtn = document.getElementById('cancel-edit-btn');
                const saveBtn = document.getElementById('save-edit-btn');

                if (editBtn) {
                    editBtn.addEventListener('click', () => {
                        editForm.style.display = 'block';
                        editBtn.style.display = 'none';
                    });
                }

                if (cancelBtn) {
                    cancelBtn.addEventListener('click', () => {
                        editForm.style.display = 'none';
                        if (editBtn) editBtn.style.display = 'inline-block';
                    });
                }

                if (saveBtn) {
                    saveBtn.addEventListener('click', () => {
                        const newTitle = document.getElementById('edit-title').value.trim();
                        if (!newTitle) {
                            alert('Title cannot be empty');
                            return;
                        }

                        sub.text = newTitle;
                        sub.description = document.getElementById('edit-description').value;
                        sub.priority = document.getElementById('edit-priority').value;
                        sub.dueDate = document.getElementById('edit-duedate').value;
                        sub.notes = document.getElementById('edit-notes').value;

                        saveData();
                        editForm.style.display = 'none';
                        if (editBtn) editBtn.style.display = 'inline-block';
                        renderApp();
                    });
                }
            }

            // initialize
            loadData();
            window.addEventListener('hashchange', handleHash);
            handleHash();
        })();
    </script>
</body>

</html>