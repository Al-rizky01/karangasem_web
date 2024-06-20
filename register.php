<?php
// Include file koneksi database
include_once 'koneksi.php';

// Inisialisasi pesan error kosong
$error = '';

// Cek jika form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Pastikan password dan confirm password sama
    if ($password !== $confirm_password) {
        $error = "Password dan Confirm Password tidak cocok!";
    } else {
        // Query untuk memeriksa apakah username sudah digunakan
        $check_query = "SELECT username FROM user WHERE username = ?";

        // Siapkan statement
        $check_stmt = mysqli_prepare($conn, $check_query);

        // Bind parameter
        mysqli_stmt_bind_param($check_stmt, "s", $username);

        // Eksekusi statement
        mysqli_stmt_execute($check_stmt);

        // Ambil hasil
        mysqli_stmt_store_result($check_stmt);

        // Cek jumlah baris yang dikembalikan
        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            // Jika username sudah digunakan, tampilkan pesan kesalahan
            $error = "Username sudah digunakan. Silakan pilih username lain.";
        } else {
            // Jika username belum digunakan, lanjutkan dengan proses registrasi
            // Hash password sebelum menyimpannya ke database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Default path untuk gambar profil
            $default_image_path = "pp/default.jpg";

            // Query untuk menyimpan data pengguna baru ke database
            $query = "INSERT INTO user (username, hashed_password, displayed_password, role, image_path) VALUES (?, ?, ?, 'user', ?)";

            // Siapkan statement
            $stmt = mysqli_prepare($conn, $query);

            // Bind parameter
            mysqli_stmt_bind_param($stmt, "ssss", $username, $hashed_password, $password, $default_image_path);

            // Eksekusi statement
            if (mysqli_stmt_execute($stmt)) {
                // Jika berhasil, arahkan ke halaman login
                header("Location: /login");
                exit();
            } else {
                $error = "Error: " . $query . "<br>" . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Register</title>
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
        <img src="images/logo.svg" alt="logo">
      </div>
      <div class="mt-4">
        <a href="/karangasem/" class="btn btn-primary">Beranda</a>
      </div>
    </div>
    <?php else : ?>
    <!-- Tampilkan form registrasi jika pengguna belum login -->
    <div class="container-fluid page-body-wrapper full-page-wrapper">
                <!-- Modal -->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <h4>Welcome to Desa Wisata!</h4>
        <p>
          Please read these Terms and Conditions carefully before using our website.
        </p>
        <h5>1. Content</h5>
        <p>
          All content available on this website, such as text, images, and videos, is owned by Desa Wisata unless stated otherwise. You are not allowed to use this content without written permission from us.
        </p>
        <h5>2. Information</h5>
        <p>
          We strive to provide accurate information, but do not guarantee the accuracy or completeness of the information provided on our website. You are responsible for verifying the accuracy of the information before taking action based on it.
        </p>
        <h5>3. Links to External Sites</h5>
        <p>
          Our website may contain links to external websites. We are not responsible for the content or privacy practices of these external websites.
        </p>
        <h5>4. Liability</h5>
        <p>
          Desa Wisata is not liable for any loss or damage arising from the use of this website.
        </p>
        <h5>5. Changes to Terms and Conditions</h5>
        <p>
          We reserve the right to change or update these Terms and Conditions from time to time without prior notice.
        </p>
        <h5>6. Governing Law</h5>
        <p>
          These Terms and Conditions shall be governed by and construed in accordance with the laws of Desa Wisata.
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo text-center">
                <img src="images/logo.svg" alt="logo">
              </div>
              <?php if (!empty($error)): ?>
              <div class="alert alert-danger" role="alert">
                  <?php echo $error; ?>
              </div>
              <?php endif; ?>

              <h6 class="font-weight-light">Signing up is easy. It only takes a few steps</h6>
              <form class="pt-3" method="post" action="register.php">
                <div class="form-group">
                  <input type="text" class="form-control form-control-lg" name="username"id="username" placeholder="Username">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" name="password" id="password" placeholder="Password">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" name="confirm_password" id="confirm_password" placeholder="Confirm Password">
                </div>
                <div class="mb-4">
                  <div class="form-check">
                    <label class="control control--checkbox">
                      <input type="checkbox" required />
                      <span class="control__indicator"></span>
                      I agree to the <a href="#" data-toggle="modal" data-target="#exampleModalLong">Terms and Conditions</a>
                    </label>
                  </div>
                </div>
                <div class="mt-3">
                  <input type="submit" value="SIGN UP" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                </div>
                <div class="text-center mt-4 font-weight-light">
                  Already have an account? <a href="/karangasem/login" class="text-primary">Login</a>
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
