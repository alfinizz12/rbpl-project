<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Board#1 - Berbinar Insightful</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f7f7f7]">
<div class="min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow-sm flex items-center justify-between px-8 py-3 border-b">
        <a href="{{ route('dashboard') }}" class="flex items-center">
            <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="w-8 h-8 mr-3" />
            <span class="text-[#7bbde8] text-xl font-light">Berbinar Insightful</span>
        </a>
        <form class="flex items-center w-64 max-w-full mr-4">
            <div class="relative w-full">
                <input type="text" placeholder="Search" class="w-full pl-10 pr-4 py-2 border border-[#7bbde8] rounded-md focus:outline-none focus:ring-1 focus:ring-[#7bbde8] bg-white" />
                <span class="absolute left-3 top-2.5 text-[#7bbde8]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                    </svg>
                </span>
            </div>
        </form>
    </header>
    <div class="flex flex-1">
        <!-- Sidebar -->
        <x-sidebar :project="$project" :boards="$boards" />
        <!-- Main Content -->
        <main class="flex-1 p-10 bg-[#f7f7f7] overflow-y-auto">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-2xl font-bold mb-6">Board#1</h2>
                <div class="flex gap-4 mb-4">
                    <a href="/projects/1/boards/1/tasks" class="px-8 py-2 rounded bg-[#7bbde8] text-white font-semibold">Task</a>
                    <a href="/projects/1/boards/1/mytask" class="px-8 py-2 rounded bg-[#7bbde8] text-white font-semibold">My Task</a>
                </div>
                <div class="bg-[#f5f5f5] rounded shadow p-6">
                    <table class="w-full mb-2">
                        <thead>
                            <tr class="bg-[#c6e2f7] text-gray-800">
                                <th class="py-2 px-4 text-left">Task Name</th>
                                <th class="py-2 px-4 text-left">Responsible</th>
                                <th class="py-2 px-4 text-left">Status</th>
                                <th class="py-2 px-4 text-left">Date</th>
                                <th class="py-2 px-4 text-left">Submission</th>
                            </tr>
                        </thead>
                        <tbody id="task-table-body">
                            <!-- Will be filled by JavaScript -->
                        </tbody>
                    </table>
                    <button class="w-full bg-white rounded shadow px-4 py-2 text-gray-700 font-semibold border border-gray-200 hover:bg-gray-50" onclick="showAddTaskModal()">Add Task</button>
                </div>
            </div>
        </main>
    </div>
    @include('components.submission-detail-modal')
    <!-- Modal Add Task -->
    <div id="addTaskModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 hidden">
        <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Add New Task</h2>
                <button onclick="hideAddTaskModal()" class="text-gray-400 hover:text-gray-700">&times;</button>
            </div>
            <form id="addTaskForm" onsubmit="addTask(event)">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Task Name</label>
                    <input type="text" id="taskName" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Responsible</label>
                    <select id="taskResponsible" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none" required>
                        <option value="">Select Member</option>
                        <option>Member 1</option>
                        <option>Member 2</option>
                        <option>Member 3</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Status</label>
                    <select id="taskStatus" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none" required>
                        <option value="not_started">Not Started</option>
                        <option value="ongoing">Ongoing</option>
                        <option value="done">Done</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Due Date</label>
                    <input type="date" id="taskDate" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none" required>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-[#7bbde8] text-white rounded hover:bg-[#5ca6d6]">Add Task</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Dummy data task
let tasks = [
    { 
        name: 'Task 1', 
        responsible: 'Member 1', 
        status: 'ongoing', 
        date: '2024-03-20',
        submission: null
    },
    { 
        name: 'Task 2', 
        responsible: 'Member 2', 
        status: 'done', 
        date: '2024-03-19',
        submission: 'Task completed successfully'
    }
];

function getStatusBadge(status) {
    const badges = {
        'not_started': '<span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Not Started</span>',
        'ongoing': '<span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Ongoing</span>',
        'done': '<span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Done</span>'
    };
    return badges[status] || badges['not_started'];
}

function renderTasks() {
    const tbody = document.getElementById('task-table-body');
    tbody.innerHTML = tasks.map(t => `
        <tr class="border-b border-gray-200">
            <td class="py-3 px-4">${t.name}</td>
            <td class="py-3 px-4">${t.responsible}</td>
            <td class="py-3 px-4">${getStatusBadge(t.status)}</td>
            <td class="py-3 px-4">${t.date}</td>
            <td class="py-3 px-4">
                ${t.submission 
                    ? `<button onclick="showSubmissionDetailModal()" class="text-[#7bbde8] hover:underline">See Submission</button>`
                    : `<span class="text-gray-500 italic">No submission yet</span>`
                }
            </td>
        </tr>
    `).join('');
}

function showAddTaskModal() {
    document.getElementById('addTaskModal').classList.remove('hidden');
}

function hideAddTaskModal() {
    document.getElementById('addTaskModal').classList.add('hidden');
}

function addTask(e) {
    e.preventDefault();
    const name = document.getElementById('taskName').value;
    const responsible = document.getElementById('taskResponsible').value;
    const status = document.getElementById('taskStatus').value;
    const date = document.getElementById('taskDate').value;
    
    tasks.push({ 
        name, 
        responsible, 
        status, 
        date,
        submission: null
    });
    
    renderTasks();
    hideAddTaskModal();
    document.getElementById('addTaskForm').reset();
}

function showSubmissionDetailModal() {
    document.getElementById('submissionDetailModal').classList.remove('hidden');
}

function hideSubmissionDetailModal() {
    document.getElementById('submissionDetailModal').classList.add('hidden');
}

// Initial render
renderTasks();
</script>
</body>
</html> 