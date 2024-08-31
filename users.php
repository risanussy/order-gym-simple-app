<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Handle penambahan dan penghapusan pengguna
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_user'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $password]);
    } elseif (isset($_POST['delete_user'])) {
        $id = $_POST['user_id'];
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
    }
}

// Ambil data pengguna
$users = $pdo->query("SELECT * FROM users")->fetchAll();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Users | Armaniy gym</title>
    <link rel="icon" href="logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body class="bg-light">
    <nav class="navbar navbar-expand-lg bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand fw-bold text-danger" href="#">
              <img src="logo.png" class="me-2" height="45px">
              Armaniy gym
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                <a class="nav-link" href="membership.php">Membership</a>
                </li>
                <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Users</a>
                </li>
            </ul>

            <div class="d-flex align-items-center">
                <span class="text-muted">alamat : Jln. Taebenu kel. Liliba depan kantor lurah liliba.</span>
                <span class="d-inline-block mx-2 text-muted">|</span>
                <a href="logout.php" class="btn btn-outline-danger">Logout</a> <!-- Tombol Logout -->
            </div>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <div class="card p-3 py-5 shadow">
                    <h2>Manajemen Pengguna</h2>
                    <form method="POST" class="mb-4">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" name="add_user" class="btn btn-primary">Tambah Pengguna</button>
                    </form>
                </div>
            </div>
            <div class="col-md-6">                
                    <div class="w-100 rounded overflow-hidden d-flex justify-content-center align-items-center" style="height: 100%;background-image: url(https://media.istockphoto.com/id/1438034462/id/foto/wanita-olahraga-latin-dan-afrika-berolahraga-dan-membangun-otot-di-stadion-aktif-kuat-cantik.jpg?s=612x612&w=0&k=20&c=UMieHTCS30-MVnkxqcBIbtYaNtnUX5W9aVroPkcfltw=);background-position: center;background-size: cover;">
                        <!-- <img src="logo.jpg" class="h-100" style="opacity: .9;"> -->
                    </div>
            </div>
        </div>
        <br>
        <br>
        <h4>Daftar Pengguna</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id']; ?></td>
                        <td><?= $user['name']; ?></td>
                        <td><?= $user['email']; ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                                <button type="submit" name="delete_user" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
