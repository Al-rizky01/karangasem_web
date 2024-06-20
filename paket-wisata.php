<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// Fungsi untuk mendapatkan image_path berdasarkan username
function getImagePathByUsername($conn, $username) {
    $sql = "SELECT image_path FROM user WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['image_path'];
    } else {
        return ''; // Mengembalikan string kosong jika tidak ada hasil
    }
}

// Fungsi untuk mendapatkan image_path berdasarkan ID
function getImagePathById($conn, $id) {
    $sql = "SELECT image_path FROM user WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['image_path'];
    } else {
        return ''; // Mengembalikan string kosong jika tidak ada hasil
    }
}

// Cek apakah pengguna telah login dan informasi pengguna tersedia di session
if (isset($_SESSION['username'])) {
    $activeUsername = $_SESSION['username'];
    // Mendapatkan image_path berdasarkan username aktif
    $activeUserImagePath = getImagePathByUsername($conn, $activeUsername);
} elseif (isset($_SESSION['user_id'])) {
    $activeUserId = $_SESSION['user_id'];
    // Mendapatkan image_path berdasarkan ID pengguna aktif
    $activeUserImagePath = getImagePathById($conn, $activeUserId);
} else {
    // Jika pengguna tidak aktif, atur image_path ke default atau tampilkan pesan kesalahan
    $activeUserImagePath = 'default_profile_image.jpg'; // Anda dapat menentukan default image_path di sini
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Paket Wisata - Karangasem</title>
    <link rel="stylesheet" href="vendors/feather/feather.css">
    <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="css/vertical-layout-light/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsVJnYLJ/xz1x4o9uZ2rs7H6+nGcfj5x2rwO8Pm6Hx" crossorigin="anonymous">
    <link rel="shortcut icon" href="images/favicon.png" />

    <style>
        .card-group {
            margin-top: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body>
<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
            <a class="navbar-brand brand-logo mr-5" href="/"><img src="logo-karangasem-persegiPanjang.png" class="mr-2" alt="logo"/></a>
            <a class="navbar-brand brand-logo-mini" href="/"><img src="logo2-karangasem-persegi.png" alt="logo"/></a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                <span class="icon-menu"></span>
            </button>
            <ul class="navbar-nav mr-lg-2">
                <li class="nav-item nav-search d-none d-lg-block">
                    <div class="input-group">
                        <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                            <span class="input-group-text" id="search">
                                <i class="icon-search"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" id="navbar-search-input" placeholder="Search now" aria-label="search" aria-describedby="search">
                    </div>
                </li>
                <span id="clock" class="navbar-text mr-3"></span> <!-- Tambahkan ini untuk menampilkan jam -->
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                    <span class="icon-menu"></span>
                </button>
            </ul>
            <ul class="navbar-nav navbar-nav-right mr-1">
                <li class="nav-item nav-profile dropdown">
                    <?php if(isset($_SESSION['username'])): ?>
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                            <!-- Menampilkan foto profil -->
                            <img src="<?php echo $activeUserImagePath; ?>" alt="profile"/>
                        </a>
                    <?php else: ?>
                        <a class="nav-link" href="/login">
                            <i class="ti-power-off text-primary"></i>
                            Login
                        </a>
                    <?php endif; ?>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                        <a class="dropdown-item" href="/settings">
                            <i class="ti-settings text-primary"></i>
                            Settings
                        </a>
                        <a class="dropdown-item" href="logout.php">
                            <i class="ti-power-off text-primary"></i>
                            Logout
                        </a>
                    </div>
                </li>
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                <span class="icon-menu"></span>
            </button>
        </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_settings-panel.html -->
        <div class="theme-setting-wrapper">
            <div id="settings-trigger"><i class="ti-settings"></i></div>
            <div id="theme-settings" class="settings-panel">
                <i class="settings-close ti-close"></i>
                <p class="settings-heading">SIDEBAR SKINS</p>
                <div class="sidebar-bg-options selected" id="sidebar-light-theme"><div class="img-ss rounded-circle bg-light border mr-3"></div>Light</div>
                <div class="sidebar-bg-options" id="sidebar-dark-theme"><div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark</div>
                <p class="settings-heading mt-2">HEADER SKINS</p>
                <div class="color-tiles mx-0 px-4">
                    <div class="tiles success" onclick="changeColor('success')"></div>
                    <div class="tiles warning" onclick="changeColor('warning')"></div>
                    <div class="tiles danger" onclick="changeColor('danger')"></div>
                    <div class="tiles info" onclick="changeColor('info')"></div>
                    <div class="tiles dark" onclick="changeColor('dark')"></div>
                    <div class="tiles default" onclick="changeColor('default')"></div>
                </div>
            </div>
        </div>
        <!-- partial -->
        <!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="/dashboard">
                        <i class="icon-grid menu-icon"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/package">
                        <i class="ti-package menu-icon"></i>
                        <span class="menu-title">Paket Wisata</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/product">
                        <i class="ti-shopping-cart menu-icon"></i>
                        <span class="menu-title">Produk Bambu</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/homestay">
                        <i class="ti-home menu-icon"></i>
                        <span class="menu-title">Homestay</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/destination">
                        <i class="ti-map menu-icon"></i>
                        <span class="menu-title">Destination</span>
                    </a>
                </li>
                <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/transactions">
                            <i class="ti-receipt menu-icon"></i>
                            <span class="menu-title">Daftar Transaksi</span>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/history">
                            <i class="ti-receipt menu-icon"></i>
                            <span class="menu-title">History Transaksi</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>

<div class="content-wrapper">
    <div class="container-fluid">
    <?php
if (isset($_GET['success']) && $_GET['success'] == 'true') {
    ?>
    <div class="alert alert-success" role="alert">
        New package added successfully!
    </div>
    <?php
} elseif (isset($_GET['error']) && $_GET['error'] == 'true') {
    ?>
    <div class="alert alert-danger" role="alert">
        There was an error adding the package. Please try again.
    </div>
    <?php
}
?>
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPackageModal">Add Package</button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="row card-group">
    <?php
    $query = "SELECT * FROM package";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            ?>
            <div class="col-md-4">
<div class="card">
    <img src="<?php echo htmlspecialchars($row['image_path']); ?>" class="card-img-top" alt="...">
    <div class="card-body">
        <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
        <p class="card-text"><?php echo htmlspecialchars(implode(' ', array_slice(explode(' ', $row['description']), 0, 15))); ?>...</p>
        <p class="card-text">Price: Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></p>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="edit_package.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-primary">Edit</a>
            <form action="delete_package.php" method="post" style="display:inline;">
                <input type="hidden" name="packageId" value="<?php echo htmlspecialchars($row['id']); ?>">
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        <?php else: ?>
            <a href="order.php?package_id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-primary">Order Now</a>
        <?php endif; ?>
    </div>
</div>

</div>
            <?php
        }
    } else {
        echo "<p>No packages available.</p>";
    }
    ?>
