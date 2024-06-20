<?php
session_start();
include 'koneksi.php';

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: /karangasem/login");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $num_people = $_POST['num_people'];
    $package_id = $_POST['package'];

    // Proses penyimpanan data pemesanan ke database
    $query = "INSERT INTO orders (fullname, phone, num_people, package_id, user_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssiii", $fullname, $phone, $num_people, $package_id, $_SESSION['user_id']);

    if ($stmt->execute()) {
        // Mendapatkan ID dari pesanan yang baru saja disimpan
        $order_id = $stmt->insert_id;
        // Redirect ke halaman detail pesanan
        header("Location: /karangasem/package/order/detail?order_id=$order_id");
        exit();
    } else {
        echo "Terjadi kesalahan saat memproses pemesanan: " . $stmt->error;
    }
}
?>
