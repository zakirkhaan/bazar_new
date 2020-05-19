<?php

if (!isset($_SESSION['customer_email'])) {

  echo "<script> window.open('../checkout.php','_self'); </script>";

}

$customer_email = $_SESSION['customer_email'];

$get_customer = "select * from customers where customer_email='$customer_email'";

$run_customer = mysqli_query($con, $get_customer);

$row_customer = mysqli_fetch_array($run_customer);

$customer_id = $row_customer['customer_id'];

function echo_active_class($product_status)
{

  if ((!isset($_GET['product_status']) and $product_status == "all") or (isset($_GET['product_status']) and $product_status == $_GET['product_status'])) {

    echo "active-link";

  }

}

function get_products_status_count($product_vendor_status)
{

  global $con;

  global $customer_id;

  if (empty($product_vendor_status)) {

    $select_products = "select * from products where status='bundle' and vendor_id='$customer_id' and not product_vendor_status='trash'";

  } else {

    $select_products = "select * from products where status='bundle' and vendor_id='$customer_id' and product_vendor_status='$product_vendor_status'";

  }

  $run_products = mysqli_query($con, $select_products);

  echo $count_products = mysqli_num_rows($run_products);

}

?>

<div class="row"><!-- 2 row Starts -->

    <div class="col-lg-12"><!-- col-lg-12 Starts -->

        <div class="panel panel-default"><!-- panel panel-default Starts -->

            <div class="panel-heading"><!-- panel-heading Starts -->

                <h3 class="panel-title"><!-- panel-title Starts -->

                    <i class="fa fa-money fa-fw"></i> View Bundles

                </h3><!-- panel-title Ends -->


            </div><!-- panel-heading Ends -->

            <div class="panel-body"><!-- panel-body Starts -->

                <a href="index.php?bundles&product_status=all"
                   class="link-separator <?php echo_active_class("all"); ?> pull-left">

                    All (<?php get_products_status_count(""); ?>)

                </a>

                <a class="link-separator pull-left">|</a>

                <a href="index.php?bundles&product_status=active"
                   class="link-separator <?php echo_active_class("active"); ?> pull-left">

                    Active (<?php get_products_status_count("active"); ?>)

                </a>

                <a class="link-separator pull-left">|</a>

                <a href="index.php?bundles&product_status=paused"
                   class="link-separator <?php echo_active_class("paused"); ?> pull-left">

                    Paused (<?php get_products_status_count("paused"); ?>)

                </a>

                <a class="link-separator pull-left">|</a>

                <a href="index.php?bundles&product_status=pending"
                   class="link-separator <?php echo_active_class("pending"); ?> pull-left">

                    Pending (<?php get_products_status_count("pending"); ?>)

                </a>

                <a href="index.php?insert_bundle" class="btn btn-success pull-right"> + Insert New Bundle </a>

                <br><br><br>

                <div class="table-responsive"><!-- table-responsive Starts -->

                    <table class="table table-bordered table-hover table-striped">
                        <!-- table table-bordered table-hover table-striped Starts -->

                        <thead>

                        <tr>

                            <th colspan="2">Bundle Title</th>

                            <th>Bundle Status</th>

                            <th>Bundle Stock</th>

                            <th>Bundle Price</th>

                            <th>Bundle Sold</th>

                            <th>Bundle Keywords</th>

                            <th>Bundle Date</th>

                            <th>Bundle Actions</th>

                        </tr>

                        </thead>

                        <tbody>

                        <?php

                        if (!isset($_GET['product_status']) or $_GET['product_status'] == "all") {

                          $select_products = "select * from products where vendor_id='$customer_id' and status='bundle' and not product_vendor_status='trash'";

                        } elseif (isset($_GET['product_status'])) {

                          $product_status = $_GET['product_status'];

                          $select_products = "select * from products where vendor_id='$customer_id' and status='bundle' and product_vendor_status='$product_status'";

                        }

                        $run_products = mysqli_query($con, $select_products);

                        while ($row_products = mysqli_fetch_array($run_products)) {

                          $product_id = $row_products['product_id'];

                          $product_title = $row_products['product_title'];

                          $product_url = $row_products['product_url'];

                          $product_img1 = $row_products['product_img1'];

                          $product_price = $row_products['product_price'];

                          $product_psp_price = $row_products['product_psp_price'];

                          $product_keywords = $row_products['product_keywords'];

                          $product_date = $row_products['date'];

                          $product_type = $row_products['product_type'];

                          $product_vendor_status = $row_products['product_vendor_status'];

                          $select_product_stock = "select * from products_stock where product_id='$product_id'";

                          $run_product_stock = mysqli_query($con, $select_product_stock);

                          if ($product_type != "variable_product") {

                            $row_product_stock = mysqli_fetch_array($run_product_stock);

                            $stock_status = $row_product_stock["stock_status"];

                            $stock_quantity = $row_product_stock["stock_quantity"];

                          } else {

                            $instock = 0;

                            $outofstock = 0;

                            $onbackorder = 0;

                            while ($row_product_stock = mysqli_fetch_array($run_product_stock)) {

                              $stock_status = $row_product_stock["stock_status"];

                              if ($stock_status == "instock") {

                                $instock += $row_product_stock["stock_quantity"];

                              } elseif ($stock_status == "outofstock") {

                                $outofstock += $row_product_stock["stock_quantity"];

                              } elseif ($stock_status == "onbackorder") {

                                $onbackorder += $row_product_stock["stock_quantity"];

                              }

                            }

                          }

                          ?>

                            <tr>

                                <td><img src="../admin_area/product_images/<?php echo $product_img1; ?>" width="60"
                                         height="60"></td>

                                <td class="bold">

                                  <?php echo $product_title; ?>

                                    <br>

                                    <small>

                                      <?php if ($product_type == "physical_product") { ?>

                                          Physical Bundle

                                      <?php } elseif ($product_type == "digital_product") { ?>

                                          Digital Product

                                      <?php } elseif ($product_type == "variable_product") { ?>

                                          Variable Product

                                      <?php } ?>

                                    </small>

                                </td>

                                <td>

                                    <h4>

                                      <?php if ($product_vendor_status == "active") { ?>

                                          <span class="label label-success"> <?php echo ucwords($product_vendor_status); ?> </span>

                                      <?php } elseif ($product_vendor_status == "pending") { ?>

                                          <span class="label label-info"> <?php echo ucwords($product_vendor_status); ?> </span>

                                      <?php } elseif ($product_vendor_status == "paused") { ?>

                                          <span class="label label-warning"> <?php echo ucwords($product_vendor_status); ?> </span>

                                      <?php } ?>

                                    </h4>

                                </td>

                                <td width="140" class="bold">

                                  <?php if ($product_type != "variable_product") { ?>

                                    <?php if ($stock_status == "instock") { ?>

                                          <span class="text-success">In Stock (<?php echo $stock_quantity; ?>)</span>

                                    <?php } elseif ($stock_status == "outofstock") { ?>

                                          <span class="text-danger">Out of stock (<?php echo $stock_quantity; ?>)</span>

                                    <?php } elseif ($stock_status == "onbackorder") { ?>

                                          <span class="text-warning">On backorder (<?php echo $stock_quantity; ?>)</span>

                                    <?php } ?>

                                  <?php } else { ?>

                                      <span class="text-success">In Stock (<?php echo $instock; ?>)</span> <br>

                                      <span class="text-danger">Out of stock (<?php echo $outofstock; ?>)</span> <br>

                                      <span class="text-warning">On backorder (<?php echo $onbackorder; ?>)</span>

                                  <?php } ?>

                                </td>

                                <td>

                                  <?php

                                  if ($product_type != "variable_product") {

                                    if ($product_psp_price != 0) {

                                      echo "<del> Rs:$product_price </del><br>";

                                      echo "<ins>Rs:$product_psp_price</ins>";

                                    } else {

                                      echo "Rs:$product_price";

                                    }

                                  } else {

                                    $select_min_product_price = "select min(product_price) as product_price from product_variations where product_id='$product_id' and product_price!='0'";

                                    $run_min_product_price = mysqli_query($con, $select_min_product_price);

                                    $row_min_product_price = mysqli_fetch_array($run_min_product_price);

                                    $minimum_product_price = $row_min_product_price["product_price"];

                                    $select_max_product_price = "select max(product_price) as product_price from product_variations where product_id='$product_id'";

                                    $run_max_product_price = mysqli_query($con, $select_max_product_price);

                                    $row_max_product_price = mysqli_fetch_array($run_max_product_price);

                                    $maximum_product_price = $row_max_product_price["product_price"];

                                    echo "Rs:$minimum_product_price <br>---<br> Rs:$maximum_product_price";

                                  }

                                  ?>

                                </td>

                                <td>
                                  <?php

                                  $order_sold = 0;

                                  $get_sold = "select * from orders_items where product_id='$product_id'";

                                  $run_sold = mysqli_query($con, $get_sold);

                                  while ($row_sold = mysqli_fetch_array($run_sold)) {

                                    $qty = $row_sold["qty"];

                                    $order_sold += $qty;

                                  }

                                  echo $order_sold;

                                  ?>
                                </td>

                                <td> <?php echo $product_keywords; ?> </td>

                                <td><?php echo $product_date; ?></td>

                                <td>

                                    <div class="dropdown">

                                        <button class="btn btn-success dropdown-toggle" data-toggle="dropdown">

                                            <span class="caret"></span>

                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-right">

                                            <li>

                                                <a href="../<?php echo $product_url; ?>" target="blank">

                                                    <i class="fa fa-eye"></i> View

                                                </a>

                                            </li>

                                          <?php if ($product_vendor_status == "active") { ?>

                                              <li>

                                                  <a href="index.php?pause_product=<?php echo $product_id; ?>&bundles">

                                                      <i class="fa fa-pause-circle-o"></i> Pause

                                                  </a>

                                              </li>

                                          <?php } elseif ($product_vendor_status == "paused") { ?>

                                              <li>

                                                  <a href="index.php?activate_product=<?php echo $product_id; ?>&bundles">

                                                      <i class="fa fa-toggle-on"></i> Activate

                                                  </a>

                                              </li>

                                          <?php } ?>

                                            <li>

                                                <a href="index.php?edit_bundle=<?php echo $product_id; ?>">

                                                    <i class="fa fa-pencil"> </i> Edit

                                                </a>

                                            </li>

                                            <li>

                                                <a href="index.php?delete_product=<?php echo $product_id; ?>&bundles">

                                                    <i class="fa fa-trash-o"> </i> Delete

                                                </a>

                                            </li>

                                        </ul>

                                    </div>

                                </td>

                            </tr>

                        <?php } ?>

                        </tbody>

                    </table><!-- table table-bordered table-hover table-striped Ends -->

                </div><!-- table-responsive Ends -->

            </div><!-- panel-body Ends -->

        </div><!-- panel panel-default Ends -->

    </div><!-- col-lg-12 Ends -->

</div><!-- 2 row Ends -->