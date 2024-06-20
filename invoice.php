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

// Fungsi untuk mengambil data paket wisata berdasarkan ID
function getPackageById($conn, $id) {
    $sql = "SELECT * FROM package WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Ambil data pesanan berdasarkan order_id
$orderId = $_GET['order_id'];
$order = getOrderById($conn, $orderId);

// Ambil data paket wisata berdasarkan ID
$package = getPackageById($conn, $order['package_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .invoice-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }
        .invoice-header {
            margin-bottom: 30px;
        }
        .invoice-header h1 {
            margin: 0;
        }
        .invoice-details {
            margin-bottom: 20px;
        }
        .invoice-details p {
            margin: 0;
        }
        .invoice-footer {
            text-align: center;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <div class="invoice-container">
            <div class="invoice-header">
                <h1>Invoice</h1>
                <p>Order ID: <?php echo $order['id']; ?></p>
                <p>Date: <?php echo date('d-m-Y'); ?></p>
            </div>
            <div class="invoice-details">
                <h5>Detail Pesanan</h5>
                <p>Paket Wisata: <?php echo $package['name']; ?></p>
                <p>Nama Lengkap: <?php echo $order['full_name']; ?></p>
                <p>Nomor Telepon: <?php echo $order['phone_number']; ?></p>
                <p>Jumlah Orang: <?php echo $order['num_people']; ?></p>
                <p>Total Harga: Rp <?php echo number_format($order['total_price'], 0, ',', '.'); ?></p>
            </div>
            <div class="invoice-footer">
                <a href="payment.php?order_id=<?php echo $order['id']; ?>" class="btn btn-primary">Konfirmasi Pembayaran</a>
            </div>
        </div>
    </div>
</body>
</html>
