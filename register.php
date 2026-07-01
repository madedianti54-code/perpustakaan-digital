<?php
include 'includes/koneksi.php';

$pesan = "";

if (isset($_POST['register'])) {

    $nama       = htmlspecialchars(trim($_POST['nama']));
    $username   = htmlspecialchars(trim($_POST['username']));
    $email      = htmlspecialchars(trim($_POST['email']));
    $password   = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi'];

    // Cek password
    if ($password != $konfirmasi) {
        $pesan = "<div class='alert alert-danger'>Konfirmasi password tidak sama.</div>";
    } else {

        // Cek username atau email
        $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' OR email='$email'");

        if (mysqli_num_rows($cek) > 0) {

            $pesan = "<div class='alert alert-warning'>Username atau Email sudah digunakan.</div>";

        } else {

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $simpan = mysqli_query($conn,"INSERT INTO users
            (nama,username,email,password)
            VALUES
            ('$nama','$username','$email','$passwordHash')");

            if($simpan){
                $pesan = "<div class='alert alert-success'>
                Registrasi berhasil.
                <a href='login.php'>Login disini</a>
                </div>";
            }else{
                $pesan = "<div class='alert alert-danger'>Registrasi gagal.</div>";
            }

        }

    }

}
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Register</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:linear-gradient(135deg,#4facfe,#00c6fb);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.card{
    border:none;
    border-radius:20px;
    box-shadow:0 10px 30px rgba(0,0,0,.2);
}

.btn-primary{
    border-radius:50px;
}

</style>

</head>

<body>

<div class="container">

<div class="row justify-content-center">

<div class="col-md-5">

<div class="card p-4">

<h2 class="text-center mb-4">
📚 Register Akun
</h2>

<?= $pesan; ?>

<form method="POST">

<div class="mb-3">
<label>Nama Lengkap</label>
<input
type="text"
name="nama"
class="form-control"
required>
</div>

<div class="mb-3">
<label>Username</label>
<input
type="text"
name="username"
class="form-control"
required>
</div>

<div class="mb-3">
<label>Email</label>
<input
type="email"
name="email"
class="form-control"
required>
</div>

<div class="mb-3">
<label>Password</label>
<input
type="password"
name="password"
class="form-control"
required>
</div>

<div class="mb-3">
<label>Konfirmasi Password</label>
<input
type="password"
name="konfirmasi"
class="form-control"
required>
</div>

<div class="d-grid">

<button
class="btn btn-primary"
name="register">

Daftar

</button>

</div>

</form>

<hr>

<p class="text-center">

Sudah punya akun?

<a href="login.php">
Login
</a>

</p>

</div>

</div>

</div>

</div>

</body>
</html>\