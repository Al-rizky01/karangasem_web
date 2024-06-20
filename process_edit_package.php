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
    $packageName = $_POST['packageName'];
    $packageDescription = $_POST['packageDescription'];
    $packagePrice = $_POST['packagePrice'];
    $packageImage = $_FILES['packageImage'];

    // Mulai query update
    $sql = "UPDATE package SET name = ?, description = ?, price = ?";

    // Proses file gambar jika ada
    if ($packageImage['size'] > 0) {
        $targetDir = "pack-photo/";
        $targetFile = $targetDir . basename($packageImage["name"]);
        move_uploaded_file($packageImage["tmp_name"], $targetFile);
        $sql .= ", image_path = ?";
    }

    $sql .= " WHERE id = ?";

    // Persiapkan statement
    $stmt = $conn->prepare($sql);

    // Bind parameter sesuai dengan apakah ada file gambar atau tidak
    if ($packageImage['size'] > 0) {
        $stmt->bind_param("ssisi", $packageName, $packageDescription, $packagePrice, $targetFile, $packageId);
    } else {
        $stmt->bind_param("ssii", $packageName, $packageDescription, $packagePrice, $packageId);
    }

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
