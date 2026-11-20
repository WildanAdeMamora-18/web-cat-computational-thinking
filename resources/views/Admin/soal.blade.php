@extends('layouts.admin')
@section('content')
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-[11px] font-bold tracking-wider uppercase text-slate-400">PARAMETER ITEM RESPONSE THEORY</p>
            <h1 class="text-2xl font-extrabold text-slate-900 mt-1">Bank Soal Terkalibrasi</h1>
            <p class="text-xs text-slate-500 mt-0.5">Butir hasil kalibrasi model Rasch untuk penilaian kompetensi
                computational thinking.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <button onclick="document.getElementById('modalImporSoal').classList.remove('hidden')"
                class="bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold px-4 py-2.5 rounded-xl transition">
                ↑ Impor dari CSV
            </button>
            <button onclick="document.getElementById('modalTambahSoal').classList.remove('hidden')"
                class="bg-[#1456E8] hover:bg-[#0F46C4] text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-xs transition">
                + Tambah Butir
            </button>
        </div>
    </div>

    <!-- 4 Kartu KPI -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-2xs flex items-start justify-between">
            <div>
                <p class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">TOTAL BUTIR</p>
                <p class="text-2xl font-extrabold text-slate-900 mt-1.5">{{ $totalButir }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center font-bold">?</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-2xs flex items-start justify-between">
            <div>
                <p class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">TARAF FIT (MNSQ)</p>
                <p class="text-2xl font-extrabold text-slate-900 mt-1.5">{{ $jumlahFit }} <span
                        class="text-xs font-semibold text-slate-400">({{ $persenFit }}%)</span></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#EEF2FF] text-[#1456E8] flex items-center justify-center font-bold">⚙</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-2xs flex items-start justify-between">
            <div>
                <p class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">BUTIR MISFIT</p>
                <p class="text-2xl font-extrabold text-red-600 mt-1.5">{{ $jumlahMisfit }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center font-bold">⚠</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-2xs flex items-start justify-between">
            <div>
                <p class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">MEAN KESUKARAN (B)</p>
                <p class="text-2xl font-extrabold text-slate-900 mt-1.5">
                    {{ ($meanB >= 0 ? '+' : '') . number_format($meanB, 2) }} <span
                        class="text-xs font-semibold text-slate-400">logit</span></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center font-bold">≡</div>
        </div>
    </div>

    <!-- Filter & Pencarian Stimulus -->
    <form method="GET" action="{{ route('admin.soal') }}"
        class="bg-white rounded-2xl p-4 border border-slate-100 shadow-2xs grid grid-cols-1 md:grid-cols-3 gap-3">
        <div>
            <label class="block text-[10px] font-bold tracking-wider text-slate-400 uppercase mb-1">INDIKATOR
                SUB-DOMAIN</label>
            <select name="indikator" onchange="this.form.submit()"
                class="w-full bg-[#F1F5F9] text-xs font-semibold text-slate-700 rounded-xl px-3.5 py-2.5 outline-none">
                <option value="semua">Semua Indikator</option>
                @foreach (['Dekomposisi', 'Abstraksi', 'Pengenalan Pola', 'Perancangan Algoritma', 'Analisis dan Evaluasi Solusi'] as $ind)
                    <option value="{{ $ind }}" {{ request('indikator') === $ind ? 'selected' : '' }}>
                        {{ $ind }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-bold tracking-wider text-slate-400 uppercase mb-1">STATUS FIT MODEL</label>
            <select name="fit" onchange="this.form.submit()"
                class="w-full bg-[#F1F5F9] text-xs font-semibold text-slate-700 rounded-xl px-3.5 py-2.5 outline-none">
                <option value="semua">Semua Status</option>
                <option value="1" {{ request('fit') === '1' ? 'selected' : '' }}>Fit (Masuk Tes)</option>
                <option value="0" {{ request('fit') === '0' ? 'selected' : '' }}>Tidak Fit (Drop)</option>
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-bold tracking-wider text-slate-400 uppercase mb-1">PENCARIAN
                STIMULUS</label>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari potongan soal, sintaks..."
                class="w-full bg-[#F1F5F9] text-xs rounded-xl px-3.5 py-2.5 outline-none focus:ring-2 focus:ring-[#1456E8]">
        </div>
    </form>

    <!-- Tabel Bank Soal -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="border-b border-slate-100 text-[10px] font-bold tracking-wider text-slate-400 uppercase bg-slate-50/50">
                        <th class="py-3.5 px-5">NO</th>
                        <th class="py-3.5 px-4">INDIKATOR</th>
                        <th class="py-3.5 px-4">CUPLIKAN SOAL</th>
                        <th class="py-3.5 px-4 text-center">KUNCI</th>
                        <th class="py-3.5 px-4 text-right">KESUKARAN B (LOGIT)</th>
                        <th class="py-3.5 px-4 text-center">STATUS FIT</th>
                        <th class="py-3.5 px-5 text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @foreach ($daftarSoal as $soal)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-5 text-slate-400 font-mono-code">
                                {{ str_pad($soal->nomor_butir, 2, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-3.5 px-4 font-semibold text-slate-700 w-36">{{ $soal->indikator }}</td>
                            <td class="py-3.5 px-4 max-w-md">
                                <p class="font-semibold text-slate-800 truncate">{{ $soal->teks_soal }}</p>
                                <p class="text-[11px] font-mono-code text-slate-400 mt-0.5">ID:
                                    ITM-RASCH-{{ str_pad($soal->nomor_butir, 2, '0', STR_PAD_LEFT) }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span
                                    class="bg-slate-100 text-slate-700 font-mono-code font-bold px-2.5 py-1 rounded-md">{{ $soal->kunci_jawaban }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono-code font-bold text-slate-800">
                                {{ ($soal->ukuran_b >= 0 ? '+' : '') . number_format($soal->ukuran_b, 2) }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if ($soal->status_fit)
                                    <span
                                        class="bg-[#EEF2FF] text-[#1456E8] font-bold px-2.5 py-1 rounded-md text-[11px]">Fit</span>
                                @else
                                    <span class="bg-red-50 text-red-600 font-bold px-2.5 py-1 rounded-md text-[11px]">Tidak
                                        Fit</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 text-right">
                                <button onclick='bukaEditSoal(@json($soal->makeVisible(['kunci_jawaban', 'ukuran_b'])))'
                                    class="text-slate-600 hover:text-[#1456E8] font-semibold">Edit</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3.5 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <span><strong>{{ $totalButir }} butir total</strong> • <span
                    class="text-[#1456E8] font-semibold">{{ $jumlahFit }} fit</span> • <span
                    class="text-red-600 font-semibold">{{ $jumlahMisfit }} tidak fit</span></span>
            <div>{{ $daftarSoal->links('pagination::tailwind') }}</div>
        </div>
    </div>

    <!-- Modal Edit Soal & Kalibrasi -->
    <div id="modalEditSoal"
        class="hidden fixed inset-0 bg-slate-900/40 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-xl my-8 text-xs">
            <h3 class="text-base font-extrabold text-slate-900 mb-4" id="judulModalSoal">Edit Parameter & Konten Butir Soal
            </h3>
            <form id="formEditSoal" method="POST" class="space-y-3">
                @csrf @method('PUT')
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="font-bold block mb-1">Indikator</label>
                        <select id="sIndikator" name="indikator" class="w-full bg-slate-100 rounded-xl px-3 py-2">
                            @foreach (['Dekomposisi', 'Abstraksi', 'Pengenalan Pola', 'Perancangan Algoritma', 'Analisis dan Evaluasi Solusi'] as $ind)
                                <option value="{{ $ind }}">{{ $ind }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="font-bold block mb-1">Kesukaran b (Logit)</label>
                        <input type="number" step="0.0001" id="sUkuranB" name="ukuran_b" required
                            class="w-full bg-slate-100 rounded-xl px-3 py-2 font-mono-code">
                    </div>
                    <div>
                        <label class="font-bold block mb-1">Status Fit</label>
                        <select id="sStatusFit" name="status_fit" class="w-full bg-slate-100 rounded-xl px-3 py-2">
                            <option value="1">Fit (1)</option>
                            <option value="0">Tidak Fit (0)</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="font-bold block mb-1">Teks Soal</label>
                    <textarea id="sTeks" name="teks_soal" rows="3" required class="w-full bg-slate-100 rounded-xl p-3"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div><label class="font-bold">Opsi A</label><input type="text" id="sOpsiA" name="opsi_a"
                            required class="w-full bg-slate-100 rounded-lg px-2.5 py-1.5"></div>
                    <div><label class="font-bold">Opsi B</label><input type="text" id="sOpsiB" name="opsi_b"
                            required class="w-full bg-slate-100 rounded-lg px-2.5 py-1.5"></div>
                    <div><label class="font-bold">Opsi C</label><input type="text" id="sOpsiC" name="opsi_c"
                            required class="w-full bg-slate-100 rounded-lg px-2.5 py-1.5"></div>
                    <div><label class="font-bold">Opsi D</label><input type="text" id="sOpsiD" name="opsi_d"
                            required class="w-full bg-slate-100 rounded-lg px-2.5 py-1.5"></div>
                    <div><label class="font-bold">Opsi E</label><input type="text" id="sOpsiE" name="opsi_e"
                            required class="w-full bg-slate-100 rounded-lg px-2.5 py-1.5"></div>
                    <div>
                        <label class="font-bold">Kunci Jawaban</label>
                        <select id="sKunci" name="kunci_jawaban"
                            class="w-full bg-slate-100 rounded-lg px-2.5 py-1.5 font-bold">
                            @foreach (['A', 'B', 'C', 'D', 'E'] as $k)
                                <option value="{{ $k }}">{{ $k }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-3">
                    <button type="button" onclick="document.getElementById('modalEditSoal').classList.add('hidden')"
                        class="px-4 py-2 rounded-xl border font-bold">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-[#1456E8] text-white font-bold">Simpan
                        Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Impor Kalibrasi CSV -->
    <div id="modalImporSoal" class="hidden fixed inset-0 bg-slate-900/40 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl text-xs">
            <h3 class="text-base font-extrabold text-slate-900 mb-2">Impor Parameter Rasch dari CSV</h3>
            <p class="text-slate-500 mb-4">Header wajib: <code
                    class="bg-slate-100 px-1.5 py-0.5 rounded">nomor_butir,ukuran_b,status_fit</code></p>
            <form action="{{ route('admin.soal.impor') }}" method="POST" enctype="multipart/form-data"
                class="space-y-4">
                @csrf
                <input type="file" name="file_csv" accept=".csv,.txt" required class="w-full border rounded-xl p-2">
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modalImporSoal').classList.add('hidden')"
                        class="px-4 py-2 rounded-xl border font-bold">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-[#1456E8] text-white font-bold">Unggah
                        CSV</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function bukaEditSoal(soal) {
            document.getElementById('formEditSoal').action = `/admin/soal/${soal.id}`;
            document.getElementById('judulModalSoal').innerText = `Edit Butir Soal #${soal.nomor_butir}`;
            document.getElementById('sIndikator').value = soal.indikator;
            document.getElementById('sUkuranB').value = soal.ukuran_b;
            document.getElementById('sStatusFit').value = soal.status_fit ? '1' : '0';
            document.getElementById('sTeks').value = soal.teks_soal;
            document.getElementById('sOpsiA').value = soal.opsi_a;
            document.getElementById('sOpsiB').value = soal.opsi_b;
            document.getElementById('sOpsiC').value = soal.opsi_c;
            document.getElementById('sOpsiD').value = soal.opsi_d;
            document.getElementById('sOpsiE').value = soal.opsi_e;
            document.getElementById('sKunci').value = soal.kunci_jawaban;
            document.getElementById('modalEditSoal').classList.remove('hidden');
        }
    </script>
@endsection
