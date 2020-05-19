<?php
session_start();
include("includes/db.php");
include("functions/functions.php");
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
                Shopping Cart Total Price: <span class="subtotal-cart-price"><?php total_price(); ?></span>, Total
                Items <?php items(); ?>
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

                    <li class="active">
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

    <div class="container-fluid"><!-- container-fluid Starts -->

        <div class="col-md-12"><!--- col-md-12 Starts -->

            <ul class="breadcrumb"><!-- breadcrumb Starts -->

                <li>
                    <a href="index.php">Home</a>
                </li>

                <li>Cart</li>

            </ul><!-- breadcrumb Ends -->

            <nav class="checkout-breadcrumbs text-center">

                <a href="cart.php" class="active">Shopping Cart</a>

                <i class="fa fa-chevron-right"></i>

                <a href="checkout.php">Checkout Details</a>

                <i class="fa fa-chevron-right"></i>

                <a href="#"> Order Complete </a>

            </nav>

        </div><!--- col-md-12 Ends -->

        <div class="col-md-9" id="cart"><!-- col-md-9 Starts -->

            <div class="box"><!-- box Starts -->

                <form action="cart.php" method="post" enctype="multipart/form-data"><!-- form Starts -->

                    <h1> Shopping Cart </h1>

                  <?php

                  $ip_add = getRealUserIp();

                  $select_cart = "select * from cart where ip_add='$ip_add'";

                  $run_cart = mysqli_query($con, $select_cart);

                  $count_cart = mysqli_num_rows($run_cart);

                  ?>

                    <p class="text-muted"> You currently have <?php items(); ?> item(s) in your cart. </p>

                    <div class="table-responsive"><!-- table-responsive Starts -->

                        <table class="table"><!-- table Starts -->

                            <thead><!-- thead Starts -->

                            <tr>

                                <th colspan="2">Product</th>

                                <th>Quantity</th>

                                <th>Unit Price</th>

                                <th colspan="1"> Delete</th>

                                <th colspan="2"> Sub Total</th>

                            </tr>

                            </thead><!-- thead Ends -->

                            <tbody id="cart-products-tbody"><!-- tbody Starts -->

                            <?php

                            $total = 0;

                            $total_weight = array();

                            $physical_products = array();

                            $vendors_ids = array();

                            while ($row_cart = mysqli_fetch_array($run_cart)) {

                              $cart_id = $row_cart['cart_id'];

                              $pro_id = $row_cart['p_id'];

                              $pro_qty = $row_cart['qty'];

                              $only_price = $row_cart['p_price'];

                              $product_weight = $row_cart['product_weight'];

                              $product_type = $row_cart['product_type'];

                              $get_products = "select * from products where product_id='$pro_id'";

                              $run_products = mysqli_query($con, $get_products);

                              while ($row_products = mysqli_fetch_array($run_products)) {

                                $vendor_id = $row_products['vendor_id'];

                                $product_url = $row_products['product_url'];

                                $product_title = $row_products['product_title'];

                                $product_img1 = $row_products['product_img1'];

                                if ($product_type == "physical_product") {

                                  if (!isset($physical_products[$vendor_id])) {

                                    $physical_products[$vendor_id] = array();

                                  }

                                  array_push($physical_products[$vendor_id], $pro_id);

                                }

                                if (!empty($vendor_id)) {

                                  if (!in_array($vendor_id, $vendors_ids)) {

                                    array_push($vendors_ids, $vendor_id);

                                  }

                                }

                                $sub_total = $only_price * $pro_qty;

                                $_SESSION['pro_qty'] = $pro_qty;

                                $total += $sub_total;

                                $sub_total_weight = $product_weight * $pro_qty;

                                @$total_weight[$vendor_id] += $sub_total_weight;

                                if (strpos($vendor_id, "admin_") !== false) {

                                  $admin_id = trim($vendor_id, "admin_");

                                  $get_admin = "select * from admins where admin_id='$admin_id'";

                                  $run_admin = mysqli_query($con, $get_admin);

                                  $row_admin = mysqli_fetch_array($run_admin);

                                  $vendor_name = $row_admin['admin_name'];

                                } else {

                                  $get_customer = "select * from customers where customer_id='$vendor_id'";

                                  $run_customer = mysqli_query($con, $get_customer);

                                  $row_customer = mysqli_fetch_array($run_customer);

                                  $vendor_name = $row_customer['customer_name'];

                                }

                                $select_cart_meta = "select * from cart_meta where ip_add='$ip_add' and cart_id='$cart_id' and product_id='$pro_id' and meta_key='variation_id'";

                                $run_cart_meta = mysqli_query($con, $select_cart_meta);

                                $count_cart_meta = mysqli_num_rows($run_cart_meta);

                                if ($count_cart_meta == 0) {

                                  $select_product_stock = "select * from products_stock where product_id='$pro_id'";

                                } else {

                                  $row_cart_meta = mysqli_fetch_array($run_cart_meta);

                                  $variation_id = str_replace("#", "", $row_cart_meta["meta_value"]);

                                  $select_product_stock = "select * from products_stock where product_id='$pro_id' and variation_id='$variation_id'";

                                }

                                $run_product_stock = mysqli_query($con, $select_product_stock);

                                $row_product_stock = mysqli_fetch_array($run_product_stock);

                                $enable_stock = $row_product_stock["enable_stock"];

                                $stock_status = $row_product_stock["stock_status"];

                                $stock_quantity = $row_product_stock["stock_quantity"];

                                $allow_backorders = $row_product_stock["allow_backorders"];

                                ?>

                                  <tr><!-- tr Starts -->

                                      <td>

                                          <img src="admin_area/product_images/<?php echo $product_img1; ?>">

                                      </td>

                                      <td width="350">

                                          <a href="<?php echo $product_url; ?>" target="blank"
                                             class="bold"> <?php echo $product_title; ?> </a>

                                          <p class="cart-product-meta">

                                            <?php

                                            $cart_meta = "";

                                            $select_cart_meta = "select * from cart_meta where ip_add='$ip_add' and cart_id='$cart_id' and product_id='$pro_id' and not meta_key='variation_id'";

                                            $run_cart_meta = mysqli_query($con, $select_cart_meta);

                                            while ($row_cart_meta = mysqli_fetch_array($run_cart_meta)) {

                                              $meta_key = ucwords($row_cart_meta["meta_key"]);

                                              $meta_value = $row_cart_meta["meta_value"];

                                              $cart_meta .= "$meta_key: <span class='text-muted'> $meta_value </span>, ";

                                            }

                                            echo rtrim($cart_meta, ", ");

                                            ?>

                                          </p>

                                          <p style="margin-top:8px;"><strong> Vendor
                                                  : </strong> <?php echo $vendor_name; ?> </p>

                                      </td>

                                      <td>

                                        <?php if ($enable_stock == "yes" and $allow_backorders == "no") { ?>

                                            <input type="text" name="quantity"
                                                   value="<?php echo $_SESSION['pro_qty']; ?>"
                                                   data-cart_id="<?php echo $cart_id; ?>"
                                                   data-product_id="<?php echo $pro_id; ?>" min="1"
                                                   max="<?php echo $stock_quantity; ?>" class="quantity form-control">

                                        <?php } elseif ($enable_stock == "yes" and ($allow_backorders == "yes" or $allow_backorders == "notify")) { ?>

                                            <input type="text" name="quantity"
                                                   value="<?php echo $_SESSION['pro_qty']; ?>"
                                                   data-cart_id="<?php echo $cart_id; ?>"
                                                   data-product_id="<?php echo $pro_id; ?>" min="1"
                                                   class="quantity form-control">

                                        <?php } elseif ($enable_stock == "no") { ?>

                                            <input type="text" name="quantity"
                                                   value="<?php echo $_SESSION['pro_qty']; ?>"
                                                   data-cart_id="<?php echo $cart_id; ?>"
                                                   data-product_id="<?php echo $pro_id; ?>" min="1"
                                                   class="quantity form-control">

                                        <?php } ?>

                                      </td>

                                      <td>

                                          Rs:<?php echo $only_price; ?>.00

                                      </td>

                                      <td>
                                          <input type="checkbox" name="remove[]" value="<?php echo $pro_id; ?>">
                                      </td>

                                      <td>

                                          Rs:<?php echo $sub_total; ?>.00

                                      </td>

                                  </tr><!-- tr Ends -->

                              <?php }
                            } ?>

                            </tbody><!-- tbody Ends -->

                            <tfoot><!-- tfoot Starts -->

                            <tr>

                                <th colspan="5"> Total</th>

                                <th colspan="2"><span class="subtotal-cart-price">Rs:<?php echo $total; ?></span>.00</th>

                            </tr>

                            </tfoot><!-- tfoot Ends -->

                        </table><!-- table Ends -->


                        <!--Coupon Code Goes Here-->
                        <div class="form-inline pull-right"><!-- form-inline pull-right Starts -->

