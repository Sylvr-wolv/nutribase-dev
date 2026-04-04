@extends('layouts.public')
@section('title', 'Beranda — Nutribase')

@section('content')

<section class="max-w-6xl mx-auto px-6 py-20">

    <div class="grid md:grid-cols-2 gap-12 items-center">

        <div>
            <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-6">
                Sistem Pengelolaan
                <span class="text-primary">Makanan Bergizi Gratis</span>
            </h1>

            <p class="text-gray-600 mb-6">
                Nutribase membantu pengelolaan program MBG secara terstruktur,
                transparan, dan efisien mulai dari distribusi hingga pelaporan.
            </p>

            <div class="flex gap-4">
                <a href="{{ route('tentang') }}"
                   class="bg-primary text-white px-6 py-3 rounded-lg">
                   Pelajari Lebih Lanjut
                </a>

                <a href="{{ route('kontak') }}"
                   class="border border-primary text-primary px-6 py-3 rounded-lg">
                   Hubungi Kami
                </a>
            </div>
        </div>

        <div>
            <img src="https://undraw.co/api/illustrations/food.svg" class="w-full">
        </div>

    </div>

</section>

{{-- FITUR --}}
<section class="bg-white py-20">
    <div class="max-w-6xl mx-auto px-6">

        <h2 class="text-2xl font-bold text-center mb-12">
            Fitur Utama Sistem
        </h2>

        <div class="grid md:grid-cols-3 gap-8">

            <div class="p-6 border rounded-xl">
                <h3 class="font-semibold mb-2">Manajemen Penerima</h3>
                <p class="text-sm text-gray-600">
                    Mengelola data penerima bantuan secara terpusat dan akurat.
                </p>
            </div>

            <div class="p-6 border rounded-xl">
                <h3 class="font-semibold mb-2">Distribusi Terjadwal</h3>
                <p class="text-sm text-gray-600">
                    Pengaturan jadwal distribusi makanan yang efisien.
                </p>
            </div>

            <div class="p-6 border rounded-xl">
                <h3 class="font-semibold mb-2">Monitoring & Laporan</h3>
                <p class="text-sm text-gray-600">
                    Memantau aktivitas dan laporan program secara real-time.
                </p>
            </div>

        </div>

    </div>
</section>

@endsection