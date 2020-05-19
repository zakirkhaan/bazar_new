<?php
session_start();
include("includes/db.php");
include("functions/functions.php");


if(isset($_SESSION['customer_email'])){
    echo " <script> window.open('checkout.php','_self'); </script> ";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bazaar </title>
    <link href="http://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
    <link href="styles/bootstrap.min.css" rel="stylesheet">
    <link href="styles/style.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
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
            <ul class="breadcrumb"><!-- breadcrumb Starts -->
                <li>
                    <a href="index.php">Home</a>
                </li>

                <li>Register</li>
            </ul><!-- breadcrumb Ends -->
        </div><!--- col-md-12 Ends -->

        <div class="col-md-12"><!-- col-md-12 Starts -->
            <div class="box"><!-- box Starts -->
                <div class="box-header"><!-- box-header Starts -->
                    <div style="text-align: center;">
                        <h3> Please enter your email address. You will receive a link to create a new password via
                            email. </h3>
                    </div>
                </div><!-- box-header Ends -->
                <div align="center"><!-- center div Starts -->
                    <form action="" method="post"><!-- form Starts -->
                        <input type="text" class="form-control" name="c_email" placeholder="Enter Your Email">
                        <br>
                        <input type="submit" name="forgot_pass" class="btn btn-primary" value="Submit">
                    </form><!-- form Ends -->
                </div><!-- center div Ends -->
            </div><!-- box Ends -->
        </div><!-- col-md-12 Ends -->
    </div><!-- container Ends -->
</div><!-- content Ends -->


<?php
include("includes/footer.php");
?>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>

<?php
if (isset($_POST['forgot_pass'])) {
  $c_email = $_POST['c_email'];
  $sel_c = "select * from customers where customer_email='$c_email'";
  $run_c = mysqli_query($con, $sel_c);
  $count_c = mysqli_num_rows($run_c);
  $row_c = mysqli_fetch_array($run_c);
  $c_name = $row_c['customer_name'];
  $c_pass = $row_c['customer_pass'];

  if ($count_c == 0) {
    echo "<script> alert('Sorry, We do not have your email') </script>";
    exit();
  } else {
    $message = "<h3> Someone has requested a password reset for the following account: </h3><h3> Site Url : www.bazar.com </h3>
<h3> Email Address : $c_email  </h3>
<h3> Name : $c_name </h3>
<h3> If this was a mistake, just ignore this email and nothing will happen. </h3>
<h3>
<a href='http://localhost/bazar/change_password.php?change_password=$c_pass'>
 
To Reset/Change Your Password, Click Here
 
</a>

</h3>

";

    $from = "zakirkhaan2@gmail.com";

    $subject = "!Important Bazar Your Password Reset";

    $headers = "From: $from\r\n";

    $headers .= "Content-type: text/html\r\n";

    mail($c_email, $subject, $message, $headers);

    echo "<script> alert('Your Password Reset/Change Link Has Been Sent To Your Email,Please Check Your Inbox.'); </script>";

    echo "<script>window.open('checkout.php','_self')</script>";

  }

}

?>

