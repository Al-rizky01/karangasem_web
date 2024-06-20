<?php


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

// Periksa apakah ada parameter ID dalam URL
if(isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Query untuk mendapatkan detail produk berdasarkan ID
    $query = "SELECT * FROM product WHERE id = $product_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Ambil data produk
        $row = $result->fetch_assoc();
        $product_name = $row['name'];
        $product_description = $row['description'];
        $product_image = $row['image_path'];
    } else {
        // Tampilkan pesan jika produk tidak ditemukan
        $product_name = "Product Not Found";
        $product_description = "This product does not exist.";
        $product_image = "placeholder-image.jpg"; // Ganti dengan path gambar placeholder
    }
} else {
    // Tampilkan pesan jika tidak ada parameter ID
    $product_name = "Product Not Found";
    $product_description = "Please provide a valid product ID.";
    $product_image = "placeholder-image.jpg"; // Ganti dengan path gambar placeholder
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product_name; ?> - Detail Product</title>
    <!-- Tambahkan Bootstrap CSS di sini -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .jumbotron {
            background-image: url('<?php echo $product_image; ?>'); /* Gunakan gambar produk sebagai latar belakang jumbotron */
            background-size: cover; /* Properti untuk memastikan gambar tidak terpotong */
            background-position: center;
            background-repeat: no-repeat;
            color: #fff;
            text-align: center;
            padding: 100px 0;
            height: 50vh;
            margin-bottom: 0; /* Tambahkan margin bawah 0 agar tidak ada spasi di bawah jumbotron */
        }

        .container {
            padding: 20px; /* Tambahkan padding agar konten tidak terlalu dekat dengan tepi */
        }
    </style>
</head>

<body>

    <div class="jumbotron">
        <div class="container">
            <h1><?php echo $product_name; ?></h1>
            <!-- Anda dapat menambahkan deskripsi singkat produk di sini jika perlu -->
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <p class="mt-4"><?php echo $product_description; ?></p>
                <!-- Tambahkan bagian lain dari detail produk di sini jika diperlukan -->
            </div>
        </div>
    </div>

    <!-- Tambahkan Bootstrap JS dan jQuery di sini jika diperlukan -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
