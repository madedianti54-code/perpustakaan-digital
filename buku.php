<?php
include "koneksi.php";

$cari = "";

if (isset($_GET['cari'])) {
    $cari = $_GET['cari'];
}

$sql = mysqli_query($koneksi, "SELECT * FROM buku WHERE Judul LIKE '%$cari%'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Buku</title>
</head>
<body>

<h2>Daftar Buku</h2>

<form method="GET">
    <input type="text" name="cari" placeholder="Cari Buku..." value="<?php echo $cari; ?>">
    <button type="submit">Cari</button>
</form>

<br>

<table border="1">
    <tr>
        <th>ID Buku</th>
        <th>Judul</th>
        <th>Penulis</th>
        <th>Penerbit</th>
        <th>Tahun Terbit</th>
        <th>Stok</th>
    </tr>

<?php while($data = mysqli_fetch_array($sql)){ ?>

<tr>
    <td><?php echo $data['ID_Buku']; ?></td>
    <td><?php echo $data['Judul']; ?></td>
    <td><?php echo $data['Penulis']; ?></td>
    <td><?php echo $data['Penerbit']; ?></td>
    <td><?php echo $data['Tahun-Terbit']; ?></td>
    <td><?php echo $data['Stok']; ?></td>
</tr>

<?php } ?>

</table>

</body>
</html>