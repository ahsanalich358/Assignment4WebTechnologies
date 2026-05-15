@extends('layouts.app')
@section('title', 'Dashboard')

@push('styles')
<style>
.charts-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-top: 1.25rem; }
.chart-card { background: #fff; border-radius: 16px; padding: 1.25rem; border: 1px solid #e9ecef; box-shadow: 0 1px 3px rgba(0,0,0,.07); }
.chart-card h3 { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: #868e96; margin-bottom: 1rem; display: flex; align-items: center; gap: 8px; }
.chart-wrap { position: relative; height: 220px; }
.priority-legend { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 10px; }
.legend-item { display: flex; align-items: center; gap: 5px; font-size: 12px; color: #495057; }
.legend-dot { width: 10px; height: 10px; border-radius: 50%; }
.recent-table-wrap { margin-top: 1.25rem; }
.welcome-banner {
    background: linear-gradient(135deg, #3B5BDB 0%, #5c7cfa 100%);
    border-radius: 16px; padding: 1.5rem 2rem; color: #fff; margin-bottom: 1.5rem;
    display: flex; align-items: center; justify-content: space-between;
}
.welcome-banner h2 { font-size: 20px; font-weight: 700; }
.welcome-banner p { font-size: 13px; opacity: .8; margin-top: 4px; }
.welcome-icon { font-size: 40px; opacity: .3; }
@media(max-width:768px) { .charts-grid { grid-template-columns: 1fr; } .welcome-banner { flex-direction: column; gap: 1rem; } }
</style>
@endpush

@section('content')
<div class="welcome-banner">
    <div>
        <h2>👋 Hello, {{ auth()->user()->name }}!</h2>
        <p>Here's your productivity overview for today – {{ now()->format('l, F j Y') }}</p>
    </div>
    <i class="fa-solid fa-rocket welcome-icon"></i>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fa-solid fa-list-check"></i></div>
        <div>
            <div class="stat-value">{{ $totalTasks }}</div>
            <div class="stat-label">Total Tasks</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber"><i class="fa-solid fa-clock"></i></div>
        <div>
            <div class="stat-value">{{ $pendingTasks }}</div>
            <div class="stat-label">Pending</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa-solid fa-circle-check"></i></div>
        <div>
            <div class="stat-value">{{ $completedTasks }}</div>
            <div class="stat-label">Completed</div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="charts-grid">
    <!-- Doughnut: Status -->
    <div class="chart-card">
        <h3><i class="fa-solid fa-chart-pie" style="color:#3B5BDB"></i> Task Status</h3>
        <div class="chart-wrap">
            <canvas id="statusChart"></canvas>
        </div>
        <div class="priority-legend" style="margin-top:10px">
            <div class="legend-item"><div class="legend-dot" style="background:#3B5BDB"></div> Pending ({{ $pendingTasks }})</div>
            <div class="legend-item"><div class="legend-dot" style="background:#2f9e44"></div> Completed ({{ $completedTasks }})</div>
        </div>
    </div>

    <!-- Doughnut: Priority -->
    <div class="chart-card">
        <h3><i class="fa-solid fa-flag" style="color:#e67700"></i> By Priority</h3>
        <div class="chart-wrap">
            <canvas id="priorityChart"></canvas>
        </div>
        <div class="priority-legend" style="margin-top:10px">
            <div class="legend-item"><div class="legend-dot" style="background:#c92a2a"></div> High ({{ $highPriority }})</div>
            <div class="legend-item"><div class="legend-dot" style="background:#e67700"></div> Medium ({{ $medPriority }})</div>
            <div class="legend-item"><div class="legend-dot" style="background:#2f9e44"></div> Low ({{ $lowPriority }})</div>
        </div>
    </div>

    <!-- Bar: Tasks per day (last 7 days) -->
    <div class="chart-card" style="grid-column:1/-1">
        <h3><i class="fa-solid fa-chart-bar" style="color:#5c7cfa"></i> Tasks Created – Last 7 Days</h3>
        <div class="chart-wrap" style="height:200px">
            <canvas id="barChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Tasks table -->
<div class="card recent-table-wrap">
    <div class="card-header">
        <h3><i class="fa-solid fa-clock-rotate-left"></i> Recent Tasks</h3>
        <a href="{{ route('tasks.index') }}" class="btn btn-sm" style="background:rgba(255,255,255,.2);color:#fff;border-radius:6px;padding:5px 12px;font-size:12px;font-weight:600;text-decoration:none">
            View All <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th><th>Title</th><th>Due Date</th><th>Priority</th><th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentTasks as $i => $task)
                <tr>
                    <td style="color:#adb5bd;font-size:12px">{{ $i+1 }}</td>
                    <td>
                        <div class="task-title-main {{ $task->status === 'completed' ? 'strikethrough' : '' }}">{{ $task->title }}</div>
                        @if($task->description)
                            <div class="task-desc-sub">{{ Str::limit($task->description, 50) }}</div>
                        @endif
                    </td>
                    <td>{{ $task->due_date ? $task->due_date->format('d M Y') : '—' }}</td>
                    <td><span class="badge badge-{{ $task->priority }}">{{ ucfirst($task->priority) }}</span></td>
                    <td><span class="badge badge-{{ $task->status }}">{{ ucfirst($task->status) }}</span></td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;padding:2rem;color:#adb5bd">No tasks yet. <a href="{{ route('tasks.index') }}" style="color:#3B5BDB">Add your first task →</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
const blue='#3B5BDB', green='#2f9e44', amber='#e67700', red='#c92a2a', gray='#dee2e6';

// Status Doughnut
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'Completed'],
        datasets: [{ data: [{{ $pendingTasks }}, {{ $completedTasks }}], backgroundColor: [blue, green], borderWidth: 0, hoverOffset: 6 }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ' ' + ctx.label + ': ' + ctx.parsed } } },
        cutout: '68%'
    }
});

// Priority Doughnut
new Chart(document.getElementById('priorityChart'), {
    type: 'doughnut',
    data: {
        labels: ['High', 'Medium', 'Low'],
        datasets: [{ data: [{{ $highPriority }}, {{ $medPriority }}, {{ $lowPriority }}], backgroundColor: [red, amber, green], borderWidth: 0, hoverOffset: 6 }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        cutout: '68%'
    }
});

// Bar Chart (last 7 days)
const barLabels = @json($barLabels);
const barData   = @json($barData);
new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: barLabels,
        datasets: [{
            label: 'Tasks Created',
            data: barData,
            backgroundColor: 'rgba(59,91,219,.7)',
            borderRadius: 6, borderSkipped: false
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1, color: '#868e96' }, grid: { color: '#f1f3f5' } },
            x: { ticks: { color: '#868e96' }, grid: { display: false } }
        }
    }
});
</script>
@endpush