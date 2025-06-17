<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Berbinar Insightful</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="flex flex-col items-center justify-center min-h-screen bg-white px-4">
        <div class="flex items-center mb-6">
            <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="w-[60px] h-[60px]" />
            <h1 class="text-[#7bbde8] text-2xl font-light ml-3">Berbinar Insightful</h1>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-2xl w-full max-w-sm">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <input
                    type="email"
                    name="email"
                    placeholder="Email"
                    class="w-full mb-4 px-4 py-2 rounded-sm border text-[#3c3c3c] border-gray-300 bg-gray-50 shadow-[inset_0_2px_4px_0_rgba(0,0,0,0.06)] placeholder-[#9cc2db] focus:outline-none"
                    required
                />
                <input
                    type="password"
                    name="password"
                    placeholder="Password"
                    class="w-full mb-4 px-4 py-2 rounded-sm border text-[#3c3c3c] border-gray-300 bg-gray-50 shadow-[inset_0_2px_4px_0_rgba(0,0,0,0.06)] placeholder-[#9cc2db] focus:outline-none"
                    required
                />
                <button type="submit" class="w-full py-2 bg-[#7bbde8] text-white rounded-md hover:bg-[#68aad4] transition">
                    Login
                </button>
            </form>
        </div>
    </div>
</body>
</html>
