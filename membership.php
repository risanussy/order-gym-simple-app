<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Handle penambahan, pengeditan, dan penghapusan membership
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_membership'])) {
        $name = $_POST['name'];
        $member_number = $_POST['member_number'];
        $join_date = $_POST['join_date'];
        $phone_number = $_POST['phone_number'];
        $amount = $_POST['amount'];

        $stmt = $pdo->prepare("INSERT INTO memberships (name, member_number, join_date, phone_number, amount) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $member_number, $join_date, $phone_number, $amount]);
    } elseif (isset($_POST['edit_membership'])) {
        $id = $_POST['membership_id'];
        $name = $_POST['name'];
        $member_number = $_POST['member_number'];
        $join_date = $_POST['join_date'];
        $phone_number = $_POST['phone_number'];
        $amount = $_POST['amount'];

        $stmt = $pdo->prepare("UPDATE memberships SET name = ?, member_number = ?, join_date = ?, phone_number = ?, amount = ? WHERE id = ?");
        $stmt->execute([$name, $member_number, $join_date, $phone_number, $amount, $id]);
    } elseif (isset($_POST['delete_membership'])) {
        $id = $_POST['membership_id'];
        $stmt = $pdo->prepare("DELETE FROM memberships WHERE id = ?");
        $stmt->execute([$id]);
        echo "<script>Swal.fire('Berhasil!', 'Data berhasil dihapus!', 'success');</script>";
    }
}

