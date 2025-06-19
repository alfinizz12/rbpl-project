<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Workspace;
use App\Models\WorkspaceLog;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $user = Auth::user();

        $ownedWorkspaces = Workspace::where('ownerId', $user->id)->get();

        $memberWorkspaces = $user->workspaceMemberships()->get();

        $allWorkspaces = $ownedWorkspaces->merge($memberWorkspaces)->unique('workspaceId');

        $recents = $allWorkspaces->sortByDesc('created_at')->take(3);

        return view('dashboard', [
            'workspaces' => $allWorkspaces,
            'recents' => $recents,
            'user_role' => $user->roleId,
        ]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Workspace::create([
            'workspaceName' => $validated['name'],
            'ownerId' => Auth::user()->id,
        ]);

        return redirect('dashboard');
    }

    public function show(Workspace $workspace)
    {
        $this->authorize('view', $workspace);
        $workspace->load(['members', 'owner']);
        $boards = Board::orderBy('created_at', 'asc')
            ->where('workspaceId', $workspace->workspaceId)->get();

        $memberIds = $workspace->members->pluck('id');
        $availableUsers = User::whereNotIn('id', $memberIds)
            ->where('id', '!=', Auth::id())
            ->get();

        // Gabungkan members + owner untuk ditampilkan
        $members = $workspace->members->concat(collect([$workspace->owner]));

        return view('projects.workspace', [
            'myWorkspace' => $workspace,
            'boards' => $boards,
            'user' => Auth::user(),
            'availableUsers' => $availableUsers,
            'members' => $members,
        ]);
    }



    public function addMember(Request $request, Workspace $workspace)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();
        $workspace->members()->attach($user->id);

        WorkspaceLog::create([
            'workspace_id' => $workspace->workspaceId,
            'user_id' => Auth::id(),
            'activity' => Auth::user()->name . ' menambahkan anggota: ' . $user->name . ' ke workspace',
        ]);

        return back()->with('success', 'Member berhasil ditambahkan.');
    }

    public function removeMember(Request $request, Workspace $workspace)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        if (Auth::id() !== $workspace->ownerId) {
            abort(403, 'Unauthorized');
        }

        $removedUser = User::findOrFail($request->user_id);
        $workspace->members()->detach($request->user_id);

        WorkspaceLog::create([
            'workspace_id' => $workspace->workspaceId,
            'user_id' => Auth::id(),
            'activity' => Auth::user()->name . ' menghapus anggota: ' . $removedUser->name . ' dari workspace',
        ]);

        return back()->with('success', 'Member removed successfully.');
    }

}
