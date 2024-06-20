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

$packageId = $_GET['id'];
$sql = "SELECT * FROM package WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $packageId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $package = $result->fetch_assoc();
} else {
    die("Paket tidak ditemukan.");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Package</title>
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="css/vertical-layout-light/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsVJnYLJ/xz1x4o9uZ2rs7H6+nGcfj5x2rwO8Pm6Hx" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <h2>Edit Package</h2>
    <form action="process_edit_package.php" method="post" enctype="multipart/form-data">
        <input type="hidden" id="editPackageId" name="packageId" value="<?php echo htmlspecialchars($package['id']); ?>">
        <div class="form-group">
            <label for="editPackageName">Package Name</label>
            <input type="text" class="form-control" id="editPackageName" name="packageName" value="<?php echo htmlspecialchars($package['name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="editPackageDescription">Description</label>
            <textarea class="form-control" id="editPackageDescription" name="packageDescription" required><?php echo htmlspecialchars($package['description']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="editPackagePrice">Price (Rp)</label>
            <input type="text" class="form-control" id="editPackagePrice" name="packagePrice" value="<?php echo htmlspecialchars($package['price']); ?>" required>
        </div>
        <div class="form-group">
            <label for="editPackageImage">Upload Image</label>
            <input type="file" class="form-control" id="editPackageImage" name="packageImage">
        </div>
        <button type="submit" class="btn btn-primary">Save changes</button>
    </form>
</div>

<script src="vendors/js/vendor.bundle.base.js"></script>
<script src="js/off-canvas.js"></script>
<script src="js/hoverable-collapse.js"></script>
<script src="js/template.js"></script>
<script src="js/settings.js"></script>
<script src="js/todolist.js"></script>
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-Hv3C0wlzcXHgY9+53vA4Dheq+LldWSwWHld8lFB2y3nUVBH98zdYLjLpcXJ7KPCnB" crossorigin="anonymous"></script>
</body>
</html>
