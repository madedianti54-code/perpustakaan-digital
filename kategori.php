<?php
include "koneksi.php";

$aksi = $_GET['aksi'] ?? 'lihat';
$id   = (int) ($_GET['id'] ?? 0);
$pesan = "";

// ================= TAMBAH =================
if ($aksi === 'tambah' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_kategori']);
    mysqli_query($koneksi, "INSERT INTO kategori (nama_kategori) VALUES ('$nama')");
    header("Location: kategori.php?sukses=Kategori berhasil ditambahkan");
    exit;
}

// ================= EDIT =================
if ($aksi === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_kategori']);
    mysqli_query($koneksi, "UPDATE kategori SET nama_kategori = '$nama' WHERE id_kategori = $id");
    header("Location: kategori.php?sukses=Kategori berhasil diperbarui");
    exit;
}

// ================= HAPUS =================
if ($aksi === 'hapus' && $id > 0) {
    mysqli_query($koneksi, "DELETE FROM kategori WHERE id_kategori = $id");
    header("Location: kategori.php?sukses=Kategori berhasil dihapus");
    exit;
}

// Ambil data kategori yang sedang diedit (kalau ada)
$dataEdit = null;
if ($aksi === 'edit' && $id > 0) {
    $dataEdit = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM kategori WHERE id_kategori = $id"));
}

$semuaKategori = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY id_kategori DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4">Data Kategori Buku</h2>

    <?php if (isset($_GET['sukses'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['sukses']) ?></div>
    <?php endif; ?>

    <!-- FORM TAMBAH / EDIT -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title"><?= $dataEdit ? 'Edit Kategori' : 'Tambah Kategori' ?></h5>
            <form method="POST" action="kategori.php?aksi=<?= $dataEdit ? 'edit&id=' . $dataEdit['id_kategori'] : 'tambah' ?>">
                <div class="mb-3">
                    <input type="text" name="nama_kategori" class="form-control"
                           placeholder="Nama kategori"
                           value="<?= $dataEdit ? htmlspecialchars($dataEdit['nama_kategori']) : '' ?>" required>
                </div>
                <button type="submit" class="btn btn-primary"><?= $dataEdit ? 'Update' : 'Simpan' ?></button>
                <?php if ($dataEdit): ?>
                    <a href="kategori.php" class="btn btn-secondary">Batal</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- TABEL DATA -->
    <table class="table table-bordered bg-white">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th width="150">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($semuaKategori)): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                <td>
                    <a href="kategori.php?aksi=edit&id=<?= $row['id_kategori'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="kategori.php?aksi=hapus&id=<?= $row['id_kategori'] ?>" class="btn btn-sm btn-danger"
                       onclick="return confirm('Yakin ingin menghapus kategori ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="buku.php" class="btn btn-secondary">Lihat Data Buku</a>
</div>
</body>
</html>