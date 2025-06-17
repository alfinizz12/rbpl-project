<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $project->name }} - Workspace - Berbinar Insightful</title>
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
                
                <!-- Members Section -->
                <div>
                    <!-- Your Team Box -->
                    <div class="flex items-center justify-between bg-white border border-[#7bbde8] rounded-md shadow p-6 mb-8">
                        <div class="text-lg font-semibold text-gray-700">Your Team</div>
                        <button onclick="showAddMemberModal()" class="flex items-center px-4 py-2 bg-[#7bbde8] text-white rounded-md hover:bg-[#5ca6d6] transition text-sm font-medium">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Members
                        </button>
                    </div>
                    <!-- Members Section -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-xl font-bold text-gray-800">Members</div>
                        <div></div>
                    </div>
                    <div class="bg-[#f7f7f7] rounded-lg shadow border border-gray-200 p-4 max-h-[400px] overflow-y-auto">
                        <ul id="member-list" class="space-y-3">
                            <!-- Will be rendered by JS -->
                        </ul>
                    </div>
                </div>

                <!-- Add Members Modal -->
                <div id="addMemberModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 hidden">
                    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold">Add Member</h2>
                            <button onclick="hideAddMemberModal()" class="text-gray-400 hover:text-gray-700">&times;</button>
                        </div>
                        <ul id="user-list" class="space-y-4">
                            <!-- Will be rendered by JS -->
                        </ul>
                    </div>
                </div>

                <!-- Add Board Modal -->
                <div id="addBoardModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 hidden">
                    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold">Add New Board</h2>
                            <button @click="addBoardModal = false" class="text-gray-400 hover:text-gray-700">&times;</button>
                        </div>
                        <form @submit.prevent="submitBoard">
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Board Name</label>
                                <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none" x-model="newBoard.name">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Description</label>
                                <textarea class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none" x-model="newBoard.description"></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="px-4 py-2 bg-[#7bbde8] text-white rounded hover:bg-[#5ca6d6]">Add Board</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tracker Modal (vanilla JS + Tailwind) -->
                <div id="trackerModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 hidden">
                    <div class="bg-white rounded-[24px] shadow-lg p-10 w-full max-w-3xl relative flex flex-col min-h-[420px]">
                        <button onclick="hideTrackerModal()" class="absolute top-6 right-6 text-[#7bbde8] text-3xl font-bold hover:text-[#5ca6d6]">&times;</button>
                        <div class="flex items-start mb-8">
                            <!-- Profile -->
                            <div class="flex items-center w-1/3">
                                <img id="trackerAvatar" src="" class="w-16 h-16 rounded-full mr-4">
                                <div>
                                    <div class="font-semibold text-lg" id="trackerName"></div>
                                    <div class="text-sm text-gray-500" id="trackerEmail"></div>
                                </div>
                            </div>
                            <!-- Title -->
                            <div class="flex-1 flex flex-col items-center">
                                <div class="text-2xl font-bold mb-2">Submission Tracker</div>
                            </div>
                        </div>
                        <div class="flex flex-row gap-8 w-full">
                            <!-- Logs -->
                            <div class="flex-1">
                                <div class="text-xl font-semibold mb-4">Logs</div>
                                <ul>
                                    <li class="py-2 border-b text-gray-800">logs 1</li>
                                    <li class="py-2 border-b text-gray-800">logs 2</li>
                                    <li class="py-2 border-b text-gray-800">logs 3</li>
                                    <li class="py-2 border-b text-gray-800">logs 4</li>
                                    <li class="py-2 border-b text-gray-800">logs 5</li>
                                </ul>
                            </div>
                            <!-- Timeline -->
                            <div class="flex flex-col items-center w-1/3">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-full bg-gray-200 mb-1 flex items-center justify-center cursor-pointer" onclick="showSubmissionDetailModal()"></div>
                                    <div class="text-xs text-[#7bbde8] mb-4">Sat, 20 March 2025</div>
                                    <div class="w-1 h-10 bg-[#7bbde8]"></div>
                                    <div class="w-16 h-16 rounded-full bg-gray-200 mb-1 flex items-center justify-center cursor-pointer" onclick="showSubmissionDetailModal()"></div>
                                    <div class="text-xs text-[#7bbde8] mb-4">Sat, 20 March 2025</div>
                                    <div class="w-1 h-10 bg-[#7bbde8]"></div>
                                    <div class="w-16 h-16 rounded-full bg-gray-200 mb-1 flex items-center justify-center cursor-pointer" onclick="showSubmissionDetailModal()"></div>
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
    // Dummy data
    let allUsers = [
        { name: 'Member 1', email: 'YourEmail@email.com', avatar: 'https://randomuser.me/api/portraits/men/1.jpg' },
        { name: 'Member 2', email: 'YourEmail@email.com', avatar: 'https://randomuser.me/api/portraits/women/2.jpg' },
        { name: 'Member 3', email: 'YourEmail@email.com', avatar: 'https://randomuser.me/api/portraits/men/3.jpg' },
        { name: 'Member 4', email: 'YourEmail@email.com', avatar: 'https://randomuser.me/api/portraits/women/4.jpg' },
        { name: 'Member 5', email: 'YourEmail@email.com', avatar: 'https://randomuser.me/api/portraits/men/5.jpg' }
    ];
    let members = [allUsers[0], allUsers[1], allUsers[2]]; // default members

    function renderMembers() {
        const ul = document.getElementById('member-list');
        ul.innerHTML = members.map((m, idx) => `
            <li class="flex items-center justify-between bg-white border border-[#7bbde8] rounded-lg px-3 py-2 shadow cursor-pointer hover:bg-[#eaf6fd] transition min-h-[56px]" onclick="showTrackerModal(${idx})">
                <div class="flex items-center gap-2">
                    <img src="${m.avatar}" class="w-10 h-10 rounded-full object-cover" />
                    <div>
                        <div class="font-semibold text-sm">${m.name}</div>
                        <div class="text-xs text-gray-500">${m.email}</div>
                    </div>
                </div>
                <span class="text-xl text-[#7bbde8]">&#8594;</span>
            </li>
        `).join('');
    }
    function showAddMemberModal() {
        document.getElementById('addMemberModal').classList.remove('hidden');
        renderUserList();
    }
    function hideAddMemberModal() {
        document.getElementById('addMemberModal').classList.add('hidden');
    }
    function renderUserList() {
        const ul = document.getElementById('user-list');
        // Filter users not already in members
        const available = allUsers.filter(u => !members.some(m => m.email === u.email));
        ul.innerHTML = available.length
            ? available.map((u, idx) => `
                <li class="flex items-center gap-3 bg-white border border-[#7bbde8] rounded-lg px-4 py-3 shadow cursor-pointer hover:bg-[#eaf6fd] transition" onclick="addMemberFromModal('${u.email}')">
                    <img src="${u.avatar}" class="w-14 h-14 rounded-full object-cover" />
                    <div>
                        <div class="font-semibold">${u.name}</div>
                        <div class="text-xs text-gray-500">${u.email}</div>
                    </div>
                </li>
            `).join('')
            : '<li class="py-3 text-gray-400 text-center">No more users to add</li>';
    }
    function addMemberFromModal(email) {
        const user = allUsers.find(u => u.email === email);
        if (user && !members.some(m => m.email === email)) {
            members.push(user);
            renderMembers();
            hideAddMemberModal();
        }
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
    // Initial render
    renderMembers();
    </script>
    @include('components.tracker-modal')
    @include('components.submission-detail-modal')
</body>
</html> 