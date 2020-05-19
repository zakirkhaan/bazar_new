<?php
if (!isset($_SESSION['customer_email'])) {
  echo "<script> window.open('../checkout.php','_self'); </script>";
}
$customer_email = $_SESSION['customer_email'];
$select_customer = "select * from customers where customer_email='$customer_email'";
$run_customer = mysqli_query($con, $select_customer);
$row_customer = mysqli_fetch_array($run_customer);
$customer_id = $row_customer['customer_id'];
$sales_count = 0;
$earnings_count = 0;
$pageviews_count = 0;
$select_vendor_orders = "select * from vendor_orders where vendor_id='$customer_id'";
$run_vendor_orders = mysqli_query($con, $select_vendor_orders);
$count_vendor_orders = mysqli_num_rows($run_vendor_orders);
while ($row_vendor_orders = mysqli_fetch_array($run_vendor_orders)) {
    $order_total = $row_vendor_orders['order_total'];
    $net_amount = $row_vendor_orders['net_amount'];
    $order_status = $row_vendor_orders['order_status'];
    $sales_count += $order_total;
    if ($order_status == "completed") {
    $earnings_count += $net_amount;
  }
}
$products_ids = array();
$select_products = "select * from products where vendor_id='$customer_id'";
$run_products = mysqli_query($con, $select_products);
while ($row_products = mysqli_fetch_array($run_products)) {
  $product_id = $row_products["product_id"];
  $product_views = $row_products["product_views"];
  $pageviews_count += $product_views;
  array_push($products_ids, $product_id);
}

function get_orders_status_count($order_status)
{
  global $con;
  global $customer_id;
  $select_vendor_orders = "select * from vendor_orders where vendor_id='$customer_id' and order_status='$order_status'";
  $run_vendor_orders = mysqli_query($con, $select_vendor_orders);
  echo $count_vendor_orders = mysqli_num_rows($run_vendor_orders);
}

function get_products_status_count($product_vendor_status)
{
  global $con;
  global $customer_id;
  if (empty($product_vendor_status)) {
    $select_products = "select * from products where vendor_id='$customer_id' and not product_vendor_status='trash'";
  } else {
    $select_products = "select * from products where vendor_id='$customer_id' and product_vendor_status='$product_vendor_status'";
  }

  $run_products = mysqli_query($con, $select_products);
  echo $count_products = mysqli_num_rows($run_products);
}

function get_reviews_status_count($review_status)
{
  global $con;
  global $products_ids;
  $reviews_products_ids = implode(",", $products_ids);

  if (empty($review_status)) {
    $select_reviews = "select * from reviews where product_id in ($reviews_products_ids)";
  } else {
    $select_reviews = "select * from reviews where product_id in ($reviews_products_ids) and review_status='$review_status'";
  }
  $run_reviews = mysqli_query($con, $select_reviews);
  echo $count_reviews = mysqli_num_rows($run_reviews);
}

?>

