<?php

namespace Database\Seeders;

use App\Models\ButirSoal;
use App\Models\SesiTes;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BankSoalSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin Default (email: admin@cat.test | pass: admin123)
        User::updateOrCreate(
            ['email' => 'admin@cat.test'],
            ['name' => 'Admin Peneliti', 'password' => Hash::make('admin123')]
        );

        // 2. Sesi Tes Default
        SesiTes::firstOrCreate(
            ['nama_sesi' => 'Penelitian Kelas X MPK 1 - SMKN 1 Pogalan'],
            ['status' => 'aktif', 'waktu_mulai' => now()]
        );

        // 3. Contoh 3 Kode Siswa Uji Coba
        $contohSiswa = [
            ['kode_peserta' => 'MPK1-01', 'nama' => 'Siswa Uji 01', 'kelas' => 'X MPK 1'],
            ['kode_peserta' => 'MPK1-02', 'nama' => 'Siswa Uji 02', 'kelas' => 'X MPK 1'],
            ['kode_peserta' => 'MPK1-03', 'nama' => 'Siswa Uji 03', 'kelas' => 'X MPK 1'],
        ];
        foreach ($contohSiswa as $s) {
            Siswa::updateOrCreate(['kode_peserta' => $s['kode_peserta']], $s);
        }

        // 4. 40 Butir Soal Berpikir Komputasional (Nilai b sementara disebar -1.5 s/d +1.5 untuk simulasi sebelum kalibrasi Rasch diinput)
        $daftarSoal = [
            [1, 'Dekomposisi', 'Sekolah ingin membuat aplikasi sederhana untuk mencatat peminjaman buku di perpustakaan. Penguraian masalah yang paling tepat adalah ...', 'Membuat perpustakaan lebih ramai; menambah koleksi buku; mempercantik ruangan; menambah rak', 'Mencatat data buku; mencatat data peminjam; mencatat tanggal pinjam dan kembali; menampilkan daftar buku yang sedang dipinjam', 'Membeli komputer; membeli kabel; membeli meja; membeli kursi', 'Membaca seluruh buku, lalu menyimpannya sekaligus tanpa urutan', 'Menyalin seluruh isi buku ke dalam komputer', 'B', -1.20],
            [2, 'Dekomposisi', 'Sistem kasir sederhana diuraikan menjadi empat komponen: mencatat barang, menghitung total, menerima pembayaran, dan mencetak struk. Toko ingin menambahkan pembayaran dengan dompet digital. Komponen yang paling tepat diubah adalah ...', 'mencatat barang', 'menghitung total', 'mencetak struk', 'menerima pembayaran', 'semua komponen harus ditulis ulang', 'D', -1.00],
            [3, 'Dekomposisi', 'Absensi digital diuraikan menjadi empat komponen. Urutan pengerjaan yang paling logis adalah ...', 'menampilkan daftar siswa → menandai kehadiran → menghitung jumlah hadir → menyimpan rekap', 'menghitung jumlah hadir → menampilkan daftar siswa → menandai kehadiran → menyimpan rekap', 'menyimpan rekap → menandai kehadiran → menampilkan daftar siswa → menghitung jumlah hadir', 'menandai kehadiran → menghitung jumlah hadir → menyimpan rekap → menampilkan daftar siswa', 'menampilkan daftar siswa → menghitung jumlah hadir → menandai kehadiran → menyimpan rekap', 'A', -0.90],
            [4, 'Dekomposisi', "Panitia lomba memiliki lima tugas:\n(1) mendata peserta;\n(2) mencetak nomor peserta, membutuhkan hasil tugas 1;\n(3) memesan konsumsi, membutuhkan hasil tugas 1;\n(4) menyusun jadwal lomba;\n(5) menyiapkan dekorasi panggung.\n\nTugas manakah yang dapat langsung dikerjakan bersamaan dengan tugas 1?", 'Tugas 2 dan 3', 'Tugas 2 dan 4', 'Tugas 3 dan 5', 'Tugas 2 dan 5', 'Tugas 4 dan 5', 'E', -0.10],
            [5, 'Dekomposisi', 'Laporan nilai memuat data 5 kelas selama 12 pekan (60 blok data berukuran sama). Tiga orang mengerjakannya bersamaan dengan beban yang sama besar. Pembagian terbaik adalah ...', 'orang pertama kelas 1-2, orang kedua kelas 3-4, orang ketiga kelas 5', 'orang pertama 6 pekan, orang kedua 4 pekan, orang ketiga 2 pekan untuk semua kelas', 'tiap orang mengerjakan 4 pekan untuk semua kelas', 'orang pertama mengerjakan semuanya, dua orang lain hanya memeriksa', 'orang pertama kelas 1, orang kedua kelas 2, orang ketiga kelas 3-5', 'C', 0.05],
            [6, 'Dekomposisi', 'Skor akhir sebuah regu = skor babak 1 + skor babak 2 - pengurangan pelanggaran. Ketiganya dihitung sebagai komponen terpisah, dan pengurangan pelanggaran tidak bergantung pada skor babak. Jika panitia hanya mengubah cara menghitung skor babak 2, komponen yang hasilnya berubah adalah ...', 'skor babak 2 dan skor akhir', 'skor babak 1 dan skor akhir', 'skor babak 2 dan pengurangan pelanggaran', 'pengurangan pelanggaran dan skor akhir', 'skor babak 1, skor babak 2, dan skor akhir', 'A', -0.05],
            [7, 'Dekomposisi', "Ada empat tugas:\n- P (3 jam)\n- Q (2 jam, baru boleh dimulai setelah P selesai)\n- R (4 jam, boleh dikerjakan kapan saja)\n- S (1 jam, baru boleh dimulai setelah Q dan R selesai)\n\nDua orang bekerja bersamaan, masing-masing mengerjakan satu tugas pada satu waktu tanpa memecah tugas. Waktu tercepat sampai keempat tugas selesai adalah ...", '5 jam', '7 jam', '10 jam', '6 jam', '8 jam', 'D', 1.15],
            [8, 'Dekomposisi', 'Lima tugas yang saling bebas berdurasi 2, 3, 4, 5, dan 6 jam. Dua orang bekerja bersamaan; satu tugas dikerjakan satu orang tanpa dipecah. Waktu tercepat sampai semua selesai adalah ...', '8 jam', '10 jam', '9 jam', '12 jam', '11 jam', 'B', 1.25],

            [9, 'Abstraksi', 'Sebuah aplikasi menghitung ongkos kirim paket. Ongkos hanya ditentukan oleh berat dan jarak tujuan. Informasi yang perlu disimpan pada model aplikasi ini adalah ...', 'warna kardus dan merek kendaraan kurir', 'nama pengirim dan warna kardus', 'nama kurir dan jam pengiriman', 'berat paket dan nama kurir', 'berat paket dan jarak tujuan', 'E', -1.30],
            [10, 'Abstraksi', 'Sebuah diagram menggambarkan alur pemesanan makanan secara daring. Informasi yang sebaiknya diabaikan agar diagram tetap sederhana adalah ...', 'urutan langkah dari memilih menu sampai pesanan diterima', 'syarat pembayaran berhasil atau gagal', 'warna seragam pengantar makanan', 'hasil akhir pesanan diterima', 'langkah memilih menu', 'C', -1.10],
            [11, 'Abstraksi', 'Kantin sekolah buka pukul 07.00-14.00. Rani datang naik sepeda biru, membeli 4 gorengan @Rp2.000, 2 teh @Rp3.500, dan 1 roti Rp6.000, lalu membayar Rp50.000. Uang kembaliannya adalah ...', 'Rp35.000', 'Rp21.000', 'Rp26.000', 'Rp29.000', 'Rp36.000', 'D', -0.85],
            [12, 'Abstraksi', 'Aplikasi daftar hadir kegiatan sekolah hanya perlu mencatat siapa yang hadir. Data minimal yang diperlukan adalah ...', 'nama, kelas, dan status hadir', 'alamat rumah, golongan darah, dan status hadir', 'nomor rekening, nama, dan kelas', 'foto, tanggal lahir, dan nama', 'nama, nomor telepon, dan golongan darah', 'A', -0.15],
            [13, 'Abstraksi', 'Biaya fotokopi Rp200 per lembar, apa pun ukuran kertas dan warna sampulnya. Tiga dokumen difotokopi: 12 lembar (A4, sampul merah), 8 lembar (F4, sampul biru), dan 5 lembar (A4, tanpa sampul). Total biayanya adalah ...', 'Rp4.600', 'Rp5.000', 'Rp6.000', 'Rp2.400', 'Rp5.200', 'B', 0.10],
            [14, 'Abstraksi', "Aplikasi peminjaman ruang kelas harus menjawab: 'apakah ruang X kosong pada jam Y?'. Data yang paling efisien disimpan untuk menjawabnya adalah ...", 'daftar peserta dan nomor telepon setiap kegiatan', 'foto dan daftar fasilitas setiap ruang', 'isi laporan setiap kegiatan', 'daftar semua kegiatan yang pernah diadakan beserta pesertanya', 'ruang, jam mulai, dan jam selesai setiap pemesanan', 'E', 0.20],
            [15, 'Abstraksi', 'Diagram jaringan pengiriman paket hanya menampilkan gudang dan jalur antar gudang: A-B, B-C, C-D, B-E, E-D (jarak diabaikan). Berapa banyak rute berbeda dari A ke D yang melewati jumlah jalur paling sedikit?', '5', '1', '2', '3', '4', 'C', 1.10],
            [16, 'Abstraksi', "Empat kegiatan memakai satu ruang:\n- P pukul 08.00-10.00\n- Q pukul 09.00-11.00\n- R pukul 11.00-12.00\n- S pukul 10.00-12.00\n\nDua kegiatan dianggap bentrok jika jamnya beririsan; kegiatan yang berakhir tepat saat kegiatan lain mulai tidak dianggap bentrok. Berapa pasang kegiatan yang bentrok?", '2', '3', '4', '5', '1', 'B', 1.35],

            [17, 'Pengenalan Pola', 'Kode kelompok disusun berurutan: A-01, B-02, C-03, D-04, ... Kode berikutnya adalah ...', 'E-05', 'E-06', 'F-05', 'D-05', 'F-06', 'A', -1.25],
            [18, 'Pengenalan Pola', 'Tiga prosedur: menghitung total belanja, total nilai, dan total iuran kas. Ketiganya dilakukan dengan cara: mulai dari 0, tambahkan setiap nilai satu per satu, lalu tampilkan hasilnya. Pola solusi umum yang sama pada ketiganya adalah ...', 'mencari nilai terbesar', 'mengurutkan data', 'mencari data tertentu', 'akumulasi (penjumlahan berulang)', 'memilah data berdasarkan kategori', 'D', -0.95],
            [19, 'Pengenalan Pola', 'Empat siswa (Ani, Budi, Citra, Dina) piket bergiliran dengan urutan tetap, satu siswa per hari sekolah (Senin-Jumat). Giliran tidak berhenti saat akhir pekan, melainkan dilanjutkan di hari sekolah berikutnya. Senin pekan pertama dijaga Ani. Siapa yang piket pada hari Rabu pekan kedua?', 'Ani', 'Budi', 'Citra', 'Tidak dapat ditentukan', 'Dina', 'E', 0.00],
            [20, 'Pengenalan Pola', 'Kode inventaris yang sudah tercatat: K24001, K24002, M24001, K24003, M25001. Menurut pola penomoran tersebut, kode untuk barang kategori K pertama yang dicatat pada tahun 2025 adalah ...', 'K24004', 'K25004', 'K25001', '25K001', 'K25002', 'C', 0.12],
            [21, 'Pengenalan Pola', 'Fungsi hitung_total(daftar_angka) dipakai untuk kasir, anggaran kegiatan, dan iuran kas tanpa diubah. Hal ini menunjukkan pola solusi umum karena ...', 'fungsi menyimpan semua rincian tiap keperluan', 'logika penjumlahan dipisahkan dari konteks data tertentu sehingga dapat dipakai ulang', 'tiap keperluan harus memiliki fungsi yang berbeda', 'daftar angka selalu berisi bilangan bulat', 'fungsi tersebut hanya terdiri atas satu baris kode', 'B', -0.12],
            [22, 'Pengenalan Pola', 'Jumlah pengunjung per hari pada sebuah bazar: 5, 8, 14, 26, ... Jumlah pada hari berikutnya adalah ...', '50', '38', '44', '52', '56', 'A', 1.05],
            [23, 'Pengenalan Pola', 'Perhatikan deret: 2, 100, 4, 90, 8, 80, 16, ... Dua suku berikutnya adalah ...', '32, 70', '24, 70', '70, 24', '70, 32', '80, 32', 'D', 1.20],
            [24, 'Pengenalan Pola', 'Perhatikan tiga baris bilangan berikut: (2, 3, 7); (4, 5, 21); (3, 6, ?). Nilai ? adalah ...', '18', '20', '21', '17', '19', 'E', 1.30],

            [25, 'Perancangan Algoritma', 'Urutan langkah yang paling logis untuk mencuci tangan dengan sabun adalah ...', 'gosok seluruh permukaan tangan → basahi tangan → beri sabun → bilas → keringkan', 'beri sabun → basahi tangan → gosok seluruh permukaan tangan → bilas → keringkan', 'basahi tangan → beri sabun → gosok seluruh permukaan tangan → bilas → keringkan', 'basahi tangan → bilas → beri sabun → gosok seluruh permukaan tangan → keringkan', 'beri sabun → gosok seluruh permukaan tangan → keringkan → basahi tangan → bilas', 'C', -1.15],
            [26, 'Perancangan Algoritma', 'Pasangan simbol flowchart dan fungsinya yang benar adalah ...', 'belah ketupat - pengambilan keputusan (ya/tidak)', 'jajar genjang - proses perhitungan', 'persegi panjang - awal dan akhir program', 'oval - input data', 'persegi panjang - pengambilan keputusan', 'A', -1.05],
            [27, 'Perancangan Algoritma', "Perhatikan pseudocode pemesanan makanan berikut. Nilai yang ditampilkan adalah ...\n\nharga ← 15000\njumlah ← 3\nongkir ← 5000\ntotal ← harga × jumlah + ongkir\ntampilkan total", 'Rp45.000', 'Rp50.000', 'Rp20.000', 'Rp60.000', 'Rp65.000', 'B', -0.80],
            [28, 'Perancangan Algoritma', 'Aturan diskon: belanja ≥ Rp200.000 diskon 10%; belanja ≥ Rp100.000 tetapi < Rp200.000 diskon 5%; selain itu tanpa diskon. Seorang pelanggan belanja Rp150.000. Yang harus dibayar adalah ...', 'Rp135.000', 'Rp150.000', 'Rp7.500', 'Rp142.500', 'Rp157.500', 'D', -0.02],
            [29, 'Perancangan Algoritma', "Perhatikan pseudocode berikut. Seorang siswa memiliki kehadiran 92 dan tugas tidak lengkap. Nilai poin yang ditampilkan adalah ...\n\npoin ← 70\njika kehadiran ≥ 90 maka poin ← poin + 10\njika tugas lengkap maka poin ← poin + 5\ntampilkan poin", '70', '75', '85', '90', '80', 'E', 0.08],
            [30, 'Perancangan Algoritma', 'Urutan langkah yang paling logis untuk memesan makanan melalui aplikasi adalah ...', 'bayar → pilih menu → buka aplikasi → konfirmasi pesanan → masukkan alamat', 'buka aplikasi → pilih pembayaran → pilih menu → masukkan alamat → konfirmasi pesanan', 'buka aplikasi → pilih menu → masukkan alamat → pilih pembayaran → konfirmasi pesanan', 'pilih menu → konfirmasi pesanan → buka aplikasi → masukkan alamat → pilih pembayaran', 'buka aplikasi → masukkan alamat → konfirmasi pesanan → pilih menu → pilih pembayaran', 'C', -0.18],
            [31, 'Perancangan Algoritma', 'Aturan: pesanan diterima jika stok ≥ jumlah pesanan, dan stok berkurang sebanyak jumlah pesanan; jika tidak, pesanan ditolak dan stok tidak berubah. Pseudocode yang benar adalah ...', "jika stok ≥ jumlah maka stok ← stok - jumlah dan tampilkan 'diterima', selain itu tampilkan 'ditolak'", "jika stok ≥ jumlah maka tampilkan 'diterima', selain itu stok ← stok - jumlah dan tampilkan 'ditolak'", "stok ← stok - jumlah; jika stok ≥ jumlah maka tampilkan 'diterima', selain itu tampilkan 'ditolak'", "jika stok < jumlah maka stok ← stok - jumlah dan tampilkan 'diterima', selain itu tampilkan 'ditolak'", "jika stok ≥ jumlah maka tampilkan 'diterima' dan stok ← stok - jumlah, selain itu stok ← 0 dan tampilkan 'ditolak'", 'A', 0.15],
            [32, 'Perancangan Algoritma', 'Perhatikan alur flowchart berikut: Mulai → masukkan nilai → apakah nilai ≥ 75? Jika ya, tampilkan \'Tuntas\'; jika tidak, tampilkan \'Remedial\' → Selesai. Jika alur dijalankan dua kali dengan nilai 74 dan 75, keluaran berturut-turut adalah ...', 'Tuntas, Remedial', 'Remedial, Tuntas', 'Tuntas, Tuntas', 'Remedial, Remedial', 'tidak ada keluaran untuk nilai 74', 'B', -0.08],
            [33, 'Perancangan Algoritma', "Perhatikan pseudocode tarif parkir berikut. Untuk jam = 6, nilai biaya yang ditampilkan adalah ...\n\nbiaya ← 2000 + (jam - 1) × 1000\njika biaya > 5000 maka biaya ← 5000\ntampilkan biaya", 'Rp6.000', 'Rp7.000', 'Rp12.000', 'Rp5.000', 'Rp4.000', 'D', 1.00],
            [34, 'Perancangan Algoritma', 'Ada 9 koin yang tampak sama, tetapi satu koin lebih berat. Kamu memiliki timbangan dua sisi (tanpa anak timbang) yang hanya menunjukkan sisi mana yang lebih berat atau seimbang. Banyak penimbangan paling sedikit yang pasti cukup untuk menemukan koin yang lebih berat adalah ...', '1', '3', '4', '8', '2', 'E', 1.40],

            [35, 'Analisis dan Evaluasi Solusi', 'Setelah mempresentasikan rancangan solusinya, sebuah kelompok menyadari bahwa langkah 3 menghasilkan jawaban yang salah untuk data tertentu. Tindakan evaluasi yang paling tepat adalah ...', 'mengabaikannya karena sebagian besar data menghasilkan jawaban benar', 'menghapus seluruh rancangan dan mengerjakan dari awal tanpa memeriksa penyebabnya', 'menguji ulang dengan beberapa data, menemukan langkah yang salah, lalu memperbaikinya', 'menambahkan langkah baru di akhir tanpa pengujian', 'meminta kelompok lain mengerjakan perbaikannya', 'C', -0.92],
            [36, 'Analisis dan Evaluasi Solusi', "Algoritma berikut dimaksudkan mencari nilai terbesar dari data 4, 9, 2.\n\nmaks ← 0\nuntuk setiap d pada data:\n    jika d < maks maka maks ← d\ntampilkan maks\n\nHasil algoritma dan perbaikan yang tepat adalah ...", 'hasil 0; ganti tanda < menjadi >', 'hasil 9; tidak perlu perbaikan', 'hasil 2; ganti nilai awal maks menjadi 100', "hasil 4; ganti 'tampilkan maks' menjadi 'tampilkan d'", 'hasil 0; ganti nilai awal maks menjadi 100', 'A', 0.03],
            [37, 'Analisis dan Evaluasi Solusi', 'Sebuah algoritma dimaksudkan menghitung rata-rata dua nilai dengan pseudocode: rata ← a + b ÷ 2 (pembagian dikerjakan lebih dulu daripada penjumlahan). Untuk a = 80 dan b = 60, hasil yang ditampilkan dan perbaikan yang tepat adalah ...', 'hasil 70; tidak perlu perbaikan', 'hasil 110; ganti menjadi a + b × 2', 'hasil 140; ganti menjadi a × b ÷ 2', 'hasil 110; ganti menjadi (a + b) ÷ 2', 'hasil 70; ganti menjadi a + (b ÷ 2)', 'D', 0.18],
            [38, 'Analisis dan Evaluasi Solusi', 'Dua kelompok menghitung jumlah semua bilangan dari 1 sampai 100. Kelompok P menjumlahkan satu per satu (1 + 2 + 3 + ... + 100). Kelompok Q memakai pola pasangan: (1 + 100) × 100 ÷ 2. Pernyataan yang paling tepat adalah ...', 'Hasil kedua kelompok berbeda karena cara kerjanya berbeda', 'Hasil keduanya sama, dan solusi Q lebih efisien karena polanya berlaku untuk bilangan akhir berapa pun', 'Solusi P lebih efisien karena tidak memerlukan rumus', 'Solusi Q hanya berlaku untuk bilangan akhir 100', 'Kedua solusi sama efisiennya karena menghasilkan jawaban yang sama', 'B', 0.22],
            [39, 'Analisis dan Evaluasi Solusi', 'Sebuah solusi menentukan diskon: belanja ≥ Rp100.000 mendapat diskon 10%. Kelompok ingin menguji ketepatan solusi dengan data uji yang sedikit tetapi paling efektif. Data uji yang paling tepat adalah ...', 'Rp10.000, Rp20.000, dan Rp30.000', 'Rp500.000, Rp600.000, dan Rp700.000', 'tiga nilai yang sangat besar', 'tiga nilai yang sangat kecil', 'Rp99.999, Rp100.000, dan Rp100.001', 'E', 0.25],
            [40, 'Analisis dan Evaluasi Solusi', "Gelas P berisi jus, gelas Q berisi susu, dan gelas R kosong. Ketiga gelas berukuran sama dan setiap gelas hanya muat satu isi penuh. Tanpa mencampur cairan, isi P dan Q harus saling bertukar. Notasi X → Y berarti menuang seluruh isi gelas X ke gelas Y. Urutan penuangan yang benar adalah ...", 'P → Q, Q → R, R → P', 'Q → P, P → R, R → Q', 'P → R, Q → P, R → Q', 'R → P, Q → R, P → Q', 'P → R, R → Q, Q → P', 'C', 1.45],
        ];

        foreach ($daftarSoal as $item) {
            ButirSoal::updateOrCreate(
                ['nomor_butir' => $item[0]],
                [
                    'indikator'     => $item[1],
                    'teks_soal'     => $item[2],
                    'opsi_a'        => $item[3],
                    'opsi_b'        => $item[4],
                    'opsi_c'        => $item[5],
                    'opsi_d'        => $item[6],
                    'opsi_e'        => $item[7],
                    'kunci_jawaban' => $item[8],
                    'ukuran_b'      => $item[9],
                    'status_fit'    => true,
                ]
            );
        }
    }
}
