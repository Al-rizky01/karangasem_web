<?php
// Koneksi ke database
include 'config.php';
include 'functions.php';
include 'vendor/autoload.php'; // Tambahkan baris ini

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

// Debug log untuk memastikan data pesanan
error_log("Order ID: " . $orderId);
error_log("Order Data: " . print_r($order, true));

// Persiapkan data untuk Midtrans
$params = array(
    'transaction_details' => array(
        'order_id' => $orderId,
        'gross_amount' => $order['total_price'],
    ),
    'customer_details' => array(
        'first_name' => $order['full_name'],
        'phone' => $order['phone_number'],
    ),
);

// Debug log untuk memastikan parameter transaksi
error_log("Transaction Params: " . print_r($params, true));

// Inisialisasi Midtrans
\Midtrans\Config::$serverKey = MIDTRANS_SERVER_KEY;
\Midtrans\Config::$isProduction = (MIDTRANS_ENV == 'production');
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// Buat transaksi
try {
    $snapToken = \Midtrans\Snap::getSnapToken($params);
    error_log("Snap Token: " . $snapToken);
} catch (Exception $e) {
    error_log("Error creating snap token: " . $e->getMessage());
    die("Error creating snap token: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"></script> <!-- Pastikan menggunakan sandbox URL -->
</head>
<body>
    <div class="container my-5">
        <h1>Pembayaran</h1>
        <div class="card">
            <div class="card-body">
                <div id="payment-button"></div>
                <script type="text/javascript">
                    var snapToken = "<?php echo $snapToken; ?>";
                    snap.pay(snapToken, {
                        // Optional
                        onSuccess: function(result) {
                            /* You may add your own implementation here */
                            alert("Pembayaran berhasil!");
                            window.location.href = "thank-you.php?order_id=<?php echo $orderId; ?>";
                        },
                        onPending: function(result) {
                            /* You may add your own implementation here */
                            alert("Pembayaran pending.");
                            window.location.href = "thank-you.php?order_id=<?php echo $orderId; ?>";
                        },
                        onError: function(result) {
                            /* You may add your own implementation here */
                            alert("Pembayaran gagal.");
                            window.location.href = "thank-you.php?order_id=<?php echo $orderId; ?>";
                        },
                        onClose: function() {
                            /* You may add your own implementation here */
                            alert("Anda telah menutup jendela pembayaran.");
                        }
                    });
                </script>
            </div>
        </div>
    </div>
</body>
</html>
