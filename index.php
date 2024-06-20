<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'koneksi.php'; // Menggunakan file koneksi.php untuk membuat koneksi ke database
session_start();

// Fungsi untuk mengambil URI yang diminta pengguna
function getCurrentUri()
{
    $uri = $_SERVER['REQUEST_URI'];
    $uri = explode('?', $uri); // Mengabaikan query string
    return $uri[0];
}

// Fungsi untuk memuat konten dari file spesifik berdasarkan URI
function route($uri)
{
    switch ($uri) {
        case '/':
            include 'landingPage.php';
            break;
         case '/dashboard':
            include 'dashboard.php';
            break;
        case '/package':
            include 'paket-wisata.php';
            break;
        case '/homestay':
            include 'homestay.php';
            break;
        case '/register':
            include 'register.php';
            break;
        case '/login':
            include 'login.php';
            break;
        case '/product':
            include 'product.php';
            break;
        case '/settings':
            include 'settings-profile.php';
            break;
        case '/logout':
            include 'logout.php';
            break;
        case '/destination':
            include 'destination.php';
            break;
        case '/product/product-detail':
            include 'product_detail.php';
            break;
        case '/package/order':
            include 'order.php';
            break;
        case '/package/order/detail':
            include 'order-detail.php';
            break;
        case '/package/order-process':
            include 'process-order.php';
            break;
        case '/order-history':
            include 'order_history.php';
            break;
            case '/transactions':
            include 'admin_orders.php';
            break;
        default:
            include '404.php';
            break;
    }
}

// Memanggil fungsi route dengan menggunakan URI yang diminta pengguna
$route = getCurrentUri();
route($route);
?>
