<?php
session_start();

// Memasukkan file konfigurasi database
include 'koneksi.php';

// Fungsi untuk mendapatkan detail paket wisata berdasarkan ID
function getPackageDetails($conn, $package_id) {
    $stmt = $conn->prepare("SELECT * FROM package WHERE id = ?");
    $stmt->bind_param("i", $package_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Mendapatkan informasi pesanan dari URL
if (isset($_POST['order_id'])) {
    // Memasukkan file koneksi.php di sini

    $order_id = $_POST['order_id'];

    // Query untuk mendapatkan informasi pesanan berdasarkan ID
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // Redirect jika pesanan tidak ditemukan
        header("Location: /karangasem/");
        exit();
    }

    $order = $result->fetch_assoc();

    // Mendapatkan detail paket wisata berdasarkan ID yang ada dalam pesanan
    $package = getPackageDetails($conn, $order['package_id']);

    // Mengirim data pesanan ke Midtrans
    // Persiapkan data yang diperlukan oleh Midtrans, misalnya:
    $transaction_details = array(
        'order_id' => $order_id,
        'gross_amount' => $order['total_price'] // Total harga pesanan
    );

    // Persiapkan item-detail pesanan (opsional)
    $item_details = array(
        array(
            'id' => $package['id'],
            'price' => $package['price'],
            'quantity' => $order['total_persons'],
            'name' => $package['name']
        )
    );

    // Persiapkan customer details (opsional)
    $customer_details = array(
        'first_name' => $order['fullname'],
        'email' => $order['email'],
        'phone' => $order['phone']
    );

    // Persiapkan konfigurasi API Midtrans
    $midtrans_api_url = 'https://api.midtrans.com/v2/charge';
    $server_key = 'SB-Mid-server-DYNY5Enkvo-9tJV28znVAF3u'; // Ganti dengan server key Midtrans Anda

    // Data yang akan dikirim ke Midtrans
    $midtrans_data = array(
        'payment_type' => 'bank_transfer',
        'transaction_details' => $transaction_details,
        'item_details' => $item_details,
        'customer_details' => $customer_details
    );

    // Buat request ke API Midtrans
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $midtrans_api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($midtrans_data),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic ' . base64_encode($server_key . ':')
        )
    ));

    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    // Jika request berhasil
// Jika request gagal
if ($httpcode !== 200) {
    echo "Error: Unable to proceed payment. ";
    echo "Midtrans API Response: " . $response;
    exit();


    } else {
        // Jika request gagal
        echo "Error: Unable to proceed payment.";
    }
} else {
    // Redirect jika tidak ada parameter order_id
    header("Location: /karangasem/");
    exit();
}
?>
