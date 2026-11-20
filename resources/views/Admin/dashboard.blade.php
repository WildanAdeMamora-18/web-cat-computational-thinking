@extends('layouts.admin')
@section('content')
    <div class="row g-4">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold">Kelola Sesi Tes</div>
                <div class="card-body">
                    <form action="{{ route('admin.sesi.simpan') }}" method="POST" class="d-flex gap-2 mb-3">
                        @csrf
                        <input type="text" name="nama_sesi" class="form-control" placeholder="Nama sesi baru..." required>
                        <button class="btn btn-primary">Buat</button>
                    </form>
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Sesi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($daftarSesi as $s)
                                <tr>
                                    <td>{{ $s->nama_sesi }}</td>
                                    <td><span
                                            class="badge {{ $s->status === 'aktif' ? 'bg-success' : 'bg-secondary' }}">{{ strtoupper($s->status) }}</span>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.sesi.toggle', $s) }}" method="POST">
                                            @csrf
                                            <button
                                                class="btn btn-sm {{ $s->status === 'aktif' ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                                {{ $s->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Hasil Tes & Ekspor Google Colab</span>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.export.ringkasan') }}" class="btn btn-success btn-sm">CSV Ringkasan</a>
                        <a href="{{ route('admin.export.respons') }}" class="btn btn-outline-success btn-sm">CSV Respons</a>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-sm table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Butir</th>
                                <th>θ Akhir</th>
                                <th>SEM</th>
                                <th>Status</th>
                                <th>Berhenti</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($daftarHasil as $h)
                                <tr>
                                    <td class="fw-bold">{{ $h->siswa->kode_peserta }}</td>
                                    <td>{{ $h->jumlah_butir }}</td>
                                    <td>{{ number_format($h->theta_akhir, 2) }}</td>
                                    <td>{{ $h->sem_akhir !== null ? number_format($h->sem_akhir, 2) : '-' }}</td>
                                    <td><span
                                            class="badge {{ $h->status_tes === 'selesai' ? 'bg-primary' : 'bg-warning text-dark' }}">{{ $h->status_tes }}</span>
                                    </td>
                                    <td><small>{{ $h->alasan_berhenti ?? '-' }}</small></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Belum ada siswa yang mengerjakan tes.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
