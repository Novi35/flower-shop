<?php
@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

require_once 'vendor/autoload.php'; // Autoload Composer

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use setasign\Fpdi\Fpdi; 
require_once 'vendor/setasign/fpdf/fpdf.php';  

$order_id = $_GET['order_id'] ?? null;
if(!$order_id){
    header('location: home.php');
    exit();
}

$order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE id = '$order_id' AND user_id = '$user_id'") or die('query failed');
$order = mysqli_fetch_assoc($order_query);

if(!$order){
    header('location: home.php');
    exit();
}

// Jika tombol konfirmasi diklik (hanya untuk pending)
if(isset($_POST['confirm_payment']) && $order['payment_status'] == 'pending'){

    // Update status pembayaran
    mysqli_query(
        $conn,
        "UPDATE `orders` SET payment_status = 'paid' WHERE id = '$order_id'"
    ) or die('query failed');

    // Hapus QR karena sudah paid
    $qr_file = 'temp/qr_'.$order_id.'.png';
    if(file_exists($qr_file)){
        unlink($qr_file);
    }

    $message[] = 'Pembayaran berhasil dikonfirmasi!';
    header('location: orders.php');
    exit();
}


$qr_image = '';

if($order['payment_status'] == 'pending'){
    $qr_data = "Bayar ke Rekening: 1234567890 | "
             . "Atas Nama: Flowers | "
             . "Total: Rp {$order['total_price']} | "
             . "ID Pesanan: {$order['id']} | "
             . "Metode: {$order['method']}";

    $options = new QROptions([
        'outputType' => QRCode::OUTPUT_IMAGE_PNG,
        'eccLevel'   => QRCode::ECC_L,
        'scale'      => 6,
    ]);

    $qrcode = new QRCode($options);

    // simpan file QR
    $qr_file = 'temp/qr_'.$order['id'].'.png';
    $qrcode->render($qr_data, $qr_file);

    $qr_image = $qr_file;
}


// Generate Struk PDF (hanya untuk paid)
if (isset($_GET['generate_struk']) && $order['payment_status'] == 'paid') {
    
    $pdf = new FPDF('P', 'mm', array(80, 150)); // Ukuran struk thermal (80mm lebar, 150mm tinggi)
    $pdf->AddPage();
    $pdf->SetMargins(5, 5, 5);

    // Judul 
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 6, 'Flowers.', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(0, 5, 'Kota Bekasi, Jawa Barat', 0, 1, 'C'); 
    $pdf->Cell(0, 5, 'Telp: +62-856-7890-2234 / +62-225-9876-3333', 0, 1, 'C');
    $pdf->Cell(0, 5, 'Struk Pembayaran', 0, 1, 'C');

    $pdf->Ln(3);
    $pdf->Cell(0, 0, str_repeat('-', 32), 0, 1);
    $pdf->Ln(3);

    // Detail Pesanan
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(0, 5, 'ID Pesanan : ' . $order['id'], 0, 1);
    $pdf->Cell(0, 5, 'Nama       : ' . $order['name'], 0, 1);
    $pdf->Cell(0, 5, 'Email      : ' . $order['email'], 0, 1);

    $pdf->Ln(2);
    $pdf->Cell(0, 0, str_repeat('-', 32), 0, 1);
    $pdf->Ln(3);

    // Produk 
    $pdf->MultiCell(0, 5, 'Produk : ' . $order['total_products']);

    $pdf->Ln(2);
    $pdf->Cell(0, 0, str_repeat('-', 32), 0, 1);
    $pdf->Ln(3);

    // Total Harga
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 6, 'TOTAL : Rp ' . number_format($order['total_price'], 0, ',', '.') . 0, 1, 'R'); 

    $pdf->Ln(2);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(0, 5, 'Metode  : ' . $order['method'], 0, 1);
    $pdf->Cell(0, 5, 'Status  : PAID', 0, 1);
    
    $placed_on = !empty($order['placed_on']) ? date('d/m/Y', strtotime($order['placed_on'])) : 'Tidak tersedia';
    $pdf->Cell(0, 5, 'Tanggal : ' . $placed_on, 0, 1); 

    $pdf->Ln(4);
    $pdf->Cell(0, 0, str_repeat('-', 32), 0, 1);
    $pdf->Ln(4);

    // Footer
    $pdf->SetFont('Arial', 'I', 8);
    $pdf->Cell(0, 5, 'Terima kasih atas pembelian Anda!', 0, 1, 'C');
    $pdf->Cell(0, 5, 'Simpan struk ini sebagai bukti.', 0, 1, 'C');
    $pdf->Cell(0, 5, 'Kunjungi kami lagi di www.flowers.com', 0, 1, 'C'); 

    // Simpan ke file sementara
    $struk_file = 'temp/struk_' . $order['id'] . '.pdf';
    $pdf->Output($struk_file, 'F');

    // Download file
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="struk_pesanan_' . $order['id'] . '.pdf"');
    readfile($struk_file);
    unlink($struk_file); // Hapus setelah download
    exit();
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Payment</title>

   <!-- icon  -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3><?php echo ($order['payment_status'] == 'paid') ? 'Payment History' : 'Payment'; ?></h3>
    <p> <a href="home.php">home</a> / <a href="orders.php">orders</a> / <?php echo ($order['payment_status'] == 'paid') ? 'history' : 'payment'; ?> </p>
</section>

<section class="payment">
    <h3>Detail Pesanan</h3>
    <p><strong>ID Pesanan :</strong> <?php echo $order['id']; ?></p>
    <p><strong>Produk     :</strong> <?php echo $order['total_products']; ?></p>
    <p><strong>Total      :</strong> Rp <?php echo $order['total_price']; ?></p>
    <p><strong>Metode     :</strong> <?php echo $order['method']; ?></p>
    <p><strong>Status     :</strong> <?php echo $order['payment_status']; ?></p>

    <?php if($order['payment_status'] == 'pending'){ ?>
        <p><em>Klik tombol di bawah untuk "membayar".</em></p>
        
        <h4>Scan QR Code untuk Pembayaran:</h4>
        <?php if($qr_image != ''){ ?>
            <div style="text-align:center;">
                <img src="<?php echo $qr_image; ?>" alt="QR Code Pembayaran" width="200">
            </div>
        <?php } ?>
        <p><small>Scan kode ini dengan aplikasi e-wallet atau bank untuk transfer ke rekening yang tertera.</small></p>
        
        <form action="" method="POST">
            <input type="submit" name="confirm_payment" value="Konfirmasi Pembayaran" class="btn">
        </form>
    <?php } else { ?>
        <p><em>Ini adalah history pembayaran Anda. Anda bisa download struk sebagai bukti.</em></p>

        <div class="payment-actions">
            <a href="?order_id=<?php echo $order_id; ?>&generate_struk" class="btn">Download Struk PDF</a>
        </div>
    <?php } ?>
</section>

<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>