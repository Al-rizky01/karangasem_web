<?php
// Koneksi ke database
include 'config.php';
include 'functions.php';

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$database = "karangasem";

$conn = new mysqli($servername, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
// Fungsi untuk mengambil semua order
function getAllOrders($conn) {
    $sql = "SELECT orders.*, package.name as package_name FROM orders JOIN package ON orders.package_id = package.id ORDER BY orders.created_at DESC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fungsi untuk memperbarui status order
function updateOrderStatus($conn, $orderId, $status) {
    $sql = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $orderId);
    $stmt->execute();
}

// Cek jika ada permintaan untuk memperbarui status order
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['order_id'];
    $status = $_POST['status'];
    updateOrderStatus($conn, $orderId, $status);
    header('Location: admin_orders.php');
    exit;
}

// Ambil semua order
$orders = getAllOrders($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Orders - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h1>All Orders</h1>
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
                    <th>Aksi</th>
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
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['id']); ?>">
                                <select name="status" class="form-select">
                                    <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="paid" <?php echo $order['status'] == 'paid' ? 'selected' : ''; ?>>Paid</option>
                                    <option value="canceled" <?php echo $order['status'] == 'canceled' ? 'selected' : ''; ?>>Canceled</option>
                                </select>
                                <button type="submit" class="btn btn-primary mt-2">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