// Ambil data memberships
try {
    $stmt = $pdo->query("SELECT * FROM memberships");
    $memberships = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $memberships = []; // Jika query gagal, set $memberships sebagai array kosong
    echo "<script>console.error('Error fetching memberships: " . $e->getMessage() . "');</script>";
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Membership | Armaniy gym</title>
    <link rel="icon" href="logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.sheetjs.com/xlsx-0.19.0/package/dist/xlsx.full.min.js"></script>
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
                <a class="nav-link active" aria-current="page" href="#">Membership</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="users.php">Users</a>
                </li>
            </ul>

            <div class="d-flex align-items-center">
                <span class="text-muted">alamat : Jln. Taebenu kel. Liliba depan kantor lurah liliba.</span>
                <span class="d-inline-block mx-2 text-muted">|</span>
                <a href="logout.php" class="btn btn-outline-danger">Logout</a>
            </div>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div id="carouselExampleSlidesOnly" class="carousel slide mb-5" data-bs-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active">                              
              <div class="w-100 rounded overflow-hidden d-flex justify-content-center align-items-center" style="height: 200px;background-image: url(https://images.squarespace-cdn.com/content/v1/628c0b6d5d2e1e3ca6023ce5/6382429b-b502-4859-9b9b-ba77f609a252/Header+Image+Gym.jpg);background-position: center;background-size: cover;">
              </div>
            </div>
            <div class="carousel-item">                              
              <div class="w-100 rounded overflow-hidden d-flex justify-content-center align-items-center" style="height: 200px;background-image: url(https://blue.kumparan.com/image/upload/fl_progressive,fl_lossy,c_fill,q_auto:best,w_640/v1634025439/01g7nc3pc0zkk5bw3vrrd0s323.jpg);background-position: center;background-size: cover;">
              </div>
            </div>
            <div class="carousel-item">                            
              <div class="w-100 rounded overflow-hidden d-flex justify-content-center align-items-center" style="height: 200px;background-image: url(https://blue.kumparan.com/image/upload/fl_progressive,fl_lossy,c_fill,q_auto:best,w_640/v1634025439/01g8d1tdk720r331crpezbr08e.jpg);background-position: center;background-size: cover;">
              </div>
            </div>
          </div>
        </div>
        <h2>Manajemen Membership</h2>

        <!-- Tombol untuk Membuka Modal Tambah Membership -->
        <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addMembershipModal">Tambah Membership</button>

        <!-- Tombol Cetak Laporan ke Excel -->
        <button class="btn btn-success mb-4" onclick="exportToExcel()">Cetak Laporan ke Excel</button>

        <h4>Daftar Membership</h4>
        <table id="membershipTable" class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Nomor Member</th>
                    <th>Tanggal Masuk</th>
                    <th>Nomor Telepon</th>
                    <th>Jumlah Uang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($memberships)): ?>
                    <?php foreach ($memberships as $membership): ?>
                        <tr>
                            <td><?= $membership['id']; ?></td>
                            <td>
                                <a href="#" onclick="showMembershipDuration('<?= $membership['name']; ?>', '<?= $membership['join_date']; ?>')">
                                    <?= $membership['name']; ?>
                                </a>
                            </td>
                            <td><?= $membership['member_number']; ?></td>
                            <td><?= $membership['join_date']; ?></td>
                            <td><?= $membership['phone_number']; ?></td>
                            <td><?= $membership['amount']; ?></td>
                            <td>
                                <!-- Tombol Edit -->
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editMembershipModal" onclick="editMembership(<?= $membership['id']; ?>, '<?= $membership['name']; ?>', '<?= $membership['member_number']; ?>', '<?= $membership['join_date']; ?>', '<?= $membership['phone_number']; ?>', '<?= $membership['amount']; ?>')">Edit</button>

                                <!-- Form Hapus dengan SweetAlert -->
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $membership['id']; ?>)">Hapus</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data membership ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah Membership -->
    <div class="modal fade" id="addMembershipModal" tabindex="-1" aria-labelledby="addMembershipModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMembershipModalLabel">Tambah Membership</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="member_number" class="form-label">Nomor Member</label>
                            <input type="text" class="form-control" id="member_number" name="member_number" required>
                        </div>
                        <div class="mb-3">
                            <label for="join_date" class="form-label">Tanggal Masuk</label>
                            <input type="date" class="form-control" id="join_date" name="join_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Jumlah Uang</label>
                            <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_membership" class="btn btn-primary">Tambah Membership</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Membership -->
    <div class="modal fade" id="editMembershipModal" tabindex="-1" aria-labelledby="editMembershipModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMembershipModalLabel">Edit Membership</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <input type="hidden" id="edit_membership_id" name="membership_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_member_number" class="form-label">Nomor Member</label>
                            <input type="text" class="form-control" id="edit_member_number" name="member_number" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_join_date" class="form-label">Tanggal Masuk</label>
                            <input type="date" class="form-control" id="edit_join_date" name="join_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_phone_number" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" id="edit_phone_number" name="phone_number" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_amount" class="form-label">Jumlah Uang</label>
                            <input type="number" step="0.01" class="form-control" id="edit_amount" name="amount" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="edit_membership" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#membershipTable').DataTable({
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Tidak ditemukan data yang sesuai",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data yang tersedia",
                    "infoFiltered": "(difilter dari total _MAX_ data)"
                }
            });
        });

        // Function untuk mengisi data ke form edit
        function editMembership(id, name, member_number, join_date, phone_number, amount) {
            $('#edit_membership_id').val(id);
            $('#edit_name').val(name);
            $('#edit_member_number').val(member_number);
            $('#edit_join_date').val(join_date);
            $('#edit_phone_number').val(phone_number);
            $('#edit_amount').val(amount);
        }

        // Function untuk konfirmasi penghapusan dengan SweetAlert
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('<form method="POST"><input type="hidden" name="membership_id" value="' + id + '"><input type="hidden" name="delete_membership"></form>').appendTo('body').submit();
                }
            })
        }

        // Function untuk ekspor tabel ke Excel menggunakan SheetJS
        function exportToExcel() {
            var wb = XLSX.utils.table_to_book(document.getElementById('membershipTable'), {sheet: "Memberships"});
            XLSX.writeFile(wb, 'Laporan_Membership.xlsx');
        }

        // Function untuk menampilkan estimasi durasi keanggotaan
        function showMembershipDuration(name, join_date) {
            // Convert join_date string to Date object
            var startDate = new Date(join_date);
            
            // Add 1 month to the join date to calculate the end date
            var endDate = new Date(startDate);
            endDate.setMonth(startDate.getMonth() + 1);

            // Format the end date
            var options = { year: 'numeric', month: 'long', day: 'numeric' };
            var formattedEndDate = endDate.toLocaleDateString('id-ID', options);

            // Show SweetAlert with membership duration information
            Swal.fire({
                title: `Estimasi Durasi Membership`,
                text: `Keanggotaan ${name} dimulai pada ${startDate.toLocaleDateString('id-ID', options)} dan akan berakhir pada ${formattedEndDate}.`,
                icon: 'info'
            });
        }
    </script>
  </body>
</html>
