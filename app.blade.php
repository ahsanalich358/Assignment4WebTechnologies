<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>To-Do App – @yield('title', 'Dashboard')</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --blue: #3B5BDB;
            --blue-light: #e7ecff;
            --blue-dark: #2f4ac2;
            --green: #2f9e44;
            --green-light: #ebfbee;
            --amber: #e67700;
            --amber-light: #fff3bf;
            --red: #c92a2a;
            --red-light: #ffe3e3;
            --gray-50: #f8f9fa;
            --gray-100: #f1f3f5;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-400: #ced4da;
            --gray-500: #adb5bd;
            --gray-600: #868e96;
            --gray-700: #495057;
            --gray-800: #343a40;
            --gray-900: #212529;
            --white: #ffffff;
            --font: 'Plus Jakarta Sans', sans-serif;
            --shadow-sm: 0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.05);
            --shadow: 0 4px 6px -1px rgba(0,0,0,.07), 0 2px 4px -1px rgba(0,0,0,.04);
            --shadow-md: 0 10px 15px -3px rgba(0,0,0,.08), 0 4px 6px -2px rgba(0,0,0,.03);
            --radius: 10px;
            --radius-sm: 6px;
            --radius-lg: 16px;
        }

        body {
            font-family: var(--font);
            background: var(--gray-100);
            color: var(--gray-900);
            min-height: 100vh;
            font-size: 14px;
            line-height: 1.5;
        }

        /* ─── Navbar ─── */
        .navbar {
            background: var(--blue);
            padding: 0 2rem;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .navbar-brand {
            display: flex; align-items: center; gap: 10px;
            color: #fff; font-weight: 700; font-size: 16px; text-decoration: none;
        }
        .navbar-brand .icon-wrap {
            width: 30px; height: 30px; background: rgba(255,255,255,.2);
            border-radius: 8px; display: flex; align-items: center; justify-content: center;
        }
        .navbar-nav { display: flex; align-items: center; gap: 4px; }
        .nav-link {
            color: rgba(255,255,255,.75); text-decoration: none; padding: 6px 14px;
            border-radius: 6px; font-weight: 500; font-size: 13px; transition: all .15s;
        }
        .nav-link:hover, .nav-link.active { color: #fff; background: rgba(255,255,255,.15); }
        .nav-user {
            display: flex; align-items: center; gap: 10px;
            color: rgba(255,255,255,.9); font-size: 13px;
        }
        .avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: rgba(255,255,255,.2); display: flex; align-items: center;
            justify-content: center; font-weight: 700; font-size: 12px; color: #fff;
        }
        .btn-logout {
            background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.3);
            color: #fff; padding: 5px 12px; border-radius: 6px; font-size: 12px;
            cursor: pointer; transition: all .15s; font-family: var(--font); font-weight: 500;
        }
        .btn-logout:hover { background: rgba(255,255,255,.25); }

        /* ─── Layout ─── */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }
        .page-content { padding: 2rem 1.5rem; max-width: 1200px; margin: 0 auto; }

        /* ─── Alert ─── */
        .alert {
            padding: 12px 16px; border-radius: var(--radius-sm); font-size: 13px;
            font-weight: 500; margin-bottom: 1rem; display: flex; align-items: center; gap: 10px;
        }
        .alert-success { background: var(--green-light); color: #1a5e2a; border: 1px solid #b2f2bb; }
        .alert-danger  { background: var(--red-light);   color: #7b1c1c; border: 1px solid #ffc9c9; }
        .alert-info    { background: var(--blue-light);  color: #1e3a8a; border: 1px solid #bac8ff; }

        /* ─── Buttons ─── */
        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 8px 18px; border-radius: var(--radius-sm);
            font-family: var(--font); font-size: 13px; font-weight: 600;
            border: none; cursor: pointer; transition: all .15s; text-decoration: none;
            white-space: nowrap;
        }
        .btn-primary   { background: var(--blue);  color: #fff; }
        .btn-primary:hover { background: var(--blue-dark); }
        .btn-success   { background: var(--green); color: #fff; }
        .btn-success:hover { background: #267a38; }
        .btn-danger    { background: var(--red);   color: #fff; }
        .btn-danger:hover { background: #a52222; }
        .btn-light     { background: var(--gray-100); color: var(--gray-800); border: 1px solid var(--gray-300); }
        .btn-light:hover { background: var(--gray-200); }
        .btn-sm { padding: 5px 12px; font-size: 12px; }
        .btn-icon { padding: 6px 10px; }

        /* ─── Cards ─── */
        .card {
            background: var(--white); border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm); border: 1px solid var(--gray-200);
        }
        .card-header {
            padding: 1rem 1.25rem; border-bottom: 1px solid var(--gray-200);
            display: flex; align-items: center; justify-content: space-between;
            background: var(--blue); border-radius: var(--radius-lg) var(--radius-lg) 0 0;
            color: #fff;
        }
        .card-header h3 { font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px; }
        .card-body { padding: 1.25rem; }

        /* ─── Forms ─── */
        .form-group { margin-bottom: 1rem; }
        .form-label { display: block; font-size: 12px; font-weight: 600; color: var(--gray-700); margin-bottom: 5px; text-transform: uppercase; letter-spacing: .4px; }
        .form-label .req { color: var(--red); }
        .form-control, .form-select {
            width: 100%; padding: 9px 12px; border: 1px solid var(--gray-300);
            border-radius: var(--radius-sm); font-family: var(--font); font-size: 13px;
            color: var(--gray-800); background: #fff; transition: border-color .15s, box-shadow .15s;
            outline: none;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--blue); box-shadow: 0 0 0 3px rgba(59,91,219,.12);
        }
        textarea.form-control { resize: vertical; min-height: 90px; }
        .form-error { font-size: 12px; color: var(--red); margin-top: 4px; display: flex; align-items: center; gap: 4px; }
        .is-invalid { border-color: var(--red) !important; }

        /* ─── Badge / Tag ─── */
        .badge {
            display: inline-block; padding: 3px 10px; border-radius: 100px;
            font-size: 11px; font-weight: 700; letter-spacing: .3px;
        }
        .badge-high    { background: #ffe0e0; color: #b91c1c; }
        .badge-medium  { background: #fff3bf; color: #92400e; }
        .badge-low     { background: #d1fae5; color: #065f46; }
        .badge-pending   { background: var(--blue-light); color: #1e3a8a; }
        .badge-completed { background: var(--green-light); color: #14532d; }

        /* ─── Stat Cards ─── */
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
        .stat-card {
            background: var(--white); border-radius: var(--radius-lg); padding: 1.25rem 1.5rem;
            box-shadow: var(--shadow-sm); border: 1px solid var(--gray-200);
            display: flex; align-items: center; gap: 1rem;
        }
        .stat-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;
        }
        .stat-icon.blue   { background: var(--blue-light); color: var(--blue); }
        .stat-icon.amber  { background: var(--amber-light); color: var(--amber); }
        .stat-icon.green  { background: var(--green-light); color: var(--green); }
        .stat-value { font-size: 28px; font-weight: 700; line-height: 1; color: var(--gray-900); }
        .stat-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; color: var(--gray-500); margin-top: 2px; }

        /* ─── Table ─── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        thead th {
            padding: 10px 14px; background: var(--gray-50); color: var(--gray-600);
            font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px;
            border-bottom: 1px solid var(--gray-200); white-space: nowrap;
        }
        tbody tr { border-bottom: 1px solid var(--gray-100); transition: background .1s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: var(--gray-50); }
        tbody td { padding: 12px 14px; color: var(--gray-800); vertical-align: middle; }
        .task-title-main { font-weight: 600; color: var(--gray-900); }
        .task-title-main.strikethrough { text-decoration: line-through; color: var(--gray-400); }
        .task-desc-sub { font-size: 12px; color: var(--gray-500); }

        /* ─── Action buttons ─── */
        .actions { display: flex; gap: 5px; }
        .btn-action {
            width: 30px; height: 30px; border-radius: 7px; border: none;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 13px; transition: all .15s;
        }
        .btn-action.undo   { background: #dbeafe; color: #1d4ed8; }
        .btn-action.edit   { background: #dcfce7; color: #15803d; }
        .btn-action.del    { background: #fee2e2; color: #dc2626; }
        .btn-action.check  { background: #d1fae5; color: #059669; }
        .btn-action:hover  { filter: brightness(.9); transform: scale(1.05); }

        /* ─── Filters ─── */
        .filters { display: flex; gap: 8px; }
        .filter-select {
            padding: 6px 10px; border: 1px solid var(--gray-300); border-radius: var(--radius-sm);
            font-family: var(--font); font-size: 12px; font-weight: 500; background: #fff;
            color: var(--gray-700); cursor: pointer; outline: none;
        }
        .filter-select:focus { border-color: var(--blue); }

        /* ─── Modal ─── */
        .modal-overlay {
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,.4);
            z-index: 999; align-items: center; justify-content: center;
        }
        .modal-overlay.open { display: flex; }
        .modal {
            background: #fff; border-radius: var(--radius-lg); width: 480px; max-width: 95vw;
            max-height: 90vh; overflow-y: auto; box-shadow: var(--shadow-md);
        }
        .modal-header {
            padding: 1rem 1.25rem; background: var(--blue); color: #fff;
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
            display: flex; align-items: center; justify-content: space-between;
        }
        .modal-header h3 { font-size: 14px; font-weight: 600; }
        .modal-close {
            background: rgba(255,255,255,.2); border: none; color: #fff; width: 28px; height: 28px;
            border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center;
        }
        .modal-body { padding: 1.25rem; }

        /* ─── Page title ─── */
        .page-heading { margin-bottom: 1.5rem; }
        .page-heading h1 { font-size: 22px; font-weight: 700; color: var(--gray-900); }
        .page-heading p  { font-size: 13px; color: var(--gray-500); margin-top: 2px; }

        /* ─── 2-col layout ─── */
        .two-col { display: grid; grid-template-columns: 1fr 1.6fr; gap: 1.25rem; align-items: start; }

        @media (max-width: 768px) {
            .two-col { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: 1fr; }
            .navbar { padding: 0 1rem; }
        }
    </style>
    @stack('styles')
</head>
<body>

<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="navbar-brand">
        <div class="icon-wrap"><i class="fa-solid fa-check"></i></div>
        To-Do App
    </a>
    <div class="navbar-nav">
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-pie"></i> Dashboard
        </a>
        <a href="{{ route('tasks.index') }}" class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
            <i class="fa-solid fa-list-check"></i> Tasks
        </a>
        <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
            About
        </a>
    </div>
    <div class="nav-user">
        <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <span>{{ auth()->user()->name }}</span>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
        </form>
    </div>
</nav>

<main>
    <div class="page-content">
        @if(session('success'))
            <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger"><i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}</div>
        @endif
        @yield('content')
    </div>
</main>

<!-- Edit Modal -->
<div class="modal-overlay" id="editModal">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fa-solid fa-pen"></i> Edit Task</h3>
            <button class="modal-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <form id="editForm" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label class="form-label">Task Title <span class="req">*</span></label>
                    <input type="text" name="title" id="editTitle" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" id="editDescription" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Due Date</label>
                    <input type="date" name="due_date" id="editDueDate" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Priority</label>
                    <select name="priority" id="editPriority" class="form-select">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" id="editStatus" class="form-select">
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:1rem">
                    <button type="button" class="btn btn-light" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEdit(id, title, description, dueDate, priority, status) {
    document.getElementById('editForm').action = '/tasks/' + id;
    document.getElementById('editTitle').value = title;
    document.getElementById('editDescription').value = description;
    document.getElementById('editDueDate').value = dueDate;
    document.getElementById('editPriority').value = priority;
    document.getElementById('editStatus').value = status;
    document.getElementById('editModal').classList.add('open');
}
function closeModal() {
    document.getElementById('editModal').classList.remove('open');
}
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
@stack('scripts')
</body>
</html>