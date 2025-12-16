<x-admin-layout>
    {{-- MAIN CONTAINER --}}
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-black text-[#001233]">Schedule Automation</h1>
                    <p class="text-gray-500 mt-1">Manage the rules that auto-generate your daily trips.</p>
                </div>
                <div class="flex gap-3">
                    {{-- Create Button --}}
                    <a href="{{ route('admin.templates.create') }}" class="bg-white border border-gray-300 text-gray-700 px-5 py-2.5 rounded-xl font-bold hover:bg-gray-50 hover:text-[#001233] transition shadow-sm flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        New Rule
                    </a>
                    
                    {{-- Generator Button --}}
                    <form action="{{ route('admin.templates.generate') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-[#001233] text-white px-6 py-2.5 rounded-xl font-bold hover:bg-blue-900 shadow-lg hover:shadow-xl transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Generate Next 7 Days
                        </button>
                    </form>
                </div>
            </div>

            {{-- SUCCESS MESSAGE --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-8 rounded shadow-sm flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-bold">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-green-700 hover:text-green-900"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
            @endif

            {{-- RULES GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($templates as $template)
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition group relative">
                        
                        {{-- DELETE BUTTON (Top Right) --}}
                        <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition duration-200">
                            <form action="{{ route('admin.templates.destroy', $template->id) }}" method="POST" onsubmit="return confirm('Delete this automation rule? This will stop future schedules from generating.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-300 hover:text-red-500 transition p-1 rounded-md hover:bg-red-50">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>

                        <div class="flex justify-between items-start mb-4 pr-8">
                            <span class="bg-blue-50 text-blue-700 text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wide border border-blue-100">
                                Bus {{ $template->bus->code ?? 'N/A' }}
                            </span>
                            <div class="text-right">
                                <p class="text-2xl font-black text-[#001233]">{{ \Carbon\Carbon::parse($template->start_time)->format('H:i') }}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">To {{ \Carbon\Carbon::parse($template->end_time)->format('H:i') }}</p>
                            </div>
                        </div>
                        
                        <h3 class="font-bold text-gray-800 text-lg mb-1">
                            {{ $template->route->origin ?? '?' }} <span class="text-gray-300 mx-1">&rarr;</span> {{ $template->route->destination ?? '?' }}
                        </h3>
                        
                        <div class="flex flex-wrap gap-1 mt-4">
                            @foreach($template->active_days as $day)
                                <span class="text-[10px] font-bold bg-gray-100 text-gray-600 px-2 py-1 rounded border border-gray-200">{{ substr($day, 0, 3) }}</span>
                            @endforeach
                        </div>
                        
                        <div class="mt-6 pt-4 border-t border-gray-50 flex justify-between items-center text-xs font-bold">
                            <span class="text-gray-400">Every {{ $template->frequency_minutes }} mins</span>
                            <div class="flex items-center gap-1.5 text-green-600 bg-green-50 px-2 py-1 rounded-full">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> Active
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-20 bg-white rounded-2xl border border-dashed border-gray-300">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-700">No Automation Rules</h3>
                        <p class="text-gray-500 text-sm mt-1 mb-6">Create a rule to start auto-generating trips.</p>
                        <a href="{{ route('admin.templates.create') }}" class="px-6 py-2 bg-[#001233] text-white rounded-xl font-bold hover:bg-blue-900 transition">
                            Create First Rule
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-admin-layout>