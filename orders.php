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


<section class="placed-orders">

    <h1 class="title">Placed orders</h1>

    <div class="box-container">

    <?php
        $select_orders = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id'") or die('query failed');
        if(mysqli_num_rows($select_orders) > 0){
            while($fetch_orders = mysqli_fetch_assoc($select_orders)){
    ?>
    <div class="order-card">

  <!-- LEFT INFO -->
  <div class="order-left">

    <div class="order-status <?php echo $fetch_orders['payment_status']; ?>">
      <?php echo ucfirst($fetch_orders['payment_status']); ?>
    </div>

    <div class="order-meta">
      <div>
        <small>Tanggal Order</small>
        <p><?php echo $fetch_orders['placed_on']; ?></p>
      </div>

      <div>
        <small>Metode Pembayaran</small>
        <p><?php echo $fetch_orders['method']; ?></p>
      </div>

      <div>
        <small>Nama</small>
        <p><?php echo $fetch_orders['name']; ?></p>
      </div>

      <div>
        <small>Telepon</small>
        <p><?php echo $fetch_orders['number']; ?></p>
      </div>

      <div>
        <small>Email</small>
        <p><?php echo $fetch_orders['email']; ?></p>
      </div>

      <div>
        <small>Alamat</small>
        <p><?php echo $fetch_orders['address']; ?></p>
      </div>
    </div>

    <div class="order-total">
      Total Pembayaran: <strong>Rp<?php echo $fetch_orders['total_price']; ?></strong>
    </div>

    <?php if($fetch_orders['payment_status'] == 'pending'){ ?>
      <a href="payment.php?order_id=<?php echo $fetch_orders['id']; ?>" class="btn">
        Proceed to Payment
      </a>
    <?php } else { ?>
      <a href="payment.php?order_id=<?php echo $fetch_orders['id']; ?>" class="btn outline">
        View Payment History
      </a>
    <?php } ?>

  </div>

  <!-- RIGHT PRODUCTS -->
  <div class="order-right">
    <h4>Produk</h4>
    <p><?php echo $fetch_orders['total_products']; ?></p>
  </div>

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