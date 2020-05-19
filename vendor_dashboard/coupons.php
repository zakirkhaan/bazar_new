<?php

if (!isset($_SESSION['customer_email'])) {
  echo "<script> window.open('../checkout.php','_self'); </script>";

}

$customer_email = $_SESSION['customer_email'];
$get_customer = "select * from customers where customer_email='$customer_email'";
$run_customer = mysqli_query($con, $get_customer);
$row_customer = mysqli_fetch_array($run_customer);
$customer_id = $row_customer['customer_id'];
?>

<div class="row"><!-- 2 row Starts -->
    <div class="col-lg-12"><!-- col-lg-12 Starts -->
        <div class="panel panel-default"><!-- panel panel-default Starts -->
            <div class="panel-heading"><!-- panel-heading Starts -->
                <h3 class="panel-title"><!-- panel-title Starts -->
                    <i class="fa fa-money fa-fw"></i> View Coupons
                </h3><!-- panel-title Ends -->
            </div><!-- panel-heading Ends -->

            <div class="panel-body"><!-- panel-body Starts -->
                <a href="index.php?insert_coupon" class="btn btn-success pull-right">
                    <i class="fa fa-gift"></i> Add new Coupon
                </a>
                <br>
                <br>
                <br>
                <div class="table-responsive"><!-- table-responsive Starts -->
                    <table class="table table-bordered table-hover table-striped">
                        <!-- table table-bordered table-hover table-striped Starts -->
                        <thead><!-- thead Starts -->
                        <tr>
                            <th> Coupon Title:</th>
                            <th> Coupon Product:</th>
                            <th> Coupon Price:</th>
                            <th> Coupon Code:</th>
                            <th> Limit:</th>
                            <th> Used:</th>
                            <th> Actions:</th>
                        </tr>
                        </thead><!-- thead Ends -->
                        <tbody><!-- tbody Starts -->
                        <?php
                        $select_coupons = "select * from coupons where vendor_id='$customer_id'";
                        $run_coupons = mysqli_query($con, $select_coupons);
                        while ($row_coupons = mysqli_fetch_array($run_coupons)) {
                            $coupon_id = $row_coupons['coupon_id'];
                            $product_id = $row_coupons['product_id'];
                            $coupon_title = $row_coupons['coupon_title'];
                            $coupon_price = $row_coupons['coupon_price'];
                            $coupon_code = $row_coupons['coupon_code'];
                            $coupon_limit = $row_coupons['coupon_limit'];
                            $coupon_used = $row_coupons['coupon_used'];
                          $get_products = "select * from products where product_id='$product_id'";
                          $run_products = mysqli_query($con, $get_products);
                          $row_products = mysqli_fetch_array($run_products);
                          $product_title = $row_products['product_title'];
                          ?>

                            <tr>
                                <td><?php echo $coupon_title; ?></td>
                                <td width="300"><?php echo $product_title; ?></td>
                                <td><?php echo "Rs:$coupon_price"; ?></td>
                                <td><?php echo $coupon_code; ?></td>
                                <td><?php echo $coupon_limit; ?></td>
                                <td><?php echo $coupon_used; ?></td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li>
                                                <a href="index.php?edit_coupon=<?php echo $coupon_id; ?>">
                                                    <i class="fa fa-pencil"></i> Edit
                                                </a>

                                            </li>

                                            <li>
                                                <a href="index.php?delete_coupon=<?php echo $coupon_id; ?>">
                                                    <i class="fa fa-trash-o"></i> Delete
                                                </a>

                                            </li>

                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody><!-- tbody Ends -->
                    </table><!-- table table-bordered table-hover table-striped Ends -->
                </div><!-- table-responsive Ends -->
            </div><!-- panel-body Ends -->
        </div><!-- panel panel-default Ends -->
    </div><!-- col-lg-12 Ends -->
</div><!-- 2 row Ends -->