<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders</title>

   <!-- icon  -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>Your orders</h3>
    <p> <a href="home.php">home</a> / orders </p>
</section>

<section class="placed-orders">

    <h1 class="title">Placed orders</h1>

    <div class="box-container">

    <?php
        $select_orders = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id'") or die('query failed');
        if(mysqli_num_rows($select_orders) > 0){
            while($fetch_orders = mysqli_fetch_assoc($select_orders)){
    ?>
    <div class="box">
        <p> Ditempatkan pada : <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
        <p> Nama             : <span><?php echo $fetch_orders['name']; ?></span> </p>
        <p> Telepon          : <span><?php echo $fetch_orders['number']; ?></span> </p>
        <p> Email            : <span><?php echo $fetch_orders['email']; ?></span> </p>
        <p> Alamat           : <span><?php echo $fetch_orders['address']; ?></span> </p>
        <p> Metode pembayaran: <span><?php echo $fetch_orders['method']; ?></span> </p>
        <p> Pesanan Anda     : <span><?php echo $fetch_orders['total_products']; ?></span> </p>
        <p> Total Pembayaran : <span>Rp<?php echo $fetch_orders['total_price']; ?></span> </p>
        <p> Status pembayaran: <span style="color:<?php if($fetch_orders['payment_status'] == 'pending'){echo 'tomato'; }else{echo 'green';} ?>"><?php echo $fetch_orders['payment_status']; ?></span> </p>
    
        <!-- untuk melihat history pembayaran -->
        <?php if($fetch_orders['payment_status'] == 'pending'){ ?>
            <a href="payment.php?order_id=<?php echo $fetch_orders['id']; ?>" class="btn">Proceed to Payment</a>
        <?php } else { ?>
            <a href="payment.php?order_id=<?php echo $fetch_orders['id']; ?>" class="btn">View Payment History</a>
        <?php } ?>
    </div>
    <?php
        }
    }else{
        echo '<p class="empty">No orders placed yet!</p>';
    }
    ?>
    </div>

</section>


<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>