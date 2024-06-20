<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Slider dengan Kartu Hover</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card {
      opacity: 0;
      transition: opacity 0.3s, transform 0.3s;
      transform: translateX(100%);
    }

    .carousel-item:hover .card {
      opacity: 1;
      transform: translateX(0%);
    }
  </style>
</head>
<body>

<div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="https://via.placeholder.com/600x300" class="d-block w-100" alt="Placeholder 1">
    </div>
    <div class="carousel-item">
      <img src="https://via.placeholder.com/600x300" class="d-block w-100" alt="Placeholder 2">
    </div>
    <!-- Tambahkan gambar-gambar lainnya -->
  </div>
</div>

<div class="card-deck position-fixed end-0" style="top: 50%; transform: translateY(-50%);">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Judul Gambar 2</h5>
      <p class="card-text">Deskripsi singkat gambar 2.</p>
    </div>
  </div>
  <!-- Tambahkan card lainnya sesuai dengan jumlah gambar -->
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
