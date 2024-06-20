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

// Fungsi untuk mengambil semua paket wisata
function getAllPackages($conn) {
    $sql = "SELECT * FROM package";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fungsi untuk mengambil paket wisata berdasarkan ID
function getPackageById($conn, $id) {
    $sql = "SELECT * FROM package WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Fungsi untuk menyimpan data pesanan
function saveOrder($conn, $userId, $fullName, $phoneNumber, $numPeople, $packageId, $totalPrice) {
    $sql = "INSERT INTO orders (user_id, full_name, phone_number, num_people, package_id, total_price, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isiiid", $userId, $fullName, $phoneNumber, $numPeople, $packageId, $totalPrice);
    $stmt->execute();
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
function calculateTotalPrice($price, $numPeople) {
    return $price * $numPeople;
}
