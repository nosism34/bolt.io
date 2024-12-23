<?php
session_start();
include 'db.php'; // Sertakan file koneksi database

// Cek apakah ada item dalam keranjang
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Keranjang Anda kosong. Silakan tambahkan item sebelum checkout.";
    exit;
}

// Proses form jika sudah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $payment_method = $_POST['payment_method']; // Pastikan ini sesuai dengan name di form
    $total_amount = $_POST['total_amount']; // Pastikan Anda menambahkan input ini di form

    // Simpan data pemesanan ke database
    try {
        $stmt = $conn->prepare("INSERT INTO pemesanan (nama, alamat, payment_method, total_amount, created_at) VALUES (:nama, :alamat, :payment_method, :total_amount, CURRENT_TIMESTAMP)");
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':alamat', $alamat);
        $stmt->bindParam(':payment_method', $payment_method);
        $stmt->bindParam(':total_amount', $total_amount);
        
        // Eksekusi query
        $stmt->execute();

        echo "Terima kasih, $nama! Pesanan Anda telah diproses dengan metode pembayaran: $payment_method.";
        
        // Kosongkan keranjang setelah checkout
        unset($_SESSION['cart']);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
</head>
<body>
    <h1>Checkout</h1>
    <p>Silakan pilih metode pembayaran melalui:</p>
    <ul>
        <li>
            <a href="https://wa.me/6281234567890?text=">
                <button>WhatsApp</button>
            </a>
        </li>
        <li>
            <a href="https://t.me/YourTelegramBot?start=">
                <button>Telegram</button>
            </a>
        </li>
    </ul>
</body>
</html>