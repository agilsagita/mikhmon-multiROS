# MIKHMON V3 - MikroTik Hotspot Monitor

**MIKHMON (MikroTik Hotspot Monitor)** adalah aplikasi web berbasis PHP yang dirancang khusus untuk mempermudah administrasi dan pemantauan server MikroTik Hotspot. Mikhmon tidak membutuhkan database SQL (seperti MySQL) karena semua data user hotspot dibaca langsung dan disimpan ke dalam router MikroTik melalui koneksi API MikroTik.

Aplikasi ini sangat cocok digunakan oleh pengelola RT/RW Net, kafe, kos-kosan, sekolah, atau usaha mikro lainnya yang menggunakan MikroTik Hotspot untuk distribusi akses internet.

---

## 🚀 Fitur Utama

- **Multi Session & Multi Router:** Kelola beberapa router MikroTik dalam satu aplikasi web.
- **Manajemen Hotspot User:**
  - Pembuatan (generate) voucher dalam jumlah banyak sekaligus secara otomatis.
  - Username dan password acak (kombinasi huruf, angka, atau karakter kustom).
  - Cetak voucher dengan desain template yang dapat disesuaikan (mendukung cetak dengan QR Code & Barcode).
- **Manajemen Profil Pengguna (User Profile):**
  - Limitasi kecepatan (Bandwidth / Simple Queue) serta dukungan Parent Queue.
  - Penentuan masa aktif (expired mode: *Remove*, *Notice*, *Notice & Record*).
  - Penghapusan otomatis user yang sudah kedaluwarsa via script monitor scheduler.
- **Laporan Penjualan (Report & Logs):**
  - Pencatatan laporan penjualan harian dan bulanan secara teratur.
  - Fitur Resume Report untuk memantau pendapatan dari periode sebelumnya.
  - Live Report untuk memantau transaksi penjualan hotspot secara real-time.
- **Monitoring Traffic:**
  - Grafik real-time pemantauan lalu lintas data (traffic RX/TX) menggunakan Highcharts.
  - Indikator koneksi router yang aktif.
- **Dukungan Cetak Mobile:**
  - Cetak voucher langsung dari perangkat Android menggunakan printer Bluetooth Thermal (menggunakan aplikasi Quick Printer / Zjiang BT Printer).

---

## 🛠️ Persyaratan Sistem

- Server Web (seperti Laragon, XAMPP, Nginx, Apache, atau Termux di Android).
- PHP versi 5.6 hingga PHP 7.x (pastikan modul `sockets` dan `curl` aktif).
- MikroTik RouterOS dengan fitur API diaktifkan (`/ip service enable api` atau port `8728`).

---

## 💻 Cara Instalasi & Menjalankan

### Metode 1: Menggunakan Docker & Docker Compose (Untuk Pengujian/Lab)
Project ini telah dilengkapi dengan konfigurasi Docker Compose untuk memudahkan pengujian.

1. Lakukan klon repositori ini:
   ```bash
   git clone <url-repository>
   cd mikhmon
   ```
2. Jalankan perintah docker-compose:
   ```bash
   docker-compose up -d
   ```
