<div id="submissionDetailModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 hidden">
    <div class="bg-white rounded-[24px] shadow-lg p-10 w-full max-w-3xl relative flex flex-row min-h-[420px] overflow-y-auto">
        <button onclick="hideSubmissionDetailModal()" class="absolute top-6 right-6 text-[#7bbde8] text-3xl font-bold hover:text-[#5ca6d6]">&times;</button>
        <!-- Kiri: Info Member & Task -->
        <div class="w-1/2 pr-8 flex flex-col justify-center">
            <div class="flex items-center mb-6">
                <img src="https://ui-avatars.com/api/?name=Member+1&background=7bbde8&color=fff" class="w-16 h-16 rounded-full mr-4">
                <div>
                    <div class="font-semibold text-lg">Member 1</div>
                    <div class="text-sm text-gray-500">YourEmail@email.com</div>
                </div>
            </div>
            <div class="text-2xl font-semibold text-center mb-4">Task #1</div>
            <div class="text-[#7bbde8] text-sm font-semibold mb-2 text-center">Sat, 20 March 2025</div>
            <div class="text-gray-700 text-sm leading-relaxed text-center mb-2">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sit amet justo ipsum. Sed accumsan quam vitae est varius fringilla. Pellentesque placerat vestibulum lorem sed porta. Nullam mattis tristique iaculis. Nullam pulvinar sit amet risus pretium auctor. Etiam quis massa pulvinar, aliquam quam vitae, tempus sem. Donec elementum pulvinar odio.
            </div>
        </div>
        <!-- Kanan: Submission -->
        <div class="w-1/2 flex flex-col justify-center">
            <div class="text-2xl font-bold text-center mb-2">Submission 1</div>
            <div class="text-lg text-center mb-4">Board 1</div>
            <div class="space-y-3 mb-4">
                <div class="flex items-center border rounded px-4 py-2 bg-gray-50">
                    <svg class="w-6 h-6 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6" />
                    </svg>
                    <span class="flex-1">File Attachment 1</span>
                    <button class="ml-2 text-gray-400 hover:text-gray-700">&times;</button>
                </div>
                <div class="flex items-center border rounded px-4 py-2 bg-gray-50">
                    <svg class="w-6 h-6 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6" />
                    </svg>
                    <span class="flex-1">File Attachment 2</span>
                    <button class="ml-2 text-gray-400 hover:text-gray-700">&times;</button>
                </div>
            </div>
            <div class="border border-[#7bbde8] rounded p-4 bg-white text-gray-700 text-sm">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sit amet justo ipsum. Sed accumsan quam vitae est varius fringilla. Pellentesque placerat vestibulum lorem sed porta. Nullam mattis tristique iaculis.
            </div>
        </div>
    </div>
</div> 