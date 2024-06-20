<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "id22276528_root";
$password = "12_Wafaras";
$database = "id22276528_karangasem";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $packageName = $_POST['homestayName']; // Ganti packageName menjadi homestayName
    $packageDescription = $_POST['homestayDescription']; // Ganti packageDescription menjadi homestayDescription

    $target_dir = "pack-photo/";
    $target_file = $target_dir . basename($_FILES["homestayImage"]["name"]); // Ganti packageImage menjadi homestayImage
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["homestayImage"]["tmp_name"]); // Ganti packageImage menjadi homestayImage
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
        header("Location: /homestay?error=true&message=File is not an image.");
        exit();
    }

    // Check file size
    if ($_FILES["homestayImage"]["size"] > 5000000) { // Ganti packageImage menjadi homestayImage
        $uploadOk = 0;
        header("Location: /homestay?error=true&message=Sorry, your file is too large.");
        exit();
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $uploadOk = 0;
        header("Location: /homestay?error=true&message=Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
        exit();
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        header("Location: /homestay?error=true&message=Sorry, your file was not uploaded.");
        exit();
    } else {
        if (move_uploaded_file($_FILES["homestayImage"]["tmp_name"], $target_file)) { // Ganti packageImage menjadi homestayImage
            $imagePath = $target_file;

            $stmt = $conn->prepare("INSERT INTO homestay (name, description, image_path) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $packageName, $packageDescription,  $imagePath); // Menggunakan "sss" karena Anda memiliki tiga string

            if ($stmt->execute()) {
                header("Location: /homestay?success=true&message=New package added successfully");
                exit();
            } else {
                header("Location: /homestay?error=true&message=Error: " . $stmt->error);
                exit();
            }
        } else {
            header("Location: /homestay?error=true&message=Sorry, there was an error uploading your file.");
            exit();
        }
    }
}

$conn->close();
?>
