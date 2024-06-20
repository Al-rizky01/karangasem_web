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
function getImagePathByUsername($username) {
  global $conn;
  $sql = "SELECT image_path FROM user WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      return $row['image_path'];
  } else {
      return ''; // Mengembalikan string kosong jika tidak ada hasil
  }
}

// Fungsi untuk mendapatkan image_path berdasarkan ID
function getImagePathById($id) {
  global $conn;
  $sql = "SELECT image_path FROM user WHERE id = $id";
  $result = $conn->query($sql);

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
  $activeUserImagePath = getImagePathByUsername($activeUsername);
} elseif (isset($_SESSION['user_id'])) {
  $activeUserId = $_SESSION['user_id'];
  // Mendapatkan image_path berdasarkan ID pengguna aktif
  $activeUserImagePath = getImagePathById($activeUserId);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <style>
        .slick-slider .card {
            width: 300px; /* Atur lebar kartu sesuai kebutuhan */
            margin: 0 15px; /* Jarak antara kartu */
        }

        .slick-slider .card .card-img-top {
            height: 200px; /* Atur tinggi gambar sesuai kebutuhan */
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
            <a class="nav-link" href="/">
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
    </button>
        </ul>
      </nav>

      <div class="content-wrapper">
      <div class="container-fluid">
    <div class="row">
        <?php
        $query = "SELECT * FROM product";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="col-md-4">
                    <div class="card mb-5">
                        <a href="product_detail.php?id=<?php echo $row['id']; ?>">
                            <img src="<?php echo $row['image_path']; ?>" class="card-img-top" alt="Product Image">
                        </a>
                    </div>
                </div>
            <?php
                }
            } else {
                echo "No packages available.";
            }
            ?>
        </div>
    </div>

        </div>
    </div>

    <script src="vendors/js/vendor.bundle.base.js"></script>
    <script src="js/off-canvas.js"></script>
    <script src="js/hoverable-collapse.js"></script>
    <script src="js/template.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/todolist.js"></script>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-Hv3C0wlzcXHgY9+53vA4Dheq+LldWSwWHld8lFB2y3nUVBH98zYLjLpcXJ7KPCnB" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5+9TI3Gdv4+1Rx4Xqe+3i1bY/iRS3VpzrEUEb4IH" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.slick-slider').slick({
                dots: true,
                infinite: true,
                speed: 300,
                slidesToShow: 2,
                slidesToScroll: 1,
                responsive: [{
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }]
            });
        });
    </script>
</body>

</html>
