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
   <title>about</title>

   <!-- icon  -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>about us</h3>
    <p> <a href="home.php">home</a> / about </p>
</section>

<section class="about">

    <div class="flex">

        <div class="image">
            <img src="images/about-img-1.png" alt="">
        </div>

        <div class="content">
            <h3>why choose us?</h3>
            <p>We use fresh flowers and arrange every bouquet with care and attention to detail. Each order is made just for you, not mass-produced. Ordering is easy, our team is responsive, and delivery is always on time. Perfect for gifts, surprises, or special moments—simple and hassle-free.</p>
            <a href="shop.php" class="btn">shop now</a>
        </div>

    </div>

    <div class="flex">

        <div class="content">
            <h3>what we provide?</h3>
            <p>We offer fresh flower bouquets for all occasions, from birthdays and anniversaries to graduations and special moments. Each arrangement is carefully crafted with quality flowers and thoughtful designs. We also provide easy ordering, custom requests, and reliable delivery to make your experience simple and enjoyable.</p>
            <a href="contact.php" class="btn">contact us</a>
        </div>

        <div class="image">
            <img src="images/about-img-2.jpg" alt="">
        </div>

    </div>

    <div class="flex">

        <div class="image">
            <img src="images/about-img-3.jpg" alt="">
        </div>

        <div class="content">
            <h3>who we are?</h3>
            <p>We are a flower shop that loves turning fresh flowers into beautiful bouquets for every special moment. We focus on quality, simple designs, and making sure every order feels personal. Our goal is to make gifting flowers easy, meaningful, and stress-free.</p>
            <a href="#reviews" class="btn">clients reviews</a>
        </div>

    </div>

</section>

<section class="reviews" id="reviews">

    <h1 class="title">client's reviews</h1>

    <div class="box-container">

        <div class="box">
            <img src="images/pic1.jpg" alt="">
            <p>Bunganya fresh banget dan rapi. Dateng sesuai pesanan, ga kecewa sama sekali.</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Mingyu</h3>
        </div>

        <div class="box">
            <img src="images/pic2.jpg" alt="">
            <p>Order gampang, pengiriman tepat waktu. Bunganya masih segar pas sampe.</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Umji</h3>
        </div>

        <div class="box">
            <img src="images/pic3.jpg" alt="">
            <p>Ini pertama kali order di sini dan hasilnya puas. Packaging-nya rapi.</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Jeonghan</h3>
        </div>

        <div class="box">
            <img src="images/pic4.jpg" alt="">
            <p>Harga masuk akal dengan kualitasnya. Bunganya awet dan ga cepet layu.</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Rose</h3>
        </div>

        <div class="box">
            <img src="images/pic5.jpg" alt="">
            <p>Harga masuk akal dengan kualitasnya. Bunganya awet dan ga cepet layu.</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Joshua</h3>
        </div>

        <div class="box">
            <img src="images/pic6.jpg" alt="">
            <p>Adminnya ramah dan fast respon. Dibantuin milih bunga juga, hasilnya cakep.</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Wonyoung</h3>
        </div>

    </div>

</section>











<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>