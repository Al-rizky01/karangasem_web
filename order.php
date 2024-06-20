<?php
// Mulai sesi
session_start();

// Koneksi ke database
include 'config.php';
include 'functions.php';

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

// Cek jika form telah dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form dan sanitasi input
    $fullName = $conn->real_escape_string($_POST['fullName']);
    $phoneNumber = $conn->real_escape_string($_POST['phoneNumber']);
    $numPeople = intval($_POST['numPeople']);
    $packageId = intval($_POST['packageId']);
    $userId = $_SESSION['user_id']; // Pastikan pengguna sudah login dan user_id tersimpan di session

    // Validasi input
    if (empty($fullName) || empty($phoneNumber) || $numPeople < 1 || empty($packageId)) {
        die("Data tidak valid.");
    }

    // Ambil data paket wisata berdasarkan ID
    $package = getPackageById($conn, $packageId);
    if (!$package) {
        die("Paket wisata tidak ditemukan.");
    }

    // Hitung total harga
    $totalPrice = calculateTotalPrice($package['price'], $numPeople);

    // Simpan data pesanan ke tabel orders
    saveOrder($conn, $userId, $fullName, $phoneNumber, $numPeople, $packageId, $totalPrice);

    // Redirect ke halaman invoice
    header('Location: invoice.php?order_id=' . $conn->insert_id);
    exit;
}

// Ambil data paket wisata
$packages = getAllPackages($conn);
$selectedPackageId = isset($_GET['package_id']) ? intval($_GET['package_id']) : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Paket Wisata</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: bold;
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
        <h1 class="text-center mb-4">Order Paket Wisata</h1>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="fullName" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="fullName" name="fullName" required>
            </div>
            <div class="mb-3">
                <label for="phoneNumber" class="form-label">Nomor Telepon</label>
                <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" required>
            </div>
            <div class="mb-3">
                <label for="numPeople" class="form-label">Jumlah Orang</label>
                <input type="number" class="form-control" id="numPeople" name="numPeople" min="1" required>
            </div>
            <div class="mb-3">
                <label for="package" class="form-label">Pilih Paket Wisata</label>
                <select class="form-control" id="package" name="packageId" required>
                    <option value="">Pilih paket wisata</option>
                    <?php foreach ($packages as $package): ?>
                        <option value="<?php echo $package['id']; ?>" <?php echo $selectedPackageId == $package['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($package['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Lanjutkan Pembayaran</button>
        </form>
    </div>
</body>
</html>

