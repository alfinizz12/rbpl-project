@props(['project'])

@php
    // Dummy boards array for initial render
    $initialBoards = [
        ['id' => 1, 'name' => 'Board#1'],
        ['id' => 2, 'name' => 'Board#2'],
        ['id' => 3, 'name' => 'Board#3'],
    ];
@endphp

<aside class="w-72 bg-white border-r flex flex-col py-8 px-6 min-h-full">
    <a href="{{ route('profile.edit') }}" class="flex flex-col items-center mb-8 rounded-lg p-2 hover:bg-[#eaf6fd] transition">
        <img src="https://ui-avatars.com/api/?name={{ urlencode($project->name) }}&background=7bbde8&color=fff" class="w-16 h-16 rounded-full mb-2" alt="Project Avatar">
        <div class="text-center">
            <div class="font-semibold text-base text-gray-800">{{ $project->name }}</div>
            <div class="text-xs text-gray-500">{{ auth()->user()->email }}</div>
        </div>
    </a>
    <nav class="mb-8">
        <ul class="space-y-2">
            <li>
                <a href="{{ route('projects.show', $project->id) }}" class="flex items-center px-3 py-2 rounded-md {{ request()->routeIs('projects.show') ? 'text-[#7bbde8] bg-[#eaf6fd]' : 'text-gray-600 hover:text-[#7bbde8] hover:bg-[#eaf6fd]' }} font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Members
                </a>
            </li>
            <li>
                <a href="{{ route('projects.logs', $project->id) }}" class="flex items-center px-3 py-2 rounded-md {{ request()->routeIs('projects.logs') ? 'text-[#7bbde8] bg-[#eaf6fd]' : 'text-gray-600 hover:text-[#7bbde8] hover:bg-[#eaf6fd]' }} font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Logs
                </a>
            </li>
        </ul>
    </nav>
    <!-- Boards List -->
    <div>
        <div class="font-semibold text-sm text-gray-700 mb-2">My Board</div>
        <ul id="boards-list" class="space-y-1">
            <!-- Boards will be rendered here by JS -->
        </ul>
        <button class="mt-4 w-full flex items-center justify-center border-2 border-[#7bbde8] text-[#7bbde8] rounded-md py-2 hover:bg-[#eaf6fd] transition font-semibold text-sm" onclick="showAddBoardModal()">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add New Board
        </button>
    </div>
    <!-- Modal Add Board -->
    <div id="addBoardModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 hidden">
        <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Add New Board</h2>
                <button onclick="hideAddBoardModal()" class="text-gray-400 hover:text-gray-700">&times;</button>
            </div>
            <form id="addBoardForm" onsubmit="addBoard(event)">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Board Name</label>
                    <input type="text" id="boardName" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none" required>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-[#7bbde8] text-white rounded hover:bg-[#5ca6d6]">Add Board</button>
                </div>
            </form>
        </div>
    </div>
</aside>

<script>
// Dummy boards array (will be used in JS)
let boards = @json($initialBoards);
const projectId = @json($project->id);

function renderBoards() {
    const ul = document.getElementById('boards-list');
    ul.innerHTML = boards.map(b =>
        `<li>
            <a href="/projects/${projectId}/boards/${b.id}" class="block px-4 py-2 rounded hover:bg-[#eaf6fd] text-gray-700">${b.name}</a>
        </li>`
    ).join('');
}

function showAddBoardModal() {
    document.getElementById('addBoardModal').classList.remove('hidden');
}
function hideAddBoardModal() {
    document.getElementById('addBoardModal').classList.add('hidden');
}
function addBoard(e) {
    e.preventDefault();
    const name = document.getElementById('boardName').value;
    const newId = boards.length ? Math.max(...boards.map(b => b.id)) + 1 : 1;
    boards.push({ id: newId, name });
    renderBoards();
    hideAddBoardModal();
    document.getElementById('addBoardForm').reset();
}
// Initial render
renderBoards();
</script> 