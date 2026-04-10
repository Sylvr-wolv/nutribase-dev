@php $isEdit = $editMode ?? false; @endphp

<div class="space-y-4">

    {{-- Nama Menu --}}
    <div>
        <label class="block text-xs font-semibold text-[#4E6F5C] uppercase tracking-wide mb-1">
            Nama Menu <span class="text-red-500">*</span>
        </label>
        @if($isEdit)
            <input type="text" name="nama_menu"
                :value="editData.nama_menu ?? ''"
                class="w-full px-3 py-2.5 bg-[#FAFCFB] border border-[#CCDFD4] focus:border-[#79C80E] focus:outline-none rounded-xl text-sm"
                required>
        @else
            <input type="text" name="nama_menu"
                value="{{ old('nama_menu') }}"
                x-ref="modalNama"
                class="w-full px-3 py-2.5 bg-[#FAFCFB] border border-[#CCDFD4] focus:border-[#79C80E] focus:outline-none rounded-xl text-sm"
                required>
        @endif
    </div>

    {{-- Deskripsi --}}
    <div>
        <label class="block text-xs font-semibold text-[#4E6F5C] uppercase tracking-wide mb-1">
            Deskripsi
        </label>
        @if($isEdit)
            <textarea name="deskripsi" rows="3"
                x-text="editData.deskripsi ?? ''"
                class="w-full px-3 py-2.5 bg-[#FAFCFB] border border-[#CCDFD4] focus:border-[#79C80E] focus:outline-none rounded-xl text-sm resize-none"></textarea>
        @else
            <textarea name="deskripsi" rows="3"
                class="w-full px-3 py-2.5 bg-[#FAFCFB] border border-[#CCDFD4] focus:border-[#79C80E] focus:outline-none rounded-xl text-sm resize-none">{{ old('deskripsi') }}</textarea>
        @endif
    </div>

    {{-- Stok --}}
    <div>
        <label class="block text-xs font-semibold text-[#4E6F5C] uppercase tracking-wide mb-1">
            Stok <span class="text-red-500">*</span>
        </label>
        @if($isEdit)
            <input type="number" name="stok" min="0"
                :value="editData.stok ?? 0"
                class="w-full px-3 py-2.5 bg-[#FAFCFB] border border-[#CCDFD4] focus:border-[#79C80E] focus:outline-none rounded-xl text-sm"
                required>
        @else
            <input type="number" name="stok" min="0"
                value="{{ old('stok', 0) }}"
                class="w-full px-3 py-2.5 bg-[#FAFCFB] border border-[#CCDFD4] focus:border-[#79C80E] focus:outline-none rounded-xl text-sm"
                required>
        @endif
        <p class="text-xs text-[#A0B4A7] mt-1">Jumlah porsi yang tersedia.</p>
    </div>

    {{-- Gambar --}}
    <div x-data="{ preview: null, fileName: '' }">
        <label class="block text-xs font-semibold text-[#4E6F5C] uppercase tracking-wide mb-1">
            Gambar Menu
        </label>

        {{-- Current image preview (edit mode only) --}}
        @if($isEdit)
            <div x-show="editData.gambar && !preview" class="mb-2">
                <img :src="'/storage/' + editData.gambar"
                     class="w-full h-36 object-cover rounded-xl border border-[#DFF0E5]"
                     alt="Gambar menu">
                <label class="mt-1.5 flex items-center gap-2 cursor-pointer select-none w-fit">
                    <input type="checkbox" name="hapus_gambar" value="1"
                           class="rounded border-[#CCDFD4] text-red-500 focus:ring-red-300">
                    <span class="text-xs text-red-500 font-medium">Hapus gambar</span>
                </label>
            </div>
        @endif

        {{-- New image preview --}}
        <div x-show="preview" class="mb-2">
            <img :src="preview" class="w-full h-36 object-cover rounded-xl border border-[#DFF0E5]" alt="Preview">
        </div>

        {{-- File input --}}
        <label class="flex items-center gap-3 w-full px-3 py-2.5 bg-[#FAFCFB] border border-dashed border-[#CCDFD4] hover:border-[#79C80E] rounded-xl cursor-pointer transition group">
            <i class="bi bi-image text-[#A0B4A7] group-hover:text-[#79C80E] text-base transition"></i>
            <span class="text-sm text-[#8A9E90] flex-1 truncate"
                  x-text="fileName || 'Pilih gambar (jpg, png, webp – maks 2MB)'"></span>
            <input type="file" name="gambar" accept="image/jpeg,image/png,image/webp" class="hidden"
                @change="
                    const f = $event.target.files[0];
                    if (f) {
                        fileName = f.name;
                        const r = new FileReader();
                        r.onload = e => preview = e.target.result;
                        r.readAsDataURL(f);
                    } else {
                        fileName = '';
                        preview = null;
                    }
                ">
        </label>
        <p class="text-xs text-[#A0B4A7] mt-1">Opsional. Biarkan kosong jika tidak ingin mengubah gambar.</p>
    </div>

</div>