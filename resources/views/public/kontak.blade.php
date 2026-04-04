@extends('layouts.public')
@section('title', 'Kontak — Nutribase')

@section('content')

<div class="max-w-5xl mx-auto px-6 py-20">

    <h1 class="text-3xl font-bold mb-10 text-center">
        Hubungi Kami
    </h1>

    <div class="grid md:grid-cols-2 gap-12">

        {{-- INFO --}}
        <div>
            <p class="text-gray-600 mb-6">
                Jika Anda memiliki pertanyaan, saran, atau ingin bekerja sama,
                silakan hubungi kami melalui informasi berikut:
            </p>

            <div class="space-y-4 text-sm">
                <p><strong>Email:</strong> nutribase@email.com</p>
                <p><strong>Telepon:</strong> +62 812-xxxx-xxxx</p>
                <p><strong>Alamat:</strong> Subang, Indonesia</p>
            </div>
        </div>

        {{-- FORM --}}
        <div class="bg-white border rounded-xl p-8">

            <h3 class="text-lg font-semibold mb-6">Kirim Pesan</h3>

            <form method="POST" action="#">
                @csrf

                <div class="mb-4">
                    <input type="text" placeholder="Nama"
                        class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary outline-none">
                </div>

                <div class="mb-4">
                    <input type="email" placeholder="Email"
                        class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary outline-none">
                </div>

                <div class="mb-4">
                    <textarea rows="4" placeholder="Pesan"
                        class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary outline-none"></textarea>
                </div>

                <button class="w-full bg-primary text-white py-3 rounded-lg">
                    Kirim Pesan
                </button>

            </form>

        </div>

    </div>

</div>

@endsection