3. Akses layanan:
   - **RouterOS Web (Test-Lab):** Buka [http://localhost:8081](http://localhost:8081), login dengan IP `192.168.88.1` dan password `12345` untuk konfigurasi awal.
   - **Aplikasi Mikhmon:** Buka [http://localhost:8080](http://localhost:8080).
     - **Username Default:** `mikhmon`
     - **Password Default:** `1234`
     - **Detail Koneksi Router Uji Coba:** IP Address `172.27.0.7`, Username `admin`, Password `12345`. Isi informasi lainnya secara bebas lalu klik **Save**.
4. Untuk menghentikan kontainer Docker:
   ```bash
   docker-compose down
   ```

### Metode 2: Menggunakan Web Server Lokal (Laragon / XAMPP / Hosting)
1. Salin seluruh isi folder project mikhmon ini ke folder root web server Anda (misal `C:\laragon\www\mikhmon` atau `C:\xampp\htdocs\mikhmon`).
2. Jalankan Web Server (Apache/Nginx dan PHP).
3. Buka browser dan akses alamat `http://localhost/mikhmon`.

---

## 📝 Konfigurasi Awal Aplikasi

1. Buka halaman utama Mikhmon di browser Anda.
2. Masuk menggunakan akun admin bawaan:
   - **Username:** `mikhmon`
   - **Password:** `1234`
3. Setelah login, Anda akan diarahkan ke halaman pengelolaan session. Klik **Add Router** untuk menambahkan router baru.
4. Isi data-data koneksi router MikroTik Anda:
   - **Session Name:** Nama sesi/router (bebas).
   - **IP Mikrotik:** Alamat IP router Anda (contoh: `192.168.1.1` atau IP public/DDNS).
   - **Username:** Username login admin MikroTik Anda yang memiliki hak akses API.
   - **Password:** Password login MikroTik Anda.
   - **Hotspot Name:** Nama server hotspot MikroTik Anda.
   - **DNS Name:** Alamat DNS login hotspot Anda (contoh: `wifi.net`).
5. Klik **Save** lalu klik **Connect** untuk masuk ke Dashboard Router.

---

## 🔄 Riwayat Perubahan (Changelog)

#### Pembaruan Kustom (Patched by Agil) - 23-07-2026
- **Kompatibilitas MikroTik RouterOS v7 (Multi-ROS Support):**
  - Penerjemahan otomatis kueri bulan alfabetis (misal `jul2026` -> `072026`) di `routeros_api.class.php` untuk pencocokan skrip ROS7.
  - Skrip hapus otomatis kedaluwarsa (*background service*) di `adduserprofile.php` dan `userprofilebyname.php` mendukung format tanggal ROS6 (`/`) dan ROS7 (`-`).
  - Laporan penjualan hari ini (*live report*) di `livereport.php` dan `selling.php` mendukung format tanggal angka ROS7, dilengkapi fungsi `trim()` untuk meniadakan selisih spasi string skrip MikroTik.
- **Penyempurnaan Visual Premium (Tema Dark/Purple Glassmorphism):**
  - Desain antarmuka modern serba semi-transparan (*glassmorphism*) yang menyatu di seluruh halaman.
  - Perbaikan tumpang tindih ikon dengan teks ketikan pada kolom input login.
  - Kustomisasi seragam tombol utama (`.btn`) dengan warna gradien modern (Ungu, Hijau, Kuning, Merah, & Kaca Gelap).
  - Penyelarasan baris tombol aksi (Save, Connect, Ping, Refresh) di pengaturan sesi ke dalam tata letak flexbox yang rapi.
  - Modernisasi elemen `.group-item` (checkbox password, tombol grup, pilihan dropdown) dengan tinggi `38px` dan penyelarasan margin otomatis.
- **Sidebar & Navigasi Pohon (Tree Navigation):**
  - Struktur navigasi submenu bertingkat dengan garis putus-putus (*dashed line*) visual layaknya folder direktori.
  - Pengurangan ukuran teks dan padding pada submenu agar muat sempurna tanpa terpotong batas lebar sidebar.
  - Isolasi garis aksen ungu aktif hanya pada menu halaman yang dibuka (bukan tombol folder induk dropdown).
  - Penghapusan menu "About" dan penempatan iklan mitra (Template Hotspot, Jasa MikroTik, seBilling, Semesta) secara halus/redup di bagian bawah sidebar.
- **Navigasi Atas & Kelurusan Baris Tabel Laporan:**
  - Konversi `#navbar` menjadi flexbox untuk meniadakan kerusakan float pembungkus kolom.
  - Pensejajaran vertikal clock, pilihan sesi/tema, dan tombol logout di navbar atas.
  - Penyelarasan horizontal kolom pencarian dengan tombol cetak laporan (Default, QR, Small) menggunakan alignment inline.
  - Penataan ulang tautan aksi *Open* dan *Generate* di dalam kartu Voucher (`userbyprofile.php`) menjadi berbaris rapi kesamping dengan transisi warna hover dinamis.

#### Pembaruan 30-06-2021 V3.20
- Perbaikan kesalahan ketik (typo) pada script profile `on-login`.
- **Saran:** Silakan perbarui user profile di Mikhmon dengan cara membuka masing-masing user profile, lalu klik **Save** kembali.

#### Pembaruan 24-01-2021
- Penambahan file `docker-compose.yml` untuk test-lab beserta image MikroTik RouterOS.

#### Pembaruan 09-08-2020 V3.19
- Penambahan kolom sisa voucher di pilihan komentar ("option comment") pada halaman daftar user.

#### Pembaruan 04-07-2020
- Penambahan `Dockerfile` untuk kemudahan pengujian kontainer Docker.

#### Pembaruan 16-08-2019 V3.18
- Penambahan fitur Harga Jual (*selling price*) yang akan tampil langsung di kertas voucher.
- Perlu memperbarui profil pengguna dengan mengisi harga jual dan memperbarui template voucher.
- Untuk pengguna Termux di Android, silakan hapus instalan Mikhmon lama lalu pasang kembali.

#### Pembaruan 06-08-2019 V3.17
- Perbaikan fitur *live report*.
- Perbaikan fungsi *generate users*.
- Penambahan fitur *idle timeout* (auto logout admin).
- Penambahan fitur ping IP MikroTik langsung dari pengaturan sesi.

#### Pembaruan 14-07-2019 V3.16
- Penambahan pilihan *address pool* pada menu tambah dan ubah profil pengguna (user profile).
- Penambahan notifikasi pembaruan sistem baru di pengaturan admin.

#### Pembaruan 02-07-2019 V3.15
- Pembaruan pustaka RouterOS API untuk mendukung sistem RouterOS versi v6.45.x.

#### Pembaruan 09-05-2019 V3.14
- Perbaikan pengaturan zona waktu untuk fitur Print / Quick Print.
- Penambahan input komentar setelah status komentar user berubah menjadi tanggal expired.

#### Pembaruan 06-04-2019 V3.13 r7
- Perbaikan modul tambah profil (mengatasi kegagalan pembuatan *monitor profile* di scheduler MikroTik).
- Perbaikan modul edit profil (menghapus *monitor profile* jika mode expired diatur ke *none*).
- Penambahan indikator *monitor profile* di daftar profil pengguna (Hijau = Monitor Profile aktif, Jingga = tidak aktif).

#### Pembaruan 02-04-2019 V3.13 r6
- Perbaikan perhitungan tanggal dan jam pada monitor user profile.
- Optimalisasi fungsi dari global ke lokal.
- **Saran:** Buka dan simpan ulang masing-masing user profile dari Mikhmon, kemudian hapus semua data environment lama di MikroTik (`System -> Scripts -> Environment`).

#### Pembaruan 30-03-2019 V3.13 r4
- Perbaikan fitur edit user.
- Penambahan filter nama profil pada pencarian berdasarkan komentar.
- Penambahan opsi hapus user yang kedaluwarsa secara cepat dari daftar user.
- Perbaikan cetak laporan penjualan.

#### Pembaruan 20-03-2019 V3.13
- Pembuatan QR Code sekarang diproses secara lokal (tidak lagi bergantung pada Google Chart API).
- Perubahan variabel QR Code menjadi `<?= $qrcode ?>` tanpa tag `<img>`. Template kustom perlu disesuaikan.
- Penghapusan masa tenggang (*grace period*) dan info start/end user.
- Pembaruan sistem mode kedaluwarsa (*expired mode*) baru yang memanfaatkan komentar user secara dinamis setelah login, menggantikan sistem scheduler per-user yang membebani router.

---

## ⚖️ Lisensi

Mikhmon V3 didevelop oleh **Laksamadi Guko** dan dirilis di bawah lisensi GNU General Public License v2.0. Anda bebas menggunakan, memodifikasi, dan mendistribusikan ulang aplikasi ini dengan tetap menyertakan hak cipta pembuat asli.
