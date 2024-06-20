<?php
// Check if session is already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'midtrans_config.php';

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "karangasem";
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Check if user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$user_id = $_SESSION['user_id'];

$query = "SELECT o.order_id, o.status, p.name, p.price, p.image_path 
          FROM orders o 
          JOIN package p ON o.package_id = p.id 
          WHERE o.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="css/vertical-layout-light/style.css">
    <style>
        .card-group {
            margin-top: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Your Orders</h1>
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="col-md-4">
                        <div class="card">
                            <img src="<?php echo htmlspecialchars($row['image_path']); ?>" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                                <p class="card-text">Price: Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></p>
                                <p class="card-text">Status: <?php echo htmlspecialchars($row['status']); ?></p>
                                <?php if ($row['status'] == 'pending'): ?>
                                    <button class="btn btn-primary pay-btn" data-order-id="<?php echo htmlspecialchars($row['order_id']); ?>">Pay Now</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No orders found.</p>";
            }
            ?>
        </div>
    </div>

    <script src="vendors/js/vendor.bundle.base.js"></script>
    <script src="js/off-canvas.js"></script>
    <script src="js/hoverable-collapse.js"></script>
    <script src="js/template.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/todolist.js"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="YOUR_CLIENT_KEY"></script>
    <script type="text/javascript">
        document.querySelectorAll('.pay-btn').forEach(button => {
            button.addEventListener('click', function () {
                const orderId = this.getAttribute('data-order-id');
                fetch('retry_payment.php?order_id=' + orderId)
                    .then(response => response.json())
                    .then(data => {
                        if (data.token) {
                            snap.pay(data.token, {
                                onSuccess: function(result) {
                                    window.location.href = "order_success.php?order_id=" + orderId;
                                },
                                onPending: function(result) {
                                    window.location.href = "order_pending.php?order_id=" + orderId;
                                },
                                onError: function(result) {
                                    window.location.href = "order_failed.php?order_id=" + orderId;
                                }
                            });
                        } else {
                            alert('Failed to get Snap token: ' + data.error);
                        }
                    });
            });
        });
    </script>
</body>
</html>
