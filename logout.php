<?php
session_start();
session_destroy(); // Mengakhiri semua sesi
header('Location: index.php'); // Mengarahkan kembali ke halaman login
exit();
?>
