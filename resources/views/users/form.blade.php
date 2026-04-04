{{-- CREATE MODAL --}}
@can('create', App\Models\User::class)
<div x-show="openModal"
     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     style="display:none">

    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="openModal = false"></div>

    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg z-10 overflow-hidden max-h-[90vh] flex flex-col"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between flex-shrink-0" style="background:#06B13D">
            <h2 class="text-base font-bold text-white">Tambah Pengguna</h2>
            <button @click="openModal = false" class="text-white/70 hover:text-white transition">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form action="{{ route('users.store') }}" method="POST"
              class="p-6 space-y-4 overflow-y-auto flex-1"
              x-data="{ createRole: 'penerima', showPass: false }">
            @csrf

            {{-- Nama --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" required maxlength="100"
                    placeholder="Masukkan nama lengkap"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-400 transition">
            </div>

            {{-- Username --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">Username</label>
                <input type="text" name="username" required maxlength="50"
                    placeholder="Masukkan username unik"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-400 transition font-mono">
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">Password</label>
                <div class="relative">
                    :type="showPass ? 'text' : 'password'"
                    <input :type="showPass ? 'text' : 'password'" name="password" required minlength="6"
                        placeholder="Min. 6 karakter"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 pr-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-400 transition">
                    <button type="button" @click="showPass = !showPass"
                        class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                        <i class="bi" :class="showPass ? 'bi-eye-slash' : 'bi-eye'"></i>
                    </button>
                </div>
            </div>

            {{-- Role --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Role</label>
                <div class="grid grid-cols-3 gap-2">
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="penerima" x-model="createRole" class="sr-only">
                        <div class="text-center py-2 rounded-xl text-xs font-semibold border-2 transition"
                             :class="createRole === 'penerima'
                                ? 'border-yellow-400 bg-yellow-50 text-yellow-700'
                                : 'border-gray-200 text-gray-500 bg-gray-50 hover:border-yellow-300'">
                            Penerima
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="kader" x-model="createRole" class="sr-only">
                        <div class="text-center py-2 rounded-xl text-xs font-semibold border-2 transition"
                             :class="createRole === 'kader'
                                ? 'border-transparent text-white'
                                : 'border-gray-200 text-gray-500 bg-gray-50 hover:border-green-300'"
                             :style="createRole === 'kader' ? 'background:#06B13D' : ''">
                            Kader
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="koordinator" x-model="createRole" class="sr-only">
                        <div class="text-center py-2 rounded-xl text-xs font-semibold border-2 transition"
                             :class="createRole === 'koordinator'
                                ? 'border-blue-400 bg-blue-50 text-blue-700'
                                : 'border-gray-200 text-gray-500 bg-gray-50 hover:border-blue-300'">
                            Koordinator
                        </div>
                    </label>
                </div>
            </div>

            {{-- Info: penerima needs profile --}}
            <div x-show="createRole === 'penerima'"
                 class="flex items-start gap-2.5 rounded-xl px-3 py-2.5 text-xs"
                 style="background:#FEF9C3;color:#854D0E">
                <i class="bi bi-info-circle-fill mt-0.5 flex-shrink-0"></i>
                <span>Setelah akun dibuat, lengkapi data profil penerima melalui menu <strong>Data Penerima</strong>.</span>
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
@endcan

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
            <h2 class="text-base font-bold text-white">Edit Pengguna</h2>
            <button @click="editModal = false" class="text-white/70 hover:text-white transition">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form :action="`/users/${editData.id}`" method="POST"
              class="p-6 space-y-4 overflow-y-auto flex-1"
              x-data="{ showPass: false }">
            @csrf
            @method('PUT')

            {{-- Nama --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" required maxlength="100"
                    x-model="editData.name"
                    placeholder="Masukkan nama lengkap"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-400 transition">
            </div>

            {{-- Username --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">Username</label>
                <input type="text" name="username" required maxlength="50"
                    x-model="editData.username"
                    placeholder="Masukkan username"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-400 transition font-mono">
            </div>

            {{-- Password (opsional) --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1.5">
                    Password <span class="text-gray-300 normal-case font-normal">(kosongkan jika tidak diubah)</span>
                </label>
                <div class="relative">
                    <input :type="showPass ? 'text' : 'password'" name="password" minlength="6"
                        placeholder="Min. 6 karakter"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 pr-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-400 transition">
                    <button type="button" @click="showPass = !showPass"
                        class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                        <i class="bi" :class="showPass ? 'bi-eye-slash' : 'bi-eye'"></i>
                    </button>
                </div>
            </div>

            {{-- Role --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Role</label>
                <div class="grid grid-cols-3 gap-2">
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="penerima" x-model="editData.role" class="sr-only">
                        <div class="text-center py-2 rounded-xl text-xs font-semibold border-2 transition"
                             :class="editData.role === 'penerima'
                                ? 'border-yellow-400 bg-yellow-50 text-yellow-700'
                                : 'border-gray-200 text-gray-500 bg-gray-50 hover:border-yellow-300'">
                            Penerima
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="kader" x-model="editData.role" class="sr-only">
                        <div class="text-center py-2 rounded-xl text-xs font-semibold border-2 transition"
                             :class="editData.role === 'kader'
                                ? 'border-transparent text-white'
                                : 'border-gray-200 text-gray-500 bg-gray-50 hover:border-green-300'"
                             :style="editData.role === 'kader' ? 'background:#06B13D' : ''">
                            Kader
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="koordinator" x-model="editData.role" class="sr-only">
                        <div class="text-center py-2 rounded-xl text-xs font-semibold border-2 transition"
                             :class="editData.role === 'koordinator'
                                ? 'border-blue-400 bg-blue-50 text-blue-700'
                                : 'border-gray-200 text-gray-500 bg-gray-50 hover:border-blue-300'">
                            Koordinator
                        </div>
                    </label>
                </div>
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