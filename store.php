<?php session_start();include("includes/db.php");include("functions/functions.php");?>

<?php
$vendor_username = $_GET["vendor_username"];
$select_customer = "select * from customers where customer_username='$vendor_username'";
$run_customer = mysqli_query($con,$select_customer);
$count_customer = mysqli_num_rows($run_customer);
if($count_customer != 0){
    $row_customer = mysqli_fetch_array($run_customer);
    $vendor_id = $row_customer['customer_id'];
    $vendor_name = $row_customer['customer_name'];
    $vendor_email = $row_customer['customer_email'];
    $vendor_role = $row_customer['customer_role'];
    if($vendor_role == "customer"){
        echo "<script> window.open('../shop.php','_self'); </script>";
    }

}else{
    $select_admin = "select * from admins where admin_username='$vendor_username'";
    $run_admin = mysqli_query($con,$select_admin);
    $row_admin = mysqli_fetch_array($run_admin);
    $vendor_id = "admin_" . $row_admin['admin_id'];
    $vendor_name = $row_admin['admin_name'];
    $vendor_email = $row_admin['admin_email'];
}
$select_store_settings = "select * from store_settings where vendor_id='$vendor_id'";
$run_store_settings = mysqli_query($con,$select_store_settings);
$row_store_settings = mysqli_fetch_array($run_store_settings);
$store_cover_image = $row_store_settings["store_cover_image"];
$store_profile_image = $row_store_settings["store_profile_image"];
$store_name = $row_store_settings["store_name"];
$store_country = $row_store_settings["store_country"];
$store_address_1 = $row_store_settings["store_address_1"];
$store_address_2 = $row_store_settings["store_address_2"];
$store_state = $row_store_settings["store_state"];
$store_city = $row_store_settings["store_city"];
$store_postcode = $row_store_settings["store_postcode"];
$phone_no = $row_store_settings["phone_no"];
$store_email = $row_store_settings["store_email"];
$seo_title = $row_store_settings["seo_title"];
$meta_author = $row_store_settings["meta_author"];
$meta_description = $row_store_settings["meta_description"];
$meta_keywords = $row_store_settings["meta_keywords"];
if(empty($store_name)){
    $store_name = $vendor_name;
}
if(empty($seo_title)){
    $seo_title = $vendor_name;

}
if(empty($store_profile_image)){
    $store_profile_image = "empty-image.png";
}
if(empty($store_cover_image)){
    $store_cover_image = "empty-cover-image.jpg";
}
$products_ids = array();
$select_products = "select * from products where vendor_id='$vendor_id'";
$run_products = mysqli_query($db,$select_products);
while($row_products = mysqli_fetch_array($run_products)){
    $product_id = $row_products["product_id"];
    array_push($products_ids, $product_id);
}
$products_ids = implode(",", $products_ids);
$count_vendor_products_reviews = 0;
$vendor_rating = 0;
$star_vendor_rating = 0;
$vendor_reviews_rating = array();
if(!empty($products_ids)){
    $select_vendor_products_reviews = "select * from reviews where product_id in ($products_ids) and NOT ( review_status='trash' or review_status='pending')";
    $run_vendor_products_reviews = mysqli_query($con,$select_vendor_products_reviews);
    $count_vendor_products_reviews = mysqli_num_rows($run_vendor_products_reviews);
    if($count_vendor_products_reviews != 0){
        while($row_vendor_products_reviews = mysqli_fetch_array($run_vendor_products_reviews)){
            $product_review_rating = $row_vendor_products_reviews['review_rating'];
            array_push($vendor_reviews_rating,$product_review_rating);
        }
        $total = array_sum($vendor_reviews_rating);
        $vendor_rating = $total/count($vendor_reviews_rating);
        $star_vendor_rating = substr($vendor_rating, 0, 1);
    }
}
$include_page = "vendor_store"
?>
<!DOCTYPE html>
<html>
<head>
    <title> <?php echo $seo_title; ?> </title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="<?php echo $meta_author; ?>">
    <meta name="description" content="<?php echo $meta_description; ?>">
    <meta name="keywords" content="<?php echo $meta_keywords; ?>">
    <link href="http://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet" >
    <link href="../styles/bootstrap.min.css" rel="stylesheet">
    <link href="../styles/style.css" rel="stylesheet">
    <link href="../font-awesome/css/font-awesome.min.css" rel="stylesheet">
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
            <a href="#" class="btn btn-success btn-sm" >
              <?php
              if(!isset($_SESSION['customer_email'])){
                  echo "Welcome :Guest";
              }else{
                  echo "Welcome : " . $_SESSION['customer_email'] . "";
              }
              ?>
            </a>
            <a href="#">Shopping Cart Total Price: <?php total_price(); ?>, Total Items <?php items(); ?></a>
        </div><!-- col-md-6 offer Ends -->

        <div class="col-md-6"><!-- col-md-6 Starts -->
            <ul class="menu"><!-- menu Starts -->
              <?php if(!isset($_SESSION['customer_email'])){ ?>
                  <li>
                      <a href="../customer_register.php"> Register </a>
                  </li>
                <?php
              }else{
                  $customer_email = $_SESSION['customer_email'];
                  $select_customer = "select * from customers where customer_email='$customer_email'";
                  $run_customer = mysqli_query($con,$select_customer);
                  $row_customer = mysqli_fetch_array($run_customer);
                  $customer_role = $row_customer['customer_role'];
                  if($customer_role == "customer"){
                      ?>
                      <li>
                          <a href="../shop.php"> Shop </a>
                      </li>
                  <?php }elseif($customer_role == "vendor"){ ?>
                      <li>
                          <a href="../vendor_dashboard/index.php"> Vendor Dashboard </a>
                      </li>
                  <?php } } ?>
                <li>
                  <?php
                  if(!isset($_SESSION['customer_email'])){
                      echo "<a href='../checkout.php' >My Account</a>";
                  }
                  else{
                      echo "<a href='../customer/my_account.php?my_orders'>My Account</a>";
                  }
                  ?>
                </li>

                <li>
                    <a href="../cart.php">
                        Go to Cart
                    </a>
                </li>

                <li>
                  <?php

                  if (!isset($_SESSION['customer_email'])) {

                    echo "<a href='../checkout.php'> Login </a>";

                  } else {

                    echo "<a href='../logout.php'> Logout </a>";

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

            <a class="navbar-brand home" href="../index.php"><!--- navbar navbar-brand home Starts -->

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
                        <a href="../index.php"> Home </a>
                    </li>

                    <li class="active">
                        <a href="../shop.php"> Shop </a>
                    </li>

                    <li>
                      <?php

                      if (!isset($_SESSION['customer_email'])) {

                        echo "<a href='../checkout.php' >My Account</a>";

                      } else {

                        echo "<a href='../customer/my_account.php?my_orders'>My Account</a>";

                      }

                      ?>
                    </li>

                    <li>
                        <a href="../cart.php"> Shopping Cart </a>
                    </li>

                    <li>
                        <a href="../about.php"> About Us </a>
                    </li>

                    <li>

                        <a href="../services.php"> Services </a>

                    </li>

                    <li>
                        <a href="../contact.php"> Contact Us </a>
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

                <li> Vendor Store</li>

                <li> <?php echo $vendor_username; ?> </li>

            </ul><!-- breadcrumb Ends -->

        </div><!--- col-md-12 Ends -->

        <div class="col-md-3"><!-- col-md-3 Starts -->

          <?php include("includes/sidebar.php"); ?>

        </div><!-- col-md-3 Ends -->

        <div class="col-md-9"><!-- col-md-9 Starts --->

            <div class="vendor-profile-box"
                 style="background-image:url(../images/<?php echo $store_cover_image; ?>); background-size:cover;">
                <!-- box Starts -->

                <div class="row"><!-- row Starts -->

                    <div class="vendor-profile-frame col-md-6"><!-- vendor-profile-frame col-md-6 Starts -->

                        <img src="../images/<?php echo $store_profile_image; ?>">

                        <h2> <?php echo $store_name; ?> </h2>

                      <?php if (!empty($store_address_1) and !empty($store_address_2)) { ?>

                          <p>

                              <i class="fa fa-map-marker"></i>

                            <?php echo $store_address_1; ?>,

                            <?php echo $store_address_2; ?>,<br>

                            <?php echo $store_city; ?>,

                            <?php echo $store_state; ?>,

                            <?php echo $store_postcode; ?>

                            <?php

                            $select_country = "select * from countries where country_id='$store_country'";

                            $run_country = mysqli_query($con, $select_country);

                            $row_country = mysqli_fetch_array($run_country);

                            echo $country_name = $row_country["country_name"];

                            ?>

                          </p>

                      <?php } ?>

                      <?php if (!empty($phone_no)) { ?>

                          <p>

                              <i class="fa fa-mobile"></i>

                              <a href="tel:<?php echo $phone_no; ?>"> <?php echo $phone_no; ?> </a>

                          </p>

                      <?php } ?>

                      <?php if ($store_email == "yes") { ?>

                          <p>

                              <i class="fa fa-envelope-o"></i>

                              <a href="mailto:<?php echo $vendor_email; ?>"> <?php echo $vendor_email; ?> </a>

                          </p>

                      <?php } ?>

                        <p>

                            <i class="fa fa-star"></i>

                          <?php printf("%.1f", $vendor_rating); ?> rating
                            from <?php echo $count_vendor_products_reviews; ?> reviews

                        </p>

                    </div><!-- vendor-profile-frame col-md-6 Ends -->

                </div><!-- row Ends -->

            </div><!-- box Ends -->

            <div class="row flex-wrap" id="Products"><!-- row Starts -->

              <?php getProducts($vendor_id); ?>

            </div><!-- row Ends -->

            <center><!-- center Starts -->

                <ul class="pagination"><!-- pagination Starts -->

                  <?php getPaginator($vendor_id); ?>

                </ul><!-- pagination Ends -->

            </center><!-- center Ends -->

        </div><!-- col-md-9 Ends --->

        <div id="wait" style="position:absolute;top:40%;left:45%;padding:100px;padding-top:200px;"><!--- wait Starts -->

        </div><!--- wait Ends -->

    </div><!-- container Ends -->

</div><!-- content Ends -->
<?php include("includes/footer.php"); ?>
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script>

    $(document).ready(function () {

/// Hide And Show Code Starts ///

        $('.nav-toggle').click(function () {

            $(".panel-collapse,.collapse-data").slideToggle(700, function () {

                if ($(this).css('display') == 'none') {

                    $(".hide-show").html('Show');

                } else {

                    $(".hide-show").html('Hide');

                }

            });

        });

/// Hide And Show Code Ends ///

/// Search Filters code Starts /// 

        $(function () {

            $.fn.extend({

                filterTable: function () {

                    return this.each(function () {

                        $(this).on('keyup', function () {

                            var $this = $(this),

                                search = $this.val().toLowerCase(),

                                target = $this.attr('data-filters'),

                                handle = $(target),

                                rows = handle.find('li a');

                            if (search == '') {

                                rows.show();

                            } else {

                                rows.each(function () {

                                    var $this = $(this);

                                    $this.text().toLowerCase().indexOf(search) === -1 ? $this.hide() : $this.show();

                                });

                            }

                        });

                    });

                }

            });

            $('[data-action="filter"][id="dev-table-filter"]').filterTable();

        });

/// Search Filters code Ends /// 

    });


</script>


<script>


    $(document).ready(function () {

        // getProducts Function Code Starts

        function getProducts() {

            // Manufacturers Code Starts

            var sPath = '';

            var aInputs = $('li').find('.get_manufacturer');

            var aKeys = Array();

            var aValues = Array();

            iKey = 0;

            $.each(aInputs, function (key, oInput) {

                if (oInput.checked) {

                    aKeys[iKey] = oInput.value

                }
                iKey++;

            });

            if (aKeys.length > 0) {

                var sPath = '';

                for (var i = 0; i < aKeys.length; i++) {

                    sPath = sPath + 'man[]=' + aKeys[i] + '&';

                }

            }

// Manufacturers Code ENDS 

// Products Categories Code Starts 

            var aInputs = Array();

            var aInputs = $('li').find('.get_p_cat');

            var aKeys = Array();

            var aValues = Array();

            iKey = 0;

            $.each(aInputs, function (key, oInput) {

                if (oInput.checked) {

                    aKeys[iKey] = oInput.value

                }
                iKey++;

            });

            if (aKeys.length > 0) {

                for (var i = 0; i < aKeys.length; i++) {

                    sPath = sPath + 'p_cat[]=' + aKeys[i] + '&';

                }

            }

// Products Categories Code ENDS 

            // Categories Code Starts

            var aInputs = Array();

            var aInputs = $('li').find('.get_cat');

            var aKeys = Array();

            var aValues = Array();

            iKey = 0;

            $.each(aInputs, function (key, oInput) {

                if (oInput.checked) {

                    aKeys[iKey] = oInput.value

                }
                iKey++;

            });

            if (aKeys.length > 0) {

                for (var i = 0; i < aKeys.length; i++) {

                    sPath = sPath + 'cat[]=' + aKeys[i] + '&';

                }

            }

            // Categories Code ENDS

            // Loader Code Starts

            $('#wait').html('<img src="../images/load.gif">');

// Loader Code ENDS

// ajax Code Starts 

            $.ajax({

                url: "../load.php",

                method: "POST",

                data: sPath + 'sAction=getProducts&vendor_id=<?php echo $vendor_id; ?>',

                success: function (data) {

                    $('#Products').html('');

                    $('#Products').html(data);

                    $("#wait").empty();

                }

            });

            $.ajax({
                url: "../load.php",
                method: "POST",
                data: sPath + 'sAction=getPaginator&vendor_id=<?php echo $vendor_id; ?>',
                success: function (data) {
                    $('.pagination').html('');
                    $('.pagination').html(data);
                }
            });
// ajax Code Ends
        }
        // getProducts Function Code Ends
        $('.get_manufacturer').click(function () {
            getProducts();
        });

        $('.get_p_cat').click(function () {
            getProducts();
        });
        $('.get_cat').click(function () {
            getProducts();
        });
    });
</script>
</body>
</html>