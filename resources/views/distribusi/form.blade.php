{{-- CREATE MODAL --}}
<div x-show="openModal"
     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     style="display:none">

    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="openModal = false"></div>

    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg z-10 overflow-hidden max-h-[90vh] flex flex-col"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between flex-shrink-0" style="background:#06B13D">
            <h2 class="text-base font-bold text-white">Tambah Distribusi</h2>
            <button @click="openModal = false" class="text-white/70 hover:text-white transition">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form action="{{ route('distribusi.store') }}" method="POST"
              class="p-6 space-y-4 overflow-y-auto flex-1"
              x-data="{ createStatus: 'pending' }">
            @csrf

            {{-- Penerima --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">Penerima</label>
                <div class="relative">
                    <select name="penerima_id" required
                        class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-400 transition pr-8">
                        <option value="">Pilih Penerima</option>
                        @foreach($penerimas as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-2.5 flex items-center">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>

            {{-- Menu --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">Menu</label>
                <div class="relative">
                    <select name="menu_id" required
                        class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-400 transition pr-8">
                        <option value="">Pilih Menu</option>
                        @foreach($menus as $m)
                            <option value="{{ $m->id }}">{{ $m->nama }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-2.5 flex items-center">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>

            {{-- Jadwal (opsional) --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">
                    Jadwal <span class="text-gray-300 normal-case font-normal">(opsional)</span>
                </label>
                <div class="relative">
                    <select name="jadwal_id"
                        class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-400 transition pr-8">
                        <option value="">Tanpa Jadwal</option>
                        @foreach($jadwals as $j)
                            <option value="{{ $j->id }}">{{ $j->tanggal->format('d M Y') }} — {{ $j->menu->nama ?? '' }} ({{ $j->rt }})</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-2.5 flex items-center">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>

            {{-- Waktu Distribusi --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">Waktu Distribusi</label>
                <input type="datetime-local" name="waktu_distribusi" required
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-400 transition">
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">Status</label>
                <div class="grid grid-cols-3 gap-2">
                    <label class="cursor-pointer">
                        <input type="radio" name="status" value="diterima" x-model="createStatus" class="sr-only">
                        <div class="text-center py-2 rounded-xl text-xs font-semibold border-2 transition"
                             :class="createStatus === 'diterima'
                                ? 'border-transparent text-white'
                                : 'border-gray-200 text-gray-500 bg-gray-50 hover:border-green-300'"
                             :style="createStatus === 'diterima' ? 'background:#06B13D' : ''">
                            Diterima
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="status" value="pending" x-model="createStatus" class="sr-only">
                        <div class="text-center py-2 rounded-xl text-xs font-semibold border-2 transition"
                             :class="createStatus === 'pending'
                                ? 'border-yellow-400 bg-yellow-50 text-yellow-700'
                                : 'border-gray-200 text-gray-500 bg-gray-50 hover:border-yellow-300'">
                            Pending
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="status" value="gagal" x-model="createStatus" class="sr-only">
                        <div class="text-center py-2 rounded-xl text-xs font-semibold border-2 transition"
                             :class="createStatus === 'gagal'
                                ? 'border-red-400 bg-red-50 text-red-600'
                                : 'border-gray-200 text-gray-500 bg-gray-50 hover:border-red-300'">
                            Gagal
                        </div>
                    </label>
                </div>
            </div>

            {{-- Keterangan --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">
                    Keterangan
                    <span x-show="createStatus === 'diterima'" class="text-gray-300 normal-case font-normal">(opsional)</span>
                    <span x-show="createStatus !== 'diterima'" class="text-red-400 normal-case font-normal">*wajib</span>
                </label>
                <textarea name="keterangan" rows="3"
                    :required="createStatus !== 'diterima'"
                    placeholder="Tambahkan keterangan..."
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-400 transition resize-none"></textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" @click="openModal = false"
                    class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 rounded-xl text-sm font-bold text-white transition"
                    style="background:#06B13D">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- EDIT MODAL --}}
<div x-show="editModal"
     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     style="display:none">

    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="editModal = false"></div>

    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg z-10 overflow-hidden max-h-[90vh] flex flex-col"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between flex-shrink-0" style="background:#06B13D">
            <h2 class="text-base font-bold text-white">Edit Distribusi</h2>
            <button @click="editModal = false" class="text-white/70 hover:text-white transition">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form :action="`/distribusi/${editData.id}`" method="POST"
              class="p-6 space-y-4 overflow-y-auto flex-1">
            @csrf
            @method('PUT')

            {{-- Penerima --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">Penerima</label>
                <div class="relative">
                    <select name="penerima_id" required
                        class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-400 transition pr-8">
                        @foreach($penerimas as $p)
                            <option value="{{ $p->id }}" :selected="editData.penerima_id == {{ $p->id }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-2.5 flex items-center">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>

            {{-- Menu --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">Menu</label>
                <div class="relative">
                    <select name="menu_id" required
                        class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-400 transition pr-8">
                        @foreach($menus as $m)
                            <option value="{{ $m->id }}" :selected="editData.menu_id == {{ $m->id }}">{{ $m->nama }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-2.5 flex items-center">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>

            {{-- Jadwal --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">
                    Jadwal <span class="text-gray-300 normal-case font-normal">(opsional)</span>
                </label>
                <div class="relative">
                    <select name="jadwal_id"
                        class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-400 transition pr-8">
                        <option value="" :selected="!editData.jadwal_id">Tanpa Jadwal</option>
                        @foreach($jadwals as $j)
                            <option value="{{ $j->id }}" :selected="editData.jadwal_id == {{ $j->id }}">
                                {{ $j->tanggal->format('d M Y') }} — {{ $j->menu->nama ?? '' }} ({{ $j->rt }})
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-2.5 flex items-center">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>

            {{-- Waktu --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">Waktu Distribusi</label>
                <input type="datetime-local" name="waktu_distribusi" required x-model="editData.waktu_distribusi"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-400 transition">
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">Status</label>
                <div class="grid grid-cols-3 gap-2">
                    <label class="cursor-pointer">
                        <input type="radio" name="status" value="diterima" x-model="editData.status" class="sr-only">
                        <div class="text-center py-2 rounded-xl text-xs font-semibold border-2 transition"
                             :class="editData.status === 'diterima' ? 'border-transparent text-white' : 'border-gray-200 text-gray-500 bg-gray-50 hover:border-green-300'"
                             :style="editData.status === 'diterima' ? 'background:#06B13D' : ''">
                            Diterima
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="status" value="pending" x-model="editData.status" class="sr-only">
                        <div class="text-center py-2 rounded-xl text-xs font-semibold border-2 transition"
                             :class="editData.status === 'pending' ? 'border-yellow-400 bg-yellow-50 text-yellow-700' : 'border-gray-200 text-gray-500 bg-gray-50 hover:border-yellow-300'">
                            Pending
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="status" value="gagal" x-model="editData.status" class="sr-only">
                        <div class="text-center py-2 rounded-xl text-xs font-semibold border-2 transition"
                             :class="editData.status === 'gagal' ? 'border-red-400 bg-red-50 text-red-600' : 'border-gray-200 text-gray-500 bg-gray-50 hover:border-red-300'">
                            Gagal
                        </div>
                    </label>
                </div>
            </div>

            {{-- Keterangan --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">
                    Keterangan
                    <span x-show="editData.status === 'diterima'" class="text-gray-300 normal-case font-normal">(opsional)</span>
                    <span x-show="editData.status !== 'diterima'" class="text-red-400 normal-case font-normal">*wajib</span>
                </label>
                <textarea name="keterangan" rows="3"
                    :required="editData.status !== 'diterima'"
                    x-model="editData.keterangan"
                    placeholder="Tambahkan keterangan..."
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