<div class="row"><!-- row Starts -->
    <div class="col-md-6"><!-- col-md-6 Starts -->
        <div class="panel panel-default"><!-- panel panel-default Starts -->
            <div class="panel-body text-center"><!-- panel-body Starts -->
                <h4 class="text-muted"> Sales </h4>
                <h3> Rs:<?php echo $sales_count; ?> </h3>
                <hr>
                <h4 class="text-muted"> Earnings </h4>
                <h3> Rs:<?php echo $earnings_count; ?> </h3>
                <hr>
                <h4 class="text-muted"> Pageviews </h4>
                <h3> <?php echo $pageviews_count; ?> </h3>
                <hr>
                <h4 class="text-muted"> Orders </h4>
                <h3> <?php echo $count_vendor_orders; ?> </h3>
            </div><!-- panel-body Ends -->
        </div><!-- panel panel-default Ends -->

        <div class="panel panel-default"><!-- panel panel-default Starts -->
            <div class="panel-heading"><!-- panel-heading Starts -->
                <h3 class="panel-title"><!-- panel-title Starts -->
                    <i class="fa fa-list"> </i> Orders
                </h3><!-- panel-title Ends -->
            </div><!-- panel-heading Ends -->

            <div class="panel-body"><!-- panel-body Starts -->
                <ul class="list-group">
                    <li class="list-group-item">
                        All <span class="badge"><?php echo $count_vendor_orders; ?></span>
                    </li>

                    <li class="list-group-item list-group-item-primary">
                        Processing <span class="badge"> <?php get_orders_status_count("processing"); ?> </span>
                    </li>

                    <li class="list-group-item list-group-item-success">
                        Completed <span class="badge"> <?php get_orders_status_count("completed"); ?> </span>
                    </li>

                    <li class="list-group-item list-group-item-info">
                        Pending <span class="badge"> <?php get_orders_status_count("pending"); ?> </span>
                    </li>

                    <li class="list-group-item list-group-item-warning">
                        Cancelled <span class="badge"> <?php get_orders_status_count("cancelled"); ?> </span>
                    </li>

                    <li class="list-group-item list-group-item-danger">
                        Refunded <span class="badge"> <?php get_orders_status_count("refunded"); ?> </span>
                    </li>
                </ul>
                <hr>

                <div class="text-right"><!-- text-right Starts -->
                    <a href="index.php?orders">
                        View All Orders <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div><!-- text-right Ends -->
            </div><!-- panel-body Ends -->
        </div><!-- panel panel-default Ends -->
    </div><!--- col-md-6 Ends -->

    <div class="col-md-6"><!-- col-md-6 Starts -->
        <div class="panel panel-default"><!-- panel panel-default Starts -->
            <div class="panel-heading"><!-- panel-heading Starts -->
                <h3 class="panel-title"><!-- panel-title Starts -->
                    <i class="fa fa-list"></i> Products & Bundles
                    <span class="pull-right"> <a href="index.php?insert_product"> + Insert New Product </a> </span>
                </h3><!-- panel-title Ends -->
            </div><!-- panel-heading Ends -->

            <div class="panel-body"><!-- panel-body Starts -->
                <ul class="list-group">
                    <li class="list-group-item">
                        All <span class="badge"> <?php get_products_status_count(""); ?> </span>
                    </li>
                    <li class="list-group-item list-group-item-success">
                        Active <span class="badge"> <?php get_products_status_count("active"); ?> </span>
                    </li>
                    <li class="list-group-item list-group-item-info">
                        Paused <span class="badge"> <?php get_products_status_count("paused"); ?> </span>
                    </li>
                    <li class="list-group-item list-group-item-danger">
                        Pending Review <span class="badge"> <?php get_products_status_count("pending"); ?> </span>
                    </li>
                </ul>
                <hr>
                <div class="text-right"><!-- text-right Starts -->
                    <a href="index.php?products">
                        View All Products <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div><!-- text-right Ends -->
            </div><!-- panel-body Ends -->

        </div><!-- panel panel-default Ends -->

        <div class="panel panel-default"><!-- panel panel-default Starts -->
            <div class="panel-heading"><!-- panel-heading Starts -->
                <h3 class="panel-title"><!-- panel-title Starts -->
                    <i class="fa fa-list"></i> Reviews
                </h3><!-- panel-title Ends -->
            </div><!-- panel-heading Ends -->

            <div class="panel-body"><!-- panel-body Starts -->

                <ul class="list-group">
                    <li class="list-group-item">
                        All <span class="badge"> <?php get_reviews_status_count(""); ?> </span>
                    </li>
                    <li class="list-group-item list-group-item-success">
                        Active <span class="badge"> <?php get_reviews_status_count("active"); ?> </span>
                    </li>
                    <li class="list-group-item list-group-item-success">
                        Pending <span class="badge"> <?php get_reviews_status_count("pending"); ?> </span>
                    </li>
                    <li class="list-group-item list-group-item-info">
                        Spam <span class="badge"> <?php get_reviews_status_count("spam"); ?> </span>
                    </li>
                    <li class="list-group-item list-group-item-danger">
                        Trash <span class="badge"> <?php get_reviews_status_count("trash"); ?> </span>
                    </li>
                </ul>
                <hr>
                <div class="text-right"><!-- text-right Starts -->
                    <a href="index.php?reviews">
                        View All Reviews <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div><!-- text-right Ends -->
            </div><!-- panel-body Ends -->
        </div><!-- panel panel-default Ends -->
    </div><!--- col-md-6 Ends -->
</div><!-- row Ends -->

