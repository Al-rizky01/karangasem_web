<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$database = "karangasem";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $package_id = $_POST['package_id'];
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $participants = $_POST['participants'];

    $sql = "INSERT INTO orders (package_id, full_name, phone, email, participants) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssi", $package_id, $full_name, $phone, $email, $participants);
    $stmt->execute();

    $order_id = $stmt->insert_id;

    header("Location: order-detail.php?order_id=" . $order_id);
    exit();
} else {
    $package_id = $_GET['package_id'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Paket</title>
</head>
<body>
    <form action="order-paket.php" method="POST">
        <input type="hidden" name="package_id" value="<?php echo $package_id; ?>">
        <label for="full_name">Nama Lengkap:</label>
        <input type="text" id="full_name" name="full_name" required>
        <br>
        <label for="phone">Nomor HP:</label>
        <input type="text" id="phone" name="phone" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="participants">Jumlah Peserta:</label>
        <input type="number" id="participants" name="participants" required>
        <br>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
