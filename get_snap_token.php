<?php
require_once 'midtrans_config.php';

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "karangasem";
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (!isset($_GET['order_id'])) {
    die(json_encode(['error' => 'Order ID is required.']));
}

$order_id = $_GET['order_id'];
$query = "SELECT o.order_id, o.package_id, o.status, p.price, p.name 
          FROM orders o 
          JOIN package p ON o.package_id = p.id 
          WHERE o.order_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    die(json_encode(['error' => 'Order not found.']));
}

// Midtrans transaction details
$transaction_details = array(
    'order_id' => $order['order_id'],
    'gross_amount' => $order['price'], // Package price from the database
);

$item_details = array(
    array(
        'id' => $order['package_id'],
        'price' => $order['price'],
        'quantity' => 1,
        'name' => $order['name'],
    ),
);

$customer_details = array(
    'first_name' => 'John',
    'last_name' => 'Doe',
    'email' => 'johndoe@example.com',
    'phone' => '08123456789',
);

$transaction_data = array(
    'transaction_details' => $transaction_details,
    'item_details' => $item_details,
    'customer_details' => $customer_details,
);

try {
    $snapToken = \Midtrans\Snap::getSnapToken($transaction_data);
    echo json_encode(['token' => $snapToken]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
