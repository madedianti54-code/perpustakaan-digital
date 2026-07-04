?php
include "koneksi.php";

$aksi = $_GET['aksi'] ?? 'lihat';
$id   = (int) ($_GET['id'] ?? 0);
$cari = $_GET['cari'] ?? '';

// ================= TAMBAH =================
if ($aksi === 'tambah' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul        = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $penulis      = mysqli_real_escape_string($koneksi, $_POST['penulis']);
    $penerbit     = mysqli_real_escape_string($koneksi, $_POST['penerbit']);
    $tahun_terbit = (int) $_POST['tahun_terbit'];
    $id_kategori  = (int) $_POST['id_kategori'];
    $stok         = (int) $_POST['stok'];

    mysqli_query($koneksi, "INSERT INTO buku (judul, penulis, penerbit, tahun_terbit, id_kategori, stok)
                             VALUES ('$judul', '$penulis', '$penerbit', $tahun_terbit, $id_kategori, $stok)");
    header("Location: buku.php?sukses=Buku berhasil ditambahkan");
    exit;
}

// ================= EDIT =================
if ($aksi === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul        = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $penulis      = mysqli_real_escape_string($koneksi, $_POST['penulis']);
    $penerbit     = mysqli_real_escape_string($koneksi, $_POST['penerbit']);
    $tahun_terbit = (int) $_POST['tahun_terbit'];
    $id_kategori  = (int) $_POST['id_kategori'];
    $stok         = (int) $_POST['stok'];

    mysqli_query($koneksi, "UPDATE buku SET judul='$judul', penulis='$penulis', penerbit='$penerbit',
                             tahun_terbit=$tahun_terbit, id_kategori=$id_kategori, stok=$stok
                             WHERE id_buku = $id");
    header("Location: buku.php?sukses=Buku berhasil diperbarui");
    exit;
}

// ================= HAPUS =================
if ($aksi === 'hapus' && $id > 0) {
    mysqli_query($koneksi, "DELETE FROM buku WHERE id_buku = $id");
    header("Location: buku.php?sukses=Buku berhasil dihapus");
    exit;
}

// Data buku yang sedang diedit (kalau ada)
$dataEdit = null;
if ($aksi === 'edit' && $id > 0) {
    $dataEdit = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM buku WHERE id_buku = $id"));
}

// Dropdown kategori
$listKategori = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY nama_kategori ASC");

// Data tabel (dengan pencarian judul/penulis)
$cariAman = mysqli_real_escape_string($koneksi, $cari);
$sql = "SELECT buku.*, kategori.nama_kategori
        FROM buku
        LEFT JOIN kategori ON buku.id_kategori = kategori.id_kategori
        WHERE judul LIKE '%$cariAman%' OR penulis LIKE '%$cariAman%'
        ORDER BY buku.id_buku DESC";
$data = mysqli_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4">Data Buku</h2>

    <?php if (isset($_GET['sukses'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['sukses']) ?></div>
    <?php endif; ?>

    <!-- FORM TAMBAH / EDIT -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title"><?= $dataEdit ? 'Edit Buku' : 'Tambah Buku' ?></h5>
            <form method="POST" action="buku.php?aksi=<?= $dataEdit ? 'edit&id=' . $dataEdit['id_buku'] : 'tambah' ?>">
                <div class="row g-2">
                    <div class="col-md-6">
                        <input type="text" name="judul" class="form-control" placeholder="Judul buku"
                               value="<?= $dataEdit ? htmlspecialchars($dataEdit['judul']) : '' ?>" required>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="penulis" class="form-control" placeholder="Penulis"
                               value="<?= $dataEdit ? htmlspecialchars($dataEdit['penulis']) : '' ?>" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="penerbit" class="form-control" placeholder="Penerbit"
                               value="<?= $dataEdit ? htmlspecialchars($dataEdit['penerbit']) : '' ?>">
                    </div>
                    <div class="col-md-4">
                        <input type="number" name="tahun_terbit" class="form-control" placeholder="Tahun terbit"
                               min="1900" max="2100"
                               value="<?= $dataEdit ? htmlspecialchars($dataEdit['tahun_terbit']) : '' ?>">
                    </div>
                    <div class="col-md-4">
                        <input type="number" name="stok" class="form-control" placeholder="Stok" min="0"
                               value="<?= $dataEdit ? (int) $dataEdit['stok'] : 0 ?>" required>
                    </div>
                    <div class="col-md-6">
                        <select name="id_kategori" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php
                            mysqli_data_seek($listKategori, 0);
                            while ($k = mysqli_fetch_assoc($listKategori)):
                            ?>
                                <option value="<?= $k['id_kategori'] ?>"
                                    <?= ($dataEdit && $dataEdit['id_kategori'] == $k['id_kategori']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($k['nama_kategori']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary"><?= $dataEdit ? 'Update' : 'Simpan' ?></button>
                        <?php if ($dataEdit): ?>
                            <a href="buku.php" class="btn btn-secondary">Batal</a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- FORM PENCARIAN -->
    <form method="GET" class="row g-2 mb-3">
        <div class="col-auto">
            <input type="text" name="cari" class="form-control" placeholder="Cari judul atau penulis..."
                   value="<?= htmlspecialchars($cari) ?>">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Cari</button>
            <?php if ($cari !== ""): ?>
                <a href="buku.php" class="btn btn-secondary">Reset</a>
            <?php endif; ?>
        </div>
    </form>

    <!-- TABEL DATA -->
    <table class="table table-bordered bg-white">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Penerbit</th>
                <th>Tahun</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th width="150">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($data) === 0): ?>
                <tr><td colspan="8" class="text-center text-muted">Buku tidak ditemukan</td></tr>
            <?php else: ?>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($data)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td><?= htmlspecialchars($row['penulis']) ?></td>
                    <td><?= htmlspecialchars($row['penerbit']) ?></td>
                    <td><?= htmlspecialchars($row['tahun_terbit']) ?></td>
                    <td><?= htmlspecialchars($row['nama_kategori'] ?? '-') ?></td>
                    <td><?= (int) $row['stok'] ?></td>
                    <td>
                        <a href="buku.php?aksi=edit&id=<?= $row['id_buku'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="buku.php?aksi=hapus&id=<?= $row['id_buku'] ?>" class="btn btn-sm btn-danger"
                           onclick="return confirm('Yakin ingin menghapus buku ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="kategori.php" class="btn btn-secondary">Lihat Data Kategori</a>
</div>
</body>
</html>