<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Task;
use App\Models\Workspace;
use App\Models\WorkspaceLog;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $tasks = Task::orderBy("created_at", "desc")->paginate(10);
        return view("", compact(""));
    }

    public function show(Workspace $workspace, Board $board)
    {
        $this->authorize("view", $workspace);
        $user = Auth::user();
        $myWorkspace = $workspace;

        $myBoard = Board::with([
            'tasks' => function ($query) use ($user) {
                $query->whereHas('users', function ($q) use ($user) {
                    $q->where('users.id', $user->id);
                });
            },
            'tasks.users',
            'tasks.attachments'
        ])
            ->where('boardId', $board->boardId)
            ->firstOrFail();

        $boards = Board::orderBy('created_at', 'asc')
            ->where('workspaceId', $workspace->workspaceId)
            ->get();

        $members = $workspace->members()->select('users.id', 'users.name')->get();

        return view('projects.mytask', compact('myBoard', 'boards', 'user', 'myWorkspace', 'members'));
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'taskName' => 'required|string|max:255',
            'taskStatus' => 'required|string',
            'taskDate' => 'required|date',
            'boardId' => 'required|exists:boards,boardId',
            'taskResponsible' => 'required|exists:users,id',
        ]);

        $task = Task::create([
            'taskName' => $validated['taskName'],
            'status' => $validated['taskStatus'],
            'due_date' => $validated['taskDate'],
            'post_date' => now(),
            'boardId' => $validated['boardId'],
        ]);


        $task->users()->attach($validated['taskResponsible']);

        WorkspaceLog::create([
            'workspace_id' => $task->board->workspaceId,
            'user_id' => Auth::id(),
            'activity' => Auth::user()->name . ' menambahkan task "' . $task->taskName . '" ke board "' . $task->board->boardName . '"',
        ]);

        return redirect()->back()->with('success', 'Task berhasil ditambahkan!');
    }

    public function submissions(Task $task)
    {
        $attachments = $task->attachments()->with('user')->get();

        $response = $attachments->map(function ($attachment) use ($task) {
            return [
                'attachmentId' => $attachment->attachmentId,
                'fileName' => $attachment->fileName,
                'filePath' => $attachment->filePath,
                'posted_date' => $attachment->posted_date,
                'note' => $attachment->note ?? null,
                'user' => [
                    'name' => $attachment->user->name,
                    'email' => $attachment->user->email,
                ],
                'task' => [
                    'taskName' => $task->taskName,
                    'due_date' => $task->due_date,
                    'boardName' => $task->board->boardName ?? null,
                ],
            ];
        });

        return response()->json($response);
    }

    public function updateStatus(Request $request, Task $task)
    {
        $validated = $request->validate([
            'status' => 'required',
        ]);

        $task->status = $validated['status'];
        $task->save();

        WorkspaceLog::create([
            'workspace_id' => $task->board->workspaceId,
            'user_id' => Auth::id(),
            'activity' => Auth::user()->name . ' mengubah status task "' . $task->taskName . '" menjadi "' . $task->status . '"',
        ]);

        return redirect()->back()->with('success', 'Task status updated successfully.');
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        $request->validate([
            'taskName' => 'required|string|max:255',
            'taskResponsible' => 'required|exists:users,id',
            'taskDate' => 'required|date',
        ]);

        $task->update([
            'taskName' => $request->taskName,
            'due_date' => $request->taskDate,
        ]);

        $task->users()->sync([$request->taskResponsible]);

        WorkspaceLog::create([
            'workspace_id' => $task->board->workspaceId,
            'user_id' => Auth::id(),
            'activity' => Auth::user()->name . ' mengedit task "' . $task->taskName . '" di board "' . $task->board->boardName . '"',
        ]);


        return redirect()->back()->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $workspaceId = $task->board->workspaceId;
        $taskName = $task->taskName;
        $boardName = $task->board->boardName;

        $task->delete();

        // Tambahkan log
        WorkspaceLog::create([
            'workspace_id' => $workspaceId,
            'user_id' => Auth::id(),
            'activity' => Auth::user()->name . ' menghapus task "' . $taskName . '" dari board "' . $boardName . '"',
        ]);

        return redirect()->back()->with('success', 'Task deleted successfully.');
    }
}
