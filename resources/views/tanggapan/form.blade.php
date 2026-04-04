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
            <h2 class="text-base font-bold text-white">Edit Tanggapan</h2>
            <button @click="editModal = false" class="text-white/70 hover:text-white transition">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form :action="`/tanggapan/${editData.id}`" method="POST"
              class="p-6 space-y-4 overflow-y-auto flex-1">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">Isi Tanggapan</label>
                <textarea name="isi_tanggapan" rows="4" required
                    x-model="editData.isi_tanggapan"
                    placeholder="Tulis tanggapan Anda..."
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