<?php
// Koneksi ke database
session_start();
include 'config.php';
include 'functions.php';

$servername = "localhost";
$username = "id22276528_root";
$password = "12_Wafaras";
$database = "id22276528_karangasem";

$conn = new mysqli($servername, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
$userId = $_SESSION['user_id']; // Pastikan pengguna sudah login dan user_id tersimpan di session

// Fungsi untuk mengambil order berdasarkan user_id
function getUserOrders($conn, $userId) {
    $sql = "SELECT orders.*, package.name as package_name FROM orders JOIN package ON orders.package_id = package.id WHERE orders.user_id = ? ORDER BY orders.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Ambil order untuk pengguna tertentu
$orders = getUserOrders($conn, $userId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Order Saya</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h1>Riwayat Order Saya</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Order</th>
                    <th>Nama Lengkap</th>
                    <th>Nomor Telepon</th>
                    <th>Jumlah Orang</th>
                    <th>Nama Paket</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Tanggal Order</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['id']); ?></td>
                        <td><?php echo htmlspecialchars($order['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['phone_number']); ?></td>
                        <td><?php echo htmlspecialchars($order['num_people']); ?></td>
                        <td><?php echo htmlspecialchars($order['package_name']); ?></td>
                        <td>Rp <?php echo number_format($order['total_price'], 0, ',', '.'); ?></td>
                        <td><?php echo htmlspecialchars($order['status']); ?></td>
                        <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