<!--                            <div class="form-group">-->
<!---->
<!--                                <label>Coupon Code : </label>-->
<!---->
<!--                                <input type="text" name="code" class="form-control">-->
<!---->
<!--                            </div>-->
<!---->
<!--                            <input class="btn btn-primary" type="submit" name="apply_coupon" value="Apply Coupon Code">-->

                        </div><!-- form-inline pull-right Ends-->

                    </div>  <!-- table-responsive Ends -->


                    <div class="box-footer"><!-- box-footer Starts -->

                        <div class="pull-left"><!-- pull-left Starts -->

                            <a href="index.php" class="btn btn-default">

                                <i class="fa fa-chevron-left"></i> Continue Shopping

                            </a>

                        </div><!-- pull-left Ends -->

                        <div class="pull-right"><!-- pull-right Starts -->

                            <button class="btn btn-default" type="submit" name="update" value="Update Cart">

                                <i class="fa fa-refresh"></i> Update Cart

                            </button>

                            <a href="checkout.php" class="btn btn-primary">

                                Proceed to checkout <i class="fa fa-chevron-right"></i>

                            </a>

                        </div><!-- pull-right Ends -->

                    </div><!-- box-footer Ends -->

                </form><!-- form Ends -->

            </div><!-- box Ends -->

          <?php

          if (isset($_POST['apply_coupon'])) {

            $code = $_POST['code'];

            if ($code == "") {

            } else {

              $get_coupons = "select * from coupons where coupon_code='$code'";

              $run_coupons = mysqli_query($con, $get_coupons);

              $check_coupons = mysqli_num_rows($run_coupons);

              if ($check_coupons == 1) {

                $row_coupons = mysqli_fetch_array($run_coupons);

                $coupon_pro = $row_coupons['product_id'];

                $coupon_price = $row_coupons['coupon_price'];

                $coupon_limit = $row_coupons['coupon_limit'];

                $coupon_used = $row_coupons['coupon_used'];

                if ($coupon_limit == $coupon_used) {

                  echo "<script>alert('Your Coupon Code Has Been Expired')</script>";

                } else {

                  $get_cart = "select * from cart where p_id='$coupon_pro' AND ip_add='$ip_add'";

                  $run_cart = mysqli_query($con, $get_cart);

                  $check_cart = mysqli_num_rows($run_cart);

                  if ($check_cart == 1) {

                    $add_used = "update coupons set coupon_used=coupon_used+1 where coupon_code='$code'";

                    $run_used = mysqli_query($con, $add_used);

                    $update_cart = "update cart set p_price='$coupon_price' where p_id='$coupon_pro' AND ip_add='$ip_add'";

                    $run_update = mysqli_query($con, $update_cart);

                    echo "<script>alert('Your Coupon Code Has Been Applied')</script>";

                    echo "<script>window.open('cart.php','_self')</script>";

                  } else {

                    echo "<script>alert('Product Does Not Exist In Cart')</script>";

                  }

                }

              } else {

                echo "<script> alert('Your Coupon Code Is Not Valid') </script>";

              }

            }

          }

          ?>

          <?php

          function update_cart()
          {

            global $con;

            if (isset($_POST['update'])) {

              foreach ($_POST['remove'] as $remove_id) {

                $ip_add = getRealUserIp();

                $delete_product = "delete from cart where ip_add='$ip_add' and p_id='$remove_id'";

                $run_delete = mysqli_query($con, $delete_product);

                if ($run_delete) {

                  $delete_cart_meta = "delete from cart_meta where ip_add='$ip_add' and product_id='$remove_id'";

                  $run_delete_cart_meta = mysqli_query($con, $delete_cart_meta);

                  if ($run_delete_cart_meta) {

                    echo "<script> window.open('cart.php','_self'); </script>";

                  }

                }

              }

            }

          }

          echo @$up_cart = update_cart();

          ?>

            <div id="row same-height-row"><!-- row same-height-row Starts -->

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

                $run_manufacturer = mysqli_query($db, $get_manufacturer);

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

                echo "

