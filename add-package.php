<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Paket</title>
</head>
<body>
    <h1>Tambah Paket</h1>
    <?php 
    include_once 'koneksi.php'; // Sertakan file koneksi database 
    
    // Periksa apakah form telah disubmit
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Ambil nilai dari form
        $nama = $_POST['nama'];
        $harga = $_POST['harga'];
        $deskripsi = $_POST['deskripsi'];

        // Proses upload gambar
        $targetDir = "pack-photo/"; // Folder untuk menyimpan gambar
        $targetFile = $targetDir . basename($_FILES["gambar"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
        // Periksa apakah file gambar yang diunggah valid
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["gambar"]["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }
        // Periksa apakah file sudah ada
        if (file_exists($targetFile)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
        // Periksa ukuran file gambar
        if ($_FILES["gambar"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Izinkan hanya format gambar tertentu
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        // Cek apakah $uploadOk bernilai 0
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // Jika semuanya berjalan lancar, coba untuk mengunggah file
        } else {
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetFile)) {

                // Query untuk menambahkan paket ke dalam database
                $sql = "INSERT INTO product (name, price, description, image_path) VALUES ('$nama', '$harga', '$deskripsi', '$targetFile')";
                // Eksekusi query
                if ($conn->query($sql) === TRUE) {
                    echo "Paket berhasil ditambahkan.";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
        <label for="nama">Nama Paket:</label><br>
        <input type="text" id="nama" name="nama"><br>
        <label for="harga">Harga Paket:</label><br>
        <input type="text" id="harga" name="harga"><br>
        <label for="deskripsi">Deskripsi Paket:</label><br>
        <textarea id="deskripsi" name="deskripsi"></textarea><br>
        <label for="gambar">Gambar Paket:</label><br>
        <input type="file" id="gambar" name="gambar"><br><br>
        <input type="submit" name="submit" value="Submit">
    </form>
</body>
</html>
