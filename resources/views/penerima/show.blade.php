@extends('layouts.app')

@section('title', 'Detail Penerima — ' . ($penerima->user->name ?? ''))

@section('content')
<div class="min-h-screen bg-[#F8FAFC]">
    {{-- Header Bar --}}
    <div class="bg-white border-b border-slate-200 px-6 py-5 sticky top-0 z-10">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-4">
            <nav class="flex items-center text-sm font-semibold">
                @php $indexRoute = 'penerima.index'; @endphp
                <a href="{{ route($indexRoute) }}" class="text-slate-400 hover:text-emerald-600 transition-colors">Penerima</a>
                <i class="bi bi-chevron-right mx-3 text-slate-300 text-[10px]"></i>
                <span class="text-slate-900 uppercase tracking-wider text-xs">Detail Profil</span>
            </nav>

            <div class="flex items-center gap-3">
                <a href="{{ route($indexRoute) }}" class="px-4 py-2 text-sm font-bold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-all shadow-sm">
                    <i class="bi bi-arrow-left mr-2"></i> Kembali
                </a>
                <a href="{{ route('penerima.edit', $penerima) }}" class="px-4 py-2 text-sm font-bold text-white bg-slate-900 rounded-xl hover:bg-slate-800 transition-all shadow-md">
                    <i class="bi bi-pencil-square mr-2"></i> Edit Data
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            {{-- Profile Sidebar --}}
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                    <div class="h-28 bg-gradient-to-br from-emerald-500 to-teal-700"></div>
                    <div class="px-8 pb-8 text-center">
                        <div class="relative -mt-14 inline-block">
                            <div class="w-28 h-28 bg-white p-1.5 rounded-[2.5rem] shadow-xl">
                                <div class="w-full h-full bg-emerald-100 rounded-[2rem] flex items-center justify-center text-4xl font-black text-emerald-700">
                                    {{ strtoupper(substr($penerima->user->name ?? '-', 0, 1)) }}
                                </div>
                            </div>
                        </div>
                        
                        <h2 class="mt-4 text-2xl font-bold text-slate-900">{{ $penerima->user->name ?? '-' }}</h2>
                        <p class="text-sm font-medium text-slate-400">@ {{ $penerima->user->username ?? 'username' }}</p>
                        
                        <div class="mt-4 inline-flex px-4 py-1.5 rounded-full text-[11px] font-bold tracking-widest uppercase border border-emerald-100 bg-emerald-50 text-emerald-700">
                            {{ str_replace('_', ' ', $penerima->kategori) }}
                        </div>

                        <div class="mt-10 space-y-4 text-left">
                            <div class="p-4 rounded-2xl bg-slate-50/50 border border-slate-100">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">NIK Personal</p>
                                <p class="font-mono text-slate-900 tracking-tighter">{{ $penerima->nik }}</p>
                            </div>
                            <div class="p-4 rounded-2xl bg-slate-50/50 border border-slate-100">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Kontak & Alamat</p>
                                <p class="text-sm font-semibold text-slate-900">{{ $penerima->no_telepon ?? 'No Telp —' }}</p>
                                <p class="text-xs text-slate-500 mt-1">{{ $penerima->alamat }} (RT {{ $penerima->rt }})</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Status Cards --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white p-5 rounded-[1.5rem] border border-slate-200 shadow-sm">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Distribusi</p>
                        <p class="text-3xl font-black text-slate-900 mt-1">{{ $penerima->distribusis->count() }}</p>
                    </div>
                    <div class="bg-emerald-600 p-5 rounded-[1.5rem] shadow-lg shadow-emerald-100">
                        <p class="text-[10px] font-bold text-emerald-100 uppercase tracking-wider">Rating</p>
                        <div class="flex items-center gap-1 mt-1">
                            <p class="text-3xl font-black text-white">{{ $penerima->feedbacks->count() ? number_format($penerima->feedbacks->avg('rating'), 1) : '0' }}</p>
                            <i class="bi bi-star-fill text-emerald-300 text-sm"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Activity Content --}}
            <div class="lg:col-span-8 space-y-8">
                {{-- Timeline / History --}}
                <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center">
                        <h3 class="font-bold text-slate-900 flex items-center">
                            <i class="bi bi-journal-text mr-3 text-emerald-500"></i> Log Distribusi Makanan
                        </h3>
                    </div>

                    @if($penerima->distribusis->isEmpty())
                        <div class="p-20 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                <i class="bi bi-box-seize text-2xl"></i>
                            </div>
                            <p class="text-slate-400 text-sm font-medium">Belum ada riwayat distribusi</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-slate-50/50">
                                    <tr>
                                        <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase">Waktu</th>
                                        <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase">Menu</th>
                                        <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @foreach($penerima->distribusis->sortByDesc('waktu_distribusi')->take(8) as $d)
                                    <tr class="group hover:bg-slate-50/50 transition-colors">
                                        <td class="px-8 py-5">
                                            <p class="text-sm font-bold text-slate-800">{{ \Carbon\Carbon::parse($d->waktu_distribusi)->translatedFormat('d M Y') }}</p>
                                            <p class="text-[11px] text-slate-400">{{ \Carbon\Carbon::parse($d->waktu_distribusi)->format('H:i') }} WIB</p>
                                        </td>
                                        <td class="px-8 py-5">
                                            <p class="text-sm font-semibold text-slate-700">{{ $d->menu->nama_menu ?? 'Menu Standar' }}</p>
                                            <p class="text-xs text-slate-400 italic mt-0.5 truncate max-w-xs">{{ $d->keterangan ?? '—' }}</p>
                                        </td>
                                        <td class="px-8 py-5 text-center">
                                            <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest 
                                                {{ $d->status == 'diterima' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                                {{ $d->status }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                {{-- Feedback --}}
                <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm p-8">
                    <h3 class="font-bold text-slate-900 mb-6 flex items-center">
                        <i class="bi bi-chat-heart-fill mr-3 text-rose-500"></i> Ulasan Terakhir
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($penerima->feedbacks->sortByDesc('created_at')->take(4) as $fb)
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100">
                            <div class="flex justify-between mb-3">
                                <div class="flex gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $fb->rating ? '-fill text-amber-400' : ' text-slate-200' }} text-xs"></i>
                                    @endfor
                                </div>
                                <span class="text-[10px] font-bold text-slate-400">{{ $fb->created_at->format('d/m/y') }}</span>
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed italic">"{{ $fb->isi_ulasan ?? 'Rating diberikan tanpa komentar.' }}"</p>
                        </div>
                        @empty
                        <div class="col-span-2 py-10 text-center text-slate-400 text-sm italic">
                            Belum ada ulasan masuk.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection