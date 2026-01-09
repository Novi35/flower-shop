<?php
@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
   exit();
}

if(isset($_POST['order'])){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);

    $address = mysqli_real_escape_string(
        $conn,
        'Alamat: '.$_POST['full_address'].
        ', Kelurahan/Kecamatan: '.$_POST['subdistrict'].
        ', Kota/Kabupaten: '.$_POST['city'].
        ', Provinsi: '.$_POST['province'].
        ' - Kode Pos: '.$_POST['pin_code']
    );

    $placed_on = date('d-m-Y');

    $cart_total = 0;
    $cart_products = [];

    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');

    if(mysqli_num_rows($cart_query) > 0){
        while($cart_item = mysqli_fetch_assoc($cart_query)){
            $cart_products[] = $cart_item['name'].' ('.$cart_item['quantity'].')';
            $cart_total += ($cart_item['price'] * $cart_item['quantity']);
        }
    }

    if($cart_total == 0){
        $message[] = 'Keranjang Anda kosong!';
    }else{

        $total_products = implode(', ', $cart_products);

        // INSERT order dengan status pending
        mysqli_query($conn, "INSERT INTO `orders`
        (user_id, name, number, email, method, address, total_products, total_price, placed_on, payment_status)
        VALUES
        ('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on', 'pending')")
        or die('query failed');

        // ambil ID order terakhir
        $order_id = mysqli_insert_id($conn);

        // hapus cart
        mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');

        // redirect ke payment
        header("Location: payment.php?order_id=$order_id");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout Pesanan</title>

   <!-- icon  -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>Checkout Pesanan</h3>
    <p> <a href="home.php">home</a> / checkout </p>
</section>

<section class="display-order">
    <?php
        $grand_total = 0;
        $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){
            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total += $total_price;
    ?>    
    <p> <?php echo $fetch_cart['name'] ?> <span>(<?php echo 'Rp '.$fetch_cart['price'].' x '.$fetch_cart['quantity']  ?>)</span> </p>
    <?php
        }
        }else{
            echo '<p class="empty">Keranjang Anda kosong</p>';
        }
    ?>
    <div class="grand-total">Total Keseluruhan : <span>Rp <?php echo $grand_total; ?></span></div>
</section>

<section class="checkout">

    <form action="" method="POST">

        <h3>Lengkapi Pesanan Anda</h3>

        <div class="flex">
            <div class="inputBox">
                <span>Nama Lengkap :</span>
                <input type="text" name="name" placeholder="Masukkan nama lengkap" required>
            </div>
            <div class="inputBox">
                <span>Nomor Telepon :</span>
                <input type="text" name="number" min="0" placeholder="Masukkan nomor telepon" required>
            </div>
            <div class="inputBox">
                <span>Email :</span>
                <input type="email" name="email" placeholder="Masukkan email" required>
            </div>
            <div class="inputBox">
                <span>Metode Pembayaran :</span>
                <select name="method" required>
                    <option value="bayar di tempat">Bayar di Tempat</option>
                    <option value="transfer bank">Transfer Bank (BCA)</option>
                    <option value="e-wallet">E-Wallet (ShopeePay)</option>
                    <option value="qris">QRIS</option>
                </select>
            </div>
            <div class="inputBox">
                <span>Alamat Lengkap :</span>
                <input type="text" name="full_address" placeholder="Jl. Sudirman No. 10, RT/RW 01/02" required>
            </div>
            <div class="inputBox">
                <span>Kelurahan/Kecamatan :</span>
                <input type="text" name="subdistrict" placeholder="Kelurahan Menteng, Kecamatan Menteng" required>
            </div>
            <div class="inputBox">
                <span>Kota/Kabupaten :</span>
                <input type="text" name="city" placeholder="Jakarta Pusat" required>
            </div>
            <div class="inputBox">
                <span>Provinsi :</span>
                <input type="text" name="province" placeholder="DKI Jakarta" required>
            </div>
            <div class="inputBox">
                <span>Kode Pos :</span>
                <input type="text" min="0" name="pin_code" placeholder="10110" required>
            </div>
        </div>

        <input type="submit" name="order" value="Pesan Sekarang" class="btn">

    </form>

</section>

<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>