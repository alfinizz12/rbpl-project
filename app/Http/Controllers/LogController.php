<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Workspace;
use App\Models\WorkspaceLog;
use Illuminate\Http\Request;
use Illuminate\Queue\Console\WorkCommand;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function index(Workspace $workspace)
    {
        // Pastikan user hanya bisa mengakses logs jika dia adalah anggota workspace
        if (!$workspace->members->contains(Auth::id()) && $workspace->ownerId !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $user = Auth::user();
        $boards = $workspace->boards; // jika kamu punya relasi boards di model Workspace

        $myLogs = WorkspaceLog::where('workspace_id', $workspace->workspaceId)
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        $teamLogs = WorkspaceLog::where('workspace_id', $workspace->workspaceId)
            ->where('user_id', '!=', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return view('projects.logs', [
            'myWorkspace' => $workspace,
            'user' => $user,
            'boards' => $boards,
            'myLogs' => $myLogs,
            'teamLogs' => $teamLogs,
        ]);
    }
    public function store(Request $request)
    {
        $task = Task::create([
            'taskName' => $request->taskName,
            'status' => $request->taskStatus,
            'due_date' => $request->taskDate,
            'boardId' => $request->boardId,
        ]);

        $task->users()->attach($request->taskResponsible);

        $board = $task->board;
        $workspace = $board->workspace;

        WorkspaceLog::create([
            'workspace_id' => $workspace->workspaceId,
            'user_id' => auth()->id(),
            'activity' => auth()->user()->name . " menambahkan task '{$task->taskName}' ke board '{$board->boardName}'",
        ]);

        return redirect()->back()->with('success', 'Task berhasil ditambahkan.');
    }

    public function destroy(Task $task)
    {
        $board = $task->board;
        $workspace = $board->workspace;

        WorkspaceLog::create([
            'workspace_id' => $workspace->workspaceId,
            'user_id' => auth()->id(),
            'activity' => auth()->user()->name . " menghapus task '{$task->taskName}' dari board '{$board->boardName}'",
        ]);

        $task->delete();

        return redirect()->back()->with('success', 'Task berhasil dihapus.');
    }
}
