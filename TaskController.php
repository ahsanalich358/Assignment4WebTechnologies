<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display the task list with filters and pagination.
     */
    public function index(Request $request)
    {
        $query = Task::forUser()
            ->withStatus($request->status)
            ->withPriority($request->priority)
            ->latest();

        $tasks         = $query->paginate(10);
        $totalTasks    = Task::forUser()->count();
        $pendingTasks  = Task::forUser()->where('status', 'pending')->count();
        $completedTasks = Task::forUser()->where('status', 'completed')->count();

        return view('tasks.index', compact(
            'tasks', 'totalTasks', 'pendingTasks', 'completedTasks'
        ));
    }

    /**
     * Store a new task.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'due_date'    => 'nullable|date|after_or_equal:today',
            'priority'    => 'required|in:low,medium,high',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status']  = 'pending';

        Task::create($validated);

        return redirect()->route('tasks.index')
            ->with('success', 'Task added successfully!');
    }

    /**
     * Update an existing task.
     */
    public function update(Request $request, Task $task)
    {
        // Ensure the task belongs to the logged-in user
        abort_if($task->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'due_date'    => 'nullable|date',
            'priority'    => 'required|in:low,medium,high',
            'status'      => 'required|in:pending,completed',
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully!');
    }

    /**
     * Toggle task status between pending and completed.
     */
    public function toggle(Task $task)
    {
        abort_if($task->user_id !== Auth::id(), 403);

        $task->update([
            'status' => $task->status === 'pending' ? 'completed' : 'pending',
        ]);

        return redirect()->back()
            ->with('success', 'Task status updated!');
    }

    /**
     * Delete a task.
     */
    public function destroy(Task $task)
    {
        abort_if($task->user_id !== Auth::id(), 403);
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully!');
    }
}