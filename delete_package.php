<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Informasi koneksi database
$servername = "localhost"; // Ganti dengan nama host Anda jika perlu
$username = "id22276528_root"; // Ganti dengan nama pengguna MySQL Anda
$password = "12_Wafaras"; // Ganti dengan kata sandi MySQL Anda
$database = "id22276528_karangasem"; // Ganti dengan nama basis data Anda

// Membuat koneksi ke basis data
$conn = new mysqli($servername, $username, $password, $database);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $packageId = $_POST['packageId'];

    $sql = "DELETE FROM package WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $packageId);

    if ($stmt->execute()) {
        header("Location: /package");
    } else {
        header("Location: /package");
    }
} else {
    header("Location: /package");
}

$conn->close();
?>
