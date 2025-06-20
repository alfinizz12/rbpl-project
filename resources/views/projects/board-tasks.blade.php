<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $board->name }} - Board Tasks - Berbinar Insightful</title>
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
        <x-sidebar :workspace="$myWorkspace" :user_email="$email" :boards="$boards"/>
        <!-- Main Content -->
        <main class="flex-1 p-10 bg-[#f7f7f7] overflow-y-auto">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-2xl font-bold mb-2">{{ $board->name }}</h2>
                <div class="text-lg mb-6">Task - x</div>
                <div class="bg-white rounded shadow border border-gray-200 p-8">
                    <div class="flex justify-end mb-4">
                        <form class="w-72 max-w-full">
                            <div class="relative">
                                <input type="text" placeholder="Search" class="w-full pl-10 pr-4 py-2 border border-[#7bbde8] rounded-md focus:outline-none focus:ring-1 focus:ring-[#7bbde8] bg-white" />
                                <span class="absolute left-3 top-2.5 text-[#7bbde8]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                                    </svg>
                                </span>
                            </div>
                        </form>
                    </div>
                    <div class="max-w-4xl mx-auto">
                        @foreach($submissions as $submission)
                            <div class="grid grid-cols-3 gap-3 mb-3">
                                <div class="bg-white border rounded shadow px-4 py-2 flex items-center font-semibold text-sm">{{ $submission['user'] }}</div>
                                <div class="bg-white border rounded shadow px-4 py-2 flex items-center font-semibold text-sm">{{ $submission['date'] }}</div>
                                <div class="bg-white border rounded shadow px-4 py-2 flex items-center font-semibold text-sm">{{ $submission['file'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html> 