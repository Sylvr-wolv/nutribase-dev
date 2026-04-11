@php $isEdit = $editMode ?? false; @endphp

<div class="space-y-4">

    {{-- Nama --}}
    <div>
        <label class="block text-xs font-semibold text-[#4E6F5C] uppercase tracking-wide mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
        @if($isEdit)
            <input
                type="text"
                name="name"
                x-ref="modalNama"
                :value="editData.name ?? ''"
                class="w-full px-3 py-2.5 bg-[#FAFCFB] border border-[#CCDFD4] focus:border-[#79C80E] focus:outline-none rounded-xl text-sm"
                required>
        @else
            <input
                type="text"
                name="name"
                x-ref="modalNama"
                value="{{ old('name') }}"
                class="w-full px-3 py-2.5 bg-[#FAFCFB] border border-[#CCDFD4] focus:border-[#79C80E] focus:outline-none rounded-xl text-sm"
                required>
        @endif
    </div>

    {{-- Username (edit only, readonly) --}}
    @if($isEdit)
    <div>
        <label class="block text-xs font-semibold text-[#4E6F5C] uppercase tracking-wide mb-1">Username</label>
        <input
            type="text"
            :value="editData.username ?? ''"
            class="w-full px-3 py-2.5 bg-[#F0F5F2] border border-[#CCDFD4] rounded-xl text-sm text-[#8A9E90] cursor-not-allowed"
            readonly disabled>
        <p class="text-xs text-[#A0B4A7] mt-1">Username tidak dapat diubah.</p>
    </div>
    @endif

    {{-- NIK --}}
    <div>
        <label class="block text-xs font-semibold text-[#4E6F5C] uppercase tracking-wide mb-1">NIK <span class="text-red-500">*</span></label>
        @if($isEdit)
            <input
                type="text"
                name="nik"
                :value="editData.nik ?? ''"
                maxlength="16" minlength="16"
                class="w-full px-3 py-2.5 bg-[#FAFCFB] border border-[#CCDFD4] focus:border-[#79C80E] focus:outline-none rounded-xl text-sm font-mono tracking-wider"
                required>
        @else
            <input
                type="text"
                name="nik"
                value="{{ old('nik') }}"
                maxlength="16" minlength="16"
                class="w-full px-3 py-2.5 bg-[#FAFCFB] border border-[#CCDFD4] focus:border-[#79C80E] focus:outline-none rounded-xl text-sm font-mono tracking-wider"
                required>
            <p class="text-xs text-[#A0B4A7] mt-1">Password awal = NIK</p>
        @endif
    </div>

    {{-- 2 kolom: Telepon + RT --}}
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-xs font-semibold text-[#4E6F5C] uppercase tracking-wide mb-1">No. Telepon</label>
            @if($isEdit)
                <input type="text" name="no_telepon" :value="editData.no_telepon ?? ''"
                    class="w-full px-3 py-2.5 bg-[#FAFCFB] border border-[#CCDFD4] focus:border-[#79C80E] focus:outline-none rounded-xl text-sm">
            @else
                <input type="text" name="no_telepon" value="{{ old('no_telepon') }}"
                    class="w-full px-3 py-2.5 bg-[#FAFCFB] border border-[#CCDFD4] focus:border-[#79C80E] focus:outline-none rounded-xl text-sm">
            @endif
        </div>
        <div>
            <label class="block text-xs font-semibold text-[#4E6F5C] uppercase tracking-wide mb-1">RT <span class="text-red-500">*</span></label>
            @if($isEdit)
                <input type="text" name="rt" :value="editData.rt ?? ''"
                    class="w-full px-3 py-2.5 bg-[#FAFCFB] border border-[#CCDFD4] focus:border-[#79C80E] focus:outline-none rounded-xl text-sm"
                    required>
            @else
                <input type="text" name="rt" value="{{ old('rt') }}"
                    class="w-full px-3 py-2.5 bg-[#FAFCFB] border border-[#CCDFD4] focus:border-[#79C80E] focus:outline-none rounded-xl text-sm"
                    required>
            @endif
        </div>
    </div>

    {{-- Alamat --}}
    <div>
        <label class="block text-xs font-semibold text-[#4E6F5C] uppercase tracking-wide mb-1">Alamat <span class="text-red-500">*</span></label>
        @if($isEdit)
            <textarea
                name="alamat"
                rows="2"
                x-text="editData.alamat ?? ''"
                class="w-full px-3 py-2.5 bg-[#FAFCFB] border border-[#CCDFD4] focus:border-[#79C80E] focus:outline-none rounded-xl text-sm resize-none"
                required></textarea>
        @else
            <textarea
                name="alamat"
                rows="2"
                class="w-full px-3 py-2.5 bg-[#FAFCFB] border border-[#CCDFD4] focus:border-[#79C80E] focus:outline-none rounded-xl text-sm resize-none"
                required>{{ old('alamat') }}</textarea>
        @endif
    </div>

    {{-- Kategori --}}
    <div>
        <label class="block text-xs font-semibold text-[#4E6F5C] uppercase tracking-wide mb-1">Kategori <span class="text-red-500">*</span></label>
        @if($isEdit)
            {{-- Kategori - edit mode --}}
            <select
            name="kategori"
            x-model="kategori"
            x-effect="kategori = editData.kategori ?? kategori"
            class="w-full px-3 py-2.5 bg-[#FAFCFB] border border-[#CCDFD4] focus:border-[#79C80E] focus:outline-none rounded-xl text-sm"
            required>
            <option value="">— Pilih —</option>
            <option value="ibu_hamil">Ibu Hamil</option>
            <option value="ibu_menyusui">Ibu Menyusui</option>
            <option value="balita">Balita</option>
            <option value="lainnya">Lainnya</option>
            </select>
        @else
            <select
                name="kategori"
                x-model="kategori"
                class="w-full px-3 py-2.5 bg-[#FAFCFB] border border-[#CCDFD4] focus:border-[#79C80E] focus:outline-none rounded-xl text-sm"
                required>
                <option value="">— Pilih —</option>
                <option value="ibu_hamil"    {{ old('kategori') === 'ibu_hamil'    ? 'selected' : '' }}>Ibu Hamil</option>
                <option value="ibu_menyusui" {{ old('kategori') === 'ibu_menyusui' ? 'selected' : '' }}>Ibu Menyusui</option>
                <option value="balita"       {{ old('kategori') === 'balita'       ? 'selected' : '' }}>Balita</option>
                <option value="lainnya"      {{ old('kategori') === 'lainnya'      ? 'selected' : '' }}>Lainnya</option>
            </select>
        @endif
    </div>

    {{-- Deskripsi (hanya jika kategori = lainnya) --}}
    <div x-show="kategori === 'lainnya'" x-cloak>
        <label class="block text-xs font-semibold text-[#4E6F5C] uppercase tracking-wide mb-1">Deskripsi <span class="text-red-500">*</span></label>
        @if($isEdit)
            <textarea
                name="deskripsi_kategori"
                rows="2"
                x-text="editData.deskripsi_kategori ?? ''"
                class="w-full px-3 py-2.5 bg-[#FAFCFB] border border-[#CCDFD4] focus:border-[#79C80E] focus:outline-none rounded-xl text-sm resize-none"></textarea>
        @else
            <textarea
                name="deskripsi_kategori"
                rows="2"
                class="w-full px-3 py-2.5 bg-[#FAFCFB] border border-[#CCDFD4] focus:border-[#79C80E] focus:outline-none rounded-xl text-sm resize-none">{{ old('deskripsi_kategori') }}</textarea>
        @endif
    </div>

    {{-- Estimasi Selesai --}}
    <div>
        <label class="block text-xs font-semibold text-[#4E6F5C] uppercase tracking-wide mb-1">Estimasi Selesai <span class="text-red-500">*</span></label>
        @if($isEdit)
            <input
                type="date"
                name="estimasi_durasi"
                :value="editData.estimasi_durasi ?? ''"
                class="w-full px-3 py-2.5 bg-[#FAFCFB] border border-[#CCDFD4] focus:border-[#79C80E] focus:outline-none rounded-xl text-sm"
                required>
        @else
            <input
                type="date"
                name="estimasi_durasi"
                value="{{ old('estimasi_durasi') }}"
                class="w-full px-3 py-2.5 bg-[#FAFCFB] border border-[#CCDFD4] focus:border-[#79C80E] focus:outline-none rounded-xl text-sm"
                required>
        @endif
    </div>

</div>