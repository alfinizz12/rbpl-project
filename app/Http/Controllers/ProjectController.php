<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Board;
use App\Models\Task;
use App\Models\WorkspaceMember;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = auth()->user()->projects()->latest()->get();
        return view('dashboard', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $project = auth()->user()->projects()->create([
            'name' => $request->name,
            'status' => 'active',
            'last_accessed' => now()
        ]);

        // Add creator as owner
        $project->members()->create([
            'user_id' => auth()->id(),
            'role' => 'owner'
        ]);

        return redirect()->back()->with('success', 'Workspace created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        // Check if user is the owner or a member of this project
        $isOwner = $project->user_id === auth()->id();
        $isMember = $project->members()->where('user_id', auth()->id())->exists();
        
        if (!$isOwner && !$isMember) {
            abort(403);
        }

        $boards = $project->boards()->with('tasks')->get();
        $members = $project->members()->with('user')->get();
        $logs = $project->logs()->latest()->get();
        $myLogs = $logs->where('user_id', auth()->id());
        $teamLogs = $logs;
        $membersArray = $members->map(function($m) {
            return [
                'id' => $m->user->id,
                'name' => $m->user->name,
                'email' => $m->user->email,
                'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($m->user->name) . '&background=7bbde8&color=fff',
            ];
        })->values();
        return view('projects.workspace', compact('project', 'boards', 'members', 'logs', 'myLogs', 'teamLogs', 'membersArray'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function updateLastAccessed(Project $project)
    {
        $project->update(['last_accessed' => now()]);
        return response()->json(['success' => true]);
    }

    public function addMember(Request $request, Project $project)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();
        
        $project->members()->create([
            'user_id' => $user->id,
            'role' => 'member'
        ]);

        return redirect()->back()->with('success', 'Member added successfully');
    }

    public function createBoard(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $project->boards()->create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->back()->with('success', 'Board created successfully');
    }

    public function createTask(Request $request, Board $board)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'responsible_id' => 'required|exists:users,id',
            'due_date' => 'required|date',
            'status' => 'required|in:not_started,ongoing,done'
        ]);

        $board->tasks()->create([
            'name' => $request->name,
            'responsible_id' => $request->responsible_id,
            'due_date' => $request->due_date,
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Task created successfully');
    }

    public function updateTaskStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:not_started,ongoing,done'
        ]);

        $task->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Task status updated successfully');
    }

    public function submitTask(Request $request, Task $task)
    {
        $request->validate([
            'submission' => 'required|string'
        ]);

        if ($task->responsible_id !== auth()->id()) {
            abort(403);
        }

        $task->update([
            'submission' => $request->submission
        ]);

        return redirect()->back()->with('success', 'Task submitted successfully');
    }

    public function availableUsers(Project $project)
    {
        $memberIds = $project->members()->pluck('user_id');
        $users = \App\Models\User::whereNotIn('id', $memberIds)
            ->where('id', '!=', auth()->id())
            ->get(['id', 'name', 'email']);
        return response()->json($users);
    }

    public function ajaxAddMember(Request $request, Project $project)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);
        $project->members()->create([
            'user_id' => $request->user_id,
            'role' => 'member'
        ]);
        return response()->json(['success' => true]);
    }

    public function ajaxAddBoard(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);
        $board = $project->boards()->create([
            'name' => $request->name,
            'description' => $request->description
        ]);
        return response()->json(['success' => true, 'board' => $board]);
    }

    public function memberTasksAndLogs(Project $project, $userId)
    {
        $tasks = $project->boards()->with(['tasks' => function($q) use ($userId) {
            $q->where('responsible_id', $userId);
        }])->get()->pluck('tasks')->flatten();
        $logs = $project->logs()->where('user_id', $userId)->latest()->get();
        return response()->json([
            'tasks' => $tasks,
            'logs' => $logs
        ]);
    }

    public function logs(Project $project)
    {
        // Check if user is the owner or a member of this project
        $isOwner = $project->user_id === auth()->id();
        $isMember = $project->members()->where('user_id', auth()->id())->exists();
        if (!$isOwner && !$isMember) {
            abort(403);
        }
        $logs = $project->logs()->latest()->get();
        $myLogs = $logs->where('user_id', auth()->id());
        $teamLogs = $logs;
        $boards = $project->boards()->with('tasks')->get();
        return view('projects.logs', compact('project', 'myLogs', 'teamLogs', 'boards'));
    }

    public function board(Project $project, $boardId)
    {
        // Check if user is the owner or a member of this project
        $isOwner = $project->user_id === auth()->id();
        $isMember = $project->members()->where('user_id', auth()->id())->exists();
        
        if (!$isOwner && !$isMember) {
            abort(403);
        }

        // Dummy data for boards
        $boards = [
            ['id' => 1, 'name' => 'Board#1'],
            ['id' => 2, 'name' => 'Board#2'],
            ['id' => 3, 'name' => 'Board#3']
        ];

        return view('projects.board', compact('project', 'boards'));
    }

    public function boardTasks($projectId, $boardId)
    {
        $project = (object)[
            'id' => $projectId,
            'name' => 'Project Dummy'
        ];
        $board = (object)[
            'id' => $boardId,
            'name' => 'Board#' . $boardId
        ];
        $submissions = [
            ['user' => 'User 1', 'date' => '2024-06-01', 'file' => 'Submission File 1'],
            ['user' => 'User 2', 'date' => '2024-06-02', 'file' => 'Submission File 2'],
            ['user' => 'User 3', 'date' => '2024-06-03', 'file' => 'Submission File 3'],
            ['user' => 'User 4', 'date' => '2024-06-04', 'file' => 'Submission File 4'],
            ['user' => 'User 5', 'date' => '2024-06-05', 'file' => 'Submission File 5'],
        ];
        $boards = [
            ['id' => 1, 'name' => 'Board#1'],
            ['id' => 2, 'name' => 'Board#2'],
            ['id' => 3, 'name' => 'Board#3'],
        ];
        return view('projects.board-tasks', compact('project', 'board', 'submissions', 'boards'));
    }
}
