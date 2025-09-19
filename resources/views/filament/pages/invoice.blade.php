<x-filament::page>
    @if ($penghuni)
        <div class="shadow rounded p-6 print:p-0 print:shadow-none text-black-900">
            {{-- HEADER PERUSAHAAN --}}
            <div class="text-center mb-4">
                <h1 class="text-2xl font-bold uppercase">Anugrah Group</h1>
                <p class="text-sm">
                    Jl. Raya Serang - Cibarusah Serang, Kongsi No.33, RT.012/RW.06, Sukadami,<br>
                    Cikarang Selatan, Kabupaten Bekasi, Jawa Barat 17530
                </p>
            </div>

            {{-- INFORMASI WAKTU & TEMPAT --}}
            <div class="flex justify-end mb-4">
                <p>Cikarang, {{ now()->translatedFormat('d F Y') }}</p>
            </div>

            {{-- INFO TUJUAN --}}
            <div class="mb-4">
                <p><strong>Kepada Yth:</strong></p>
                <p><strong>Nama Penghuni:</strong> {{ $penghuni->nama }}</p>
                <p><strong>Kamar:</strong> {{ $penghuni->dataKamar->nama_kamar }} - {{ $penghuni->dataKamar->lokasi }}</p>
            </div>

            {{-- JUDUL TAGIHAN --}}
            <div class="text-center mb-3">
                <h2 class="text-lg font-bold underline">INVOICE TAGIHAN SEWA KOST</h2>
            </div>

            {{-- TABEL TAGIHAN --}}
            <table class="w-full border border-300 mb-4">
                <thead class="bg">
                    <tr>
                        <th class="border px-2 py-1">Periode</th>
                        <th class="border px-2 py-1">Jatuh Tempo</th>
                        <th class="border px-2 py-1">Nominal</th>
                        <th class="border px-2 py-1">Status</th>
                        <th class="border px-2 py-1">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tagihanData as $tagihan)
                        <tr>
                            <td class="border px-2 py-1">{{ $tagihan['periode'] }}</td>
                            <td class="border px-2 py-1">{{ \Carbon\Carbon::parse($tagihan['jatuh_tempo'])->format('d-m-Y') }}</td>
                            <td class="border px-2 py-1">Rp{{ number_format($tagihan['nominal'], 0, ',', '.') }}</td>
                            <td class="border px-2 py-1 capitalize">{{ $tagihan['status'] }}</td>
                            <td class="border px-2 py-1">{{ $tagihan['catatan'] ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- TOTAL --}}
            <div class="text-right font-bold mb-6">
                Total Tagihan: Rp{{ number_format($totalTagihan, 0, ',', '.') }}
            </div>

            {{-- TANDA TANGAN --}}
            <div class="flex justify-end mt-8">
                <div class="text-center">
                    <p>Hormat Kami,</p>
                    <br><br>
                    <p><strong>Wisma Anugrah Group</strong></p>
                </div>
            </div>

         {{-- TOMBOL AKSI --}}
        <div class="mt-6 flex gap-3 print:hidden">
            <x-filament::button
                wire:click="downloadPdf"
                color="info"
                icon="heroicon-o-arrow-down-tray"
            >
                Unduh PDF
            </x-filament::button>

        @unless(auth()->user()?->hasRole('penghuni'))
            <x-filament::button
                wire:click="kirimKeWhatsApp"
                color="success"
                icon="heroicon-o-chat-bubble-left-right"
            >
                Kirim ke WhatsApp
            </x-filament::button>
        @endunless

        
</div>

        </div>
    @else
        <p class="text-gray-600 italic">Silakan pilih penghuni untuk melihat tagihan.</p>
    @endif

    <div class="mt-6">
        {{ $this->form }}
    </div>
</x-filament::page> 
