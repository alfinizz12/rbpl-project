<?php

use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('dashboard');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [WorkspaceController::class, 'index'])->name('dashboard');
    Route::get('/workspace/{workspace}', [WorkspaceController::class, 'show'])->name('workspace.show');
    Route::post('/workspace/store', [WorkspaceController::class, 'store'])->name('workspace.store');
    Route::post('/workspace/{workspace}/add-member', [WorkspaceController::class, 'addMember'])->name('workspace.store-member');
    Route::delete('/workspace/{workspace}/remove-member', [WorkspaceController::class, 'removeMember'])->name('workspace.remove-member');

    // boards
    Route::post('/board/store', [BoardController::class, 'store'])->name('board.store');
    Route::delete('/boards/{board}', [BoardController::class, 'destroy'])->name('board.destroy');
    Route::get('/workspace/{workspace}/board/{board}', [BoardController::class, 'show'])->name('board.show');

    // tasks
    Route::post('/task/store', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/workspace/{workspace}/board/{board}/mytask', [TaskController::class, 'show'])->name('mytasks');
    Route::put('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('task.update-status');
    Route::resource('tasks', TaskController::class)->only(['update', 'destroy']);
    Route::get('/tasks/{task}/submissions', [TaskController::class, 'submissions'])->name('tasks.submissions');


    // attachments
    Route::post('/tasks/{task}/attachments', [AttachmentController::class, 'store'])->name('attachments.store');
    Route::put('/submissions/{attachment}', [AttachmentController::class, 'update'])->name('submissions.update');
    Route::delete('/submissions/{attachment}', [AttachmentController::class, 'destroy'])->name('submissions.destroy');
    Route::get('/tasks/find-attachment/{attachment}', function (App\Models\Attachment $attachment) {
        return response()->json($attachment);
    });
    // Route::post('/submissions/update/{attachment}', [AttachmentController::class, 'update'])->name('submissions.update');

    // logs
    Route::get('/workspace/{workspace}/logs', [LogController::class, 'index'])->name('logs.index');


});


require __DIR__ . '/auth.php';
