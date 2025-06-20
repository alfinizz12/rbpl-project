<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Task - Berbinar Insightful</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f7f7f7]">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow-sm flex items-center justify-between px-8 py-3 border-b">
            <a href="/dashboard" class="flex items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8 mr-3" />
                <span class="text-[#7bbde8] text-xl font-light">Berbinar Insightful</span>
            </a>
            <form class="flex items-center w-64 max-w-full mr-4">
                <div class="relative w-full">
                    <input type="text" placeholder="Search"
                        class="w-full pl-10 pr-4 py-2 border border-[#7bbde8] rounded-md focus:outline-none focus:ring-1 focus:ring-[#7bbde8] bg-white" />
                    <span class="absolute left-3 top-2.5 text-[#7bbde8]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                        </svg>
                    </span>
                </div>
            </form>
        </header>
        <div class="flex flex-1">
            <!-- Sidebar -->
            <x-sidebar :workspace="$myWorkspace" :user="$user" :boards="$boards" />
            <!-- Main Content -->
            <main class="flex-1 p-10 bg-[#f7f7f7] overflow-y-auto">
                <div class="max-w-6xl mx-auto">
                    <h2 class="text-2xl font-bold mb-6">My Task</h2>
                    <div class="bg-white rounded shadow border border-gray-200 p-8">
                        <table class="w-full mb-2">
                            <thead>
                                <tr class="bg-[#c6e2f7] text-gray-800">
                                    <th class="py-2 px-4 text-left">Task Name</th>
                                    <th class="py-2 px-4 text-left">Status</th>
                                    <th class="py-2 px-4 text-left">Date</th>
                                    <th class="py-2 px-4 text-left">Submission</th>
                                </tr>
                            </thead>
                            <tbody id="my-task-table-body">
                                @foreach ($myBoard->tasks as $task)
                                    @php
                                        $assigned = $task->users->contains($user->id);
                                        $submission = $task->attachments->where('userId', $user->id)->first();
                                    @endphp
                                    @if ($assigned)
                                        <tr class="border-b border-gray-200">
                                            <td class="py-3 px-4">{{ $task->taskName }}</td>
                                            <td class="py-3 px-4">
                                                <form action="{{ route('task.update-status', $task->taskId) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <select name="status" onchange="this.form.submit()"
                                                        class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-[#7bbde8] focus:ring-1">
                                                        <option value="Not Started" {{ $task->status === 'Not Started' ? 'selected' : '' }}>Not Started</option>
                                                        <option value="Ongoing" {{ $task->status === 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                                                        <option value="Done" {{ $task->status === 'Done' ? 'selected' : '' }}>Done
                                                        </option>
                                                    </select>
                                                </form>

                                            </td>
                                            <td class="py-3 px-4">
                                                {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M Y') : '-' }}
                                            </td>
                                            <td class="py-3 px-4">
                                                @if ($submission)
                                                    <div>
                                                        <div class="text-xs text-gray-700 mb-1">
                                                            Submitted on
                                                            {{ \Carbon\Carbon::parse($submission->posted_date)->format('d M Y') }}
                                                        </div>
                                                        <span
                                                            class="inline-block bg-[#eaf6fd] text-[#7bbde8] rounded px-2 py-1 text-xs mr-1 mb-1">
                                                            {{ $submission->fileName }}
                                                        </span>
                                                    </div>
                                                @else
                                                    <button onclick="showAddSubmissionModal({{ $task->taskId }})"
                                                        class="px-3 py-1 bg-[#7bbde8] text-white rounded text-xs">
                                                        Add Submission
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>

        <!-- Modal Add Submission -->
        <div id="addSubmissionModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 hidden">
            <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Add Submission</h2>
                    <button onclick="hideAddSubmissionModal()"
                        class="text-gray-400 hover:text-gray-700">&times;</button>
                </div>
                <form action="{{ route('attachments.store', ['task' => '__TASK_ID__']) }}" method="POST"
                    enctype="multipart/form-data" id="addSubmissionForm">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Submission Note</label>
                        <input type="text" name="note" id="submissionNote"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Upload Files</label>
                        <input type="file" name="files[]" id="submissionFiles" class="w-full" multiple required>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                            class="px-4 py-2 bg-[#7bbde8] text-white rounded hover:bg-[#5ca6d6]">Submit</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <script>
        function showAddSubmissionModal(taskId) {
            const form = document.getElementById('addSubmissionForm');
            form.action = `/tasks/${taskId}/attachments`;
            document.getElementById('addSubmissionModal').classList.remove('hidden');
        }
        function hideAddSubmissionModal() {
            document.getElementById('addSubmissionModal').classList.add('hidden');
            document.getElementById('addSubmissionForm').reset();
        }
        function submitSubmission(e) {
            e.preventDefault();
            const note = document.getElementById('submissionNote').value;
            const filesInput = document.getElementById('submissionFiles');
            const files = Array.from(filesInput.files).map(f => f.name);
            myTasks[currentTaskIndex].submission = { note, files };
            renderMyTasks();
            hideAddSubmissionModal();
        }

        function updateTaskStatus(taskId, status) {
            fetch(`/tasks/${taskId}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: status })
            })
                .then(response => {
                    if (response.ok) {
                        location.reload();
                    } else {
                        alert("Gagal mengupdate status");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Terjadi kesalahan");
                });
        }
    </script>
</body>

</html>