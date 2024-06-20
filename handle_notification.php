<?php
require_once 'midtrans_config.php';

$notif = new \Midtrans\Notification();

$order_id = $notif->order_id;
$transaction_status = $notif->transaction_status;

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "karangasem";
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($transaction_status == 'capture' || $transaction_status == 'settlement') {
    // Update your database to mark the order as paid
    $conn->query("UPDATE orders SET status = 'paid' WHERE order_id = '$order_id'");
} else if ($transaction_status == 'pending') {
    // Update the order status to pending
    $conn->query("UPDATE orders SET status = 'pending' WHERE order_id = '$order_id'");
} else {
    // Update the order status to failed or canceled
    $conn->query("UPDATE orders SET status = 'failed' WHERE order_id = '$order_id'");
}
?>
