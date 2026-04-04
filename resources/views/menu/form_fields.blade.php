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

</div>