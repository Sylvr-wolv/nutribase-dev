<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Nutribase'))</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.3); border-radius: 10px; }

        /* Sidebar slide transition */
        [x-cloak] { display: none !important; }
    </style>
</head>
<body x-data="{ sidebarOpen: false }">

    {{-- Mobile overlay backdrop --}}
    <div
        x-show="sidebarOpen"
        x-cloak
        @click="sidebarOpen = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 z-30 lg:hidden">
    </div>

    @yield('content')

    {{-- Toast Notification --}}
    @if(session('success'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 4000)"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        class="fixed bottom-6 right-6 z-[9999] flex items-center gap-3 bg-white border border-green-200 shadow-xl rounded-2xl px-5 py-4 max-w-sm">

        <div class="flex-shrink-0 bg-green-100 rounded-full p-1.5">
            <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
            </svg>
        </div>

        <p class="text-sm font-medium text-gray-800 flex-1">{{ session('success') }}</p>

        <button @click="show = false" class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        {{-- Progress bar --}}
        <div class="absolute bottom-0 left-0 h-1 bg-green-400 rounded-b-2xl"
            style="animation: shrink 4s linear forwards;"
            x-show="show">
        </div>
    </div>

    <style>
        @keyframes shrink {
            from { width: 100%; }
            to   { width: 0%; }
        }
    </style>
    @endif

    {{-- Error toast --}}
    @if(session('error'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 4000)"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        class="fixed bottom-6 right-6 z-[9999] flex items-center gap-3 bg-white border border-red-200 shadow-xl rounded-2xl px-5 py-4 max-w-sm">

        <div class="flex-shrink-0 bg-red-100 rounded-full p-1.5">
            <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>

        <p class="text-sm font-medium text-gray-800 flex-1">{{ session('error') }}</p>

        <button @click="show = false" class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div class="absolute bottom-0 left-0 h-1 bg-red-400 rounded-b-2xl"
            style="animation: shrink 4s linear forwards;"
            x-show="show">
        </div>
    </div>
    @endif

    @stack('scripts')
</body>
</html>