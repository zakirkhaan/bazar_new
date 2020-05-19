<?php
session_start();
include("includes/db.php");
include("functions/functions.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bazaar </title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
            0% {
                transform: translate(0, 0);
            }
            100% {
                transform: translate(-100%, 0);
            }
    </style>
</head>

<body>
<div class="marquee">
    <p style="background-color:black; text-align: center; font-size: 17px; margin-top: 0; margin-bottom: 1px;">
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
            <a href="#">Shopping Cart Total Price: <?php total_price(); ?>, Total Items <?php items(); ?></a>
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

                <h2 style="color: black;font-size: 35px;border:1px; margin:2px;">
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

                    <li class="active">
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

                <li>Search Results For "<?php echo @$_GET['user_query']; ?>"</li>

            </ul><!-- breadcrumb Ends -->

        </div><!--- col-md-12 Ends -->

        <div class="col-md-12"><!-- col-md-12 Starts --->

            <div class="row" id="Products"><!-- row Starts -->

              <?php

              if (isset($_GET['search'])) {

                $user_keyword = $_GET['user_query'];

                $select_products = "select * from products where product_keywords like '%$user_keyword%' or product_title like '%$user_keyword%' or product_seo_desc like '%$user_keyword%'";

                $run_products = mysqli_query($con, $select_products);

                $count = mysqli_num_rows($run_products);

                if ($count == 0) {

                  echo "

<div class='box'>

<h2>No Search Results Found</h2>

</div>

";

                } else {

                  while ($row_products = mysqli_fetch_array($run_products)) {

                    $pro_id = $row_products['product_id'];

                    $pro_title = $row_products['product_title'];

                    $pro_price = $row_products['product_price'];

                    $pro_img1 = $row_products['product_img1'];

                    $pro_label = $row_products['product_label'];

                    $manufacturer_id = $row_products['manufacturer_id'];

                    $get_manufacturer = "select * from manufacturers where manufacturer_id='$manufacturer_id'";

                    $run_manufacturer = mysqli_query($db, $get_manufacturer);

                    $row_manufacturer = mysqli_fetch_array($run_manufacturer);

                    $manufacturer_name = $row_manufacturer['manufacturer_title'];

                    $pro_psp_price = $row_products['product_psp_price'];

                    $pro_url = $row_products['product_url'];

                    $product_type = $row_products['product_type'];

                    if ($product_type != "variable_product") {

                      if ($pro_psp_price != 0) {

                        $product_price = "<del>Rs:$pro_price</del>";

                        $product_psp_price = " | Rs:$pro_psp_price";

                      } else {

                        $product_psp_price = "";

                        $product_price = "Rs:$pro_price";

                      }

                    } else {

                      $select_min_product_price = "select min(product_price) as product_price from product_variations where product_id='$pro_id' and product_price!='0'";

                      $run_min_product_price = mysqli_query($db, $select_min_product_price);

                      $row_min_product_price = mysqli_fetch_array($run_min_product_price);

                      $minimum_product_price = $row_min_product_price["product_price"];

                      $select_max_product_price = "select max(product_price) as product_price from product_variations where product_id='$pro_id'";

                      $run_max_product_price = mysqli_query($db, $select_max_product_price);

                      $row_max_product_price = mysqli_fetch_array($run_max_product_price);

                      $maximum_product_price = $row_max_product_price["product_price"];

                      $product_price = "Rs:$minimum_product_price - Rs:$maximum_product_price";

                      $product_psp_price = "";

                    }

                    if ($pro_label == "") {

                      $product_label = "";

                    } else {

                      $product_label = "

<a class='label sale' href='#' style='color:black;'>

<div class='thelabel'>$pro_label</div>

<div class='label-background'> </div>

</a>

";

                    }

                    $reviews_rating = array();

                    $select_product_reviews = "select * from reviews where product_id='$pro_id' and review_status!='pending'";

                    $run_product_reviews = mysqli_query($db, $select_product_reviews);

                    $count_product_reviews = mysqli_num_rows($run_product_reviews);

                    if ($count_product_reviews != 0) {

                      while ($row_product_reviews = mysqli_fetch_array($run_product_reviews)) {

                        $product_review_rating = $row_product_reviews['review_rating'];

                        array_push($reviews_rating, $product_review_rating);

                      }

                      $total = array_sum($reviews_rating);

                      $product_rating = $total / count($reviews_rating);

                      $star_product_rating = substr($product_rating, 0, 1);

                    } else {

                      $star_product_rating = 0;

                    }

                    $product_rating_stars = "";

                    for ($product_i = 0; $product_i < $star_product_rating; $product_i++) {

                      $product_rating_stars .= " <img class='rating' src='images/star_full_big.png'> ";

                    }

                    for ($product_i = $star_product_rating; $product_i < 5; $product_i++) {

                      $product_rating_stars .= " <img class='rating' src='images/star_blank_big.png'> ";

                    }

                    $product_rating_stars .= " ($count_product_reviews)";

                    $select_product_stock = "select * from products_stock where product_id='$pro_id'";

                    $run_product_stock = mysqli_query($db, $select_product_stock);

                    if ($product_type != "variable_product") {

                      $row_product_stock = mysqli_fetch_array($run_product_stock);

                      $stock_status = $row_product_stock["stock_status"];

                    } else {

                      $instock = 0;

                      while ($row_product_stock = mysqli_fetch_array($run_product_stock)) {

                        $stock_status = $row_product_stock["stock_status"];

                        if ($stock_status == "instock") {

                          $instock += $row_product_stock["stock_quantity"];

                        }

                      }

                    }

                    if (

                      ($product_type != "variable_product" and $stock_status == "outofstock") or
                      ($product_type == "variable_product" and $instock == 0)

                    ) {

                      $outofstock_label = " <div class='out-of-stock-label'>Out of stock</div> ";

                    } else {

                      $outofstock_label = "";

                    }

                    echo "

<div class='col-md-3 col-sm-6 center-responsive' >

<div class='product' >

<a href='$pro_url'>

<img src='admin_area/product_images/$pro_img1' class='product-img'>

$outofstock_label

</a>

<div class='text' >

<center>

<p class='btn btn-primary'> $manufacturer_name </p>

</center>

<hr>

<h3 class='product-title'> <a href='$pro_url'> $pro_title </a> </h3>

<p class='star-rating'> $product_rating_stars </p>

<p class='price' > $product_price $product_psp_price </p>

<p class='buttons' >

<a href='$pro_url' class='btn btn-default' >View details</a>

<a href='$pro_url' class='btn btn-primary'>

<i class='fa fa-shopping-cart'></i> Add to cart

</a>


</p>

</div>

$product_label


</div>

</div>

";

                  }

                }

              }
              ?>

            </div><!-- row Ends -->

        </div><!-- col-md-9 Ends --->

    </div><!-- container Ends -->

</div><!-- content Ends -->


<?php

include("includes/footer.php");

?>

<script src="js/jquery.min.js"></script>

<script src="js/bootstrap.min.js"></script>


</body>

</html>