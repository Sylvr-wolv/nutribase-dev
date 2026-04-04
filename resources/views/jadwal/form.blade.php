{{-- CREATE MODAL --}}
<div
    x-show="openModal"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="display:none">

    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="openModal = false"></div>

    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md z-10 overflow-hidden"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">

        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between"
             style="background:#06B13D">
            <h2 class="text-base font-bold text-white">Tambah Jadwal</h2>
            <button @click="openModal = false" class="text-white/70 hover:text-white transition">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form action="{{ route('jadwal.store') }}" method="POST" class="p-6 space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">Menu</label>
                <div class="relative">
                    <select name="menu_id" required
                        class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 pr-8"
                        style="focus:ring-color:#06B13D">
                        <option value="">Pilih Menu</option>
                        @foreach($menus as $menu)
                            <option value="{{ $menu->id }}">{{ $menu->nama }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-2.5 flex items-center">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">Tanggal</label>
                <input type="date" name="tanggal" required
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-400 transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">RT</label>
                <input type="text" name="rt" maxlength="10" required placeholder="Contoh: RT 01"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-400 transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">Keterangan <span class="text-gray-300 normal-case font-normal">(opsional)</span></label>
                <textarea name="keterangan" rows="3" placeholder="Tambahkan keterangan..."
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
<div
    x-show="editModal"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="display:none">

    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="editModal = false"></div>

    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md z-10 overflow-hidden"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">

        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between"
             style="background:#06B13D">
            <h2 class="text-base font-bold text-white">Edit Jadwal</h2>
            <button @click="editModal = false" class="text-white/70 hover:text-white transition">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form :action="`/jadwal/${editData.id}`" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">Menu</label>
                <div class="relative">
                    <select name="menu_id" required
                        class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-400 transition pr-8">
                        @foreach($menus as $menu)
                            <option value="{{ $menu->id }}" :selected="editData.menu_id == {{ $menu->id }}">{{ $menu->nama }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-2.5 flex items-center">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">Tanggal</label>
                <input type="date" name="tanggal" required x-model="editData.tanggal"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-400 transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">RT</label>
                <input type="text" name="rt" maxlength="10" required x-model="editData.rt"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-400 transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">Keterangan <span class="text-gray-300 normal-case font-normal">(opsional)</span></label>
                <textarea name="keterangan" rows="3" x-model="editData.keterangan"
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