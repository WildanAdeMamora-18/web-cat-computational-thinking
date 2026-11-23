@extends('layouts.admin')
@section('content')
    <!-- Header Judul & Tombol Aksi -->
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-[11px] font-bold tracking-wider uppercase text-slate-400">
                MODUL PESERTA <span class="mx-1">○</span> <span class="text-[#1456E8]">TAHUN AJARAN 2026/2027</span>
            </p>
            <h1 class="text-2xl font-extrabold text-slate-900 mt-1">Data Siswa</h1>
        </div>
        <div class="flex items-center gap-2.5">
            <button onclick="document.getElementById('modalImporSiswa').classList.remove('hidden')"
                class="bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold px-4 py-2.5 rounded-xl flex items-center gap-2 transition">
                <span>↑ Impor dari CSV</span>
            </button>
            <button onclick="document.getElementById('modalTambahSiswa').classList.remove('hidden')"
                class="bg-[#1456E8] hover:bg-[#0F46C4] text-white text-xs font-bold px-4 py-2.5 rounded-xl flex items-center gap-2 shadow-xs transition">
                <span>+ Tambah Siswa</span>
            </button>
        </div>
    </div>

    <!-- 4 Kartu KPI -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-2xs flex items-start justify-between">
            <div>
                <p class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">TOTAL PESERTA KELAS</p>
                <p class="text-2xl font-extrabold text-slate-900 mt-1.5">{{ $totalSiswa }}</p>
                <p class="text-xs text-slate-400 mt-1">Rombel Terdaftar</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#EEF2FF] text-[#1456E8] flex items-center justify-center font-bold">👥
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-2xs flex items-start justify-between">
            <div>
                <p class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">SEDANG UJIAN</p>
                <p class="text-2xl font-extrabold text-[#1456E8] mt-1.5">{{ $sedangUjian }}</p>
                <p class="text-xs text-slate-400 mt-1">Sesi Aktif Online</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#EEF2FF] text-[#1456E8] flex items-center justify-center font-bold">⏱</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-2xs flex items-start justify-between">
            <div>
                <p class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">SELESAI EVALUASI</p>
                <p class="text-2xl font-extrabold text-slate-900 mt-1.5">{{ $selesaiUjian }}</p>
                <p class="text-xs text-slate-400 mt-1">Jawaban Terkunci</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center font-bold">✓</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-2xs flex items-start justify-between">
            <div>
                <p class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">BELUM LOGIN</p>
                <p class="text-2xl font-extrabold text-slate-900 mt-1.5">{{ $belumLogin }}</p>
                <p class="text-xs text-slate-400 mt-1">Token Belum Dipakai</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center font-bold">⏳</div>
        </div>
    </div>

    <!-- Bar Filter & Pencarian -->
    <form id="formCariSiswa" method="GET" action="{{ route('admin.siswa') }}"
        class="bg-white rounded-2xl p-3.5 border border-slate-100 shadow-2xs flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2.5">
            <select name="kelas" onchange="this.form.submit()"
                class="bg-[#F1F5F9] text-xs font-bold text-slate-700 rounded-xl px-3.5 py-2.5 outline-none">
                <option value="semua">KELAS: Semua Kelas</option>
                @foreach ($daftarKelas as $k)
                    <option value="{{ $k }}" {{ request('kelas') === $k ? 'selected' : '' }}>KELAS:
                        {{ $k }}</option>
                @endforeach
            </select>
            <a href="{{ route('admin.siswa') }}"
                class="bg-blue-50 hover:bg-blue-100 text-[#1456E8] text-xs font-bold px-3.5 py-2.5 rounded-xl transition">
                ↻ Refresh Pantauan Tab
            </a>
        </div>
        <div class="relative w-full sm:w-72">
            <input type="text" id="inputCariSiswa" name="q" value="{{ request('q') }}"
                placeholder="Ketik nama atau kode..."
                class="w-full bg-[#F1F5F9] text-xs rounded-xl pl-4 pr-9 py-2.5 outline-none focus:ring-2 focus:ring-[#1456E8]">
            @if (request('q'))
                <a href="{{ route('admin.siswa', ['kelas' => request('kelas')]) }}"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-red-500 text-xs font-bold"
                    title="Hapus pencarian">✕</a>
            @else
                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs">🔍</span>
            @endif
        </div>
    </form>

    <!-- Tabel Data Siswa -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="border-b border-slate-100 text-[10px] font-bold tracking-wider text-slate-400 uppercase bg-slate-50/50">
                        <th class="py-3.5 px-5">NO</th>
                        <th class="py-3.5 px-4">KODE PESERTA</th>
                        <th class="py-3.5 px-4">NAMA LENGKAP</th>
                        <th class="py-3.5 px-4">KELAS</th>
                        <th class="py-3.5 px-4">STATUS TES</th>
                        <th class="py-3.5 px-4">AKTIVITAS TAB</th>
                        <th class="py-3.5 px-5 text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($daftarSiswa as $idx => $s)
                        @php $tes = $s->hasilTes->first(); @endphp
                        <tr
                            class="hover:bg-slate-50/70 transition {{ $tes && $tes->jumlah_keluar_tab > 0 && $tes->status_tes === 'sedang_mengerjakan' ? 'bg-amber-50/40' : '' }}">
                            <td class="py-3.5 px-5 text-slate-400 font-mono-code">
                                {{ str_pad($daftarSiswa->firstItem() + $idx, 2, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-3.5 px-4">
                                <span
                                    class="bg-[#EEF2FF] text-[#1456E8] font-mono-code font-bold px-2.5 py-1 rounded-md text-[11px]">{{ $s->kode_peserta }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-800">{{ $s->nama }}</td>
                            <td class="py-3.5 px-4 text-slate-600">{{ $s->kelas }}</td>
                            <td class="py-3.5 px-4">
                                @if (!$tes)
                                    <span
                                        class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 border border-red-200/70 font-bold px-2.5 py-1 rounded-full text-[11px]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Belum mulai
                                    </span>
                                @elseif($tes->status_tes === 'sedang_mengerjakan')
                                    <span
                                        class="inline-flex items-center gap-1.5 bg-blue-50 text-[#1456E8] border border-blue-200/70 font-bold px-2.5 py-1 rounded-full text-[11px]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#1456E8] animate-pulse"></span> Sedang
                                        mengerjakan
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200/70 font-bold px-2.5 py-1 rounded-full text-[11px]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Selesai
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if (!$tes)
                                    <span class="text-slate-400 text-[11px]">-</span>
                                @elseif($tes->jumlah_keluar_tab > 0)
                                    <span
                                        class="inline-flex items-center gap-1 bg-amber-100 text-amber-800 border border-amber-300 font-bold px-2.5 py-1 rounded-lg text-[11px]"
                                        title="Terakhir keluar: {{ $tes->terakhir_keluar_tab?->format('H:i:s') }}">
                                        ⚠ Keluar Tab ({{ $tes->jumlah_keluar_tab }}x)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-emerald-600 font-semibold text-[11px]">
                                        ✓ Fokus di Ujian
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 text-right space-x-2">
                                <button
                                    onclick="bukaEditSiswa({{ $s->id }}, '{{ $s->kode_peserta }}', '{{ addslashes($s->nama) }}', '{{ $s->kelas }}')"
                                    class="text-slate-600 hover:text-[#1456E8] font-semibold">Edit</button>
                                <span class="text-slate-200">|</span>
                                <form action="{{ route('admin.siswa.hapus', $s) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Hapus peserta ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="text-slate-500 hover:text-red-600 font-semibold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">Tidak ada data peserta yang cocok.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3.5 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <span>Menampilkan <strong>{{ $daftarSiswa->firstItem() ?? 0 }}-{{ $daftarSiswa->lastItem() ?? 0 }}</strong>
                dari <strong>{{ $daftarSiswa->total() }}</strong> siswa</span>
            <div>{{ $daftarSiswa->links('pagination::tailwind') }}</div>
        </div>
    </div>

    <!-- Modal Tambah Siswa -->
    <div id="modalTambahSiswa" class="hidden fixed inset-0 bg-slate-900/40 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl">
            <h3 class="text-base font-extrabold text-slate-900 mb-4">Tambah Peserta Baru</h3>
            <form action="{{ route('admin.siswa.simpan') }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="font-bold text-slate-700">Kode Peserta</label>
                        <span class="text-[10px] font-semibold text-[#1456E8] bg-[#EEF2FF] px-2 py-0.5 rounded">Otomatis &
                            Terkunci</span>
                    </div>
                    <input type="text" value="{{ $kodeOtomatis }}" readonly
                        class="w-full bg-slate-200/80 text-slate-600 font-bold rounded-xl px-3.5 py-2.5 uppercase font-mono-code cursor-not-allowed outline-none">
                </div>
                <div>
                    <label class="font-bold text-slate-700 block mb-1">Nama Lengkap *</label>
                    <input type="text" name="nama" placeholder="Masukkan nama lengkap siswa" required
                        class="w-full bg-slate-100 rounded-xl px-3.5 py-2.5 uppercase focus:ring-2 focus:ring-[#1456E8] outline-none">
                </div>
                <div>
                    <label class="font-bold text-slate-700 block mb-1">Kelas *</label>
                    <input type="text" name="kelas" value="X MPK 1" required
                        class="w-full bg-slate-100 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-[#1456E8] outline-none">
                </div>
                <div class="flex justify-end gap-2 pt-3">
                    <button type="button" onclick="document.getElementById('modalTambahSiswa').classList.add('hidden')"
                        class="px-4 py-2 rounded-xl border font-bold">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-[#1456E8] text-white font-bold">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Siswa & Reset Tes -->
    <div id="modalEditSiswa" class="hidden fixed inset-0 bg-slate-900/40 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl">
            <h3 class="text-base font-extrabold text-slate-900 mb-4">Edit Data Peserta</h3>

            <form id="formEditSiswa" method="POST" class="space-y-3 text-xs">
                @csrf
                @method('PUT')
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="font-bold text-slate-700">Kode Peserta</label>
                        <span class="text-[10px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded">Tidak dapat
                            diubah</span>
                    </div>
                    <input type="text" id="editKode" readonly
                        class="w-full bg-slate-200/80 text-slate-600 font-bold rounded-xl px-3.5 py-2.5 uppercase font-mono-code cursor-not-allowed outline-none">
                </div>
                <div>
                    <label class="font-bold text-slate-700 block mb-1">Nama Lengkap *</label>
                    <input type="text" id="editNama" name="nama" required
                        class="w-full bg-slate-100 rounded-xl px-3.5 py-2.5 uppercase focus:ring-2 focus:ring-[#1456E8] outline-none">
                </div>
                <div>
                    <label class="font-bold text-slate-700 block mb-1">Kelas *</label>
                    <input type="text" id="editKelas" name="kelas" required
                        class="w-full bg-slate-100 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-[#1456E8] outline-none">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('modalEditSiswa').classList.add('hidden')"
                        class="px-4 py-2 rounded-xl border font-bold">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-[#1456E8] text-white font-bold">Simpan
                        Perubahan</button>
                </div>
            </form>

            <!-- Bagian Reset / Batalkan Tes Siswa -->
            <div class="mt-5 pt-4 border-t border-slate-100">
                <p class="text-[11px] font-bold text-slate-700 mb-1">Atur Ulang / Batalkan Tes</p>
                <p class="text-[11px] text-slate-400 mb-3">Menghapus riwayat jawaban siswa pada sesi aktif agar siswa bisa
                    login dan mengerjakan tes kembali dari awal.</p>
                <form id="formResetTesSiswa" method="POST"
                    onsubmit="return confirm('Yakin ingin mereset tes siswa ini? Seluruh jawaban sebelumnya pada sesi aktif akan dihapus.');">
                    @csrf
                    <button type="submit"
                        class="w-full bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 font-bold py-2.5 rounded-xl text-xs transition">
                        ↺ Reset & Izinkan Mengerjakan Ulang
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Impor CSV Siswa -->
    <div id="modalImporSiswa" class="hidden fixed inset-0 bg-slate-900/40 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl text-xs">
            <h3 class="text-base font-extrabold text-slate-900 mb-2">Impor Peserta dari CSV</h3>
            <p class="text-slate-500 mb-4">Format header CSV: <code
                    class="bg-slate-100 px-1.5 py-0.5 rounded">kode_peserta,nama,kelas</code></p>
            <form action="{{ route('admin.siswa.impor') }}" method="POST" enctype="multipart/form-data"
                class="space-y-4">
                @csrf
                <input type="file" name="file_csv" accept=".csv,.txt" required class="w-full border rounded-xl p-2">
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modalImporSiswa').classList.add('hidden')"
                        class="px-4 py-2 rounded-xl border font-bold">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-[#1456E8] text-white font-bold">Unggah
                        CSV</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Otomatis reset / cari saat input diketik atau dihapus
        let timerCari;
        const inputCari = document.getElementById('inputCariSiswa');
        inputCari.addEventListener('input', function() {
            clearTimeout(timerCari);
            if (this.value.trim() === '') {
                window.location.href = "{{ route('admin.siswa', ['kelas' => request('kelas')]) }}";
                return;
            }
            timerCari = setTimeout(() => {
                document.getElementById('formCariSiswa').submit();
            }, 450);
        });
        // Kembalikan fokus kursor ke akhir teks setelah reload pencarian
        if (inputCari.value.length > 0) {
            inputCari.focus();
            inputCari.setSelectionRange(inputCari.value.length, inputCari.value.length);
        }

        function bukaEditSiswa(id, kode, nama, kelas) {
            const baseUrl = "{{ url('/admin/siswa') }}";
            document.getElementById('formEditSiswa').action = `${baseUrl}/${id}`;
            document.getElementById('formResetTesSiswa').action = `${baseUrl}/${id}/reset-tes`;
            document.getElementById('editKode').value = kode;
            document.getElementById('editNama').value = nama;
            document.getElementById('editKelas').value = kelas;
            document.getElementById('modalEditSiswa').classList.remove('hidden');
        }
    </script>
@endsection