<div class='col-md-3 col-sm-6 center-responsive' >

<div class='product' >

<a href='$pro_url' >

<img src='admin_area/product_images/$pro_img1' class='product-img'>

</a>

<div class='text' >

<center>

<p class='btn btn-primary'> $manufacturer_name </p>

</center>

<hr>

<h3 class='product-title'><a href='$pro_url' >$pro_title</a></h3>

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

              ?>

            </div><!-- row same-height-row Ends -->

        </div><!-- col-md-9 Ends -->

        <div class="col-md-3"><!-- col-md-3 Starts -->

            <div class="box" id="order-summary"><!-- box Starts -->

                <div class="box-header"><!-- box-header Starts -->

                    <h3>Order Summary</h3>

                </div><!-- box-header Ends -->

                <p class="text-muted">
                    Shipping and additional costs are calculated based on the values you have entered.
                </p>

                <div class="table-responsive"><!-- table-responsive Starts -->

                    <table class="table"><!-- table Starts -->

                        <tbody id="cart-summary-tbody"><!-- tbody Starts -->

                        <tr>

                            <td> Order Subtotal:</td>

                            <th> Rs:<?php echo $total; ?>.00</th>

                        </tr>

                        <?php if (count($physical_products) > 0) { ?>

                            <tr>

                                <th colspan="2">

                                    <p class="shipping-header text-muted"> Cart Total
                                        Weight: <?php echo array_sum($total_weight); ?> Kg </p>

                                    <p class="shipping-header text-muted"><i class="fa fa-truck"></i> Shipping: </p>

                                    <ul class="shipping-ul-list list-unstyled">

                                      <?php

                                      if (isset($_SESSION['customer_email'])) {

                                        $customer_email = $_SESSION['customer_email'];

                                        $get_customer = "select * from customers where customer_email='$customer_email'";

                                        $run_customer = mysqli_query($con, $get_customer);

                                        $row_customer = mysqli_fetch_array($run_customer);

                                        $customer_id = $row_customer['customer_id'];

                                        $get_customers_addresses = "select * from customers_addresses where customer_id='$customer_id'";

                                        $run_customers_addresses = mysqli_query($con, $get_customers_addresses);

                                        $row_addresses = mysqli_fetch_array($run_customers_addresses);

                                        $billing_country = $row_addresses["billing_country"];

                                        $billing_postcode = $row_addresses["billing_postcode"];

                                        $shipping_country = $row_addresses["shipping_country"];

                                        $shipping_postcode = $row_addresses["shipping_postcode"];

                                        foreach ($vendors_ids as $vendor_id) {

                                          if (isset($physical_products[$vendor_id])) {

                                            $shipping_zone_id = "";

                                            if (strpos($vendor_id, "admin_") !== false) {

                                              $admin_id = trim($vendor_id, "admin_");

                                              $get_admin = "select * from admins where admin_id='$admin_id'";

                                              $run_admin = mysqli_query($con, $get_admin);

                                              $row_admin = mysqli_fetch_array($run_admin);

                                              $vendor_name = $row_admin['admin_name'];

                                            } else {

                                              $get_customer = "select * from customers where customer_id='$vendor_id'";

                                              $run_customer = mysqli_query($con, $get_customer);

                                              $row_customer = mysqli_fetch_array($run_customer);

                                              $vendor_name = $row_customer['customer_name'];

                                            }

                                            ?>

                                              <div class="shipping-vendor-header"> <?php echo $vendor_name; ?>
                                                  Shipping:
                                              </div>

                                            <?php

                                            if (@$_SESSION["is_shipping_address"] == "yes") {

                                              if (empty($billing_country) and empty($billing_postcode)) {

                                                echo "

<li> 

<p> 

There are no shipping types available. Please double check your address, or contact us if you need any help. 

</p> 

</li>

";

                                              }

                                              $select_zones = "select * from zones where vendor_id='$vendor_id' order by zone_order DESC";

                                              $run_zones = mysqli_query($con, $select_zones);

                                              while ($row_zones = mysqli_fetch_array($run_zones)) {

                                                $zone_id = $row_zones['zone_id'];

                                                $select_zone_locations = "select DISTINCT zone_id from zones_locations where zone_id='$zone_id' and (location_code='$billing_country' and location_type='country')";

                                                $run_zones_locations = mysqli_query($con, $select_zone_locations);

                                                $count_zones_locations = mysqli_num_rows($run_zones_locations);

                                                if ($count_zones_locations != "0") {

                                                  $row_zones_locations = mysqli_fetch_array($run_zones_locations);

                                                  $zone_id = $row_zones_locations["zone_id"];

                                                  $select_zone_shipping = "select * from shipping where shipping_zone='$zone_id'";

                                                  $run_zone_shipping = mysqli_query($con, $select_zone_shipping);

                                                  $count_zone_shipping = mysqli_num_rows($run_zone_shipping);

                                                  if ($count_zone_shipping != "0") {

                                                    $select_zone_postcodes = "select * from zones_locations where zone_id='$zone_id' and location_type='postcode'";

                                                    $run_zones_postcodes = mysqli_query($con, $select_zone_postcodes);

                                                    $count_zones_postcodes = mysqli_num_rows($run_zones_postcodes);

                                                    if ($count_zones_postcodes != "0") {

                                                      while ($row_zones_postcodes = mysqli_fetch_array($run_zones_postcodes)) {

                                                        $location_code = $row_zones_postcodes["location_code"];

                                                        if ($location_code == $billing_postcode) {

                                                          $shipping_zone_id = $zone_id;

                                                        }

                                                      }

                                                    } else {

                                                      $shipping_zone_id = $zone_id;

                                                    }

                                                  }

                                                }

                                              }

                                            } elseif (@$_SESSION["is_shipping_address"] == "no") {

                                              if (empty($shipping_country) and empty($shipping_postcode)) {

                                                echo "

<li> 

<p> There are no shipping types available. Please double check your address, or contact us if you need any help. </p> 

</li>

";

                                              }

                                              $select_zones = "select * from zones where vendor_id='$vendor_id' order by zone_order DESC";

                                              $run_zones = mysqli_query($con, $select_zones);

                                              while ($row_zones = mysqli_fetch_array($run_zones)) {

                                                $zone_id = $row_zones['zone_id'];

                                                $select_zone_locations = "select DISTINCT zone_id from zones_locations where zone_id='$zone_id' and (location_code='$shipping_country' and location_type='country')";

                                                $run_zones_locations = mysqli_query($con, $select_zone_locations);

                                                $count_zones_locations = mysqli_num_rows($run_zones_locations);

                                                if ($count_zones_locations != "0") {

                                                  $row_zones_locations = mysqli_fetch_array($run_zones_locations);

                                                  $zone_id = $row_zones_locations["zone_id"];

                                                  $select_zone_shipping = "select * from shipping where shipping_zone='$zone_id'";

                                                  $run_zone_shipping = mysqli_query($con, $select_zone_shipping);

                                                  $count_zone_shipping = mysqli_num_rows($run_zone_shipping);

                                                  if ($count_zone_shipping != "0") {

                                                    $select_zone_postcodes = "select * from zones_locations where zone_id='$zone_id' and location_type='postcode'";

                                                    $run_zones_postcodes = mysqli_query($con, $select_zone_postcodes);

                                                    $count_zones_postcodes = mysqli_num_rows($run_zones_postcodes);

                                                    if ($count_zones_postcodes != "0") {

                                                      while ($row_zones_postcodes = mysqli_fetch_array($run_zones_postcodes)) {

                                                        $location_code = $row_zones_postcodes["location_code"];

                                                        if ($location_code == $shipping_postcode) {

                                                          $shipping_zone_id = $zone_id;

                                                        }

                                                      }

                                                    } else {

                                                      $shipping_zone_id = $zone_id;

                                                    }

                                                  }

                                                }

                                              }

                                            } else {

                                              if (empty($billing_country) and empty($billing_postcode)) {

                                                echo "

<li> 

<p> There are no shipping types available. Please double check your address, or contact us if you need any help. </p> 

</li>

";

                                              }

                                              $select_zones = "select * from zones where vendor_id='$vendor_id' order by zone_order DESC";

                                              $run_zones = mysqli_query($con, $select_zones);

                                              while ($row_zones = mysqli_fetch_array($run_zones)) {

                                                $zone_id = $row_zones['zone_id'];

                                                $select_zone_locations = "select DISTINCT zone_id from zones_locations where zone_id='$zone_id' and (location_code='$billing_country' and location_type='country')";

                                                $run_zones_locations = mysqli_query($con, $select_zone_locations);

                                                $count_zones_locations = mysqli_num_rows($run_zones_locations);

                                                if ($count_zones_locations != "0") {

                                                  $row_zones_locations = mysqli_fetch_array($run_zones_locations);

                                                  $zone_id = $row_zones_locations["zone_id"];

                                                  $select_zone_postcodes = "select * from zones_locations where zone_id='$zone_id' and location_type='postcode'";

                                                  $run_zones_postcodes = mysqli_query($con, $select_zone_postcodes);

                                                  $count_zones_postcodes = mysqli_num_rows($run_zones_postcodes);

                                                  if ($count_zones_postcodes != "0") {

                                                    while ($row_zones_postcodes = mysqli_fetch_array($run_zones_postcodes)) {

                                                      $location_code = $row_zones_postcodes["location_code"];

                                                      if ($location_code == $billing_postcode) {

                                                        $shipping_zone_id = $zone_id;

                                                      }

                                                    }

                                                  } else {

                                                    $shipping_zone_id = $zone_id;

                                                  }

                                                }

                                              }

                                            }

                                            $shipping_weight = $total_weight[$vendor_id];

                                            if (!empty($shipping_zone_id)) {

                                              $select_shipping = "
SELECT *,
IF (
$shipping_weight > (
SELECT MAX(shipping_weight)
FROM shipping
WHERE shipping_type = type_id
AND shipping_zone = '$shipping_zone_id'
),
(
SELECT shipping_cost
FROM shipping
WHERE shipping_type = type_id
AND shipping_zone = '$shipping_zone_id'
ORDER BY shipping_weight DESC
LIMIT 0, 1
),
(
SELECT shipping_cost
FROM shipping
WHERE shipping_type = type_id
AND shipping_zone = '$shipping_zone_id'
AND shipping_weight >= '$shipping_weight'
ORDER BY shipping_weight ASC
LIMIT 0, 1
)
) AS shipping_cost
FROM shipping_type
WHERE type_local = 'yes'
and vendor_id='$vendor_id'
ORDER BY type_order ASC
";

                                              $run_shipping = mysqli_query($con, $select_shipping);

                                              $i = 0;

                                              while ($row_shipping = mysqli_fetch_array($run_shipping)) {

                                                $i++;

                                                $type_id = $row_shipping["type_id"];

                                                $type_name = $row_shipping["type_name"];

                                                $type_default = $row_shipping["type_default"];

                                                $shipping_cost = $row_shipping["shipping_cost"];

                                                if (!empty($shipping_cost)) {

                                                  ?>

                                                    <li>

                                                        <input type="radio"
                                                               name="[<?php echo $vendor_id; ?>][shipping_type]"
                                                               value="<?php echo $type_id; ?>" class="shipping_type"
                                                               data-shipping_cost="<?php echo $shipping_cost; ?>"

                                                          <?php

                                                          if ($type_default == "yes") {

                                                            $_SESSION["shipping_type_$vendor_id"] = $type_id;

                                                            $_SESSION["shipping_cost_$vendor_id"] = $shipping_cost;

                                                            echo "checked";

                                                          } elseif ($i == 1) {

                                                            $_SESSION["shipping_type_$vendor_id"] = $type_id;

                                                            $_SESSION["shipping_cost_$vendor_id"] = $shipping_cost;

                                                            echo "checked";

                                                          }

                                                          ?>>

                                                        <span class="shipping-type-name">

<?php echo $type_name; ?>: <span class="text-muted"> Rs:<?php echo $shipping_cost; ?> </span>

</span>

                                                    </li>

                                                  <?php

                                                }

                                              }

                                            } else {

                                              if (!empty($billing_country) or !empty($shipping_country)) {

                                                if (@$_SESSION["is_shipping_address"] == "yes") {

                                                  $select_country_shipping = "select * from shipping where shipping_country='$billing_country'";

                                                } elseif (@$_SESSION["is_shipping_address"] == "no") {

                                                  $select_country_shipping = "select * from shipping where shipping_country='$shipping_country'";

                                                } else {

                                                  $select_country_shipping = "select * from shipping where shipping_country='$billing_country'";

                                                }

                                                $run_country_shipping = mysqli_query($con, $select_country_shipping);

                                                $count_country_shipping = mysqli_num_rows($run_country_shipping);

                                                if ($count_country_shipping == "0") {

                                                  echo "

<li> 

<p> There are no shipping types matched/available for your address, or contact us if you need any help. </p> 

</li>

";

                                                } else {

                                                  if (@$_SESSION["is_shipping_address"] == "yes") {

                                                    $select_shipping = "
SELECT *,
IF (
$shipping_weight > (
SELECT MAX(shipping_weight)
FROM shipping
WHERE shipping_type = type_id
AND shipping_country = '$billing_country'
),
(
SELECT shipping_cost
FROM shipping
WHERE shipping_type = type_id
AND shipping_country = '$billing_country'
ORDER BY shipping_weight DESC
LIMIT 0, 1
),
(
SELECT shipping_cost
FROM shipping
WHERE shipping_type = type_id
AND shipping_country = '$billing_country'
AND shipping_weight >= '$shipping_weight'
ORDER BY shipping_weight ASC
LIMIT 0, 1
)
) AS shipping_cost
FROM shipping_type
WHERE type_local = 'no'
and vendor_id='$vendor_id'
ORDER BY type_order ASC
";

                                                  } elseif (@$_SESSION["is_shipping_address"] == "no") {

                                                    $select_shipping = "
SELECT *,
IF (
$shipping_weight > (
SELECT MAX(shipping_weight)
FROM shipping
WHERE shipping_type = type_id
AND shipping_country = '$shipping_country'
),
(
SELECT shipping_cost
FROM shipping
WHERE shipping_type = type_id
AND shipping_country = '$shipping_country'
ORDER BY shipping_weight DESC
LIMIT 0, 1
),
(
SELECT shipping_cost
FROM shipping
WHERE shipping_type = type_id
AND shipping_country = '$shipping_country'
AND shipping_weight >= '$shipping_weight'
ORDER BY shipping_weight ASC
LIMIT 0, 1
)
) AS shipping_cost
FROM shipping_type
WHERE type_local = 'no'
and vendor_id='$vendor_id'
ORDER BY type_order ASC
";

                                                  } else {

                                                    $select_shipping = "
SELECT *,
IF (
$shipping_weight > (
SELECT MAX(shipping_weight)
FROM shipping
WHERE shipping_type = type_id
AND shipping_country = '$billing_country'
),
(
SELECT shipping_cost
FROM shipping
WHERE shipping_type = type_id
AND shipping_country = '$billing_country'
ORDER BY shipping_weight DESC
LIMIT 0, 1
),
(
SELECT shipping_cost
FROM shipping
WHERE shipping_type = type_id
AND shipping_country = '$billing_country'
AND shipping_weight >= '$shipping_weight'
ORDER BY shipping_weight ASC
LIMIT 0, 1
)
) AS shipping_cost
FROM shipping_type
WHERE type_local = 'no'
and vendor_id='$vendor_id'
ORDER BY type_order ASC
";

                                                  }

                                                  $run_shipping = mysqli_query($con, $select_shipping);

                                                  $i = 0;

                                                  while ($row_shipping = mysqli_fetch_array($run_shipping)) {

                                                    $i++;

                                                    $type_id = $row_shipping["type_id"];

                                                    $type_name = $row_shipping["type_name"];

                                                    $type_default = $row_shipping["type_default"];

                                                    $shipping_cost = $row_shipping["shipping_cost"];

                                                    if (!empty($shipping_cost)) {

                                                      ?>

                                                        <li>

                                                            <input type="radio"
                                                                   name="[<?php echo $vendor_id; ?>][shipping_type]"
                                                                   value="<?php echo $type_id; ?>" class="shipping_type"
                                                                   data-shipping_cost="<?php echo $shipping_cost; ?>"

                                                              <?php

                                                              if ($type_default == "yes") {

                                                                $_SESSION["shipping_type_$vendor_id"] = $type_id;

                                                                $_SESSION["shipping_cost_$vendor_id"] = $shipping_cost;

                                                                echo "checked";

                                                              } elseif ($i == 1) {

                                                                $_SESSION["shipping_type_$vendor_id"] = $type_id;

                                                                $_SESSION["shipping_cost_$vendor_id"] = $shipping_cost;

                                                                echo "checked";

                                                              }

                                                              ?>>

                                                            <span class="shipping-type-name">

<?php echo $type_name; ?>: <span class="text-muted"> Rs:<?php echo $shipping_cost; ?> </span>

</span>

                                                        </li>

                                                      <?php

                                                    }

                                                  }

                                                }

                                              }

                                            }

                                            ?>


                                            <?php

                                          }

                                        }

                                      } else {

                                        echo "

<li> 

<p> There are no shipping types available. Please login to view available shipping types, or contact us if you need any help. </p> 

</li>

";

                                      }

                                      ?>

                                    </ul>

                                </th>

                            </tr>

                          <?php

                          $total_shipping_cost = 0;

                          if (count($physical_products) > 0) {

                            foreach ($vendors_ids as $vendor_id) {

                              if (isset($physical_products[$vendor_id])) {

                                if (isset($_SESSION["shipping_cost_$vendor_id"])) {

                                  $total_shipping_cost += $_SESSION["shipping_cost_$vendor_id"];

                                }

                              }

                            }

                          }

                          $total_cart_price = $total + $total_shipping_cost;

                        }

                        ?>

                        <tr>

                            <td>Tax:</td>

                            <th>Rs:0.00</th>

                        </tr>

                        <tr class="total">

                            <td>Total:</td>

                          <?php if (count($physical_products) > 0) { ?>

                              <th class="total-cart-price">Rs:<?php echo $total_cart_price; ?>.00</th>

                          <?php } else { ?>

                              <th class="total-cart-price">Rs:<?php echo $total; ?>.00</th>

                          <?php } ?>

                        </tr>

                        </tbody><!-- tbody Ends -->

                    </table><!-- table Ends -->

                </div><!-- table-responsive Ends -->

            </div><!-- box Ends -->

        </div><!-- col-md-3 Ends -->

    </div><!-- container Ends -->

