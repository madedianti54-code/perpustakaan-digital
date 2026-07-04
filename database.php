<?php
$host = "localhost";
$user = "root";
$pass = "";

$koneksi = mysqli_connect($host, $user, $pass);

if (!$koneksi) {
    die("Koneksi ke server MySQL gagal: " . mysqli_connect_error());
}

$sqlBuatDb = "CREATE DATABASE IF NOT EXISTS perpustakaan_digital";
if (mysqli_query($koneksi, $sqlBuatDb)) {
    echo "Database 'perpustakaan_digital' berhasil dibuat / sudah ada.<br>";
} else {
    die("Gagal membuat database: " . mysqli_error($koneksi));
}

mysqli_select_db($koneksi, "perpustakaan_digital");

$sqlKategori = "CREATE TABLE IF NOT EXISTS kategori (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL
)";
if (mysqli_query($koneksi, $sqlKategori)) {
    echo "Tabel 'kategori' berhasil dibuat.<br>";
} else {
    die("Gagal membuat tabel kategori: " . mysqli_error($koneksi));
}

$sqlBuku = "CREATE TABLE IF NOT EXISTS buku (
    id_buku INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    penulis VARCHAR(100) NOT NULL,
    penerbit VARCHAR(100),
    tahun_terbit YEAR,
    id_kategori INT,
    stok INT DEFAULT 0,
    FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori) ON DELETE SET NULL
)";
if (mysqli_query($koneksi, $sqlBuku)) {
    echo "Tabel 'buku' berhasil dibuat.<br>";
} else {
    die("Gagal membuat tabel buku: " . mysqli_error($koneksi));
}

$cekData = mysqli_query($koneksi, "SELECT * FROM kategori");
if (mysqli_num_rows($cekData) == 0) {
    mysqli_query($koneksi, "INSERT INTO kategori (nama_kategori) VALUES
        ('Fiksi'), ('Non-Fiksi'), ('Sains'), ('Sejarah')");
    echo "Data contoh kategori berhasil ditambahkan.<br>";
}

echo "<br><strong>Selesai! Database dan tabel sudah siap dipakai.</strong><br>";
echo "Sekarang kamu bisa buka <a href='kategori.php'>kategori.php</a> atau <a href='buku.php'>buku.php</a>.";
?>