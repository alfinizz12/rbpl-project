<?php

namespace App\Http\Controllers;

use App\Models\WorkspaceLog;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attachment;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttachmentController extends Controller
{
    use AuthorizesRequests;

    public function submissions($taskId)
    {
        $attachments = Attachment::with(['user', 'task'])
            ->where('taskId', $taskId)
            ->get()
            ->map(function ($attachment) {
                return [
                    'attachmentId' => $attachment->id,
                    'note' => $attachment->note,
                    'fileName' => basename($attachment->filePath),
                    'filePath' => $attachment->filePath,
                    'user' => [
                        'name' => $attachment->user->name,
                        'email' => $attachment->user->email,
                    ],
                    'task' => [
                        'taskName' => $attachment->task->taskName,
                        'due_date' => $attachment->task->due_date,
                        'boardName' => $attachment->task->board->boardName,
                    ],
                ];
            });

        return response()->json($attachments);
    }
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'note' => 'nullable|string|max:255',
            'files.*' => 'required|file|max:10240', // 10MB max per file
        ]);

        foreach ($request->file('files') as $file) {
            $filename = Str::random(10) . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('attachments', $filename, 'public');

            Attachment::create([
                'note' => $request->note,
                'fileName' => $filename,
                'filePath' => $path,
                'posted_date' => now(),
                'userId' => Auth::id(),
                'taskId' => $task->taskId,
            ]);

            WorkspaceLog::create([
                'workspace_id' => $task->board->workspaceId,
                'user_id' => Auth::id(),
                'activity' => Auth::user()->name . ' menambahkan submission ke task "' . $task->taskName . '" di board "' . $task->board->boardName . '"',
            ]);
        }

        return back()->with('success', 'Attachment submitted successfully.');
    }

    public function update(Request $request, Attachment $attachment)
    {
        $request->validate([
            'note' => 'nullable|string|max:1000',
            'file' => 'nullable|file|max:2048',
        ]);

        $attachment->note = $request->note;

        // Jika ada file baru, ganti
        if ($request->hasFile('file')) {
            if ($attachment->filePath && Storage::disk('public')->exists($attachment->filePath)) {
                Storage::disk('public')->delete($attachment->filePath);
            }

            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('attachments', $filename, 'public');

            $attachment->filePath = $path;
            $attachment->fileName = $file->getClientOriginalName();
        }

        $attachment->save();

        WorkspaceLog::create([
            'workspace_id' => $attachment->task->board->workspaceId,
            'user_id' => Auth::id(),
            'activity' => Auth::user()->name . ' mengedit submission pada task "' . $attachment->task->taskName . '" di board "' . $attachment->task->board->boardName . '"',
        ]);


        return redirect()->back()->with('success', 'Submission updated successfully.');
    }


    public function destroy(Attachment $attachment)
    {
        $this->authorize('delete', $attachment);

        Storage::disk('public')->delete($attachment->filePath);
        $attachment->delete();

        WorkspaceLog::create([
            'workspace_id' => $attachment->task->board->workspaceId,
            'user_id' => Auth::id(),
            'activity' => Auth::user()->name . ' menghapus submission dari task "' . $attachment->task->taskName . '" di board "' . $attachment->task->board->boardName . '"',
        ]);


        return redirect()->back()->with('success', '');
    }
}
