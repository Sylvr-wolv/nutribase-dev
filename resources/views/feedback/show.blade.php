@extends('layouts.app')

@section('content')
<div class="flex min-h-screen" style="background:#FAFCFB">

    @include('layouts.sidebar')

    <main class="flex-1 flex flex-col min-w-0 lg:pl-72">

        {{-- Mobile Navbar --}}
        <header class="lg:hidden flex items-center justify-between bg-white border-b border-gray-200 px-4 py-3 sticky top-0 z-20 shadow-sm">
            <button @click="sidebarOpen = true" style="color:#06B13D">
                <i class="bi bi-list text-2xl"></i>
            </button>
            <div class="flex items-center gap-2">
                <img src="{{ asset('favicon.ico') }}" class="w-7 h-7 object-contain" alt="Logo">
                <span class="font-bold text-sm" style="color:#06B13D">NutriBase</span>
            </div>
            <div class="w-8"></div>
        </header>

        <div class="flex-1 p-4 sm:p-6 lg:p-8 max-w-3xl mx-auto w-full">

            {{-- Back --}}
            <a href="{{ route('feedback.index') }}"
               class="inline-flex items-center gap-2 text-sm font-medium mb-5 transition"
               style="color:#4E6F5C">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Daftar Ulasan
            </a>

            {{-- Flash --}}
            @if(session('success'))
            <div class="mb-4 flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-white" style="background:#06B13D">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="mb-4 flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-white bg-red-500">
                <i class="bi bi-x-circle-fill"></i>
                {{ session('error') }}
            </div>
            @endif

            {{-- ══ FEEDBACK CARD ══ --}}
            <div class="bg-white rounded-2xl shadow p-5 mb-4">
                {{-- Header --}}
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-bold flex-shrink-0"
                             style="background:#4E6F5C;color:#D7F487">
                            {{ strtoupper(substr($feedback->penerima->nama ?? 'P', 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $feedback->penerima->nama ?? '-' }}</p>
                            <p class="text-xs text-gray-400">{{ $feedback->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    {{-- Rating --}}
                    <div class="flex flex-col items-end gap-1">
                        <div class="flex items-center gap-0.5">
                            @for($s = 1; $s <= 5; $s++)
                            <i class="bi bi-star-fill text-sm {{ $s <= $feedback->rating ? 'text-yellow-400' : 'text-gray-200' }}"></i>
                            @endfor
                        </div>
                        <span class="text-xs font-bold text-gray-500">{{ $feedback->rating }}/5</span>
                    </div>
                </div>

                {{-- Distribusi context --}}
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg"
                          style="background:#D7F487;color:#4E6F5C">
                        <i class="bi bi-basket2-fill text-[10px]"></i>
                        {{ $feedback->distribusi->menu->nama ?? '-' }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-lg bg-gray-100 text-gray-500">
                        <i class="bi bi-calendar3 text-[10px]"></i>
                        {{ $feedback->distribusi->waktu_distribusi->format('d M Y') }}
                    </span>
                    @php
                        $statusStyle = match($feedback->distribusi->status) {
                            'diterima' => 'background:#D7F487;color:#4E6F5C',
                            'gagal'    => 'background:#FEE2E2;color:#991B1B',
                            default    => 'background:#FEF9C3;color:#854D0E',
                        };
                    @endphp
                    <span class="inline-flex items-center text-xs font-semibold px-3 py-1.5 rounded-lg"
                          style="{{ $statusStyle }}">
                        {{ strtoupper($feedback->distribusi->status) }}
                    </span>
                </div>

                {{-- Isi ulasan --}}
                @if($feedback->isi_ulasan)
                <p class="text-gray-700 text-sm leading-relaxed">{{ $feedback->isi_ulasan }}</p>
                @else
                <p class="text-gray-400 text-sm italic">Tidak ada isi ulasan.</p>
                @endif

                {{-- Actions (edit/delete for penerima owner) --}}
                @can('update', $feedback)
                <div class="flex gap-2 mt-4 pt-4 border-t border-gray-100">
                    <a href="#"
                       x-data
                       @click.prevent="$dispatch('open-edit-feedback')"
                       class="text-xs font-semibold px-3 py-1.5 rounded-lg border border-blue-200 text-blue-600 hover:bg-blue-50 transition">
                        <i class="bi bi-pencil mr-1"></i>Edit Ulasan
                    </a>
                    @can('delete', $feedback)
                    <form action="{{ route('feedback.destroy', $feedback->id) }}" method="POST"
                          onsubmit="return confirm('Hapus ulasan ini beserta semua tanggapannya?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="text-xs font-semibold px-3 py-1.5 rounded-lg border border-red-200 text-red-500 hover:bg-red-50 transition">
                            <i class="bi bi-trash mr-1"></i>Hapus
                        </button>
                    </form>
                    @endcan
                </div>
                @endcan
            </div>

            {{-- ══ THREAD ══ --}}
            <div class="space-y-3 mb-4">
                @if($feedback->tanggapans->isEmpty())
                <div class="text-center py-8 text-gray-400 text-sm">
                    <i class="bi bi-chat-dots text-3xl block mb-2"></i>
                    Belum ada tanggapan. Jadilah yang pertama membalas.
                </div>
                @else
                @foreach($feedback->tanggapans as $t)
                @php
                    $isMe = $t->user_id === auth()->id();
                    $isPenerima = $t->user->isPenerima();
                @endphp
                <div class="flex gap-3 {{ $isMe ? 'flex-row-reverse' : '' }}" x-data="{ editing: false }">

                    {{-- Avatar --}}
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold flex-shrink-0 mt-1"
                         style="{{ $isPenerima ? 'background:#FEF9C3;color:#854D0E' : 'background:#4E6F5C;color:#D7F487' }}">
                        {{ strtoupper(substr($t->user->name ?? 'U', 0, 1)) }}
                    </div>

                    <div class="flex-1 max-w-[85%]">
                        {{-- Bubble --}}
                        <div x-show="!editing"
                             class="rounded-2xl px-4 py-3 text-sm {{ $isMe ? 'rounded-tr-sm' : 'rounded-tl-sm' }}"
                             style="{{ $isMe ? 'background:#D7F487;color:#2E3D33' : 'background:#F3F4F6;color:#374151' }}">
                            <p class="leading-relaxed">{{ $t->isi_tanggapan }}</p>
                        </div>

                        {{-- Inline edit form --}}
                        @can('update', $t)
                        <form x-show="editing" action="{{ route('tanggapan.update', $t->id) }}" method="POST"
                              class="mt-1" style="display:none">
                            @csrf @method('PUT')
                            <textarea name="isi_tanggapan" rows="2" required
                                class="w-full bg-white border border-green-300 rounded-xl px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300 resize-none"
                                >{{ $t->isi_tanggapan }}</textarea>
                            <div class="flex gap-2 mt-1.5">
                                <button type="submit"
                                    class="text-xs font-semibold px-3 py-1.5 rounded-lg text-white transition"
                                    style="background:#06B13D">Simpan</button>
                                <button type="button" @click="editing = false"
                                    class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition">Batal</button>
                            </div>
                        </form>
                        @endcan

                        {{-- Meta --}}
                        <div class="flex items-center gap-2 mt-1 {{ $isMe ? 'justify-end' : '' }}">
                            <span class="text-[11px] font-semibold {{ $isMe ? 'text-green-700' : 'text-gray-500' }}">
                                {{ $isMe ? 'Saya' : ($t->user->name ?? '-') }}
                                <span class="font-normal text-gray-400 ml-0.5 capitalize">({{ $t->user->role ?? '' }})</span>
                            </span>
                            <span class="text-[10px] text-gray-400">{{ $t->created_at->format('d M Y, H:i') }}</span>

                            @can('update', $t)
                            <button @click="editing = !editing"
                                class="text-[10px] text-gray-400 hover:text-blue-500 transition">
                                <i class="bi bi-pencil"></i>
                            </button>
                            @endcan

                            @can('delete', $t)
                            <form action="{{ route('tanggapan.destroy', $t->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Hapus tanggapan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-[10px] text-gray-400 hover:text-red-500 transition">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>

            {{-- ══ REPLY BOX ══ --}}
            <div class="bg-white rounded-2xl shadow p-4 sticky bottom-4">
                <form action="{{ route('tanggapan.store') }}" method="POST" class="flex gap-3 items-end">
                    @csrf
                    <input type="hidden" name="feedback_id" value="{{ $feedback->id }}">

                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold flex-shrink-0"
                         style="background:#4E6F5C;color:#D7F487">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>

                    <div class="flex-1">
                        <textarea name="isi_tanggapan" rows="1" required
                            placeholder="Tulis balasan..."
                            onInput="this.style.height='auto';this.style.height=this.scrollHeight+'px'"
                            class="w-full bg-gray-100 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300 transition resize-none overflow-hidden"
                            style="min-height:40px;max-height:120px"></textarea>
                    </div>

                    <button type="submit"
                        class="w-10 h-10 flex items-center justify-center rounded-xl text-white transition flex-shrink-0"
                        style="background:#06B13D">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                        </svg>
                    </button>
                </form>
            </div>

        </div>
    </main>
</div>

{{-- Edit feedback modal (penerima only) --}}
@can('update', $feedback)
<div x-data="{ open: false }" @open-edit-feedback.window="open = true">
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display:none">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="open = false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md z-10 overflow-hidden"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between" style="background:#06B13D">
                <h2 class="text-base font-bold text-white">Edit Ulasan</h2>
                <button @click="open = false" class="text-white/70 hover:text-white transition"><i class="bi bi-x-lg"></i></button>
            </div>
            <form action="{{ route('feedback.update', $feedback->id) }}" method="POST"
                  class="p-6 space-y-4" x-data="{ editHover: 0, rating: {{ $feedback->rating }} }">
                @csrf @method('PUT')
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Rating</label>
                    <input type="hidden" name="rating" :value="rating">
                    <div class="flex items-center gap-2">
                        @for($s = 1; $s <= 5; $s++)
                        <button type="button"
                            @click="rating = {{ $s }}"
                            @mouseenter="editHover = {{ $s }}"
                            @mouseleave="editHover = 0"
                            class="text-3xl transition">
                            <i class="bi" :class="(editHover || rating) >= {{ $s }} ? 'bi-star-fill text-yellow-400' : 'bi-star text-gray-300'"></i>
                        </button>
                        @endfor
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">Isi Ulasan <span class="text-gray-300 normal-case font-normal">(opsional)</span></label>
                    <textarea name="isi_ulasan" rows="4"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-400 transition resize-none"
                        >{{ $feedback->isi_ulasan }}</textarea>
                </div>
                <div class="flex gap-3 pt-1">
                    <button type="button" @click="open = false"
                        class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">Batal</button>
                    <button type="submit"
                        class="flex-1 py-2.5 rounded-xl text-sm font-bold text-white transition"
                        style="background:#06B13D">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan

@endsection