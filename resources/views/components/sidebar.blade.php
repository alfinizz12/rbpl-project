@props(['workspace', 'user', 'boards',])

<aside class="w-72 bg-white border-r flex flex-col py-8 px-6 min-h-full">
    <a href="{{ route('profile.edit') }}"
        class="flex flex-col items-center mb-8 rounded-lg p-2 hover:bg-[#eaf6fd] transition">
        <img src="https://ui-avatars.com/api/?name={{ urlencode($workspace->workspaceName) }}&background=7bbde8&color=fff"
            class="w-16 h-16 rounded-full mb-2" alt="Project Avatar">
        <div class="text-center">
            <div class="font-semibold text-base text-gray-800">{{ $workspace->workspaceName}}</div>
            <div class="text-xs text-gray-500">{{ $user->email }}</div>
        </div>
    </a>

    <nav class="mb-8">
        <ul class="space-y-2">
            <li>
                <a href="{{ route('workspace.show', $workspace->workspaceId) }}"
                    class="flex items-center px-3 py-2 rounded-md {{ request()->routeIs('projects.show') ? 'text-[#7bbde8] bg-[#eaf6fd]' : 'text-gray-600 hover:text-[#7bbde8] hover:bg-[#eaf6fd]' }} font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Members
                </a>
            </li>
            <li>
                <a href="{{ route('logs.index', $workspace->workspaceId) }}"
                    class="flex items-center px-3 py-2 rounded-md {{ request()->routeIs('projects.logs') ? 'text-[#7bbde8] bg-[#eaf6fd]' : 'text-gray-600 hover:text-[#7bbde8] hover:bg-[#eaf6fd]' }} font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Logs
                </a>
            </li>
        </ul>
    </nav>

    <!-- Boards List -->
    <div>
        <div class="font-semibold text-sm text-gray-700 mb-2">My Board</div>
        <ul class="space-y-1">
            @forelse($boards as $board)
                <li class="flex items-center justify-between px-2 py-1 rounded hover:bg-[#eaf6fd] group">
                    <a href="/workspace/{{ $workspace->workspaceId }}/board/{{ $board->boardId }}"
                        class="text-gray-700 group-hover:text-[#7bbde8]">
                        {{ $board->boardName }}
                    </a>
                    @if (Auth::id() === $workspace->ownerId)
                        <form method="POST" action="{{ route('board.destroy', $board->boardId) }}"
                            onsubmit="return confirm('Are you sure you want to delete this board?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 ml-2 text-sm" title="Delete Board">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </form>
                    @endif
                </li>

            @empty
                <li class="text-sm text-gray-500">No boards yet.</li>
            @endforelse
        </ul>

        @if (Auth::id() === $workspace->ownerId)
            <button
                class="mt-4 w-full flex items-center justify-center border-2 border-[#7bbde8] text-[#7bbde8] rounded-md py-2 hover:bg-[#eaf6fd] transition font-semibold text-sm"
                onclick="showAddBoardModal()">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add New Board
            </button>
        @endif
    </div>

    <!-- Modal Add Board -->
    <div id="addBoardModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 hidden">
        <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Add New Board</h2>
                <button onclick="hideAddBoardModal()" class="text-gray-400 hover:text-gray-700">&times;</button>
            </div>
            <form id="addBoardForm" method="POST" action="{{ route('board.store') }}">
                @csrf
                <input type="hidden" name="workspaceId" id="" value="{{ $workspace->workspaceId }}">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Board Name</label>
                    <input type="text" name="name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none" required>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-[#7bbde8] text-white rounded hover:bg-[#5ca6d6]">Add
                        Board</button>
                </div>
            </form>
        </div>
    </div>
</aside>

<script>
    function showAddBoardModal() {
        document.getElementById('addBoardModal').classList.remove('hidden');
    }
    function hideAddBoardModal() {
        document.getElementById('addBoardModal').classList.add('hidden');
    }
</script>