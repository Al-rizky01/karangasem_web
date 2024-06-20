<?php
// Include file koneksi database
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


$username = $_SESSION['username'];

$sql = "SELECT * FROM user WHERE username = '$username'";
$result = $conn->query($sql);

$userData = [];

if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
}

// Fungsi untuk mengupdate kata sandi pengguna
function updatePassword($conn, $username, $newPassword) {
    // Hash new password sebelum disimpan
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Query untuk mengupdate hashed_password dan displayed_password
    $sql = "UPDATE user SET hashed_password = '$hashedPassword', displayed_password = '$newPassword' WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result) {
        return true;
    } else {
        return false;
    }
}


// Memeriksa apakah form ubah kata sandi telah disubmit
if (isset($_POST['change_password'])) {
    // Memeriksa apakah fungsi updatePassword tersedia
    if (function_exists('updatePassword')) {
        $username = $_SESSION['username'];

        // Memeriksa apakah pengguna ada dalam database
        $sql = "SELECT * FROM user WHERE username = '$username'";
        $result = $conn->query($sql);

        $userData = [];

        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
        }

        // Mengambil data dari form
        $oldPassword = $_POST['old_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        // Memeriksa apakah kata sandi lama benar
        if (password_verify($oldPassword, $userData['hashed_password'])) {
            // Memeriksa apakah kata sandi baru dan konfirmasi kata sandi sama
            if ($newPassword === $confirmPassword) {
                // Memanggil fungsi untuk mengupdate kata sandi
                if (updatePassword($conn, $username, $newPassword)) {
                    // Jika berhasil, tampilkan pesan sukses
                    $passwordChangeSuccess = true;
                } else {
                    // Jika gagal, tampilkan pesan error
                    $passwordChangeError = true;
                }
            } else {
                // Jika konfirmasi kata sandi tidak sesuai, tampilkan pesan error
                $passwordMismatchError = true;
            }
        } else {
            // Jika kata sandi lama tidak benar, tampilkan pesan error
            $oldPasswordError = true;
        }
    }
}
if (isset($_POST['change_photo'])) {
    $targetDir = "pp/"; // Direktori tempat menyimpan foto profil
    $targetFile = $targetDir . basename($_FILES["profile_photo"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));

    // Memeriksa apakah file gambar valid
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["profile_photo"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Memeriksa ukuran file
    if ($_FILES["profile_photo"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Memeriksa format file
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Memeriksa apakah upload berhasil
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $targetFile)) {
            echo "The file ". basename( $_FILES["profile_photo"]["name"]). " has been uploaded.";

            // Update path foto profil di tabel user
            $newImagePath = $targetDir . basename($_FILES["profile_photo"]["name"]);
            $username = $_SESSION['username'];
            $updateImageQuery = "UPDATE user SET image_path = '$newImagePath' WHERE username = '$username'";
            $conn->query($updateImageQuery);
        } else {
            echo "Sorry, there was an error uploading your file.";
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
        /* Custom CSS */
        .profile-image-container {
      position: relative;
      display: inline-block;
      margin-bottom: 20px;
       height: 300px;
    width: 300px;
    border-radius: 50%;
    }

    .change-photo-btn {
      position: absolute;
      bottom: 10px;
      left: 50%;
      transform: translateX(-50%);
      padding: 5px 10px;
      background-color: rgba(0, 0, 0, 0.7);
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .profile-image-container:hover .change-photo-btn {
      opacity: 1;
    }

    .edit-icon {
      margin-left: 5px;
      cursor: pointer;
      color: #007bff;
    }

.img-thumbnail {
    padding: 0.25rem;
    background-color: #fff;
    border: 1px solid #dee2e6;
    border-radius: 50%;
    max-width: 100%;
    height: auto;
}
  </style>
</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href="index.html"><img src="logo-karangasem-persegiPanjang.png" class="mr-2" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="index.html"><img src="logo2-karangasem-persegi.png" alt="logo"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="icon-menu"></span>
        </button>
        <ul class="navbar-nav navbar-nav-right mr-1">
          <li class="nav-item nav-profile dropdown">
          <?php if(isset($_SESSION['username'])): ?>
            
              <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                <!-- Menampilkan foto profil -->
                <img src="<?php echo $_SESSION['image_path']; ?>" alt="profile"/>
            </a>
          <?php else: ?>
              <a class="nav-link" href="/karangasem/login">
                  <i class="ti-power-off text-primary"></i>
                  Login
              </a>
          <?php endif; ?>

            <div class="dropdown-menu dropdown-menu-right navbar-dropdown active" aria-labelledby="profileDropdown">
              <a class="dropdown-item" href="/karangasem/settings">
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
            <a class="nav-link" href="/karangasem/">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/karangasem/package">
              <i class="ti-package menu-icon"></i>
              <span class="menu-title">Paket Wisata</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/karangasem/product">
              <i class="ti-shopping-cart menu-icon"></i>
              <span class="menu-title">Produk Bambu</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/karangasem/homestay">
              <i class="ti-home menu-icon"></i>
              <span class="menu-title">Homestay</span>
            </a>
          </li>
          <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
        <li class="nav-item">
            <a class="nav-link" href="/karangasem/transactions">
                <i class="ti-receipt menu-icon"></i>
                <span class="menu-title">Daftar Transaksi</span>
            </a>
        </li>
        <?php else: ?>
        <li class="nav-item">
            <a class="nav-link" href="/karangasem/history">
                <i class="ti-receipt menu-icon"></i>
                <span class="menu-title">History Transaksi</span>
            </a>
        </li>
        <?php endif; ?>
        </ul>
      </nav>
<!-- Content Wrapper -->
<div class="content-wrapper">
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-6">
        <h1>User Profile</h1>
        <?php if (isset($passwordChangeSuccess) && $passwordChangeSuccess): ?>
          <div class="alert alert-success" role="alert">Password has been changed successfully!</div>
        <?php endif; ?>
        <?php if (isset($passwordChangeError) && $passwordChangeError): ?>
          <div class="alert alert-danger" role="alert">Error occurred while changing password. Please try again later.</div>
        <?php endif; ?>
        <?php if (isset($passwordMismatchError) && $passwordMismatchError): ?>
          <div class="alert alert-danger" role="alert">New password and confirm password do not match.</div>
        <?php endif; ?>
        <?php if (isset($oldPasswordError) && $oldPasswordError): ?>
          <div class="alert alert-danger" role="alert">Incorrect old password. Please enter the correct old password.</div>
        <?php endif; ?>
        <h2>Welcome, <?php echo $userData['username']; ?></h2>
        <div class="profile-image-container">
          <img src="<?php echo $userData['image_path']; ?>" alt="Profile Picture" class="img-thumbnail">
          <button class="change-photo-btn" onclick="openChangePhotoModal()">Change Photo</button>
        </div>
        <i class="fas fa-pencil-alt edit-icon" onclick="editEmail()"></i>
        <p id="emailField"><?php echo $userData['email']; ?></p>
        <div>
          <label for="displayed_password">Displayed Password:</label>
          <div id="displayed_password" class="d-inline-block p-2 border">
            <?php
            $displayedPassword = $userData['displayed_password'];
            $passwordLength = strlen($displayedPassword);
            // Display the displayed password as asterisks
            for ($i = 0; $i < $passwordLength; $i++) {
              echo "*";
            }
            ?>
          </div>
          <!-- Add Edit icon -->
          <i class="fas fa-pencil-alt edit-icon" onclick="openModal()"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal for changing password -->
<div id="myModal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Change Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="passwordForm" action="/karangasem/settings" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label for="old_password">Old Password:</label>
            <input type="password" class="form-control" id="old_password" name="old_password" required>
          </div>
          <div class="form-group">
            <label for="new_password">New Password:</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
          </div>
          <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" name="change_password">Change Password</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal for changing profile photo -->
<div id="changePhotoModal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Change Profile Photo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="changePhotoForm" action="/karangasem/settings" method="post" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="form-group">
            <label for="profile_photo">Select Photo:</label>
            <input type="file" class="form-control-file" id="profile_photo" name="profile_photo" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" name="change_photo">Upload Photo</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

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
  // Function to open modal
  function openModal() {
    $('#myModal').modal('show');
  }

  // Function to close modal
  function closeModal() {
    $('#myModal').modal('hide');
  }

  // Function to open modal for changing profile photo
  function openChangePhotoModal() {
    $('#changePhotoModal').modal('show');
  }

  // Function to close modal for changing profile photo
  function closeChangePhotoModal() {
    $('#changePhotoModal').modal('hide');
  }

  // Function to edit email
  function editEmail() {
    var emailElement = document.getElementById("emailField");
    var currentEmail = emailElement.textContent.trim();
    var emailInput = document.createElement("input");
    emailInput.type = "email";
    emailInput.value = currentEmail;
    emailInput.className = "form-control";
    emailElement.parentNode.replaceChild(emailInput, emailElement);

    // Add event listener to save email changes
    emailInput.addEventListener("change", function() {
      var newEmail = emailInput.value;
      // Save newEmail to the database or perform other actions as needed
      console.log("New email:", newEmail);
    });
  }
</script>
</body>
</html>

