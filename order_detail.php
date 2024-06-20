<?php
session_start();

// Memasukkan file konfigurasi dan fungsi database
include 'koneksi.php';

// Fungsi untuk mendapatkan detail paket wisata berdasarkan ID
function getPackageDetails($conn, $package_id) {
    $stmt = $conn->prepare("SELECT * FROM package WHERE id = ?");
    $stmt->bind_param("i", $package_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: /karangasem/login");
    exit();
}

// Ambil informasi pesanan dari URL
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Query untuk mendapatkan informasi pesanan berdasarkan ID
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // Redirect jika pesanan tidak ditemukan
        header("Location: /karangasem/");
        exit();
    }

    $order = $result->fetch_assoc();

    // Mendapatkan detail paket wisata berdasarkan ID yang ada dalam pesanan
    $package = getPackageDetails($conn, $order['package_id']);
} else {
    // Redirect jika tidak ada parameter order_id
    header("Location: /karangasem/");
    exit();
}

// Fungsi untuk mengubah status pesanan
function updateOrderStatus($conn, $order_id, $status) {
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();
}

// Proses pembayaran dengan Midtrans
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['payment_status'])) {
    $payment_status = $_POST['payment_status'];

    if ($payment_status === 'success') {
        // Jika pembayaran sukses, update status pesanan menjadi 'paid'
        updateOrderStatus($conn, $order_id, 'paid');
        // Tambahkan tindakan apa yang ingin dilakukan setelah pembayaran sukses
    } elseif ($payment_status === 'pending') {
        // Jika pembayaran pending, update status pesanan menjadi 'pending'
        updateOrderStatus($conn, $order_id, 'pending');
        // Tambahkan tindakan apa yang ingin dilakukan setelah pembayaran pending
    } else {
        // Jika pembayaran gagal atau terjadi kesalahan, bisa tambahkan penanganan sesuai kebutuhan
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Head section -->
</head>
<body>

<!-- Navbar -->

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h2>Detail Pesanan</h2>
            <p><strong>Nama Paket Wisata:</strong> <?php echo $package['name']; ?></p>
            <p><strong>Total Orang:</strong> <?php echo $order['total_persons']; ?></p>
            <p><strong>Total Harga:</strong> Rp <?php echo number_format($order['total_price'], 0, ',', '.'); ?></p>
            <p><strong>Status Pembayaran:</strong> <?php echo ucfirst($order['status']); ?></p>

            <!-- Tampilkan form pembayaran jika status pesanan adalah 'pending' -->
            <?php if ($order['status'] === 'pending'): ?>
                <h3>Proses Pembayaran</h3>
                <form method="post" action="midtrans_payment.php">
                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                    <button type="submit" class="btn btn-primary">Bayar Sekarang</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Footer -->

</body>
</html>
