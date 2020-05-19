<?php
if (!isset($_SESSION['customer_email'])) {
  echo "<script> window.open('../checkout.php','_self'); </script>";
}

$customer_email = $_SESSION['customer_email'];
$get_customer = "select * from customers where customer_email='$customer_email'";
$run_customer = mysqli_query($con, $get_customer);
$row_customer = mysqli_fetch_array($run_customer);
$customer_id = $row_customer['customer_id'];

function echo_active_class($order_status)
{
  if ((!isset($_GET['order_status']) and $order_status == "all") or (isset($_GET['order_status']) and $order_status == $_GET['order_status'])) {
    echo "active-link";
  }
}

$select_vendor_orders = "select * from vendor_orders where vendor_id='$customer_id'";
$run_vendor_orders = mysqli_query($con, $select_vendor_orders);
$count_vendor_orders = mysqli_num_rows($run_vendor_orders);

function get_orders_status_count($order_status)
{
  global $con;
  global $customer_id;

  $select_vendor_orders = "select * from vendor_orders where vendor_id='$customer_id' and order_status='$order_status'";
  $run_vendor_orders = mysqli_query($con, $select_vendor_orders);

  echo $count_vendor_orders = mysqli_num_rows($run_vendor_orders);

}

?>

<div class="row"><!-- 2 row Starts -->
    <div class="col-lg-12"><!-- col-lg-12 Starts -->
        <div class="panel panel-default"><!-- panel panel-default Starts -->
            <div class="panel-heading"><!-- panel-heading Starts -->
                <h3 class="panel-title"><!-- panel-title Starts -->
                    <i class="fa fa-money fa-fw"></i> View Orders
                </h3><!-- panel-title Ends -->
            </div><!-- panel-heading Ends -->

            <div class="panel-body"><!-- panel-body Starts -->
                <a href="index.php?orders&order_status=all" class="link-separator <?php echo_active_class("all"); ?>">
                    All (<?php echo $count_vendor_orders; ?>)
                </a>

                <a class="link-separator">|</a>

                <a href="index.php?orders&order_status=pending"
                   class="link-separator <?php echo_active_class("pending"); ?>">

                    Pending (<?php get_orders_status_count("pending"); ?>)

                </a>

                <a class="link-separator">|</a>

                <a href="index.php?orders&order_status=on hold"
                   class="link-separator <?php echo_active_class("on hold"); ?>">

                    On Hold (<?php get_orders_status_count("on hold"); ?>)

                </a>

                <a class="link-separator">|</a>

                <a href="index.php?orders&order_status=processing"
                   class="link-separator <?php echo_active_class("processing"); ?>">

                    Processing (<?php get_orders_status_count("processing"); ?>)

                </a>

                <a class="link-separator">|</a>

                <a href="index.php?orders&order_status=completed"
                   class="link-separator <?php echo_active_class("completed"); ?>">

                    Completed (<?php get_orders_status_count("completed"); ?>)

                </a>

                <a class="link-separator">|</a>

                <a href="index.php?orders&order_status=cancelled"
                   class="link-separator <?php echo_active_class("cancelled"); ?>">

                    Cancelled (<?php get_orders_status_count("cancelled"); ?>)

                </a>

                <a class="link-separator">|</a>

                <a href="index.php?orders&order_status=refunded"
                   class="link-separator <?php echo_active_class("refunded"); ?>">

                    Refunded (<?php get_orders_status_count("refunded"); ?>)

                </a>

                <br><br>

                <div class="table-responsive"><!-- table-responsive Starts -->

                    <table class="table table-bordered table-hover table-striped">
                        <!-- table table-bordered table-hover table-striped Starts -->

                        <thead>

                        <tr>

                            <th>Order No:</th>

                            <th>Invoice No:</th>

                            <th>Order Shipping:</th>

                            <th>Order Date:</th>

                            <th>Order Status:</th>

                            <th>Order Total</th>

                            <th>Actions:</th>

                        </tr>

                        </thead>

                        <tbody>

                        <?php

                        if (!isset($_GET['order_status']) or $_GET['order_status'] == "all") {

                          $select_vendor_orders = "select * from vendor_orders where vendor_id='$customer_id' order by 1 desc";

                        } elseif (isset($_GET['order_status'])) {

                          $order_status = $_GET['order_status'];

                          $select_vendor_orders = "select * from vendor_orders where vendor_id='$customer_id' and order_status='$order_status' order by 1 desc";

                        }

                        $run_vendor_orders = mysqli_query($con, $select_vendor_orders);

                        $i = 0;

                        while ($row_vendor_orders = mysqli_fetch_array($run_vendor_orders)) {

                          $sub_order_id = $row_vendor_orders['id'];

                          $order_id = $row_vendor_orders['order_id'];

                          $vendor_id = $row_vendor_orders['vendor_id'];

                          $invoice_no = $row_vendor_orders['invoice_no'];

                          $shipping_type = $row_vendor_orders['shipping_type'];

                          $shipping_cost = $row_vendor_orders['shipping_cost'];

                          $order_total = $row_vendor_orders['order_total'];

                          $order_status = $row_vendor_orders['order_status'];

                          $select_order = "select * from orders where order_id='$order_id'";

                          $run_order = mysqli_query($con, $select_order);

                          $row_order = mysqli_fetch_array($run_order);

                          $payment_method = $row_order['payment_method'];

                          $order_date = $row_order['order_date'];

                          $i++;

                          ?>

                            <tr><!-- tr Starts -->

                                <th><?php echo $i; ?></th>

                                <td>#<?php echo $invoice_no; ?></td>

                                <th>

                                  <?php if (!empty($shipping_type)) { ?>

                                      <span class="text-muted"> <i
                                                  class="fa fa-truck"></i> <?php echo $shipping_type; ?>: </span>

                                      Rs:<?php echo $shipping_cost; ?>

                                  <?php } else { ?>

                                      Shipping None ----

                                  <?php } ?>

                                </th>

                                <td><?php echo $order_date; ?></td>

                                <td>

                                  <?php

                                  if ($order_status == "pending") {

                                    echo ucwords($order_status . " Payment");

                                  } else {

                                    echo ucwords($order_status);

                                  }

                                  ?>

                                </td>

                                <td>

                                    <strong>Rs:<?php echo $order_total; ?></strong>

                                    for <?php

                                  $total = 0;

                                  $select_orders_items = "select * from orders_items where order_id='$order_id'";

                                  $run_orders_items = mysqli_query($con, $select_orders_items);

                                  while ($row_orders_items = mysqli_fetch_array($run_orders_items)) {

                                    $product_id = $row_orders_items['product_id'];

                                    $product_qty = $row_orders_items['qty'];

                                    $select_product = "select * from products where product_id='$product_id' and vendor_id='$vendor_id'";

                                    $run_product = mysqli_query($con, $select_product);

                                    $count_product = mysqli_num_rows($run_product);

                                    if ($count_product != 0) {

                                      $total += $product_qty;

                                    }

                                  }

                                  if ($total == 1) {

                                    echo $total . " item";

                                  } else {

                                    echo $total . " items";

                                  }

                                  ?>
                                    <br><span class="text-muted"> Via <?php echo ucwords($payment_method); ?> </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li><a href="index.php?view_order_id=<?php echo $sub_order_id; ?>"> View /
                                                    Edit </a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr><!-- tr Ends -->
                        <?php } ?>
                        </tbody>
                    </table><!-- table table-bordered table-hover table-striped Ends -->
                </div><!-- table-responsive Ends -->
            </div><!-- panel-body Ends -->
        </div><!-- panel panel-default Ends -->
    </div><!-- col-lg-12 Ends -->
</div><!-- 2 row Ends -->