</div>

    </div>
</div>

<!-- Modal add -->
<div class="modal fade" id="addPackageModal" tabindex="-1" role="dialog" aria-labelledby="addPackageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="process_add_package.php" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPackageModalLabel">Add Package</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form tambah paket di sini -->
                    <div class="form-group">
                        <label for="packageName">Package Name</label>
                        <input type="text" class="form-control" id="packageName" name="packageName" required>
                    </div>
                    <div class="form-group">
                        <label for="packageDescription">Description</label>
                        <textarea class="form-control" id="packageDescription" name="packageDescription" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="packagePrice">Price (Rp)</label>
                        <input type="text" class="form-control" id="packagePrice" name="packagePrice" required>
                    </div>
                    <div class="form-group">
                        <label for="packageImage">Upload Image</label>
                        <input type="file" class="form-control" id="packageImage" name="packageImage" required>
                    </div>
                    <!-- Tambahkan input lainnya sesuai kebutuhan -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Edit -->

</div>

<script src="vendors/js/vendor.bundle.base.js"></script>
<script src="js/off-canvas.js"></script>
<script src="js/hoverable-collapse.js"></script>
<script src="js/template.js"></script>
<script src="js/settings.js"></script>
<script src="js/todolist.js"></script>
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-Hv3C0wlzcXHgY9+53vA4Dheq+LldWSwWHld8lFB2y3nUVBH98zdYLjLpcXJ7KPCnB" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</body>
</html>
