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
                    <li class="py-2 border-b text-gray-800">logs 3</li>
                    <li class="py-2 border-b text-gray-800">logs 4</li>
                    <li class="py-2 border-b text-gray-800">logs 5</li>
                </ul>
            </div>
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