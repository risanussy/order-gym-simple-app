<?php
session_start();
include 'db.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        header('Location: membership.php');
        exit();
    } else {
        $error = "Email atau password salah!";
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Armaniy gym</title>
    <link rel="icon" href="logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .w-custom {
            width: 50%;
        }

        @media (max-width: 768px) {
        .w-custom {
            width: 100%;
        }
        }
    </style>
  </head>
  <body class="bg-light">
    <div class="w-100 d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="p-3 w-custom w-sm-50 bg-white shadow rounded">
            <div class="row">
                <div class="col-md-6">
                    <div class="w-100 rounded overflow-hidden d-flex justify-content-center align-items-center" style="height: 400px;background-image: url(https://media.istockphoto.com/id/1438034462/id/foto/wanita-olahraga-latin-dan-afrika-berolahraga-dan-membangun-otot-di-stadion-aktif-kuat-cantik.jpg?s=612x612&w=0&k=20&c=UMieHTCS30-MVnkxqcBIbtYaNtnUX5W9aVroPkcfltw=);background-position: center;background-size: cover;">
                        <img src="logo.jpg" class="h-100" style="opacity: .9;">
                    </div>
                </div>
                <div class="col-md-6 d-flex align-items-center">
                    <div class="w-100">
                        <h2>Login Admin</h2>
                        <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100" name="login">Submit</button>
                        </form>
                        <br>
                        <small>alamat : Jln. Taebenu kel. Liliba depan kantor lurah liliba.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
