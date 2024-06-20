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

// Ambil data pesanan berdasarkan order_id
$orderId = $_GET['order_id'];
$order = getOrderById($conn, $orderId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terima Kasih</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h1>Terima Kasih</h1>
        <div class="card">
            <div class="card-body">
                <p>Pesanan Anda dengan nomor <?php echo $order['id']; ?> telah berhasil dibuat.</p>
                <p>Status pembayaran: <?php echo ucfirst($order['status']); ?></p>
            </div>
        </div>
    </div>
</body>
</html>
