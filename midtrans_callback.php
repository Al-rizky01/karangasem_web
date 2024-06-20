<?php
// Koneksi ke database
$servername = "localhost";
$username = "id22276528_root";
$password = "12_Wafaras";
$database = "id22276528_karangasem";

$conn = new mysqli($servername, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi untuk mengambil data pesanan berdasarkan order_id
function getOrderById($conn, $id) {
    $sql = "SELECT * FROM orders WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Fungsi untuk memperbarui status pembayaran
function updateOrderStatus($conn, $id, $status) {
    $sql = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
}

// Ambil data notifikasi dari Midtrans
$notif = new Midtrans\Notification();

// Ambil data pesanan berdasarkan order_id
$orderId = $notif->order_id;
$order = getOrderById($conn, $orderId);

// Perbarui status pembayaran
if ($notif->transaction_status == 'capture') {
    // Pembayaran berhasil
    updateOrderStatus($conn, $orderId, 'paid');
} else {
    // Pembayaran gagal
    updateOrderStatus($conn, $orderId, 'failed');
}

// Kirim respons ke Midtrans
http_response_code(200);
