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

$sql = "SELECT * FROM package";
$result = $conn->query($sql);

$sections = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sections[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Karangasem</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <link rel="stylesheet" href="landing.css" />
  <link rel="icon" type="image/x-icon" href="assets_landing/ve-removebg-preview.png">
  <style>
    /* Additional CSS for smooth animation */
    .nav-in {
        transition: top 0.3s ease-in-out;
    }
  </style>
</head>

<body>
  <div class="allcontent">
    <nav class="right-nav">
      <ul>
        <li>
          <a href="#home" class="dot" data-scroll="home"><span>Home</span></a>
        </li>
        <?php
        $sectionCounter = 1;
        foreach ($sections as $row) {
            echo '<li><a href="#section-paket'.$sectionCounter.'" class="dot" data-scroll="section-paket'.$sectionCounter.'"><span>Paket '.$sectionCounter.'</span></a></li>';
            $sectionCounter++;
        }
        ?>
        <li>
          <a href="#maps" class="dot" data-scroll="maps"><span>Maps</span></a>
        </li>
        <li>
          <a href="#contact" class="dot" data-scroll="contact"><span>Contact</span></a>
        </li>
      </ul>
      <div class="nav-nav">
        <div class="nav-in"></div>
      </div>
    </nav>
    <section class="home" id="home">
      <header>
        <input type="checkbox" name="" id="toggler" />
        <label for="toggler" class="fas fa-bars"></label>
        <a href="#" class="logo"><img src="logopersegipanjang.png" alt="logo" /></a>
        <nav class="navbar">
          <a href="#section-paket1">Paket</a>
          <a href="#maps">Maps</a>
          <a href="#contact">Contact</a>
        </nav>
        <div class="icons">
          <a href="/login" class="fas fa-sign-in-alt"></a>
        </div>
      </header>
      <div class="content">
        <p></p>
        <h3>Desa Wisata <br />Karangasem, Jogja</h3>
        <a href="#section-paket1" class="button-explore">
          <p>
            Explore Now
            <svg width="29" height="34" viewBox="0 0 29 34" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M28 1.5L1 33M28 1.5H5.5M28 1.5V27.75" stroke="#FAFAFA" stroke-width="1.5" />
            </svg>
          </p>
        </a>
      </div>
    </section>
    
    
    <div class="logos">
      <div class="logos-slide">
        <img src="assets_landing/deswit_white.png" />
        <img src="assets_landing/jadestawhite.png" />
        <img src="assets_landing/karangtrans.png" />
        <img src="assets_landing/wonder.png" />
        <img src="assets_landing/sisparnas_white.png" />
        <img src="assets_landing/deswit_white.png" />
        <img src="assets_landing/jadestawhite.png" />
        <img src="assets_landing/karangtrans.png" />
      </div>
    </div>
    
    
    <div id="sections-container">
      <h1 class="hah1">PAKET WISATA DESA KARANG ASEM</h1>
      <?php
      $sectionCounter = 1;
      foreach ($sections as $row) {
          echo '<section class="p1" id="section-paket'.$sectionCounter.'">';
          echo '  <div class="paket-section">';
          echo '      <div class="content">';
          echo '          <div class="number">Paket '.$sectionCounter.'</div>';
          echo '          <div class="details">';
          echo '              <div class="subtitle">~'.htmlspecialchars($row['price']).'</div>';
          echo '              <h1>'.htmlspecialchars($row['name']).'</h1>';
          echo '              <p>Deskripsi Paket :</p>';
          echo '              <br />';
          echo '              <p>'.htmlspecialchars($row['description']).'</p>';
          echo '              <a href="#" class="order-button">Order now →</a>';
          echo '          </div>';
          echo '      </div>';
          echo '      <div class="image">';
          echo '          <img src="'.htmlspecialchars($row['image_path']).'" alt="'.htmlspecialchars($row['name']).'" />';
          echo '      </div>';
          echo '  </div>';
          echo '</section>';
          $sectionCounter++;
      }
      ?>
    </div>

    <section class="maps" id="maps">
      <div class="titleles">
        <h1 class="">Maps</h1>
      </div>

      <div class="map-container">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3951.771159316171!2d110.4418760373257!3d-7.918955910426736!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a53b1ec518463%3A0x64276e584add050b!2sLimasan%20Desa%20Wisata%20Karangasem!5e0!3m2!1sid!2sid!4v1717987203591!5m2!1sid!2sid"
          width="600" height="450" style="border: 0" allowfullscreen="" loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    </section>
    <section class="contact" id="contact">
      <div class="content-new">
        <div class="titlelesb">
          <h1>Contact Person</h1>
        </div>
        <div class="image-new">
          <img src="assets_landing/supriyanto.png" alt="Supriyanto" />
        </div>
        <div class="text-new">
          <p>Contact Person:</p>
          <p>Supriyanto</p>
          <div class="contact-details-new">
            <div class="contact-item-new">
              <i class="fab fa-instagram"></i>
              <span>@karangasem_bamboovillage</span>
            </div>
            <div class="contact-item-new">
              <i class="fab fa-whatsapp"></i>
              <span>085726643029</span>
            </div>
            <div class="contact-item-new">
              <i class="fab fa-facebook"></i>
              <span>Desa Wisata Karangasem</span>
            </div>
            <div class="contact-item-new">
              <i class="fas fa-envelope"></i>
              <span>arifnurhidayanti94@gmail.com</span>
            </div>
          </div>
        </div>
      </div>
    </section>
    <footer class="footer">
      <div class="footer-content">
        <div class="footer-section about">
          <h1 class="logo-text kuning">KDA</h1>
          <p>
            Go to Karangasem village to enjoy <br />
            the beauty of nature~
          </p>
          <p>Copyright 2024 - , Inc. Terms & Privacy</p>
        </div>
        <div class="footer-section links">
          <p class="kuning">More on The Blog</p>
          <ul>
            <li><a href="#login">Login to dashboard</a></li>
            <li><a href="#section-paket1">Paket Wisata</a></li>
            <li><a href="#maps">Maps</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
        </div>
        <div class="footer-section links">
          <p class="kuning">More on KDA</p>
          <ul>
            <li><a href="#team">The Team</a></li>
            <li><a href="#jobs">Jobs</a></li>
          </ul>
        </div>
      </div>
    </footer>
  </div>
  <script>
document.addEventListener('DOMContentLoaded', () => {
    updateNavigation();
});

function updateNavigation() {
    const navIn = document.querySelector(".nav-in");
    const navLinks = document.querySelectorAll(".right-nav ul li a");
    const sections = document.querySelectorAll("section");

    function updateNavPositionOnScroll() {
        const scrollPosition = window.pageYOffset || document.documentElement.scrollTop;

        sections.forEach((section, index) => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.offsetHeight;

            if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                const link = navLinks[index];
                moveNavInToLink(link);
            }
        });
    }

    function moveNavInToLink(link) {
        const linkRect = link.getBoundingClientRect();
        const navRightRect = link.closest('.right-nav').getBoundingClientRect();
        const newTop = linkRect.top - navRightRect.top + linkRect.height / 2 - navIn.offsetHeight / 2;

        navIn.style.top = `${newTop}px`;
    }

    window.addEventListener("scroll", updateNavPositionOnScroll);

    navLinks.forEach((link) => {
        link.addEventListener("click", (event) => {
            event.preventDefault();
            const targetId = link.getAttribute("href").slice(1);
            const targetSection = document.getElementById(targetId);

            if (targetSection) {
                window.scrollTo({
                    top: targetSection.offsetTop,
                    behavior: "smooth",
                });
            }

            moveNavInToLink(link);
        });
    });

    updateNavPositionOnScroll();
}

  var copy = document.querySelector(".logos-slide").cloneNode(true);
      document.querySelector(".logos").appendChild(copy);
</script>
</body>

</html>
