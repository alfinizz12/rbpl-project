<!DOCTYPE html>
<html lang="en">

@php
    function getStatusBadgeBlade($status)
    {
        $badges = [
            'Not Started' => '<span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Not Started</span>',
            'Ongoing' => '<span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Ongoing</span>',
            'Done' => '<span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Done</span>',
        ];
        return $badges[$status] ?? $badges['Not Started'];
    }
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $myBoard->boardName }} - Berbinar Insightful</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f7f7f7]">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow-sm flex items-center justify-between px-8 py-3 border-b">
            <a href="{{ route('dashboard') }}" class="flex items-center">
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
                    <h2 class="text-2xl font-bold mb-6">{{ $myBoard->boardName }}</h2>
                    <div class="flex gap-4 mb-4">
                        <a href="/workspace/{{ $myWorkspace->workspaceId }}/board/{{ $myBoard->boardId }}"
                            class="px-8 py-2 rounded bg-[#7bbde8] text-white font-semibold">Task</a>
                        <a href="/workspace/{{ $myWorkspace->workspaceId }}/board/{{ $myBoard->boardId }}/mytask"
                            class="px-8 py-2 rounded bg-[#7bbde8] text-white font-semibold">My Task</a>
                    </div>
                    <div class="bg-[#f5f5f5] rounded shadow p-6">
                        <table class="w-full mb-2">
                            @if(Auth::user()->roleId <= 3)
                                <thead>
                                    <tr class="bg-[#c6e2f7] text-gray-800">
                                        <th class="py-2 px-4 text-left">Task Name</th>
                                        <th class="py-2 px-4 text-left">Responsible</th>
                                        <th class="py-2 px-4 text-left">Status</th>
                                        <th class="py-2 px-4 text-left">Date</th>
                                        <th class="py-2 px-4 text-left">Submission</th>
                                        <th class="py-2 px-4 text-left">Actions</th>
                                    </tr>
                                </thead>
                            @else
                                <thead>
                                    <tr class="bg-[#c6e2f7] text-gray-800">
                                        <th class="py-2 px-4 text-left">Task Name</th>
                                        <th class="py-2 px-4 text-left">Responsible</th>
                                        <th class="py-2 px-4 text-left">Status</th>
                                        <th class="py-2 px-4 text-left">Date</th>
                                        <th class="py-2 px-4 text-left">Submission</th>
                                    </tr>
                                </thead>
                            @endif
                            <tbody id="task-table-body">
                                @forelse ($myBoard->tasks as $task)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="py-2 px-4">{{ $task->taskName }}</td>
                                        <td class="py-2 px-4">
                                            @foreach ($task->users as $user)
                                                <span
                                                    class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">{{ $user->name }}</span>
                                            @endforeach
                                        </td>
                                        <td class="py-2 px-4">{!! getStatusBadgeBlade($task->status) !!}</td>
                                        <td class="py-2 px-4">{{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}
                                        </td>
                                        <td class="py-2 px-4">
                                            @php
                                                $assignedUserIds = $task->users->pluck('id')->toArray();
                                            @endphp

                                            @if (in_array(Auth::id(), $assignedUserIds) || Auth::id() === $myWorkspace->ownerId)
                                                <button onclick="showSubmissionDetailModal({{ $task->taskId }})"
                                                    class="text-blue-600 hover:underline text-sm">View</button>

                                            @else
                                                <span class="text-gray-400 text-sm">No Access</span>
                                            @endif
                                        </td>
                                        @if(Auth::user()->roleId <= 3)
                                            <td class="py-2 px-4">
                                                <button onclick='showEditTaskModal(@json($task))'
                                                    class="text-yellow-600 hover:underline text-sm mr-2">Edit</button>

                                                <form action="/tasks/{{ $task->taskId }}" method="POST" class="inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this task?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:underline text-sm">Delete</button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-4 text-center text-gray-500">Belum ada task pada board
                                            ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @auth
                            @if (Auth::id() == $myWorkspace->ownerId)
                                <button
                                    class="w-full bg-white rounded shadow px-4 py-2 text-gray-700 font-semibold border border-gray-200 hover:bg-gray-50"
                                    onclick="showAddTaskModal()">Add Task
                                </button>
                            @endif
                        @endauth
                    </div>
                </div>
            </main>
        </div>
        @include('components.submission-detail-modal')
        <!-- Modal Add Task -->
        <div id="addTaskModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 hidden">
            <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Add New Task</h2>
                    <button onclick="hideAddTaskModal()" class="text-gray-400 hover:text-gray-700">&times;</button>
                </div>
                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="boardId" value="{{ $myBoard->boardId }}">

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Task Name</label>
                        <input type="text" name="taskName"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Responsible</label>
                        <select name="taskResponsible"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none" required>
                            <option value="">Select Member</option>
                            @foreach ($members as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <input type="hidden" name="taskStatus" value="Not Started">

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Due Date</label>
                        <input type="date" name="taskDate"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none" required>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-[#7bbde8] text-white rounded hover:bg-[#5ca6d6]">Add
                            Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="editSubmissionModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 hidden">
        <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Edit Submission</h2>
                <button onclick="hideEditSubmissionModal()" class="text-gray-400 hover:text-gray-700">&times;</button>
            </div>
            <form id="editSubmissionForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <input type="hidden" name="attachmentId" id="editSubmissionId"> <!-- ini yang diperbaiki -->

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Note</label>
                    <input type="text" name="note" id="editNote"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Replace File (optional)</label>
                    <input type="file" name="file" class="w-full">
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Update</button>
                </div>
            </form>


        </div>
    </div>


    <script>
        // Dummy data task

        function getStatusBadgeBlade(status) {
            const badges = {
                'not_started': '<span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Not Started</span>',
                'ongoing': '<span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Ongoing</span>',
                'done': '<span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Done</span>'
            };
            return badges[status] || badges['not_started'];
        }


        function showAddTaskModal() {
            document.getElementById('addTaskModal').classList.remove('hidden');
        }

        function hideAddTaskModal() {
            document.getElementById('addTaskModal').classList.add('hidden');
        }


        function showSubmissionDetailModal(taskId) {
            fetch(`/tasks/${taskId}/submissions`)
                .then(response => {
                    if (!response.ok) throw new Error('Network error');
                    return response.json();
                })
                .then(data => {
                    const modal = document.getElementById('submissionDetailModal');
                    const leftSection = modal.querySelector('.left-section');
                    const rightSection = modal.querySelector('.right-section');

                    if (data.length === 0) {
                        leftSection.innerHTML = `
                    <div class="text-center w-full text-gray-600 text-lg font-semibold">
                        Belum ada submission untuk task ini.
                    </div>
                `;
                        rightSection.innerHTML = '';
                    } else {
                        const submission = data[0]; // ambil submission pertama

                        leftSection.innerHTML = `
                    <div class="flex items-center mb-6">
                        <img src="https://ui-avatars.com/api/?name=${submission.user.name}&background=7bbde8&color=fff" class="w-16 h-16 rounded-full mr-4">
                        <div>
                            <div class="font-semibold text-lg">${submission.user.name}</div>
                            <div class="text-sm text-gray-500">${submission.user.email}</div>
                        </div>
                    </div>
                    <div class="text-2xl font-semibold text-center mb-4">${submission.task.taskName}</div>
                    <div class="text-[#7bbde8] text-sm font-semibold mb-2 text-center">${formatDate(submission.task.due_date)}</div>
                    <div class="text-gray-700 text-sm leading-relaxed text-center mb-2">
                        ${submission.note ?? 'Tidak ada catatan.'}
                    </div>
                `;

                        rightSection.innerHTML = `
                    <div class="text-2xl font-bold text-center mb-2">Submission</div>
                    <div class="text-lg text-center mb-4">${submission.task.boardName}</div>
                    <div class="space-y-3 mb-4">
                        ${data.map(file => `
                            <div class="flex items-center border rounded px-4 py-2 bg-gray-50">
                                <svg class="w-6 h-6 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6" />
                                </svg>
                                <a href="/storage/${file.filePath}" target="_blank" class="flex-1 text-blue-600 underline">${file.fileName}</a>
                            </div>
                        `).join('')}
                    </div>
                    <div class="flex justify-end mt-4 gap-2">
                        <button onclick="openEditSubmissionModal(${submission.attachmentId})"
                            class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Edit</button>
                            <form method="POST" action="/submissions/${submission.attachmentId}" onsubmit="return confirm('Yakin ingin menghapus submission?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Delete</button>
                            </form>
                    </div>
                `;
                    }

                    modal.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error loading submission data:', error);
                    alert('Gagal memuat submission.');
                });
        }


        function formatDate(dateStr) {
            const date = new Date(dateStr);
            return date.toLocaleDateString('id-ID', {
                weekday: 'short',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }


        function hideSubmissionDetailModal() {
            document.getElementById('submissionDetailModal').classList.add('hidden');
        }


        function hideSubmissionDetailModal() {
            document.getElementById('submissionDetailModal').classList.add('hidden');
        }

        function showEditTaskModal(task) {
            document.getElementById('editTaskModal').classList.remove('hidden');

            const form = document.getElementById('editTaskForm');
            form.action = `/tasks/${task.taskId}`;

            document.getElementById('editTaskId').value = task.taskId;
            document.getElementById('editTaskName').value = task.taskName;
            document.getElementById('editTaskDate').value = task.due_date.split('T')[0]; // tanggal

            if (task.users && task.users.length > 0) {
                document.getElementById('editTaskResponsible').value = task.users[0].id;
            } else {
                document.getElementById('editTaskResponsible').selectedIndex = 0;
            }
        }

        function hideEditTaskModal() {
            document.getElementById('editTaskModal').classList.add('hidden');
        }

        function openEditSubmissionModal(attachmentId) {
            fetch(`/tasks/find-attachment/${attachmentId}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('editSubmissionId').value = attachmentId;
                    document.getElementById('editNote').value = data.note || '';

                    // Set action form ke route update dengan ID
                    document.getElementById('editSubmissionForm').action = `/submissions/update/${attachmentId}`;

                    document.getElementById('editSubmissionModal').classList.remove('hidden');
                });
        }


        function hideEditSubmissionModal() {
            document.getElementById('editSubmissionModal').classList.add('hidden');
            document.getElementById('editSubmissionForm').reset();
        }

        function submitEditSubmission(e) {
            e.preventDefault();

            const id = document.getElementById('editSubmissionId').value;
            const note = document.getElementById('editSubmissionNote').value;

            fetch(`/submissions/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ note })
            })
                .then(res => res.json())
                .then(() => {
                    alert('Submission updated!');
                    hideEditSubmissionModal();
                    hideSubmissionDetailModal();
                })
                .catch(() => alert('Gagal update submission'));
        }

        function deleteSubmission(id) {
            if (!confirm("Yakin ingin menghapus submission ini?")) return;

            fetch(`/submissions/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(res => res.json())
                .then(() => {
                    alert('Submission deleted!');
                    hideSubmissionDetailModal();
                })
                .catch(() => alert('Gagal menghapus submission'));
        }


    </script>
</body>

</html>