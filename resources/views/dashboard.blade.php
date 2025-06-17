<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Berbinar Insightful</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="px-8 py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="w-10 h-10 mr-3" />
                    <span class="text-[#7bbde8] text-2xl font-light">Berbinar Insightful</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('profile.edit') }}" class="px-4 py-2 rounded text-[#7bbde8] font-semibold hover:underline">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded text-red-500 font-semibold hover:underline">Logout</button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-6xl mx-auto px-8 py-12">
            <div class="flex justify-between items-start mb-10">
                <!-- Profile -->
                <div class="flex items-center space-x-4">
                    <img src="#" class="w-20 h-20 rounded-full object-cover border-4 border-white shadow" alt="Profile">
                    <div>
                        <div class="text-xl font-bold text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="text-gray-600">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <!-- Welcome Text -->
                <div class="mt-6 md:mt-0">
                    <h2 class="text-2xl font-bold text-gray-800">Welcome Back, {{ Auth::user()->name }}!</h2>
                </div>
            </div>

            <!-- Your Workspaces -->
            <div class="mb-10">
                <h2 class="text-xl font-bold mb-4">Your Workspaces</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach($projects as $project)
                    <a href="{{ route('projects.show', $project) }}" class="block">
                        <div class="rounded-xl border-2 border-blue-300 overflow-hidden shadow hover:shadow-lg transition bg-gradient-to-tr from-[#7bbde8] via-pink-200 to-blue-200 h-40 flex items-end p-5 relative">
                            <div class="text-white font-bold text-lg drop-shadow mb-2">{{ $project->name }}</div>
                            <div class="absolute left-5 bottom-3 text-xs text-white/80">Created {{ $project->created_at->format('y/m/d') }}</div>
                        </div>
                    </a>
                    @endforeach
                    <!-- Add New -->
                    <div x-data="{ showForm: false }" class="rounded-xl border-2 border-dashed border-blue-300 flex flex-col items-center justify-center h-40 cursor-pointer hover:bg-blue-50 transition">
                        <div x-show="!showForm" @click="showForm = true" class="flex flex-col items-center justify-center w-full h-full">
                            <svg class="w-12 h-12 text-blue-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span class="text-blue-300 font-semibold">Add New</span>
                        </div>
                        <form x-show="showForm" action="{{ route('projects.store') }}" method="POST" class="w-full px-4 py-2">
                            @csrf
                            <input type="text" name="name" placeholder="Workspace Name" required class="w-full px-3 py-2 rounded border border-blue-200 mb-2 focus:outline-none">
                            <div class="flex space-x-2">
                                <button type="submit" class="flex-1 py-2 bg-[#7bbde8] text-white rounded hover:bg-[#68aad4] transition">Add</button>
                                <button type="button" @click="showForm = false" class="px-4 py-2 text-gray-600 hover:text-gray-900">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Recently Workspaces -->
            <div>
                <h2 class="text-xl font-bold mb-4">Recently Workspaces</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach($projects as $project)
                    <a href="{{ route('projects.show', $project) }}" class="block">
                        <div class="rounded-xl border-2 border-blue-300 overflow-hidden shadow hover:shadow-lg transition bg-gradient-to-tr from-[#7bbde8] via-pink-200 to-blue-200 h-40 flex items-end p-5 relative">
                            <div class="text-white font-bold text-lg drop-shadow mb-2">{{ $project->name }}</div>
                            <div class="absolute left-5 bottom-3 text-xs text-white/80">Created {{ $project->created_at->format('y/m/d') }}</div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </main>
    </div>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>


