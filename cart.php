<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id'") or die('query failed');
    header('location:cart.php');
}

if(isset($_GET['delete_all'])){
    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    header('location:cart.php');
};

if(isset($_POST['update_quantity'])){
    $cart_id = $_POST['cart_id'];
    $cart_quantity = $_POST['cart_quantity'];
    mysqli_query($conn, "UPDATE `cart` SET quantity = '$cart_quantity' WHERE id = '$cart_id'") or die('query failed');
    $message[] = 'cart quantity updated!';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shopping cart</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>

<?php
$grand_total = 0;
$select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'");
?>

<section class="shopping-cart">
    <h1 class="title">shopping cart</h1>
    <div class="cart-layout">
        <div class="cart-left">
            <div class="cart-header">
            <span>Product</span>
            <span>Price</span>
            <span>Quantity</span>
            <span>Subtotal</span>
            <span>Action</span>
            </div>

            <?php if(mysqli_num_rows($select_cart) > 0){ ?>
            <?php while($fetch_cart = mysqli_fetch_assoc($select_cart)){ 

            $sub_total = $fetch_cart['price'] * $fetch_cart['quantity'];
            $grand_total += $sub_total;
            ?>

            <form class="cart-item"
                data-id="<?php echo $fetch_cart['id']; ?>"
                data-price="<?php echo $fetch_cart['price']; ?>">

            <div class="product">
                <!-- <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="remove">×</a> -->
                <img src="flowers/<?php echo $fetch_cart['image']; ?>">
                <h4><?php echo $fetch_cart['name']; ?></h4>
            </div>

            <div class="price">
                Rp <?php echo $fetch_cart['price']; ?>.000
            </div>

            <div class="qty-wrapper">
                <button type="button" class="qty-btn minus">−</button>
                <input type="number" class="qty" value="<?php echo $fetch_cart['quantity']; ?>" min="1">
                <button type="button" class="qty-btn plus">+</button>
            </div>

            <div class="subtotal">
                Rp <span class="subtotal-value"><?php echo $sub_total; ?></span>.000
            </div>

             <div class="delete">
                <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="bi bi-trash"></a>
            </div>

            </form>

            <?php } } else { ?>
            <p class="empty">your cart is empty</p>
            <?php } ?>

        </div>

        <!-- RIGHT -->
        <div class="cart-right">
            <h3>Order Summary</h3>

            <div class="summary-row">
                <span>Subtotal</span>
                <span>Rp <span id="grand-total"><?php echo $grand_total; ?></span>.000</span>
            </div>

            <div class="summary-row">
                <span>Shipping</span>
                <span>Rp 0</span>
            </div>

            <div class="summary-row total">
                <span>Total</span>
                <span>Rp <span id="grand-total-final"><?php echo $grand_total; ?></span>.000</span>
            </div>

            <a href="checkout.php"
                class="checkout-btn <?php echo ($grand_total > 0)?'':'disabled' ?>">
                Proceed to Checkout
            </a>
        </div>
    </div>
</section>







<?php @include 'footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="js/script.js"></script>

<script>
$(document).on('click', '.qty-btn', function () {

  let item = $(this).closest('.cart-item');
  let qtyInput = item.find('.qty');
  let price = parseInt(item.data('price'));
  let cartId = item.data('id');

  let qty = parseInt(qtyInput.val());

  if ($(this).hasClass('plus')) {
    qty++;
  } else if ($(this).hasClass('minus') && qty > 1) {
    qty--;
  }

  qtyInput.val(qty);

  // update subtotal per item
  let subtotal = price * qty;
  item.find('.subtotal-value').text(subtotal);

  // update grand total
  updateGrandTotal();

  // update database
  $.ajax({
    url: 'update_cart.php',
    type: 'POST',
    data: {
      cart_id: cartId,
      cart_quantity: qty
    }
  });

});

function updateGrandTotal() {
  let total = 0;
  $('.subtotal-value').each(function () {
    total += parseInt($(this).text());
  });
  $('#grand-total, #grand-total-final').text(total);
}
</script>



</body>
</html>