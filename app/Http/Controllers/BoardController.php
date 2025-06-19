<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Workspace;
use App\Models\WorkspaceLog;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{
    use AuthorizesRequests;
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Board::create([
            'boardName' => $validated['name'],
            'workspaceId' => $request->workspaceId
        ]);

        WorkspaceLog::create([
            'workspace_id' => $request->workspaceId,
            'user_id' => Auth::id(),
            'activity' => Auth::user()->name . ' menambahkan board baru bernama "' . $validated['name'] . '"',
        ]);

        return back();
    }

    public function show(Workspace $workspace, Board $board)
    {
        $this->authorize('view', $workspace);
        $user = Auth::user();
        $myWorkspace = $workspace;

        $myBoard = Board::with([
            'tasks' => function ($query) {
                $query->orderBy('due_date', 'asc');
            },
            'tasks.users'
        ])
            ->where('boardId', $board->boardId)
            ->firstOrFail();


        $boards = Board::orderBy('created_at', 'asc')
            ->where('workspaceId', $workspace->workspaceId)
            ->get();

        $members = $workspace->members()->select('users.id', 'users.name')->get();

        return view('projects.board', compact('myBoard', 'boards', 'user', 'myWorkspace', 'members'));
    }


    public function destroy(Board $board)
    {
        $user = Auth::user();
        $workspace = Workspace::where('workspaceId', $board->workspaceId)->first();

        if ($workspace && $workspace->ownerId === $user->id) {
            WorkspaceLog::create([
                'workspace_id' => $workspace->workspaceId,
                'user_id' => $user->id,
                'activity' => Auth::user()->name . ' menghapus board "' . $board->boardName . '"',
            ]);

            $board->delete();
            return back()->with('success', 'Board deleted successfully.');
        }

        return redirect()->back()->with('error', 'Unauthorized to delete this board.');
    }

}
