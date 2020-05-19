<?php session_start(); include("includes/db.php"); include("functions/functions.php"); ?>
<?php
//getting the pro_id from shop.php page and then selecting the products through product it//
$product_id = @$_GET['pro_id'];
$get_product = "select * from products where product_url='$product_id'";
$run_product = mysqli_query($con, $get_product);
$check_product = mysqli_num_rows($run_product);
if ($check_product == 0) {
  echo "<script> window.open('index.php','_self') </script>";
} else {
  $row_product = mysqli_fetch_array($run_product);
  $p_cat_id = $row_product['p_cat_id'];
  $pro_id = $row_product['product_id'];
  $pro_title = $row_product['product_title'];
  $pro_price = $row_product['product_price'];
  $pro_desc = $row_product['product_desc'];
  $pro_img1 = $row_product['product_img1'];
  $pro_img2 = $row_product['product_img2'];
  $pro_img3 = $row_product['product_img3'];
  $pro_label = $row_product['product_label'];
  $pro_psp_price = $row_product['product_psp_price'];
  $pro_features = $row_product['product_features'];
  $pro_video = $row_product['product_video'];
  $status = $row_product['status'];
  $pro_url = $row_product['product_url'];
  $pro_type = $row_product['product_type'];

  if ($pro_label == "") {

    $product_label = "";

  } else {

    $product_label = "<a class='label sale' href='#' style='color:black;'><div class='thelabel'>$pro_label</div><div class='label-background'> </div></a>";

  }
  $pro_keywords = $row_product['product_keywords'];
  $pro_seo_desc = $row_product['product_seo_desc'];
  $vendor_id = $row_product['vendor_id'];
  if (strpos($vendor_id, "admin_") !== false) {
    $admin_id = trim($vendor_id, "admin_");
    $select_admin = "select * from admins where admin_id='$admin_id'";
    $run_admin = mysqli_query($con, $select_admin);
    $row_admin = mysqli_fetch_array($run_admin);
    $vendor_name = $row_admin['admin_name'];
    $vendor_username = $row_admin['admin_username'];
  } else {
    $select_customer = "select * from customers where customer_id='$vendor_id'";
    $run_customer = mysqli_query($con, $select_customer);
    $row_customer = mysqli_fetch_array($run_customer);
    $vendor_name = $row_customer['customer_name'];
    $vendor_username = $row_customer['customer_username'];
  }
  //getting product categories through product category id
  $get_p_cat = "select * from product_categories where p_cat_id='$p_cat_id'";
  $run_p_cat = mysqli_query($con, $get_p_cat);
  $row_p_cat = mysqli_fetch_array($run_p_cat);
  $p_cat_title = $row_p_cat['p_cat_title'];
  $reviews_rating = array();
  $select_product_reviews = "select * from reviews where product_id='$pro_id' and NOT (review_status='trash' or review_status='pending')";
  $run_product_reviews = mysqli_query($con, $select_product_reviews);
  $count_product_reviews = mysqli_num_rows($run_product_reviews);
//counting the no of product reviews//
  if ($count_product_reviews != 0) {
    while ($row_product_reviews = mysqli_fetch_array($run_product_reviews)) {
      $product_review_rating = $row_product_reviews['review_rating'];
      array_push($reviews_rating, $product_review_rating);
    }

    $total = array_sum($reviews_rating);
    $product_rating = $total / count($reviews_rating);
    $star_product_rating = substr($product_rating, 0, 1);

  } else {
    //setting the value of product rating and star product rating to o by default
    $product_rating = 0;
    $star_product_rating = 0;

  }
//function for products start ratings
  function get_star_rating($star)
  {
    global $con;
    global $pro_id;
    $select_product_reviews = "select * from reviews where product_id='$pro_id' and review_rating='$star' and NOT (review_status='trash' or review_status='pending')";
    $run_product_reviews = mysqli_query($con, $select_product_reviews);
    echo $count_product_reviews = mysqli_num_rows($run_product_reviews);
  }
//function for getting percentage
  function get_star_percentage($star)
  {
    global $con;
    global $pro_id;

    $select_product_reviews = "SELECT review_rating, round(count( * ) *100 / (SELECT count( * ) FROM `reviews`)) AS percentage FROM reviews where product_id='$pro_id' and review_rating='$star' and NOT ( review_status='trash' or review_status='pending') GROUP BY review_rating";
    $run_product_reviews = mysqli_query($con, $select_product_reviews);
    $row_product_reviews = mysqli_fetch_array($run_product_reviews);
    echo $percentage = $row_product_reviews["percentage"];
  }

  if ($pro_type != "variable_product") {

    $select_product_stock = "select * from products_stock where product_id='$pro_id'";

    $run_product_stock = mysqli_query($con, $select_product_stock);

    $row_product_stock = mysqli_fetch_array($run_product_stock);

    $stock_status = $row_product_stock["stock_status"];

    $enable_stock = $row_product_stock["enable_stock"];

    $stock_quantity = $row_product_stock["stock_quantity"];

    $allow_backorders = $row_product_stock["allow_backorders"];

  }

  if (isset($_SESSION['customer_email'])) {

    $customer_email = $_SESSION['customer_email'];

    $select_customer = "select * from customers where customer_email='$customer_email'";

    $run_customer = mysqli_query($con, $select_customer);

    $row_customer = mysqli_fetch_array($run_customer);

    $customer_id = $row_customer['customer_id'];

  } else {

    $customer_id = 0;

  }

  if ((isset($_SESSION['customer_email']) and $customer_id != $vendor_id) or !isset($_SESSION['customer_email'])) {

    if (!isset($_SESSION['product_views'])) {

      $product_views = array();

      $update_product_views = "update products set product_views=product_views+1 where product_id='$pro_id'";

      $run_update_product_views = mysqli_query($con, $update_product_views);

      array_push($product_views, $pro_id);

      $_SESSION['product_views'] = $product_views;

    } else {

      if (!in_array($pro_id, $_SESSION['product_views'])) {

        $product_views = $_SESSION['product_views'];

        $update_product_views = "update products set product_views=product_views+1 where product_id='$pro_id'";

        $run_update_product_views = mysqli_query($con, $update_product_views);

        array_push($product_views, $pro_id);

        $_SESSION['product_views'] = $product_views;

      }

    }

  }

  if (

    (isset($_SESSION['customer_email']) and $customer_id != $vendor_id)

    or !isset($_SESSION['customer_email'])

  ) {

    $ip_address = getRealUserIp();

    $select_all_customer_history = "select * from customers_history where ip_address='$ip_address' and customer_id='$customer_id'";

    $run_all_customer_history = mysqli_query($con, $select_all_customer_history);

    $count_all_customer_history = mysqli_num_rows($run_all_customer_history);

    if ($count_all_customer_history > 3) {

      $delete_customer_history = "delete from customers_history where ip_address='$ip_address' and customer_id='$customer_id' LIMIT 1";

      $run_delete_customer_history = mysqli_query($con, $delete_customer_history);

    }

    $select_customer_history = "select * from customers_history where ip_address='$ip_address' and customer_id='$customer_id' and product_id='$pro_id'";

    $run_customer_history = mysqli_query($con, $select_customer_history);

    $count_customer_history = mysqli_num_rows($run_customer_history);

    if ($count_customer_history == 0) {

      $insert_customer_history = "insert into customers_history (ip_address,customer_id,product_id) values ('$ip_address','$customer_id','$pro_id')";

      $run_customer_history = mysqli_query($con, $insert_customer_history);

    } else {

      $delete_customer_history = "delete from customers_history where ip_address='$ip_address' and customer_id='$customer_id' and product_id='$pro_id'";

      $run_delete_customer_history = mysqli_query($con, $delete_customer_history);

      $insert_customer_history = "insert into customers_history (ip_address,customer_id,product_id) values ('$ip_address','$customer_id','$pro_id')";

      $run_customer_history = mysqli_query($con, $insert_customer_history);

    }

  }

  $select_store_settings = "select * from store_settings where vendor_id='$vendor_id'";

  $run_store_settings = mysqli_query($con, $select_store_settings);

  $row_store_settings = mysqli_fetch_array($run_store_settings);

  @$store_name = $row_store_settings["store_name"];

  @$store_country = $row_store_settings["store_country"];

  @$store_address_1 = $row_store_settings["store_address_1"];

  @$store_address_2 = $row_store_settings["store_address_2"];

  @$store_state = $row_store_settings["store_state"];

  @$store_city = $row_store_settings["store_city"];

  @$store_postcode = $row_store_settings["store_postcode"];

  $products_ids = array();

  $select_products = "select * from products where vendor_id='$vendor_id'";

  $run_products = mysqli_query($db, $select_products);

  while ($row_products = mysqli_fetch_array($run_products)) {

    $product_id = $row_products["product_id"];

    array_push($products_ids, $product_id);

  }

  $products_ids = implode(",", $products_ids);

  $vendor_reviews_rating = array();

  $select_vendor_products_reviews = "select * from reviews where product_id in ($products_ids) and NOT ( review_status='trash' or review_status='pending')";

  $run_vendor_products_reviews = mysqli_query($con, $select_vendor_products_reviews);

  $count_vendor_products_reviews = mysqli_num_rows($run_vendor_products_reviews);

  if ($count_vendor_products_reviews != 0) {

    while ($row_vendor_products_reviews = mysqli_fetch_array($run_vendor_products_reviews)) {

      $product_review_rating = $row_vendor_products_reviews['review_rating'];

      array_push($vendor_reviews_rating, $product_review_rating);

    }

    $total = array_sum($vendor_reviews_rating);

    $vendor_rating = $total / count($vendor_reviews_rating);

    $star_vendor_rating = substr($vendor_rating, 0, 1);

  } else {

    $vendor_rating = 0;

    $star_vendor_rating = 0;

  }

  ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title> <?php echo $pro_title; ?> </title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="<?php echo $pro_seo_desc; ?>">
        <meta name="keywords" content="<?php echo $pro_keywords; ?>">
        <meta name="author" content="<?php echo $vendor_name; ?>">
        <link href="http://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
        <link href="styles/bootstrap.min.css" rel="stylesheet">
        <link href="styles/style.css" rel="stylesheet">
        <link href="styles/star-rating.min.css" rel="stylesheet">
        <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <script src="js/jquery.min.js"></script>
        <script src="js/star-rating.min.js"></script>
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

                <a class="btn btn-primary navbar-btn right" href="cart.php">
                    <!-- btn btn-primary navbar-btn right Starts -->
                    <i class="fa fa-shopping-cart"></i>

                    <span> <?php items(); ?> items in cart </span>

                </a><!-- btn btn-primary navbar-btn right Ends -->

                <div class="navbar-collapse collapse right"><!-- navbar-collapse collapse right Starts -->
                    <button class="btn navbar-btn btn-primary" type="button" data-toggle="collapse"
                            data-target="#search">
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

                    <li><a href="shop.php">Shop</a></li>

                    <li>

                        <a href="shop.php?p_cat=<?php echo $p_cat_id; ?>"> <?php echo $p_cat_title; ?> </a>

                    </li>

                    <li> <?php echo $pro_title; ?> </li>

                </ul><!-- breadcrumb Ends -->
            </div><!--- col-md-12 Ends -->

            <div class="col-md-12"><!-- col-md-12 Starts -->
                <div class="row" id="productMain"><!-- row Starts -->
                    <div class="col-sm-6"><!-- col-sm-6 Starts -->
                        <div id="mainImage"><!-- mainImage Starts -->
                            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                                <ol class="carousel-indicators"><!-- carousel-indicators Starts -->
                                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                                  <?php if (!empty($pro_img2)) { ?>
                                      <li data-target="#myCarousel" data-slide-to="1"></li>
                                  <?php } ?>

                                  <?php if (!empty($pro_img3)) { ?>

                                      <li data-target="#myCarousel" data-slide-to="2"></li>

                                  <?php } ?>

                                </ol><!-- carousel-indicators Ends -->

                                <div class="carousel-inner"><!-- carousel-inner Starts -->

                                    <div class="item active">
                                       <div style="align:center;">
                                            <img src="admin_area/product_images/<?php echo $pro_img1; ?>"
                                                 class="img-responsive">
                                        </div>
                                    </div>

                                  <?php if (!empty($pro_img2)) { ?>

                                      <div class="item">
                                           <div style="align:center;">
                                              <img src="admin_area/product_images/<?php echo $pro_img2; ?>"
                                                   class="img-responsive">
                                        </div>
                                      </div>

                                  <?php } ?>

                                  <?php if (!empty($pro_img3)) { ?>

                                      <div class="item">
                                       <div align="center">
                                              <img src="admin_area/product_images/<?php echo $pro_img3; ?>"
                                                   class="img-responsive">
                                     </div>
                                      </div>

                                  <?php } ?>

                                </div><!-- carousel-inner Ends -->

                                <a href="#myCarousel" class="left carousel-control" data-slide="prev">
                                    <!-- left carousel-control Starts -->
                                    <span class="glyphicon glyphicon-chevron-left"> </span>
                                    <span class="sr-only"> Previous </span>
                                </a><!-- left carousel-control Ends -->

                                <a class="right carousel-control" href="#myCarousel" data-slide="next">
                                    <!-- right carousel-control Starts -->
                                    <span class="glyphicon glyphicon-chevron-right"> </span>

                                    <span class="sr-only"> Next </span>

                                </a><!-- right carousel-control Ends -->

                            </div>
                        </div><!-- mainImage Ends -->

                      <?php echo $product_label; ?>

                    </div><!-- col-sm-6 Ends -->

                    <div class="col-sm-6"><!-- col-sm-6 Starts -->

                        <div class="box"><!-- box Starts -->

                            <div class="detail-product-info">

                                <h2> <?php echo $pro_title; ?> </h2>

                                <span class="text-muted"> By </span>

                                <a href="store/<?php echo $vendor_username; ?>"
                                   class="text-muted"><?php echo $vendor_name; ?></a>

                                &nbsp;

                              <?php for ($product_i = 0; $product_i < $star_product_rating; $product_i++) { ?>

                                  <img class="rating" src="images/star_full_small.png">

                              <?php } ?>

                              <?php for ($product_i = $star_product_rating; $product_i < 5; $product_i++) { ?>

                                  <img class="rating" src="images/star_blank_small.png">

                              <?php } ?>

                                <span class="text-muted"> &nbsp; (<?php echo $count_product_reviews; ?> reviews) &nbsp;&nbsp; </span>

                                <a href="#write-a-review" class="text-muted"> Write A Review </a>

                                <hr>

                            </div>

                            <form action="" method="post" class="form-horizontal"><!-- form-horizontal Starts -->

                              <?php

                              if ($pro_type == "variable_product") {

                                $select_product_variations = "select * from product_variations where product_id='$pro_id' and not product_type='default_attributes_variation'";

                                $run_product_variations = mysqli_query($con, $select_product_variations);

                                $count_product_variations = mysqli_num_rows($run_product_variations);

                                if ($count_product_variations != 0) {

                                  ?>

                                    <div id="variable-product-div"><!-- variable-product-div Starts -->

                                      <?php

                                      $select_default_variation = "select * from product_variations where product_id='$pro_id' and product_type='default_attributes_variation'";

                                      $run_default_variation = mysqli_query($con, $select_default_variation);

                                      $count_default_variation = mysqli_num_rows($run_default_variation);

                                      if ($count_default_variation != 0) {

                                        $row_default_variation = mysqli_fetch_array($run_default_variation);

                                        $default_variation_id = $row_default_variation["variation_id"];

                                      } else {

                                        $default_variation_id = 0;

                                      }

                                      $default_variation_meta_array = array();

                                      $default_attributes = array();

                                      if ($count_default_variation != 0) {

                                        $select_default_variations_meta = "select * from variations_meta where variation_id='$default_variation_id' order by 1 desc";

                                        $run_default_variations_meta = mysqli_query($con, $select_default_variations_meta);

                                        $i = 0;

                                        while ($row_default_variations_meta = mysqli_fetch_array($run_default_variations_meta)) {

                                          $meta_key = $row_default_variations_meta["meta_key"];

                                          $meta_value = $row_default_variations_meta["meta_value"];

                                          $default_attributes[$meta_key] = $meta_value;

                                          $i++;

                                          if (!empty($meta_value)) {

                                            $default_variation_meta_array[$i]["meta_key"] = "meta_key='$meta_key'";

                                            $default_variation_meta_array[$i]["meta_value"] = "meta_value='$meta_value'";

                                          }

                                        }

                                      }

                                      $product_variations = array();

                                      $select_product_variations = "select * from product_variations where product_id='$pro_id' and not product_type='default_attributes_variation'";

                                      $run_product_variations = mysqli_query($con, $select_product_variations);

                                      while ($row_product_variations = mysqli_fetch_array($run_product_variations)) {

                                        $variation_id = $row_product_variations["variation_id"];

                                        array_push($product_variations, $variation_id);

                                      }

                                      $variation_ids = implode(",", $product_variations);

                                      $attribute_i = 0;

                                      $select_product_attributes = "select * from product_attributes where product_id='$pro_id'";

                                      $run_product_attributes = mysqli_query($con, $select_product_attributes);

                                      while ($row_product_attributes = mysqli_fetch_array($run_product_attributes)) {

                                        $attribute_i++;

                                        $attribute_name = $row_product_attributes["attribute_name"];

                                        $meta_key = str_replace(' ', '_', strtolower($attribute_name));

                                        $select_default_variation_meta = "select * from variations_meta where variation_id='$default_variation_id' and meta_key='$meta_key'";

                                        $run_default_variation_meta = mysqli_query($con, $select_default_variation_meta);

                                        $row_default_variation_meta = mysqli_fetch_array($run_default_variation_meta);

                                        $default_meta_value = $row_default_variation_meta["meta_value"];

                                        $attribute_variation_meta_array = $default_variation_meta_array;

                                        if (!empty($default_meta_value)) {

                                          unset($attribute_variation_meta_array[$attribute_i]);

                                          array_unshift($attribute_variation_meta_array, "");

                                          unset($attribute_variation_meta_array[0]);

                                        }

                                        if (!empty($attribute_variation_meta_array)) {

                                          $variation_meta_variation_ids = array();

                                          $loop_number = 0;

                                          foreach ($attribute_variation_meta_array as $array_id => $variation_meta) {

                                            $loop_number++;

                                            $variation_meta = implode(" and ", $variation_meta);

                                            $select_variations_meta = "select DISTINCT variation_id from variations_meta where $variation_meta and variation_id IN ($variation_ids) and not variation_id='$default_variation_id'";

                                            $run_variations_meta = mysqli_query($con, $select_variations_meta);

                                            $i = 0;

                                            while ($row_variations_meta = mysqli_fetch_array($run_variations_meta)) {

                                              $i++;

                                              $variation_id = $row_variations_meta["variation_id"];

                                              if ($loop_number == 1) {

                                                $variation_meta_variation_ids[$array_id][$i] = $variation_id;

                                              } else {

                                                $prev_array_id = $loop_number - 1;

                                                if (in_array($variation_id, $variation_meta_variation_ids[$prev_array_id])) {

                                                  $variation_meta_variation_ids[$array_id][$i] = $variation_id;

                                                }

                                              }

                                            }

                                          }

                                          $array_end = end($variation_meta_variation_ids);

                                          if ($array_end) {

                                            $attribute_variation_ids = implode(",", $array_end);

                                          }

                                        } else {

                                          $attribute_variation_ids = $variation_ids;

                                        }

                                        ?>

                                          <div class="form-group"><!-- form-group Starts -->

                                              <label class="col-lg-4 col-md-3 control-label"> <?php echo $attribute_name; ?> </label>

                                              <div class="col-lg-6 col-md-9"><!-- col-lg-6 col-md-9 Starts -->

                                                  <select name="<?php echo $meta_key; ?>"
                                                          class="form-control attribute-select" required>

                                                      <option value=""> Choose an option</option>

                                                    <?php

                                                    if (isset($attribute_variation_ids)) {

                                                      $select_variations_meta = "select DISTINCT meta_value from variations_meta where variation_id IN ($attribute_variation_ids) and meta_key='$meta_key'";

                                                    } else {

                                                      $select_variations_meta = "select DISTINCT meta_value from variations_meta where variation_id IN ($variation_ids) and meta_key='$meta_key'";

                                                    }

                                                    $run_variations_meta = mysqli_query($con, $select_variations_meta);

                                                    while ($row_variations_meta = mysqli_fetch_array($run_variations_meta)) {

                                                      $meta_value = $row_variations_meta["meta_value"];

                                                      if ($default_meta_value == $meta_value) {

                                                        echo "<option selected>$meta_value</option>";

                                                      } else {

                                                        echo "<option>$meta_value</option>";

                                                      }

                                                    }

                                                    ?>

                                                  </select>

                                              </div><!-- col-lg-6 col-md-9 Ends -->

                                          </div><!-- form-group Ends -->

                                        <?php

                                      }

                                      $variation_meta_variation_ids = array();

                                      $loop_number = 0;

                                      foreach ($default_variation_meta_array as $array_id => $variation_meta) {

                                        $loop_number++;

                                        $variation_meta = implode(" and ", $variation_meta);

                                        $select_variations_meta = "select DISTINCT variation_id from variations_meta where $variation_meta and variation_id IN ($variation_ids) and not variation_id='$default_variation_id'";

                                        $run_variations_meta = mysqli_query($con, $select_variations_meta);

                                        $i = 0;

                                        while ($row_variations_meta = mysqli_fetch_array($run_variations_meta)) {

                                          $i++;

                                          $variation_id = $row_variations_meta["variation_id"];

                                          if ($loop_number == 1) {

                                            $variation_meta_variation_ids[$array_id][$i] = $variation_id;

                                          } else {

                                            $prev_array_id = $loop_number - 1;

                                            if (in_array($variation_id, $variation_meta_variation_ids[$prev_array_id])) {

                                              $variation_meta_variation_ids[$array_id][$i] = $variation_id;

                                            }

                                          }

                                        }

                                      }

                                      $array_end = end($variation_meta_variation_ids);

                                      if ($array_end) {

                                        $current_variation_id = array_values($array_end)["0"];

                                      }

                                      ?>

                                      <?php if ($array_end) { ?>

                                          <input type="hidden" name="variation_id"
                                                 value="<?php echo $current_variation_id; ?>">

                                      <?php } ?>

                                        <script>

                                            $(document).ready(function () {

                                                $(".attribute-select").on("change", function () {

                                                    var i  =  i + 0;

                                                    var product_attributes = {};

                                                    var selected_attributes = {};

                                                  <?php

                                                  $select_product_attributes = "select * from product_attributes where product_id='$pro_id'";

                                                  $run_product_attributes = mysqli_query($con, $select_product_attributes);

                                                  while($row_product_attributes = mysqli_fetch_array($run_product_attributes)){

                                                  $attribute_id = $row_product_attributes["attribute_id"];

                                                  $meta_key = str_replace(' ', '_', strtolower($row_product_attributes["attribute_name"]));

                                                  ?>

                                                    var i = i + 1;

                                                    product_attributes[i] = {};

                                                    if ($("select[name='<?php echo $meta_key; ?>']").val() != "") {

                                                        var attribute_key = "meta_key='<?php echo $meta_key; ?>'";

                                                        var attribute_value = "meta_value='" + $("select[name='<?php echo $meta_key; ?>']").val() + "'";

                                                        product_attributes[i]["meta_key"] = attribute_key;

                                                        product_attributes[i]["meta_value"] = attribute_value;

                                                    }

                                                    var selected_option = $("select[name='<?php echo $meta_key; ?>']").val();

                                                    selected_attributes["<?php echo $meta_key; ?>"] = selected_option;

                                                  <?php } ?>

                                                    $(".box").addClass("table-loader");

                                                    var variation_ids = "<?php echo $variation_ids; ?>";

                                                    $.ajax({

                                                        method: "POST",

                                                        url: "load_product_variations.php",

                                                        data: {
                                                            product_id: <?php echo $pro_id; ?>,
                                                            default_variation_id: <?php echo $default_variation_id; ?>,
                                                            variation_ids: variation_ids,
                                                            selected_attributes: selected_attributes,
                                                            variation_meta_array: product_attributes
                                                        },

                                                        success: function (data) {

                                                            $("#variable-product-div").html(data);

                                                            $(".box").removeClass("table-loader");

                                                        }

                                                    });

                                                });

                                            });

                                        </script>

                                    </div><!-- variable-product-div Ends -->

                                  <?php

                                  if ($array_end) {

                                    $select_product_stock = "select * from products_stock where product_id='$pro_id' and variation_id='$current_variation_id'";

                                    $run_product_stock = mysqli_query($con, $select_product_stock);

                                    $row_product_stock = mysqli_fetch_array($run_product_stock);

                                    $enable_stock = $row_product_stock["enable_stock"];

                                    $stock_status = $row_product_stock["stock_status"];

                                    $stock_quantity = $row_product_stock["stock_quantity"];

                                    $allow_backorders = $row_product_stock["allow_backorders"];

                                  }

                                }

                              }

                              ?>

                                <div class="form-group"><!-- form-group Starts -->

                                  <?php if ($status == "product") { ?>

                                      <label class="col-lg-4 col-md-3 control-label">Product Quantity </label>

                                  <?php } elseif ($status == "bundle") { ?>

                                      <label class="col-lg-4 col-md-3 control-label">Bundle Quantity </label>

                                  <?php } ?>

                                    <div class="col-lg-6 col-md-9"><!-- col-lg-6 col-md-9 Starts -->

                                      <?php

                                      if (($pro_type == "variable_product" and $count_product_variations != 0 and $array_end) or $pro_type != "variable_product") {

                                        $stock_manage = "show";

                                      } else {

                                        $stock_manage = "hide";

                                      }

                                      if ($stock_manage == "show" and $enable_stock == "yes" and $allow_backorders == "no") {

                                        ?>

                                          <input type="number" name="product_qty" class="form-control" value="1" min="1"
                                                 max="<?php echo $stock_quantity; ?>" required>

                                        <?php

                                      } elseif ($stock_manage == "show" and $enable_stock == "yes" and ($allow_backorders == "yes" or $allow_backorders == "notify")) {

                                        ?>

                                          <input type="number" name="product_qty" class="form-control" value="1" min="1"
                                                 required>

                                      <?php } elseif ($stock_manage == "show" and $enable_stock == "no") { ?>

                                          <input type="number" name="product_qty" class="form-control" value="1" min="1"
                                                 required>

                                      <?php } else { ?>

                                          <input type="number" name="product_qty" class="form-control" value="1" min="1"
                                                 required>

                                      <?php } ?>

                                        <script>

                                            $(document).ready(function () {

                                                function limitquantity() {

                                                    var value = parseInt($("input[name='product_qty']").val(), 10);

                                                    var max = parseInt($("input[name='product_qty']").attr("max"), 10);

                                                    var min = parseInt($("input[name='product_qty']").attr("min"), 10);

                                                    if (value > max) {

                                                        value = max;

                                                        $("input[name='product_qty']").val(value);

                                                    } else if (value < min) {

                                                        value = min;

                                                        $("input[name='product_qty']").val(value);

                                                    }

                                                }

                                                $("input[name='product_qty']").keyup(function () {

                                                    limitquantity();

                                                });

                                            });

                                        </script>

                                    </div><!-- col-lg-6 col-md-9 Ends -->

                                </div><!-- form-group Ends -->

                              <?php include("productIncludes/productIcons.php"); ?>

                              <?php

                              if ($pro_type == "physical_product" or $pro_type == "digital_product") {

                                if ($status == "product") {

                                  if ($pro_psp_price == 0) {

                                    echo "

<p class='price'>

Product Price : Rs:$pro_price

</p>

";

                                  } else {

                                    echo "

<p class='price'>

Product Price : <del> Rs:$pro_price </del><br>

Product Sale Price : Rs:$pro_psp_price

</p>

";

                                  }

                                } elseif ($status == "bundle") {

                                  if ($pro_psp_price == 0) {

                                    echo "

<p class='price'>

Bundle Price : Rs:$pro_price

</p>

";

                                  } else {

                                    echo "

<p class='price'>

Bundle Price : <del> Rs:$pro_price </del><br>

Bundle Sale Price : Rs:$pro_psp_price

</p>

";

                                  }

                                }

                              } elseif ($pro_type == "variable_product" and $count_product_variations != 0) {

// if($array_end){

                                foreach ($default_attributes as $attribute) {

                                  if (empty($attribute)) {

                                    $all_attributes_selected = false;

                                    break;

                                  } else {

                                    $all_attributes_selected = true;

                                  }

                                }

                                echo " <p class='price'> ";

                                if ($all_attributes_selected) {

                                  $select_product_variation = "select * from product_variations where product_id='$pro_id' and variation_id='$current_variation_id'";

                                  $run_product_variation = mysqli_query($con, $select_product_variation);

                                  $row_product_variation = mysqli_fetch_array($run_product_variation);

                                  $variation_product_img1 = $row_product_variation["product_img1"];

                                  $variation_product_price = $row_product_variation["product_price"];

                                  $variation_product_psp_price = $row_product_variation["product_psp_price"];

                                  $variation_product_weight = $row_product_variation["product_weight"];

                                  if ($status == "product") {

                                    if ($variation_product_psp_price == 0) {

                                      echo " Product Price : Rs:$variation_product_price ";

                                    } else {

                                      echo "

Product Price : <del> Rs:$variation_product_price </del><br>

Product Sale Price : Rs:$variation_product_psp_price

";

                                    }

                                  } elseif ($status == "bundle") {

                                    if ($variation_product_psp_price == 0) {

                                      echo " Bundle Price : Rs:$variation_product_price ";

                                    } else {

                                      echo "

Bundle Price : <del> Rs:$variation_product_price </del><br>

Bundle Sale Price : Rs:$variation_product_psp_price

";

                                    }

                                  }

                                }

                                echo "</p>";

// }else{

// $all_attributes_selected = false;

// }

                              }

                              ?>

                                <h4 class="stock-caption text-success text-center">

                                  <?php

                                  if ($pro_type == "physical_product" or $pro_type == "digital_product") {

                                    $stock_caption_display = "show";

                                  } elseif ($pro_type == "variable_product" and $count_product_variations != 0 and $all_attributes_selected) {

                                    $stock_caption_display = "show";

                                  } else {

                                    $stock_caption_display = "hide";

                                  }

                                  if ($stock_caption_display == "show") {

                                    ?>

                                    <?php if ($stock_manage == "show" and ($enable_stock == "yes" or $enable_stock == "no") and $stock_status == "outofstock") { ?>

                                          Out of stock

                                    <?php } elseif ($stock_manage == "show" and $enable_stock == "yes" and ($stock_status == "instock" or $stock_status == "onbackorder")) { ?>

                                      <?php if ($stock_quantity > 0 and ($allow_backorders == "yes" or $allow_backorders == "no")) { ?>

                                        <?php echo $stock_quantity; ?> items in stock

                                      <?php } elseif ($stock_quantity > 0 and $allow_backorders == "notify") { ?>

                                        <?php echo $stock_quantity; ?> items in stock (can be backordered)

                                      <?php } elseif ($stock_quantity < 1 and $allow_backorders == "notify") { ?>

                                              Available on backorder

                                      <?php } ?>

                                    <?php } ?>

                                  <?php } ?>

                                </h4>

                                <script>

                                    $(document).ready(function () {

                                        var _0x7acd = ["\x68\x69\x64\x65", "\x2E\x74\x65\x78\x74\x2D\x63\x65\x6E\x74\x65\x72\x2E\x62\x75\x74\x74\x6F\x6E\x73"];
                                        $(_0x7acd[1])[_0x7acd[0]](0);

//$(".text-center.buttons").hide(0);//

                                      <?php

                                      if ($pro_type == "variable_product" and $count_product_variations != 0 and !$all_attributes_selected) {

                                        $stock_status = "instock";

                                      }

                                      if($stock_manage == "show" and ($stock_status == "instock" or $stock_status == "onbackorder") or $stock_manage == "hide"){

                                      ?>

                                        var _0x29d5 = ["\x73\x68\x6F\x77", "\x2E\x74\x65\x78\x74\x2D\x63\x65\x6E\x74\x65\x72\x2E\x62\x75\x74\x74\x6F\x6E\x73"];
                                        $(_0x29d5[1])[_0x29d5[0]](0);

//$(".text-center.buttons").show(0);//

                                      <?php } ?>

                                    });

                                </script>

                                <p class="text-center buttons"><!-- text-center buttons Starts -->

                                    <button class="btn btn-primary" type="submit" name="add_cart">

                                        <i class="fa fa-shopping-cart"></i> Add to Cart

                                    </button>

                                    <button class="btn btn-primary" type="submit" name="add_wishlist">

                                        <i class="fa fa-heart"></i> Add to Wishlist

                                    </button>

                                  <?php

                                  if (isset($_POST['add_cart'])) {

                                    $ip_add = getRealUserIp();

                                    $p_id = $pro_id;

                                    $product_qty = $_POST['product_qty'];

                                    if ($pro_type == "physical_product" or $pro_type == "digital_product") {

                                      $select_cart = "select * from cart where ip_add='$ip_add' AND p_id='$p_id'";

                                      $run_cart = mysqli_query($con, $select_cart);

                                      if (mysqli_num_rows($run_cart) > 0) {

                                        echo "<script> alert('This Product is already added in cart'); </script>";

                                        echo "<script> window.open('$pro_url','_self'); </script>";

                                      } else {

                                        $select_price = "select * from products where product_id='$p_id'";

                                        $run_price = mysqli_query($con, $select_price);

                                        $row_price = mysqli_fetch_array($run_price);

                                        $pro_price = $row_price['product_price'];

                                        $pro_psp_price = $row_price['product_psp_price'];

                                        $pro_label = $row_price['product_label'];

                                        $pro_type = $row_price['product_type'];

                                        $pro_weight = $row_price['product_weight'];

                                        if ($pro_psp_price != 0) {

                                          $product_price = $pro_psp_price;

                                        } else {

                                          $product_price = $pro_price;

                                        }

                                        if ($pro_type == "physical_product") {

                                          $query = "insert into cart (p_id,ip_add,qty,p_price,product_weight,product_type) values ('$p_id','$ip_add','$product_qty','$product_price','$pro_weight','$pro_type')";

                                        } elseif ($pro_type == "digital_product") {

                                          $query = "insert into cart (p_id,ip_add,qty,p_price,product_weight,product_type) values ('$p_id','$ip_add','$product_qty','$product_price','0.0','$pro_type')";

                                        }

                                        $run_query = mysqli_query($con, $query);

                                      }

                                    } elseif ($pro_type == "variable_product") {

                                      $variation_id = $_POST['variation_id'];

                                      $select_cart_meta = "select * from cart_meta where ip_add='$ip_add' and product_id='$p_id' and meta_key='variation_id' and meta_value='#$variation_id'";

                                      $run_cart_meta = mysqli_query($con, $select_cart_meta);

                                      $count_cart_meta = mysqli_num_rows($run_cart_meta);

                                      if ($count_cart_meta > 0) {

                                        echo "

<script>

alert('This Combination Of Product Is Already Added In Cart, Please Choose A Different Combination.'); 
 
</script>

";

                                        echo "<script> window.open('$pro_url','_self'); </script>";

                                      } else {

                                        $select_price = "select * from products where product_id='$p_id'";

                                        $run_price = mysqli_query($con, $select_price);

                                        $row_price = mysqli_fetch_array($run_price);

                                        $pro_price = $row_price['product_price'];

                                        $pro_psp_price = $row_price['product_psp_price'];

                                        $pro_label = $row_price['product_label'];

                                        $pro_type = $row_price['product_type'];

                                        $pro_weight = $row_price['product_weight'];

                                        if ($pro_psp_price != 0) {

                                          $product_price = $pro_psp_price;

                                        } else {

                                          $product_price = $pro_price;

                                        }

                                        $select_product_variation = "select * from product_variations where product_id='$p_id' and variation_id='$variation_id'";

                                        $run_product_variation = mysqli_query($con, $select_product_variation);

                                        $row_product_variation = mysqli_fetch_array($run_product_variation);

                                        $variation_product_price = $row_product_variation["product_price"];

                                        $variation_product_psp_price = $row_product_variation["product_psp_price"];

                                        $variation_product_weight = $row_product_variation["product_weight"];

                                        $variation_product_type = $row_product_variation["product_type"];

                                        if ($variation_product_psp_price != 0) {

                                          $product_price = $variation_product_psp_price;

                                        } else {

                                          $product_price = $variation_product_price;

                                        }

                                        if ($variation_product_type == "physical_product") {

                                          $query = "insert into cart (p_id,ip_add,qty,p_price,product_weight,product_type) values ('$p_id','$ip_add','$product_qty','$product_price','$variation_product_weight','$variation_product_type')";

                                        } elseif ($variation_product_type == "digital_product") {

                                          $query = "insert into cart (p_id,ip_add,qty,p_price,product_weight,product_type) values ('$p_id','$ip_add','$product_qty','$product_price','0.0','$variation_product_type')";

                                        }

                                        $run_query = mysqli_query($con, $query);

                                        $cart_id = mysqli_insert_id($con);

                                        $select_product_variations = "select * from product_variations where product_id='$pro_id' and not product_type='default_attributes_variation'";

                                        $run_product_variations = mysqli_query($con, $select_product_variations);

                                        $count_product_variations = mysqli_num_rows($run_product_variations);

                                        if ($count_product_variations != 0) {

                                          $select_product_attributes = "select * from product_attributes where product_id='$p_id'";

                                          $run_product_attributes = mysqli_query($con, $select_product_attributes);

                                          while ($row_product_attributes = mysqli_fetch_array($run_product_attributes)) {

                                            $meta_key = str_replace(' ', '_', strtolower($row_product_attributes["attribute_name"]));

                                            $meta_value = $_POST[$meta_key];

                                            $insert_cart_meta = "insert into cart_meta (ip_add,cart_id,product_id,meta_key,meta_value) values ('$ip_add','$cart_id','$p_id','$meta_key','$meta_value')";

                                            $run_cart_meta = mysqli_query($con, $insert_cart_meta);

                                          }

                                          $insert_variation_id_meta = "insert into cart_meta (ip_add,cart_id,product_id,meta_key,meta_value) values ('$ip_add','$cart_id','$p_id','variation_id','#$variation_id')";

                                          $run_variation_id_meta = mysqli_query($con, $insert_variation_id_meta);

                                        }

                                      }

                                    }

                                    echo "<script> window.open('$pro_url','_self'); </script>";

                                  }

                                  if (isset($_POST['add_wishlist'])) {

                                    if (!isset($_SESSION['customer_email'])) {

                                      echo "<script>alert('You Must Login To Add Product In Wishlist')</script>";

                                      echo "<script>window.open('checkout.php','_self')</script>";

                                    } else {

                                      $customer_session = $_SESSION['customer_email'];

                                      $get_customer = "select * from customers where customer_email='$customer_session'";

                                      $run_customer = mysqli_query($con, $get_customer);

                                      $row_customer = mysqli_fetch_array($run_customer);

                                      $customer_id = $row_customer['customer_id'];

                                      if ($pro_type == "physical_product" or $pro_type == "digital_product") {

                                        $select_wishlist = "select * from wishlist where customer_id='$customer_id' AND product_id='$pro_id'";

                                        $run_wishlist = mysqli_query($con, $select_wishlist);

                                        $check_wishlist = mysqli_num_rows($run_wishlist);

                                        if ($check_wishlist == 1) {

                                          echo "<script>alert('This Product Has Been already Added In Wishlist')</script>";

                                          echo "<script>window.open('$pro_url','_self')</script>";

                                        } else {

                                          $insert_wishlist = "insert into wishlist (customer_id,product_id) values ('$customer_id','$pro_id')";

                                          $run_wishlist = mysqli_query($con, $insert_wishlist);

                                        }

                                      } elseif ($pro_type == "variable_product") {

                                        $variation_id = $_POST['variation_id'];

                                        $select_wishlist_meta = "select * from wishlist_meta where customer_id='$customer_id' AND product_id='$pro_id' and meta_key='variation_id' and meta_value='#$variation_id'";

                                        $run_wishlist_meta = mysqli_query($con, $select_wishlist_meta);

                                        $count_wishlist_meta = mysqli_num_rows($run_wishlist_meta);

                                        if ($count_wishlist_meta == 1) {

                                          echo "

<script>

alert('This Combination Of Product Is Already Added In Wishlist, Please Choose A Different Combination.'); 
 
</script>

";

                                          echo "<script> window.open('$pro_url','_self'); </script>";

                                        } else {

                                          $insert_wishlist = "insert into wishlist (customer_id,product_id) values ('$customer_id','$pro_id')";

                                          $run_wishlist = mysqli_query($con, $insert_wishlist);

                                          $wishlist_id = mysqli_insert_id($con);

                                          if ($run_wishlist) {

                                            $select_product_variations = "select * from product_variations where product_id='$pro_id' and not product_type='default_attributes_variation'";

                                            $run_product_variations = mysqli_query($con, $select_product_variations);

                                            $count_product_variations = mysqli_num_rows($run_product_variations);

                                            if ($count_product_variations != 0) {

                                              $select_product_attributes = "select * from product_attributes where product_id='$pro_id'";

                                              $run_product_attributes = mysqli_query($con, $select_product_attributes);

                                              while ($row_product_attributes = mysqli_fetch_array($run_product_attributes)) {

                                                $meta_key = str_replace(' ', '_', strtolower($row_product_attributes["attribute_name"]));

                                                $meta_value = $_POST[$meta_key];

                                                $insert_wishlist_meta = "insert into wishlist_meta (wishlist_id,customer_id,product_id,meta_key,meta_value) values ('$wishlist_id','$customer_id','$pro_id','$meta_key','$meta_value')";

                                                $run_wishlist_meta = mysqli_query($con, $insert_wishlist_meta);

                                              }

                                              $insert_wishlist_meta = "insert into wishlist_meta (wishlist_id,customer_id,product_id,meta_key,meta_value) values ('$wishlist_id','$customer_id','$pro_id','variation_id','#$variation_id')";

                                              $run_wishlist_meta = mysqli_query($con, $insert_wishlist_meta);

                                            }

                                          }

                                        }

                                      }

                                      if ($run_wishlist) {

                                        echo "<script> alert('Product Has Inserted Into Wishlist') </script>";

                                        echo "<script>window.open('$pro_url','_self')</script>";

                                      }

                                    }

                                  }

                                  ?>

                                </p><!-- text-center buttons Ends -->

                              <?php //}//
                              ?>

                            </form><!-- form-horizontal Ends -->

                        </div><!-- box Ends -->


                        <div class="row" id="thumbs"><!-- row Starts -->

                            <div class="col-xs-4"><!-- col-xs-4 Starts -->

                                <a href="#" class="thumb">

                                    <img src="admin_area/product_images/<?php echo $pro_img1; ?>"
                                         class="img-responsive">

                                </a>

                            </div><!-- col-xs-4 Ends -->

                          <?php if (!empty($pro_img2)) { ?>

                              <div class="col-xs-4"><!-- col-xs-4 Starts -->

                                  <a href="#" class="thumb">

                                      <img src="admin_area/product_images/<?php echo $pro_img2; ?>"
                                           class="img-responsive">

                                  </a>

                              </div><!-- col-xs-4 Ends -->

                          <?php } ?>

                          <?php if (!empty($pro_img3)) { ?>

                              <div class="col-xs-4"><!-- col-xs-4 Starts -->

                                  <a href="#" class="thumb">

                                      <img src="admin_area/product_images/<?php echo $pro_img3; ?>"
                                           class="img-responsive">

                                  </a>

                              </div><!-- col-xs-4 Ends -->

                          <?php } ?>

                        </div><!-- row Ends -->

                    </div><!-- col-sm-6 Ends -->

                </div><!-- row Ends -->

                <div class="box" id="details"><!-- box Starts -->

                    <ul class="nav nav-pills" style="margin-bottom:15px;"><!-- ul nav pills Starts -->

                        <li class="active"><!-- li Starts -->

                            <a class="tab" href="#description" data-toggle="tab"><!-- btn btn-primary Starts -->

                              <?php

                              if ($status == "product") {

                                echo "Product Description";

                              } else {

                                echo "Bundle Description";

                              }

                              ?>

                            </a><!-- btn btn-primary Ends -->

                        </li><!-- li Ends -->

                        <li><!-- li Starts -->
<!--Li for features vendors info starts here-->
                            <a class="tab" href="#features" data-toggle="tab"><!-- btn btn-primary Starts -->

                                Features

                            </a><!-- btn btn-primary Ends -->

                        </li><!-- li Ends -->

<!--                        <li>-->
                                <!-- li Starts -->

<!--                            <a class="tab" href="#video" data-toggle="tab">--><!-- btn btn-primary Starts -->

                                    <!--Sounds and Videos-->

<!--                            </a>--><!-- btn btn-primary Ends -->

<!--                        </l1>--><!-- li Ends-->

                        <li><!-- li Starts -->

                            <a class="tab" href="#vendor_info" data-toggle="tab"><!-- btn btn-primary Starts -->

                                Vendor/ shopkeeper Info

                            </a><!-- btn btn-primary Ends -->

                        </li><!-- li Ends -->

                        <li><!-- li Starts -->

                            <a class="tab" href="#reviews" data-toggle="tab"><!-- btn btn-primary Starts -->

                                Reviews

                            </a><!-- btn btn-primary Ends -->

                        </li><!-- li Ends -->

                    </ul><!-- ul nav pills Ends -->

                    <hr style="margin-top:0px; margin-right:-20px; margin-left:-20px;">

                    <div class="tab-content"><!-- tab-content Starts -->

                        <div id="description" class="tab-pane fade in active" style="margin-top:7px;">
                            <!-- description tab-pane fade in active Starts -->

                            <h3>Product Description</h3>

                          <?php echo $pro_desc; ?>

                          <?php if ($pro_type == "variable_product") { ?>

                              <h3>Additional information</h3>

                              <table class="table">

                                  <tbody>

                                  <?php

                                  $select_product_attributes = "select * from product_attributes where product_id='$pro_id'";

                                  $run_product_attributes = mysqli_query($con, $select_product_attributes);

                                  while ($row_product_attributes = mysqli_fetch_array($run_product_attributes)) {

                                    $attribute_name = $row_product_attributes["attribute_name"];

                                    $attribute_values = str_replace("|", ", ", $row_product_attributes["attribute_values"]);

                                    ?>

                                      <tr>

                                          <td><strong><?php echo $attribute_name; ?></strong></td>

                                          <td> <?php echo $attribute_values; ?> </td>

                                      </tr>

                                  <?php } ?>

                                  </tbody>

                              </table>

                          <?php } ?>

                        </div><!-- description tab-pane fade in active Ends -->

                        <div id="features" class="tab-pane fade in" style="margin-top:7px;">
                            <!-- features tab-pane fade in  Starts -->

                            <h3>Product Features</h3>

                          <?php echo $pro_features; ?>

                        </div><!-- features tab-pane fade in  Ends -->

                        <div id="video" class="tab-pane fade in" style="margin-top:7px;">
                            <!-- video tab-pane fade in Starts -->

                            <h3>Product Sounds And Videos</h3>

                              <?php /*echo $pro_video; */?>

                        </div> <!--sddsvideo tab-pane fade in  Ends-->

                        <div id="vendor_info" class="tab-pane fade in"><!-- vendor_info tab-pane fade in Starts -->

                            <h3> Vendor/ShopKeeper Information </h3>

                            <p><strong> Store Name: </strong> <?php echo $store_name; ?> </p>

                            <p>

                                <strong> Vendor/ShopKeeper: </strong>

                                <a href="store/<?php echo $vendor_username; ?>"><?php echo $vendor_username; ?></a>

                            </p>

                          <?php if (!empty($store_address_1) and !empty($store_address_2)) { ?>

                              <address>

                                  <strong> Address: </strong>

                                <?php echo $store_address_1; ?><br>

                                <?php echo $store_address_2; ?><br>

                                <?php echo $store_city; ?><br>

                                <?php echo $store_state; ?><br>

                                <?php echo $store_postcode; ?><br>

                                <?php

                                $select_country = "select * from countries where country_id='$store_country'";

                                $run_country = mysqli_query($con, $select_country);

                                $row_country = mysqli_fetch_array($run_country);

                                echo $country_name = $row_country["country_name"];

                                ?><br>

                              </address>

                          <?php } ?>

                            <p>

                              <?php for ($vendor_i = 0; $vendor_i < $star_vendor_rating; $vendor_i++) { ?>

                                  <img class="rating" src="images/star_full_big.png">

                              <?php } ?>

                              <?php for ($vendor_i = $star_vendor_rating; $vendor_i < 5; $vendor_i++) { ?>

                                  <img class="rating" src="images/star_blank_big.png">

                              <?php } ?>

                                <span class="bold"> <?php printf("%.1f", $vendor_rating); ?> </span>

                                rating from <?php echo $count_vendor_products_reviews; ?> reviews

                            </p>

                        </div><!-- vendor_info tab-pane fade in  Ends -->

                        <div id="reviews" class="tab-pane fade in" style="margin-top:7px;">
                            <!-- reviews tab-pane fade in Starts -->

                            <div class="product-reviews" id="write-a-review"><!-- product-reviews Starts -->

                                <section class="reviews-summary row"><!-- reviews-summary row Starts -->

                                    <article class="avg-rating col-md-3"><!-- avg-rating Starts -->

                                        <header> Average Rating</header>

                                        <i class="fa fa-star"></i>

                                        <footer>

                                            <span><?php printf("%.1f", $product_rating); ?></span>

                                            Based on <?php echo $count_product_reviews; ?> ratings

                                        </footer>

                                    </article><!-- avg-rating Ends -->

                                    <article class="rating-bars col-md-4"><!-- rating-bars Starts -->

                                        <ul><!-- rating-bars ul Starts -->

                                            <li><!-- li Starts -->

                                                <span class="star">5 Stars</span>

                                                <div class="percent-rating">

                                                    <span class="percent"> <span
                                                                style="width: <?php echo get_star_percentage("5"); ?>%"></span> </span>

                                                    <span class="rating">(<?php echo get_star_rating("5"); ?>)</span>

                                                </div>

                                            </li><!-- li Ends -->

                                            <li><!-- li Starts -->

                                                <span class="star">4 Stars</span>

                                                <div class="percent-rating">

                                                    <span class="percent"> <span
                                                                style="width: <?php echo get_star_percentage("4"); ?>%"></span> </span>

                                                    <span class="rating">(<?php echo get_star_rating("4"); ?>)</span>

                                                </div>

                                            </li><!-- li Ends -->

                                            <li><!-- li Starts -->

                                                <span class="star">3 Stars</span>

                                                <div class="percent-rating">

                                                    <span class="percent"> <span
                                                                style="width: <?php echo get_star_percentage("3"); ?>%"></span> </span>

                                                    <span class="rating">(<?php echo get_star_rating("3"); ?>)</span>

                                                </div>

                                            </li><!-- li Ends -->

                                            <li><!-- li Starts -->

                                                <span class="star">2 Stars</span>

                                                <div class="percent-rating">

                                                    <span class="percent"> <span
                                                                style="width: <?php echo get_star_percentage("2"); ?>%"></span> </span>

                                                    <span class="rating">(<?php echo get_star_rating("2"); ?>)</span>

                                                </div>

                                            </li><!-- li Ends -->

                                            <li><!-- li Starts -->

                                                <span class="star">1 Stars</span>

                                                <div class="percent-rating">

                                                    <span class="percent"> <span
                                                                style="width: <?php echo get_star_percentage("1"); ?>%"></span> </span>

                                                    <span class="rating">(<?php echo get_star_rating("1"); ?>)</span>

                                                </div>

                                            </li><!-- li Ends -->

                                        </ul><!-- rating-bars ul Ends -->

                                    </article><!-- rating-bars Ends -->

                                    <article class="write-review col-md-4"><!-- write-review Starts -->

                                        <header> Have you used this product before?</header>

                                        <form method="post" action="customer/write_review.php">

                                            <input type="hidden" name="product_id" value="<?php echo $pro_id; ?>">

                                            <input type="hidden" name="referral" value="product">

                                            <div class="form-group"><!-- form-group Starts -->

                                                <label class="control-label"> Rate It: * </label>

                                                <input type="hidden" name="review_rating" class="rating-loading"
                                                       data-size="sm" required>

                                                <script>

                                                    $(document).ready(function () {

                                                        $('.rating-loading').rating({

                                                            step: 1,

                                                            starCaptions: {
                                                                1: 'I hate it',
                                                                2: 'I do not like it',
                                                                3: 'It is ok',
                                                                4: 'I like it',
                                                                5: 'It is perfect!'
                                                            },

                                                            starCaptionClasses: {
                                                                1: 'btn btn-danger',
                                                                2: 'btn btn-warning',
                                                                3: 'btn btn-info',
                                                                4: 'btn btn-primary',
                                                                5: 'btn btn-success'
                                                            },

                                                            clearCaptionClass: "btn btn-default"

                                                        });

                                                    });

                                                </script>

                                            </div><!-- form-group Ends -->

                                            <button type="submit" name="submit" class="btn btn-success btn-lg">
                                                Write A Review
                                            </button>

                                        </form>

                                    </article><!-- write-review Ends -->

                                </section><!-- reviews-summary Ends -->

                                <section class="reviews-listing"><!-- reviews-listing Starts -->

                                    <hr>

                                    <h4 class="pull-left"> All Product Reviews </h4>

                                    <div class="dropdown pull-right"><!--- dropdown float-right Starts -->

                                        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">

                                            <span id="dropdown-button"> Most Recent </span> <span class="caret"></span>

                                        </button>

                                        <ul class="dropdown-menu"><!-- dropdown-menu Starts -->

                                            <li class="active all"><a href="#"> Most Recent </a></li>

                                            <li class="good"><a href="#"> Positive Reviews </a></li>

                                            <li class="bad"><a href="#"> Negative Reviews </a></li>

                                        </ul><!-- dropdown-menu Ends -->

                                    </div><!--- dropdown float-right Ends -->

                                    <div class="clearfix"></div>

                                    <hr>

                                    <ul id="all" class="reviews-list"><!-- all reviews-list Starts -->

                                      <?php

                                      if ($count_product_reviews == 0) {

                                        ?>

                                          <li><!-- li Starts -->

                                              <h3 align="center"> This Product Has No Reivew, Become First To Write A
                                                  Reivew. </h3>

                                          </li><!-- li Ends -->

                                        <?php

                                      }

                                      $select_product_reviews = "select * from reviews where product_id='$pro_id' and review_status!='pending' order by 1 desc LIMIT 0,8";

                                      $run_product_reviews = mysqli_query($con, $select_product_reviews);

                                      while ($row_product_reviews = mysqli_fetch_array($run_product_reviews)) {

                                        $review_id = $row_product_reviews['review_id'];

                                        $review_title = $row_product_reviews['review_title'];

                                        $customer_id = $row_product_reviews['customer_id'];

                                        $review_rating = $row_product_reviews['review_rating'];

                                        $review_content = $row_product_reviews['review_content'];

                                        $review_date = $row_product_reviews['review_date'];

                                        $review_status = $row_product_reviews['review_status'];

                                        $select_customer = "select * from customers where customer_id='$customer_id'";

                                        $run_customer = mysqli_query($con, $select_customer);

                                        $row_customer = mysqli_fetch_array($run_customer);

                                        $review_customer_name = $row_customer['customer_name'];

                                        $review_customer_image = $row_customer['customer_image'];

                                        ?>


                                          <li><!-- li Starts -->

                                              <span class="user-picture"><!-- user-picture Starts -->

<img src="customer/customer_images/<?php echo $review_customer_image; ?>" width="60" height="60">

</span><!-- user-picture Ends -->

                                              <div class="review-body"><!-- review-body Starts -->

                                                  <h4><!-- h4 Starts -->

                                                    <?php echo $review_title; ?>

                                                    <?php for ($review_rating_i = 0; $review_rating_i < $review_rating; $review_rating_i++) { ?>

                                                        <img class="rating" src="images/star_full_big.png">

                                                    <?php } ?>

                                                    <?php for ($review_rating_i = $review_rating; $review_rating_i < 5; $review_rating_i++) { ?>

                                                        <img class="rating" src="images/star_blank_big.png">

                                                    <?php } ?>

                                                  </h4><!-- h4 Ends -->

                                                  <h5><!-- h4 Starts -->

                                                      By <?php echo $review_customer_name; ?>
                                                      <span>(Verified owner)</span>

                                                  </h5><!-- h4 Ends -->

                                                  <span class="review-meta">

<?php

$reviews_meta = "";

$select_reviews_meta = "select * from reviews_meta where review_id='$review_id' and meta_key!='variation_id'";

$run_reviews_meta = mysqli_query($con, $select_reviews_meta);

while ($row_reviews_meta = mysqli_fetch_array($run_reviews_meta)) {

  $meta_key = ucwords($row_reviews_meta["meta_key"]);

  $meta_value = $row_reviews_meta["meta_value"];

  $reviews_meta .= "$meta_key: $meta_value | ";

}

echo rtrim($reviews_meta, "| ");

?>

</span>

                                                  <p><?php echo $review_content; ?></p>

                                                  <span class="review-date"> <?php echo $review_date; ?> </span>

                                              </div><!-- review-body Ends -->

                                          </li><!-- li Ends -->

                                          <hr>

                                      <?php } ?>

                                    </ul><!-- all reviews-list Ends -->

                                    <ul id="good" class="reviews-list"><!-- good reviews-list Starts -->

                                      <?php

                                      $select_product_reviews = "select * from reviews where product_id='$pro_id' AND (review_rating='5' or review_rating='4') and review_status!='pending' order by 1 desc LIMIT 0,8";

                                      $run_product_reviews = mysqli_query($con, $select_product_reviews);

                                      $count_product_reviews = mysqli_num_rows($run_product_reviews);

                                      if ($count_product_reviews == 0) {

                                        ?>

                                          <li><!-- li Starts -->

                                              <h3 align="center"> There Is Currently No Positive Review Of This
                                                  Product. </h3>

                                          </li><!-- li Ends -->

                                        <?php

                                      }

                                      while ($row_product_reviews = mysqli_fetch_array($run_product_reviews)) {

                                        $review_id = $row_product_reviews['review_id'];

                                        $review_title = $row_product_reviews['review_title'];

                                        $customer_id = $row_product_reviews['customer_id'];

                                        $review_rating = $row_product_reviews['review_rating'];

                                        $review_content = $row_product_reviews['review_content'];

                                        $review_date = $row_product_reviews['review_date'];

                                        $review_status = $row_product_reviews['review_status'];

                                        $select_customer = "select * from customers where customer_id='$customer_id'";

                                        $run_customer = mysqli_query($con, $select_customer);

                                        $row_customer = mysqli_fetch_array($run_customer);

                                        $review_customer_name = $row_customer['customer_name'];

                                        $review_customer_image = $row_customer['customer_image'];

                                        ?>


                                          <li><!-- li Starts -->

                                              <span class="user-picture"><!-- user-picture Starts -->

<img src="customer/customer_images/<?php echo $review_customer_image; ?>" width="60" height="60">

</span><!-- user-picture Ends -->

                                              <div class="review-body"><!-- review-body Starts -->

                                                  <h4><!-- h4 Starts -->

                                                    <?php echo $review_title; ?>

                                                    <?php for ($review_rating_i = 0; $review_rating_i < $review_rating; $review_rating_i++) { ?>

                                                        <img class="rating" src="images/star_full_big.png">

                                                    <?php } ?>

                                                    <?php for ($review_rating_i = $review_rating; $review_rating_i < 5; $review_rating_i++) { ?>

                                                        <img class="rating" src="images/star_blank_big.png">

                                                    <?php } ?>

                                                  </h4><!-- h4 Ends -->

                                                  <h5><!-- h4 Starts -->

                                                      By <?php echo $review_customer_name; ?>
                                                      <span>(Verified owner)</span>

                                                  </h5><!-- h4 Ends -->

                                                  <span class="review-meta">

<?php

$reviews_meta = "";

$select_reviews_meta = "select * from reviews_meta where review_id='$review_id' and meta_key!='variation_id'";

$run_reviews_meta = mysqli_query($con, $select_reviews_meta);

while ($row_reviews_meta = mysqli_fetch_array($run_reviews_meta)) {

  $meta_key = ucwords($row_reviews_meta["meta_key"]);

  $meta_value = $row_reviews_meta["meta_value"];

  $reviews_meta .= "$meta_key: $meta_value | ";

}

echo rtrim($reviews_meta, "| ");

?>

</span>

                                                  <p><?php echo $review_content; ?></p>

                                                  <span class="review-date"> <?php echo $review_date; ?> </span>

                                              </div><!-- review-body Ends -->

                                          </li><!-- li Ends -->

                                          <hr>

                                      <?php } ?>

                                    </ul><!-- good reviews-list Ends -->

                                    <ul id="bad" class="reviews-list"><!-- bad reviews-list Starts -->

                                      <?php

                                      $select_product_reviews = "select * from reviews where product_id='$pro_id' AND (review_rating='1' or review_rating='2' or review_rating='3') and review_status!='pending' order by 1 desc LIMIT 0,8";

                                      $run_product_reviews = mysqli_query($con, $select_product_reviews);

                                      $count_product_reviews = mysqli_num_rows($run_product_reviews);

                                      if ($count_product_reviews == 0) {

                                        ?>

                                          <li><!-- li Starts -->

                                              <h3 align="center"> There Is Currently No Positive Review Of This
                                                  Product. </h3>

                                          </li><!-- li Ends -->

                                        <?php

                                      }

                                      while ($row_product_reviews = mysqli_fetch_array($run_product_reviews)) {

                                        $review_id = $row_product_reviews['review_id'];

                                        $review_title = $row_product_reviews['review_title'];

                                        $customer_id = $row_product_reviews['customer_id'];

                                        $review_rating = $row_product_reviews['review_rating'];

                                        $review_content = $row_product_reviews['review_content'];

                                        $review_date = $row_product_reviews['review_date'];

                                        $review_status = $row_product_reviews['review_status'];

                                        $select_customer = "select * from customers where customer_id='$customer_id'";

                                        $run_customer = mysqli_query($con, $select_customer);

                                        $row_customer = mysqli_fetch_array($run_customer);

                                        $review_customer_name = $row_customer['customer_name'];

                                        $review_customer_image = $row_customer['customer_image'];

                                        ?>


                                          <li><!-- li Starts -->

                                              <span class="user-picture"><!-- user-picture Starts -->

<img src="customer/customer_images/<?php echo $review_customer_image; ?>" width="60" height="60">

</span><!-- user-picture Ends -->

                                              <div class="review-body"><!-- review-body Starts -->

                                                  <h4><!-- h4 Starts -->

                                                    <?php echo $review_title; ?>

                                                    <?php for ($review_rating_i = 0; $review_rating_i < $review_rating; $review_rating_i++) { ?>

                                                        <img class="rating" src="images/star_full_big.png">

                                                    <?php } ?>

                                                    <?php for ($review_rating_i = $review_rating; $review_rating_i < 5; $review_rating_i++) { ?>

                                                        <img class="rating" src="images/star_blank_big.png">

                                                    <?php } ?>

                                                  </h4><!-- h4 Ends -->

                                                  <h5><!-- h4 Starts -->

                                                      By <?php echo $review_customer_name; ?>
                                                      <span>(Verified owner)</span>

                                                  </h5><!-- h4 Ends -->

                                                  <span class="review-meta">

<?php

$reviews_meta = "";

$select_reviews_meta = "select * from reviews_meta where review_id='$review_id' and meta_key!='variation_id'";

$run_reviews_meta = mysqli_query($con, $select_reviews_meta);

while ($row_reviews_meta = mysqli_fetch_array($run_reviews_meta)) {

  $meta_key = ucwords($row_reviews_meta["meta_key"]);

  $meta_value = $row_reviews_meta["meta_value"];

  $reviews_meta .= "$meta_key: $meta_value | ";

}

echo rtrim($reviews_meta, "| ");

?>

</span>

                                                  <p><?php echo $review_content; ?></p>

                                                  <span class="review-date"> <?php echo $review_date; ?> </span>

                                              </div><!-- review-body Ends -->

                                          </li><!-- li Ends -->

                                          <hr>

                                      <?php } ?>

                                    </ul><!-- bad reviews-list Ends -->

                                </section><!-- reviews-listing Ends -->

                            </div><!-- product-reviews Ends -->

                        </div><!-- reviews tab-pane fade in  Ends -->

                    </div><!-- tab-content Ends -->

                </div><!-- box Ends -->

                <div id="row same-height-row flex-wrap"><!-- row same-height-row Starts -->

                  <?php if ($status == "product") { ?>

                      <div class="col-md-3 col-sm-6"><!-- col-md-3 col-sm-6 Starts -->

                          <div class="box same-height headline"><!-- box same-height headline Starts -->

                              <h3 class="text-center"> You also like these Products </h3>

                          </div><!-- box same-height headline Ends -->

                      </div><!-- col-md-3 col-sm-6 Ends -->

                    <?php

                    $get_products = "select * from products order by rand() LIMIT 0,3";

                    $run_products = mysqli_query($con, $get_products);

                    while ($row_products = mysqli_fetch_array($run_products)) {

                      $pro_id = $row_products['product_id'];

                      $pro_title = $row_products['product_title'];

                      $pro_price = $row_products['product_price'];

                      $pro_img1 = $row_products['product_img1'];

                      $pro_label = $row_products['product_label'];

                      $manufacturer_id = $row_products['manufacturer_id'];

                      $get_manufacturer = "select * from manufacturers where manufacturer_id='$manufacturer_id'";

                      $run_manufacturer = mysqli_query($con, $get_manufacturer);

                      $row_manufacturer = mysqli_fetch_array($run_manufacturer);

                      $manufacturer_name = $row_manufacturer['manufacturer_title'];

                      $pro_psp_price = $row_products['product_psp_price'];

                      $pro_url = $row_products['product_url'];

                      $product_type = $row_products['product_type'];

                      if ($product_type != "variable_product") {

                        if ($pro_label == "Sale" or $pro_label == "Gift") {

                          $product_price = "<del> Rs:$pro_price </del>";

                          $product_psp_price = "| Rs:$pro_psp_price";

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

                      if (empty($vendor_id)) {

                        $store_back_url = "";

                      } else {

                        $store_back_url = "../";

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

<a href='$pro_url' >

<img src='admin_area/product_images/$pro_img1' class='product-img'>

$outofstock_label

</a>

<div class='text' >

<div align='center'>

<p class='btn btn-primary'> $manufacturer_name </p>

</div>

<hr>

<h3 class='product-title'><a href='$pro_url' >$pro_title</a></h3>

<p class='star-rating'> $product_rating_stars </p>

<p class='price'> $product_price $product_psp_price </p>

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

                    ?>

                  <?php } else { ?>

                      <div class="box same-height"><!-- box same-height Starts -->

                          <h3 class="text-center"> Bundle Products </h3>

                      </div><!-- box same-height Ends -->

                    <?php

                    $get_bundle_product_relation = "select * from bundle_product_relation where bundle_id='$pro_id'";

                    $run_bundle_product_relation = mysqli_query($con, $get_bundle_product_relation);

                    while ($row_bundle_product_relation = mysqli_fetch_array($run_bundle_product_relation)) {

                      $bundle_product_relation_product_id = $row_bundle_product_relation['product_id'];

                      $get_products = "select * from products where product_id='$bundle_product_relation_product_id'";

                      $run_products = mysqli_query($con, $get_products);

                      while ($row_products = mysqli_fetch_array($run_products)) {
                        $pro_id = $row_products['product_id'];

                        $pro_title = $row_products['product_title'];

                        $pro_price = $row_products['product_price'];

                        $pro_img1 = $row_products['product_img1'];

                        $pro_label = $row_products['product_label'];

                        $manufacturer_id = $row_products['manufacturer_id'];

                        $get_manufacturer = "select * from manufacturers where manufacturer_id='$manufacturer_id'";

                        $run_manufacturer = mysqli_query($con, $get_manufacturer);

                        $row_manufacturer = mysqli_fetch_array($run_manufacturer);

                        $manufacturer_name = $row_manufacturer['manufacturer_title'];

                        $pro_psp_price = $row_products['product_psp_price'];

                        $pro_url = $row_products['product_url'];

                        $product_type = $row_products['product_type'];

                        if ($product_type != "variable_product") {

                          if ($pro_label == "Sale" or $pro_label == "Gift") {

                            $product_price = "<del> Rs:$pro_price </del>";

                            $product_psp_price = "| Rs:$pro_psp_price";

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

                        if (empty($vendor_id)) {

                          $store_back_url = "";

                        } else {

                          $store_back_url = "../";

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

<a href='$pro_url' >

<img src='admin_area/product_images/$pro_img1' class='product-img'>

$outofstock_label

</a>

<div class='text' >

<center>

<p class='btn btn-primary'> $manufacturer_name </p>

</center>

<hr>

<h3 class='product-title'><a href='$pro_url' >$pro_title</a></h3>

<p class='star-rating'> $product_rating_stars </p>

<p class='price'> $product_price $product_psp_price </p>

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

                    ?>


                  <?php } ?>

                </div><!-- row same-height-row Ends -->

            </div><!-- col-md-12 Ends -->

        </div><!-- container Ends -->

    </div><!-- content Ends -->

    <script>

        $(document).ready(function () {

            $('#good').hide();

            $('#bad').hide();

            $(".all").click(function (event) {

                event.preventDefault();

                $("#dropdown-button").html("Most Recent");

                $(".all").attr('class', 'dropdown-item all active');

                $(".bad").attr('class', 'dropdown-item bad');

                $(".good").attr('class', 'dropdown-item good');

                $("#all").show();

                $("#good").hide();

                $("#bad").hide();

            });

            $(".good").click(function (event) {

                event.preventDefault();

                $("#dropdown-button").html("Positive Reviews");

                $(".all").attr('class', 'dropdown-item all');

                $(".bad").attr('class', 'dropdown-item bad');

                $(".good").attr('class', 'dropdown-item good active');

                $("#all").hide();

                $("#good").show();

                $("#bad").hide();

            });

            $(".bad").click(function (event) {

                event.preventDefault();

                $("#dropdown-button").html("Negative Reviews");

                $(".all").attr('class', 'dropdown-item all');

                $(".bad").attr('class', 'dropdown-item bad active');

                $(".good").attr('class', 'dropdown-item good');

                $("#all").hide();

                $("#good").hide();

                $("#bad").show();

            });

        });

    </script>
    <?php include("includes/footer.php"); ?>
    <script src="js/bootstrap.min.js"></script>
    </body>
    </html>

<?php } ?>