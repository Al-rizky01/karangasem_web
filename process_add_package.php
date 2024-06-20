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
    $packageName = $_POST['packageName'];
    $packageDescription = $_POST['packageDescription'];
    $packagePrice = $_POST['packagePrice'];

    $target_dir = "pack-photo/";
    $target_file = $target_dir . basename($_FILES["packageImage"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["packageImage"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
        header("Location: /package?error=true&message=File is not an image.");
        exit();
    }

    // Check file size
    if ($_FILES["packageImage"]["size"] > 5000000) {
        $uploadOk = 0;
        header("Location: /package?error=true&message=Sorry, your file is too large.");
        exit();
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $uploadOk = 0;
        header("Location: /package?error=true&message=Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
        exit();
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        header("Location: /package?error=true&message=Sorry, your file was not uploaded.");
        exit();
    } else {
        if (move_uploaded_file($_FILES["packageImage"]["tmp_name"], $target_file)) {
            $imagePath = $target_file;

            $stmt = $conn->prepare("INSERT INTO package (name, description, price, image_path) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssis", $packageName, $packageDescription, $packagePrice, $imagePath);

            if ($stmt->execute()) {
                header("Location: /package?success=true&message=New package added successfully");
                exit();
            } else {
                header("Location: /package?error=true&message=Error: " . $stmt->error);
                exit();
            }
        } else {
            header("Location: /package?error=true&message=Sorry, there was an error uploading your file.");
            exit();
        }
    }
}

$conn->close();
?>
