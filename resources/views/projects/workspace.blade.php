<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $myWorkspace->workspaceName }} - Workspace - Berbinar Insightful</title>
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
            <x-sidebar :workspace="$myWorkspace" :user="$user" :boards="$boards" />
            <main class="flex-1 p-10 bg-[#f7f7f7] overflow-y-auto">
                <div>
                    <!-- Team Header -->
                    <div
                        class="flex items-center justify-between bg-white border border-[#7bbde8] rounded-md shadow p-6 mb-8">
                        <div class="text-lg font-semibold text-gray-700">Your Team</div>
                        @if (Auth::id() === $myWorkspace->ownerId)
                            <button onclick="showAddMemberModal()"
                                class="flex items-center px-4 py-2 bg-[#7bbde8] text-white rounded-md hover:bg-[#5ca6d6] transition text-sm font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Add Members
                            </button>
                        @endif
                    </div>

                    <!-- Members List -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-xl font-bold text-gray-800">Members</div>
                        <div></div>
                    </div>
                    <div
                        class="bg-[#f7f7f7] rounded-lg shadow border border-gray-200 p-4 max-h-[400px] overflow-y-auto">
                        <ul class="space-y-4">
                            @forelse ($members as $index => $member)
                                <li onclick="showTrackerModal({{ $index }})"
                                    class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-100 cursor-pointer">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-sm text-white uppercase">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-sm flex items-center gap-1">
                                                {{ $member->name }}
                                                @if ($member->id === $myWorkspace->ownerId)
                                                    <span
                                                        class="text-xs text-white bg-blue-500 px-2 py-0.5 rounded">Owner</span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $member->email }}</div>
                                        </div>
                                    </div>
                                    @if (Auth::id() === $myWorkspace->ownerId && $member->id !== Auth::id())
                                        <button
                                            onclick="showRemoveMemberModal({{ $member->id }}, '{{ addslashes($member->name) }}')"
                                            class="text-red-500 hover:text-red-700 text-sm">
                                            Remove
                                        </button>
                                    @endif
                                </li>
                            @empty
                                <li class="text-sm text-gray-500">No members found.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- Add Members Modal -->
                <div id="addMemberModal"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 hidden">
                    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold">Add Member</h2>
                            <button onclick="hideAddMemberModal()"
                                class="text-gray-400 hover:text-gray-700">&times;</button>
                        </div>
                        <ul id="user-list" class="space-y-4">
                            @forelse ($availableUsers as $user)
                                <li class="flex items-center justify-between bg-gray-100 p-3 rounded">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                                            class="w-8 h-8 rounded-full object-cover" />
                                        <div>
                                            <div class="font-medium">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                    <form action="{{ route('workspace.store-member', $myWorkspace->workspaceId) }}"
                                        method="POST">
                                        @csrf
                                        <input type="hidden" name="email" value="{{ $user->email }}">
                                        <button type="submit"
                                            class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
                                            Add
                                        </button>
                                    </form>
                                </li>
                            @empty
                                <li class="text-center text-gray-500">All users already added.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- Remove Member Modal -->
                <div id="removeMemberModal"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 hidden">
                    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-gray-800">Confirm Remove</h2>
                            <button onclick="hideRemoveMemberModal()"
                                class="text-gray-400 hover:text-gray-700 text-xl">&times;</button>
                        </div>
                        <p class="text-sm text-gray-600 mb-6">Are you sure you want to remove <span
                                id="memberToRemoveName" class="font-semibold text-gray-800"></span> from this workspace?
                        </p>
                        <form id="removeMemberForm" method="POST"
                            action="{{ route('workspace.remove-member', $myWorkspace->workspaceId) }}">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="user_id" id="removeMemberUserId">
                            <div class="flex justify-end gap-2">
                                <button type="button" onclick="hideRemoveMemberModal()"
                                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                                    Cancel
                                </button>
                                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                    Remove
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tracker Modal & Other Components -->
                <div id="trackerModal"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 hidden">
                    <div
                        class="bg-white rounded-[24px] shadow-lg p-10 w-full max-w-3xl relative flex flex-col min-h-[420px]">
                        <button onclick="hideTrackerModal()"
                            class="absolute top-6 right-6 text-[#7bbde8] text-3xl font-bold hover:text-[#5ca6d6]">&times;</button>
                        <div class="flex items-start mb-8">
                            <div class="flex items-center w-1/3">
                                <img id="trackerAvatar" src="" class="w-16 h-16 rounded-full mr-4">
                                <div>
                                    <div class="font-semibold text-lg" id="trackerName"></div>
                                    <div class="text-sm text-gray-500" id="trackerEmail"></div>
                                </div>
                            </div>
                            <div class="flex-1 flex flex-col items-center">
                                <div class="text-2xl font-bold mb-2">Submission Tracker</div>
                            </div>
                        </div>
                        <div class="flex flex-row gap-8 w-full">
                            <div class="flex-1">
                                <div class="text-xl font-semibold mb-4">Logs</div>
                                <ul>
                                    <li class="py-2 border-b text-gray-800">logs 1</li>
                                    <li class="py-2 border-b text-gray-800">logs 2</li>
                                </ul>
                            </div>
                            <div class="flex flex-col items-center w-1/3">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-full bg-gray-200 mb-1 flex items-center justify-center cursor-pointer"
                                        onclick="showSubmissionDetailModal()"></div>
                                    <div class="text-xs text-[#7bbde8] mb-4">Sat, 20 March 2025</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function showAddMemberModal() {
            document.getElementById('addMemberModal').classList.remove('hidden');
        }

        function hideAddMemberModal() {
            document.getElementById('addMemberModal').classList.add('hidden');
        }

        function showRemoveMemberModal(userId, userName) {
            document.getElementById('removeMemberUserId').value = userId;
            document.getElementById('memberToRemoveName').innerText = userName;
            document.getElementById('removeMemberModal').classList.remove('hidden');
        }

        function hideRemoveMemberModal() {
            document.getElementById('removeMemberModal').classList.add('hidden');
        }

        function showTrackerModal(idx) {
            const m = members[idx];
            document.getElementById('trackerModal').classList.remove('hidden');
            document.getElementById('trackerName').innerText = m.name;
            document.getElementById('trackerEmail').innerText = m.email;
            document.getElementById('trackerAvatar').src = m.avatar;
        }

        function hideTrackerModal() {
            document.getElementById('trackerModal').classList.add('hidden');
        }

        function showSubmissionDetailModal() {
            hideTrackerModal();
            document.getElementById('submissionDetailModal').classList.remove('hidden');
        }

        function hideSubmissionDetailModal() {
            document.getElementById('submissionDetailModal').classList.add('hidden');
        }
    </script>

    @include('components.tracker-modal')
    @include('components.submission-detail-modal')
</body>

</html>