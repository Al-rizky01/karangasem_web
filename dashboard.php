<?php
include_once 'koneksi.php'; // Sertakan file koneksi database

// Fungsi untuk mengambil jumlah data dari tabel
function countData($table) {
    global $conn;
    $sql = "SELECT COUNT(*) AS total FROM $table";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}

// Fungsi untuk mendapatkan waktu terakhir data diupdate
// Fungsi untuk mendapatkan jumlah data yang ditambahkan dalam satu bulan terakhir
function getDataAddedLastMonth($table) {
    global $conn;
    // Hitung tanggal satu bulan yang lalu dari sekarang
    $lastMonth = date('Y-m-d', strtotime('-1 month'));

    // Query untuk menghitung jumlah data yang ditambahkan dalam satu bulan terakhir
    $sql = "SELECT COUNT(*) AS total FROM $table WHERE created_at >= '$lastMonth'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total'];
    } else {
        return 0; // Jika tidak ada data yang ditambahkan dalam satu bulan terakhir
    }
}



// Fungsi untuk mendapatkan data cuaca dari OpenWeatherMap API
function getWeatherData($apiKey, $location) {
    $url = "http://api.openweathermap.org/data/2.5/weather?q=" . urlencode($location) . "&appid=" . $apiKey;
    $response = file_get_contents($url);
    return json_decode($response, true);
}

// Menggunakan API key yang diberikan
$apiKey = "e8247c4c50d32ad746432b23b9e58536"; // Ganti dengan API key yang Anda miliki
$location = "Karangasem, Bantul"; // Lokasi yang ingin Anda dapatkan cuacanya

// Mendapatkan data cuaca
$weatherData = getWeatherData($apiKey, $location);

// Menggunakan fungsi getDataAddedLastMonth untuk mendapatkan jumlah data yang ditambahkan dalam satu bulan terakhir
$totalDataAddedLastMonth = getDataAddedLastMonth('package');

// Menggunakan fungsi countData untuk mengambil jumlah data dari tabel package
$totalPackage = countData('package');
// Menggunakan fungsi countData untuk mengambil jumlah data dari tabel product
$totalProduct = countData('product');
$totalHomestay = countData('homestay');
$totalDestination = countData('destination');

// Fungsi untuk mendapatkan image_path berdasarkan username
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
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Karangasem</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/feather/feather.css">
  <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" type="text/css" href="js/select.dataTables.min.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="logo1-karangasem-perseginobg.png" />
  <style>
    #clock {
  font-size: 14px; /* Ukuran font jam */
  color: #333; /* Warna teks jam */
  margin-right: 10px; /* Jarak dari elemen lain di navbar */
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
          <li class="nav-item active">
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
            <a class="nav-link" href="/homestay">
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
      <!-- partial -->
      <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-md-12 grid-margin">
                            <div class="row">
                            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                                <?php if(isset($_SESSION['username'])): ?>
                                    <h3 class="font-weight-bold">Welcome, <?php echo $_SESSION['username']; ?></h3>
                                <?php else: ?>
                                    <h3 class="font-weight-bold">Welcome, Guest</h3>
                                <?php endif; ?>
                            </div>

                                <div class="col-12 col-xl-4">
                                    <!-- Dropdown content here -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card tale-bg">
                                <div class="card-people mt-auto">
                                  
                                    <img src="images/dashboard/people.svg" alt="people">
                                    <div class="weather-info">
                                        <div class="d-flex">
                                            <div>
                                                <h2 class="mb-0 font-weight-normal"><i class="fa-solid fa-temperature-half"></i></i><?php echo round($weatherData['main']['temp'] - 273.15) ?><sup>C</sup></h2>
                                            </div>
                                            <div class="ml-2">
                                                <h4 class="location font-weight-normal"><?php echo $location ?></h4>
                                                <h6 class="font-weight-normal"><?php echo $weatherData['weather'][0]['description'] ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
            <div class="col-md-6 grid-margin transparent">
              <div class="row">
                <div class="col-md-6 mb-4 stretch-card transparent">
                  <div class="card card-tale">
                    <div class="card-body">
                      <h3 class="mb-4">Total Paket</h3>
                      <p class="fs-30 mb-2"><?php echo $totalPackage; ?></p>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mb-4 stretch-card transparent">
                  <div class="card card-dark-blue">
                    <div class="card-body">
                      <h3 class="mb-4">Total Produk</h3>
                      <p class="fs-30 mb-2"><?php echo $totalProduct; ?></p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
                  <div class="card card-light-blue">
                    <div class="card-body">
                    <h3 class="mb-4">Total Homestay</h3>
                      <p class="fs-30 mb-2"><?php echo $totalHomestay; ?></p>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 stretch-card transparent">
                  <div class="card card-light-danger">
                    <div class="card-body">
                    <h3 class="mb-4">Total Destinasi</h3>
                      <p class="fs-30 mb-2"><?php echo $totalDestination; ?></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        <!-- content-wrapper ends -->
  <!-- plugins:js -->
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="vendors/chart.js/Chart.min.js"></script>
  <script src="vendors/datatables.net/jquery.dataTables.js"></script>
  <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
  <script src="js/dataTables.select.min.js"></script>

  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="js/off-canvas.js"></script>
  <script src="js/hoverable-collapse.js"></script>
  <script src="js/template.js"></script>
  <script src="js/settings.js"></script>
  <script src="js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="js/dashboard.js"></script>
  <script src="js/Chart.roundedBarCharts.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
  var settingsButton = document.querySelector('[data-toggle="theme-settings"]');
  var settingsPanel = document.getElementById('theme-settings');

  settingsButton.addEventListener('click', function() {
    settingsPanel.classList.toggle('active');
  });

  // Tutup panel saat user klik di luar panel
  document.addEventListener('click', function(event) {
    if (!settingsButton.contains(event.target) && !settingsPanel.contains(event.target)) {
      settingsPanel.classList.remove('active');
    }
  });
});

document.addEventListener('DOMContentLoaded', function() {
  var sidebar = document.getElementById('sidebar');
  var sidebarToggleButton = document.querySelector('.navbar-toggler[data-toggle="offcanvas"]');

  sidebarToggleButton.addEventListener('click', function() {
    sidebar.classList.toggle('closed');
  });
});

// Fungsi untuk mengambil waktu dan menampilkannya
function displayTime() {
        var now = new Date();
        var hours = now.getHours();
        var minutes = now.getMinutes();
        var seconds = now.getSeconds();

        // Format waktu ke dalam format hh:mm:ss
        var timeString = pad(hours) + ":" + pad(minutes) + ":" + pad(seconds);

        // Tampilkan waktu di elemen dengan id "clock"
        document.getElementById('clock').textContent = timeString;
    }

    // Fungsi untuk menambahkan nol di depan angka jika angka kurang dari 10
    function pad(number) {
        if (number < 10) {
            return "0" + number;
        }
        return number;
    }

    // Panggil fungsi displayTime() setiap detik
    setInterval(displayTime, 1000);

    // Panggil fungsi displayTime() sekali saat halaman dimuat
    displayTime();

  </script>
</body>
</html>
