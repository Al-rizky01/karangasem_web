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

// Inisialisasi pesan error kosong
$error = '';

// Cek jika form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk memeriksa apakah username ada di database
    $query = "SELECT * FROM user WHERE username=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Memeriksa apakah pengguna ditemukan
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Memeriksa apakah password cocok
        if (password_verify($password, $row['hashed_password'])) {
            // Password cocok, atur session dan arahkan ke halaman dashboard

            $_SESSION['username'] = $username;
            $_SESSION['role'] = $row['role'];
            $_SESSION['image_path'] = $row['image_path'];
            $_SESSION['user_id'] = $row['id']; // Correctly set the user ID
            header("Location: /dashboard");
            exit();
        } else {
            // Password tidak cocok
            $error = "Username atau Password salah!";
        }
    } else {
        // Username tidak ditemukan
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Login</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/feather/feather.css">
  <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
  <style>
    .container-fluid {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
  </style>
</head>

<body>
  <div class="container-fluid">
    <!-- Tampilkan bagian logo dan tombol "Beranda" jika pengguna sudah login -->
    <?php if (isset($_SESSION['username'])) : ?>
    <div class="text-center">
      <div class="brand-logo">
        <img src="logo-karangasem-persegiPanjang-removebg-preview.png" alt="logo">
      </div>
      <div class="mt-4">
        <a href="/dashboard" class="btn btn-primary">Beranda</a>
      </div>
    </div>
    <?php else : ?>
    <!-- Tampilkan form login jika pengguna belum login -->
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo text-center">
                <img src="logo-karangasem-persegiPanjang-removebg-preview.png" alt="logo">
              </div>
              <?php if (!empty($error)): ?>
              <div class="alert alert-danger" role="alert">
                  <?php echo $error; ?>
              </div>
              <?php endif; ?>

              <h4>Hello! let's get started</h4>
              <h6 class="font-weight-light">Sign in to continue.</h6>
              <form class="pt-3" method="post">
                <div class="form-group">
                  <input type="text" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Username" name="username" required>
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="Password" name="password" required>
                </div>
                <div class="mt-3">
                  <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN IN</button>
                </div>
                <div class="my-2 d-flex justify-content-between align-items-center">
                  <div class="form-check">
                    <label class="form-check-label text-muted">
                      <input type="checkbox" class="form-check-input">
                      Keep me signed in
                    </label>
                  </div>
                </div>
                <div class="text-center mt-4 font-weight-light">
                  Don't have an account? <a href="/register" class="text-primary">Create</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>

  <!-- scripts -->
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <script src="js/off-canvas.js"></script>
  <script src="js/hoverable-collapse.js"></script>
  <script src="js/template.js"></script>
  <script src="js/settings.js"></script>
  <script src="js/todolist.js"></script>

</body>

</html>
