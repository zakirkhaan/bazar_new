<?php
session_start();
include("includes/db.php");
include("functions/functions.php");
//constants , checking customer login and review_order if both are true the customer shall
//see the below page if false then will be redirected back to the index page//
define("customer_login", TRUE);
define("review_order", TRUE);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bazar </title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="http://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
    <link href="styles/bootstrap.min.css" rel="stylesheet">
    <link href="styles/style.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <script src="js/jquery.min.js"></script>
    <script src="https://checkout.stripe.com/checkout.js"></script>
 <style>
         .marquee {
             width: 100%;
             line-height: 50px;
             background-color: black;
             color: white;
             white-space: nowrap;
             overflow: hidden;
             box-sizing: border-box;
         }
         .marquee p {
             display: inline-block;
             padding-left: 100%;
             animation: marquee 15s linear infinite;
         }
         @keyframes marquee {
             0%   { transform: translate(0, 0); }
             100% { transform: translate(-100%, 0); }
     </style>
</head>

<body>
<div class="marquee">
    <p  style="background-color:black; text-align: center; font-size: 17px; margin-top: 0; margin-bottom: 1px;">
        <span style="color: white;">Peshawar! Get Ready ! Bazaar is currently Delivering Services Only In Peshawar!</span>
    </p>
</div>

<div id="top"><!-- top Starts -->
    <div class="container"><!-- container Starts -->
        <div class="col-md-6 offer"><!-- col-md-6 offer Starts -->
            <a href="#" class="btn btn-success btn-sm">
              <?php
              if (!isset($_SESSION['customer_email'])) {
                  echo "Welcome :Guest";

              } else {
                  echo "Welcome : " . $_SESSION['customer_email'] . "";

              }

              ?>

            </a>

            <a href="#">
                Shopping Cart Total Price: <?php total_price(); ?>, Total Items <?php items(); ?>
            </a>

        </div><!-- col-md-6 offer Ends -->

        <div class="col-md-6"><!-- col-md-6 Starts -->
            <ul class="menu"><!-- menu Starts -->

              <?php if (!isset($_SESSION['customer_email'])) { ?>

                  <li>

                      <a href="customer_register.php"> Register </a>

                  </li>

                <?php

              } else {

                $customer_email = $_SESSION['customer_email'];
                $select_customer = "select * from customers where customer_email='$customer_email'";
                $run_customer = mysqli_query($con, $select_customer);
                $row_customer = mysqli_fetch_array($run_customer);
                $customer_role = $row_customer['customer_role'];
                if ($customer_role == "customer") {
                    ?>
                    <li>
                        <a href="shop.php"> Shop </a>
                    </li>
                <?php } elseif ($customer_role == "vendor") { ?>
                    <li>

                        <a href="vendor_dashboard/index.php"> Vendor Dashboard </a>

                    </li>

                <?php }
              } ?>

                <li>
                  <?php

                  if (!isset($_SESSION['customer_email'])) {

                    echo "<a href='checkout.php' >My Account</a>";

                  } else {

                    echo "<a href='customer/my_account.php?my_orders'>My Account</a>";

                  }

                  ?>
                </li>

                <li>
                    <a href="cart.php">
                        Go to Cart
                    </a>
                </li>

                <li>
                  <?php

                  if (!isset($_SESSION['customer_email'])) {

                    echo "<a href='checkout.php'> Login </a>";

                  } else {

                    echo "<a href='logout.php'> Logout </a>";

                  }

                  ?>
                </li>

            </ul><!-- menu Ends -->

        </div><!-- col-md-6 Ends -->

    </div><!-- container Ends -->
</div><!-- top Ends -->

<div class="navbar navbar-default" id="navbar"><!-- navbar navbar-default Starts -->
    <div class="container"><!-- container Starts -->

        <div class="navbar-header"><!-- navbar-header Starts -->

            <a class="navbar-brand home" href="index.php"><!--- navbar navbar-brand home Starts -->

                 <h2 style="color: black;font-size: 35px;border:1px; margin:2px;" >
                                     Ba<span style="color:darkred; font-size: 35px;">zaar</span></h2>
            </a><!--- navbar navbar-brand home Ends -->

            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation">

                <span class="sr-only">Toggle Navigation </span>

                <i class="fa fa-align-justify"></i>

            </button>

            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#search">

                <span class="sr-only">Toggle Search</span>

                <i class="fa fa-search"></i>

            </button>


        </div><!-- navbar-header Ends -->

        <div class="navbar-collapse collapse" id="navigation"><!-- navbar-collapse collapse Starts -->

            <div class="padding-nav"><!-- padding-nav Starts -->

                <ul class="nav navbar-nav navbar-left"><!-- nav navbar-nav navbar-left Starts -->

                    <li>
                        <a href="index.php"> Home </a>
                    </li>

                    <li>
                        <a href="shop.php"> Shop </a>
                    </li>

                    <li>
                      <?php

                      if (!isset($_SESSION['customer_email'])) {

                        echo "<a href='checkout.php' >My Account</a>";

                      } else {

                        echo "<a href='customer/my_account.php?my_orders'>My Account</a>";

                      }

                      ?>
                    </li>

                    <li>
                        <a href="cart.php"> Shopping Cart </a>
                    </li>

                    <li>
                        <a href="about.php"> About Us </a>
                    </li>

                    <li>

                        <a href="services.php"> Services </a>

                    </li>

                    <li>
                        <a href="contact.php"> Contact Us </a>
                    </li>

                </ul><!-- nav navbar-nav navbar-left Ends -->

            </div><!-- padding-nav Ends -->

            <a class="btn btn-primary navbar-btn right" href="cart.php"><!-- btn btn-primary navbar-btn right Starts -->

                <i class="fa fa-shopping-cart"></i>

                <span> <?php items(); ?> items in cart </span>

            </a><!-- btn btn-primary navbar-btn right Ends -->

            <div class="navbar-collapse collapse right"><!-- navbar-collapse collapse right Starts -->

                <button class="btn navbar-btn btn-primary" type="button" data-toggle="collapse" data-target="#search">

                    <span class="sr-only">Toggle Search</span>

                    <i class="fa fa-search"></i>

                </button>

            </div><!-- navbar-collapse collapse right Ends -->

            <div class="collapse clearfix" id="search"><!-- collapse clearfix Starts -->
                <form class="navbar-form" method="get" action="results.php"><!-- navbar-form Starts -->
                    <div class="input-group"><!-- input-group Starts -->
                        <input class="form-control" type="text" placeholder="Search" name="user_query" required>
                        <span class="input-group-btn"><!-- input-group-btn Starts -->
                            <button type="submit" value="Search" name="search" class="btn btn-primary">
                                <i class="fa fa-search"></i>
                            </button>
                        </span><!-- input-group-btn Ends -->
                    </div><!-- input-group Ends -->
                </form><!-- navbar-form Ends -->
            </div><!-- collapse clearfix Ends -->


        </div><!-- navbar-collapse collapse Ends -->
    </div><!-- container Ends -->
</div><!-- navbar navbar-default Ends -->


<div id="content"><!-- content Starts -->
    <div class="container"><!-- container Starts -->
        <div class="col-md-12"><!--- col-md-12 Starts -->
          <?php if (!isset($_SESSION['customer_email'])) { ?>
              <ul class="breadcrumb"><!-- breadcrumb Starts -->
                  <li><a href="index.php">Home</a></li>
                  <li>Login Details</li>
              </ul><!-- breadcrumb Ends -->

          <?php } else { ?>
              <ul class="breadcrumb"><!-- breadcrumb Starts -->
                  <li><a href="index.php">Home</a></li>
                  <li>Checkout Details</li>
              </ul><!-- breadcrumb Ends -->
              <nav class="checkout-breadcrumbs text-center">
                  <a href="cart.php">Shopping Cart</a>
                  <i class="fa fa-chevron-right"></i>
                  <a href="checkout.php" class="active">Checkout Details</a>
                  <i class="fa fa-chevron-right"></i>
                  <a href="#"> Order Complete </a>
              </nav>
          <?php } ?>
        </div><!--- col-md-12 Ends -->
        <div class="col-md-12"><!-- col-md-12 Starts -->
          <?php
          if (!isset($_SESSION['customer_email'])) {
            include("customer/customer_login.php");
          } else {
            include("review_order.php");
          }
          ?>
        </div><!-- col-md-12 Ends -->
    </div><!-- container Ends -->
</div><!-- content Ends -->

<?php include("includes/footer.php"); ?>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>