</div><!-- content Ends -->

<?php include("includes/footer.php"); ?>

<script src="js/jquery.min.js"></script>

<script src="js/bootstrap.min.js"></script>

<script>

    $(document).ready(function () {

        $(document).on('keyup', '.quantity', function () {

            var value = parseInt($(this).val(), 10);

            var max = parseInt($(this).attr("max"), 10);

            var min = parseInt($(this).attr("min"), 10);

            if (value > max) {

                value = max;

                $(this).val(value);

            } else if (value < min) {

                value = min;

                $(this).val(value);

            }

            var quantity = $(this).val();

            var cart_id = $(this).data("cart_id");

            var product_id = $(this).data("product_id");

            if ($("input[name=shipping_type]").length) {

                var shipping_type = $("input[name=shipping_type]:checked").val();

                var shipping_cost = Number($("input[name=shipping_type]:checked").data("shipping_cost"));

                var post_data = {
                    cart_id: cart_id,
                    product_id: product_id,
                    quantity: quantity,
                    shipping_type: shipping_type,
                    shipping_cost: shipping_cost
                };

            } else {

                var post_data = {cart_id: cart_id, product_id: product_id, quantity: quantity};

            }

            if (quantity != '') {

                $("table").addClass("wait-loader");

                $.ajax({

                    url: "change.php",

                    method: "POST",

                    data: post_data,

                    success: function (data) {

                        $(".subtotal-cart-price").html(data);

                        $("#cart-products-tbody").load('cart_body.php');

                        $("#cart-summary-tbody").load('cart_summary_body.php');

                        $("table").removeClass("wait-loader");

                    }

                });

            }

        });

      <?php if(count($physical_products) > 0 ){ ?>

        $(document).on("change", ".shipping_type", function () {

            var total_shipping_cost = Number(0);

          <?php

          foreach($vendors_ids as $vendor_id){

          if(isset($physical_products[$vendor_id])){

          ?>

            var shipping_cost = Number($("input[name='[<?php echo $vendor_id; ?>][shipping_type]']:checked").data("shipping_cost"));

            total_shipping_cost += shipping_cost;

          <?php } } ?>

            var total = Number(<?php echo $total; ?>);

            var total_cart_price = total + total_shipping_cost;

            $(".total-cart-price").html("Rs:" + total_cart_price + ".00");

        });

      <?php } ?>

    });

</script>

</body>

</html>