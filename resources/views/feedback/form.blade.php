{{-- ===== CREATE MODAL (penerima only) ===== --}}
@can('create', App\Models\Feedback::class)
<div x-show="openModal"
     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     style="display:none">

    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="openModal = false"></div>

    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg z-10 overflow-hidden max-h-[90vh] flex flex-col"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between flex-shrink-0" style="background:#06B13D">
            <h2 class="text-base font-bold text-white">Tambah Ulasan</h2>
            <button @click="openModal = false" class="text-white/70 hover:text-white transition">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form action="{{ route('feedback.store') }}" method="POST"
              class="p-6 space-y-4 overflow-y-auto flex-1"
              x-data="{ createRating: 0, hoverRating: 0 }">
            @csrf

            {{-- Distribusi --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">Distribusi</label>
                <div class="relative">
                    <select name="distribusi_id" required
                        class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-400 transition pr-8">
                        <option value="">Pilih Distribusi</option>
                        @foreach($distribusiList as $d)
                            <option value="{{ $d->id }}">{{ $d->waktu_distribusi->format('d M Y') }} — {{ $d->menu->nama ?? '-' }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-2.5 flex items-center">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>

            {{-- Hidden penerima_id --}}
            <input type="hidden" name="penerima_id" value="{{ auth()->user()->penerimaProfile->id ?? '' }}">

            {{-- Rating --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Rating</label>
                <input type="hidden" name="rating" :value="createRating" required>
                <div class="flex items-center gap-2">
                    @for($s = 1; $s <= 5; $s++)
                    <button type="button"
                        @click="createRating = {{ $s }}"
                        @mouseenter="hoverRating = {{ $s }}"
                        @mouseleave="hoverRating = 0"
                        class="text-3xl transition">
                        <i class="bi"
                           :class="(hoverRating || createRating) >= {{ $s }} ? 'bi-star-fill text-yellow-400' : 'bi-star text-gray-300'"></i>
                    </button>
                    @endfor
                    <span class="text-sm text-gray-500 ml-1" x-text="createRating ? createRating + ' bintang' : 'Pilih rating'"></span>
                </div>
            </div>

            {{-- Isi Ulasan --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">
                    Isi Ulasan <span class="text-gray-300 normal-case font-normal">(opsional)</span>
                </label>
                <textarea name="isi_ulasan" rows="4"
                    placeholder="Ceritakan pengalaman Anda..."
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-400 transition resize-none"></textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" @click="openModal = false"
                    class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                    Batal
                </button>
                <button type="submit" :disabled="createRating === 0"
                    class="flex-1 py-2.5 rounded-xl text-sm font-bold text-white transition disabled:opacity-50"
                    style="background:#06B13D">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endcan

{{-- ===== EDIT MODAL (penerima only) ===== --}}
<div x-show="editModal"
     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     style="display:none">

    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="editModal = false"></div>

    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg z-10 overflow-hidden max-h-[90vh] flex flex-col"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between flex-shrink-0" style="background:#06B13D">
            <h2 class="text-base font-bold text-white">Edit Ulasan</h2>
            <button @click="editModal = false" class="text-white/70 hover:text-white transition">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form :action="`/feedback/${editData.id}`" method="POST"
              class="p-6 space-y-4 overflow-y-auto flex-1"
              x-data="{ editHover: 0 }">
            @csrf
            @method('PUT')

            {{-- Rating --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Rating</label>
                <input type="hidden" name="rating" :value="editData.rating">
                <div class="flex items-center gap-2">
                    @for($s = 1; $s <= 5; $s++)
                    <button type="button"
                        @click="editData.rating = {{ $s }}"
                        @mouseenter="editHover = {{ $s }}"
                        @mouseleave="editHover = 0"
                        class="text-3xl transition">
                        <i class="bi"
                           :class="(editHover || editData.rating) >= {{ $s }} ? 'bi-star-fill text-yellow-400' : 'bi-star text-gray-300'"></i>
                    </button>
                    @endfor
                    <span class="text-sm text-gray-500 ml-1" x-text="editData.rating ? editData.rating + ' bintang' : ''"></span>
                </div>
            </div>

            {{-- Isi Ulasan --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">
                    Isi Ulasan <span class="text-gray-300 normal-case font-normal">(opsional)</span>
                </label>
                <textarea name="isi_ulasan" rows="4"
                    x-model="editData.isi_ulasan"
                    placeholder="Ceritakan pengalaman Anda..."
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-400 transition resize-none"></textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" @click="editModal = false"
                    class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 rounded-xl text-sm font-bold text-white transition"
                    style="background:#06B13D">
                    Perbarui
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ===== TANGGAPAN MODAL (kader/koordinator) ===== --}}
@can('create', App\Models\Tanggapan::class)
<div x-show="tanggapanModal"
     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     style="display:none">

    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="tanggapanModal = false"></div>

    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg z-10 overflow-hidden max-h-[90vh] flex flex-col"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between flex-shrink-0" style="background:#06B13D">
            <h2 class="text-base font-bold text-white">Tanggapi Ulasan</h2>
            <button @click="tanggapanModal = false" class="text-white/70 hover:text-white transition">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="p-6 overflow-y-auto flex-1 space-y-4">

            {{-- Feedback summary --}}
            <div class="rounded-xl p-4" style="background:#F0FDF4;border:1px solid #D7F487">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-semibold text-gray-700" x-text="tanggapanFeedback.penerima"></span>
                    <div class="flex items-center gap-0.5">
                        <template x-for="s in 5" :key="s">
                            <i class="bi bi-star-fill text-xs" :class="s <= tanggapanFeedback.rating ? 'text-yellow-400' : 'text-gray-200'"></i>
                        </template>
                    </div>
                </div>
                <p class="text-sm text-gray-600 italic" x-text="tanggapanFeedback.ulasan || 'Tidak ada isi ulasan.'"></p>
            </div>

            {{-- Existing tanggapans --}}
            <div x-show="tanggapanFeedback.tanggapans && tanggapanFeedback.tanggapans.length > 0">
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Tanggapan Sebelumnya</p>
                <div class="space-y-2">
                    <template x-for="t in tanggapanFeedback.tanggapans" :key="t.id">
                        <div class="bg-gray-50 rounded-xl p-3">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-semibold text-gray-600" x-text="t.user"></span>
                                <span class="text-xs text-gray-400" x-text="t.tgl"></span>
                            </div>
                            <p class="text-sm text-gray-700" x-text="t.isi"></p>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Form tanggapan baru --}}
            <form action="{{ route('tanggapan.store') }}" method="POST" class="space-y-3">
                @csrf
                <input type="hidden" name="feedback_id" :value="tanggapanFeedback.id">

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">Tulis Tanggapan</label>
                    <textarea name="isi_tanggapan" rows="3" required
                        placeholder="Tulis tanggapan Anda..."
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-400 transition resize-none"></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" @click="tanggapanModal = false"
                        class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 py-2.5 rounded-xl text-sm font-bold text-white transition"
                        style="background:#06B13D">
                        Kirim Tanggapan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan