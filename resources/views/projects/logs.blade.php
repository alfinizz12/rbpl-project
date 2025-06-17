<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs - {{ $project->name }} - Berbinar Insightful</title>
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
                <!-- Your Logs -->
                <div class="mb-10">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Your Logs</h2>
                        <form class="w-72 max-w-full">
                            <div class="relative">
                                <input type="text" placeholder="Search" class="w-full pl-10 pr-4 py-2 border border-[#7bbde8] rounded-md focus:outline-none focus:ring-1 focus:ring-[#7bbde8] bg-white">
                                <span class="absolute left-3 top-2.5 text-[#7bbde8]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                                    </svg>
                                </span>
                            </div>
                        </form>
                    </div>
                    <div class="bg-white rounded-lg shadow border border-blue-200 p-4 max-h-[200px] overflow-y-auto">
                        <ul class="space-y-2">
                            @forelse($myLogs as $log)
                                <li class="flex justify-between items-center bg-[#f7f7f7] border border-blue-200 rounded px-4 py-3">
                                    <span>{{ $log->description }}</span>
                                    <span class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</span>
                                </li>
                            @empty
                                <li class="text-gray-400">No logs found.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                <!-- Team Logs -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Team Logs</h2>
                        <form class="w-72 max-w-full">
                            <div class="relative">
                                <input type="text" placeholder="Search" class="w-full pl-10 pr-4 py-2 border border-[#7bbde8] rounded-md focus:outline-none focus:ring-1 focus:ring-[#7bbde8] bg-white">
                                <span class="absolute left-3 top-2.5 text-[#7bbde8]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                                    </svg>
                                </span>
                            </div>
                        </form>
                    </div>
                    <div class="bg-white rounded-lg shadow border border-blue-200 p-4 max-h-[200px] overflow-y-auto">
                        <ul class="space-y-2">
                            @forelse($teamLogs as $log)
                                <li class="flex justify-between items-center bg-[#f7f7f7] border border-blue-200 rounded px-4 py-3">
                                    <span>{{ $log->description }}</span>
                                    <span class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</span>
                                </li>
                            @empty
                                <li class="text-gray-400">No logs found.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html> 