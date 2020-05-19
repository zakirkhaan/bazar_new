<?php if (!isset($_SESSION['admin_email'])) { echo "<script>window.open('login.php','_self')</script>"; } else {

  $admin_email = $_SESSION['admin_email'];

  $select_admin = "select * from admins where admin_email='$admin_email'";

  $run_admin = mysqli_query($con, $select_admin);

  $row_admin = mysqli_fetch_array($run_admin);

  $admin_id = $row_admin['admin_id'];

  $vendor_id = "";

  $filter_where = "";

  $filter_url = "";

  if (isset($_REQUEST["customer_id"]) and isset($_REQUEST["vendor_id"])) {

    $customer_id = mysqli_real_escape_string($con, $_REQUEST["customer_id"]);

    $vendor_id = mysqli_real_escape_string($con, $_REQUEST["vendor_id"]);

    if (!empty($customer_id)) {

      $filter_where .= "orders.customer_id='$customer_id'";

    }

    $filter_url = "&customer_id=$customer_id&vendor_id=$vendor_id";

  }

  function get_orders_status_count($order_status)
  {

    global $con;

    global $vendor_id;

    global $filter_where;

    $orders_count_where = $filter_where;

    if ($order_status != "all") {

      if (!empty($orders_count_where)) {

        $orders_count_where .= " and orders.order_status='$order_status'";

      } else {

        $orders_count_where .= "orders.order_status='$order_status'";

      }

    }

    if (empty($orders_count_where) and $order_status == "all") {

      $select_orders = "select * from orders";

    } else {

      if (empty($vendor_id)) {

        $select_orders = "select * from orders where $orders_count_where";

      } else {

        $select_orders = "select * from orders INNER JOIN vendor_orders ON vendor_orders.order_id = orders.order_id and vendor_orders.vendor_id='$vendor_id' where $orders_count_where";

      }

    }

    $run_orders = mysqli_query($con, $select_orders);

    echo $count_orders = mysqli_num_rows($run_orders);

  }

  function echo_active_class($order_status)
  {

    if ((!isset($_REQUEST['order_status']) and $order_status == "all") or (isset($_REQUEST['order_status']) and $order_status == $_REQUEST['order_status'])) {

      echo "active-link";

    }

  }

  ?>

    <div class="row"><!-- 1 row Starts -->

        <div class="col-lg-12"><!-- col-lg-12 Starts -->

            <ol class="breadcrumb"><!-- breadcrumb Starts  --->

                <li class="active">

                    <i class="fa fa-dashboard"></i> Dashboard / View Orders

                </li>

            </ol><!-- breadcrumb Ends  --->

        </div><!-- col-lg-12 Ends -->

    </div><!-- 1 row Ends -->

    <div class="row"><!-- 2 row Starts -->

        <div class="col-lg-12"><!-- col-lg-12 Starts -->

            <div class="panel panel-default"><!-- panel panel-default Starts -->

                <div class="panel-body"><!-- panel-body Starts -->

                    <h3 style="margin-top:0px;"> Filter Orders </h3>

                    <form method="post" action="index.php?view_orders=1"><!--- form Starts --->

                        <div class="row"><!-- row Starts -->

                            <div class="col-md-3 col-sm-6"><!-- col-md-4 col-sm-6 Starts -->

                                <div class="form-group"><!--- form-group Starts --->

                                    <label> Filter by customer : </label>

                                    <select name="customer_id" class="form-control">

                                        <option value=""> Select A Order Customer</option>

                                      <?php

                                      $select_customer = "select * from customers";

                                      $run_customer = mysqli_query($con, $select_customer);

                                      while ($row_customer = mysqli_fetch_array($run_customer)) {

                                        $customer_id = $row_customer['customer_id'];

                                        $customer_name = $row_customer['customer_name'];

                                        if (@$_REQUEST["customer_id"] == $customer_id) {

                                          echo "<option value='$customer_id' selected> $customer_name </option>";

                                        } else {

                                          echo "<option value='$customer_id'> $customer_name </option>";

                                        }

                                      }

                                      ?>

                                    </select>

                                </div><!--- form-group Ends --->

                            </div><!-- col-md-3 col-sm-6 Ends -->

                            <div class="col-md-3 col-sm-6"><!-- col-md-3 col-sm-6 Starts -->

                                <div class="form-group"><!--- form-group Starts --->

                                    <label> Filter by vendor : </label>

                                    <select name="vendor_id" class="form-control">

                                        <option value=""> Select A Order Vendor</option>

                                      <?php

                                      $select_customer = "select * from customers where customer_role='vendor'";

                                      $run_customer = mysqli_query($con, $select_customer);

                                      while ($row_customer = mysqli_fetch_array($run_customer)) {

                                        $customer_id = $row_customer['customer_id'];

                                        $customer_name = $row_customer['customer_name'];

                                        if (@$_REQUEST["vendor_id"] == $customer_id) {

                                          echo "<option value='$customer_id' selected> $customer_name </option>";

                                        } else {

                                          echo "<option value='$customer_id'> $customer_name </option>";

                                        }

                                      }

                                      ?>

                                    </select>

                                </div><!--- form-group Ends --->

                            </div><!-- col-md-3 col-sm-6 Ends -->

                          <?php if (isset($_REQUEST["order_status"])) { ?>

                              <input type="hidden" name="order_status" value="<?php echo $_REQUEST["order_status"]; ?>">

                          <?php } ?>

                            <div class="col-md-3 col-sm-6"><!-- col-md-3 col-sm-6 Starts -->

                                <label></label>

                                <button type="submit" class="btn btn-success form-control"> Filter Orders</button>

                            </div><!-- col-md-3 col-sm-6 Ends -->

                        </div><!-- row Ends -->

                    </form><!--- form Ends --->

                </div><!-- panel-body Ends -->

            </div><!-- panel panel-default Ends -->

        </div><!-- col-lg-12 Ends -->

    </div><!-- 2 row Ends -->

    <div class="row"><!-- 2 row Starts -->

        <div class="col-lg-12"><!-- col-lg-12 Starts -->

            <div class="panel panel-default"><!-- panel panel-default Starts -->

                <div class="panel-heading"><!-- panel-heading Starts -->

                    <h3 class="panel-title"><!-- panel-title Starts -->

                        <i class="fa fa-money fa-fw"></i> View Orders

                    </h3><!-- panel-title Ends -->

                </div><!-- panel-heading Ends -->

                <div class="panel-body"><!-- panel-body Starts -->

                    <a href="index.php?view_orders=1&order_status=all<?php echo $filter_url; ?>"
                       class="link-separator <?php echo_active_class("all"); ?>">

                        All (<?php get_orders_status_count("all"); ?>)

                    </a>

                    <a class="link-separator">|</a>

                    <a href="index.php?view_orders=1&order_status=pending<?php echo $filter_url; ?>"
                       class="link-separator <?php echo_active_class("pending"); ?>">

                        Pending (<?php get_orders_status_count("pending"); ?>)

                    </a>

                    <a class="link-separator">|</a>

                    <a href="index.php?view_orders=1&order_status=on hold<?php echo $filter_url; ?>"
                       class="link-separator <?php echo_active_class("on hold"); ?>">

                        On Hold (<?php get_orders_status_count("on hold"); ?>)

                    </a>

                    <a class="link-separator">|</a>

                    <a href="index.php?view_orders=1&order_status=processing<?php echo $filter_url; ?>"
                       class="link-separator <?php echo_active_class("processing"); ?>">

                        Processing (<?php get_orders_status_count("processing"); ?>)

                    </a>

                    <a class="link-separator">|</a>

                    <a href="index.php?view_orders=1&order_status=completed<?php echo $filter_url; ?>"
                       class="link-separator <?php echo_active_class("completed"); ?>">

                        Completed (<?php get_orders_status_count("completed"); ?>)

                    </a>

                    <a class="link-separator">|</a>

                    <a href="index.php?view_orders=1&order_status=cancelled<?php echo $filter_url; ?>"
                       class="link-separator <?php echo_active_class("cancelled"); ?>">

                        Cancelled (<?php get_orders_status_count("cancelled"); ?>)

                    </a>

                    <a class="link-separator">|</a>

                    <a href="index.php?view_orders=1&order_status=refunded<?php echo $filter_url; ?>"
                       class="link-separator <?php echo_active_class("refunded"); ?>">

                        Refunded (<?php get_orders_status_count("refunded"); ?>)

                    </a>

                    <br><br>

                    <div class="table-responsive"><!-- table-responsive Starts -->

                        <table class="table table-bordered table-hover table-striped">
                            <!-- table table-bordered table-hover table-striped Starts -->

                            <thead><!-- thead Starts -->

                            <tr>

                                <th>Order No:</th>

                                <th>Ship To:</th>

                                <th>Customer Email:</th>

                                <th>Invoice No:</th>

                                <th>Order Date:</th>

                                <th>Total Amount:</th>

                                <th>Order Status:</th>

                                <th>Actions:</th>

                            </tr>

                            </thead><!-- thead Ends -->

                            <tbody><!-- tbody Starts -->

                            <?php

                            $per_page = 10;

                            if (!empty($_GET["view_orders"])) {

                              $page = $_GET["view_orders"];

                            } else {

                              $page = 1;

                            }

                            // Page will start from 0 and Multiple by Per Page
                            $start_from = ($page - 1) * $per_page;

                            //Selecting the data from table but with limit

                            if ((isset($_REQUEST["customer_id"]) and isset($_REQUEST["vendor_id"])) or isset($_REQUEST["order_status"])) {

                              if (isset($_REQUEST["order_status"])) {

                                if ($_REQUEST["order_status"] != "all") {

                                  $order_status = $_REQUEST["order_status"];

                                  if (isset($_REQUEST["customer_id"]) and isset($_REQUEST["vendor_id"])) {

                                    $filter_where .= " and orders.order_status='$order_status'";

                                  } else {

                                    $filter_where .= "orders.order_status='$order_status'";

                                  }

                                }

                              }

                              if (empty($filter_where)) {

                                $select_filter_where = "";

                              } else {

                                $select_filter_where = "where $filter_where";

                              }

// if(empty($filter_where)){

                              if (empty($vendor_id)) {

                                $select_orders = "select * from orders $select_filter_where order by 1 desc LIMIT $start_from,$per_page";

                              } else {

                                $select_orders = "
select * from orders INNER JOIN vendor_orders ON vendor_orders.order_id = orders.order_id and vendor_orders.vendor_id='$vendor_id' $select_filter_where order by 1 desc LIMIT $start_from,$per_page
";

                              }

                            } else {

                              $select_orders = "select * from orders order by 1 desc LIMIT $start_from,$per_page";

                            }

                            $i = $start_from;

                            $run_orders = mysqli_query($con, $select_orders);

                            $count_orders = mysqli_num_rows($run_orders);

                            if ($count_orders == 0) {

                              ?>

                                <tr>

                                    <td colspan="8" class="text-center">

                                        <h3> Sorry, We Have Not Found Any Orders. </h3>

                                    </td>

                                </tr>

                              <?php

                            }

                            while ($row_orders = mysqli_fetch_array($run_orders)) {

                              $i++;

                              $order_id = $row_orders['order_id'];

                              $customer_id = $row_orders['customer_id'];

                              $invoice_no = $row_orders['invoice_no'];

                              $order_total = $row_orders['order_total'];

                              $shipping_type = $row_orders['shipping_type'];

                              $payment_method = $row_orders['payment_method'];

                              $order_date = $row_orders['order_date'];

                              $order_status = $row_orders['order_status'];

                              $get_customer = "select * from customers where customer_id='$customer_id'";

                              $run_customer = mysqli_query($con, $get_customer);

                              $row_customer = mysqli_fetch_array($run_customer);

                              $customer_email = $row_customer['customer_email'];

                              $get_orders_addresses = "select * from orders_addresses where order_id='$order_id'";

                              $run_orders_addresses = mysqli_query($con, $get_orders_addresses);

                              $row_addresses = mysqli_fetch_array($run_orders_addresses);

                              $billing_first_name = $row_addresses["billing_first_name"];

                              $billing_last_name = $row_addresses["billing_last_name"];

                              $billing_address_1 = $row_addresses["billing_address_1"];

                              $billing_address_2 = $row_addresses["billing_address_2"];

                              $billing_city = $row_addresses["billing_city"];

                              $billing_postcode = $row_addresses["billing_postcode"];

                              $billing_country = $row_addresses["billing_country"];

                              $billing_state = $row_addresses["billing_state"];

                              $is_shipping_address = $row_addresses["is_shipping_address"];

// Shopping Details Starts

                              $shipping_first_name = $row_addresses["shipping_first_name"];

                              $shipping_last_name = $row_addresses["shipping_last_name"];

                              $shipping_address_1 = $row_addresses["shipping_address_1"];

                              $shipping_address_2 = $row_addresses["shipping_address_2"];

                              $shipping_city = $row_addresses["shipping_city"];

                              $shipping_postcode = $row_addresses["shipping_postcode"];

                              $shipping_country = $row_addresses["shipping_country"];

                              $shipping_state = $row_addresses["shipping_state"];

                              $select_hide_admin_orders = "select * from hide_admin_orders where admin_id='$admin_id' and order_id='$order_id'";

                              $run_hide_admin_orders = mysqli_query($con, $select_hide_admin_orders);

                              $count_hide_admin_orders = mysqli_fetch_array($run_hide_admin_orders);

                              if ($count_hide_admin_orders == 0) {

                                ?>

                                  <tr>

                                      <td><?php echo $i; ?></td>

                                      <td>

                                          <strong>

                                            <?php if ($is_shipping_address == "yes") { ?>

                                              <?php echo $billing_first_name . " ";
                                              echo $billing_last_name; ?>, <?php echo $billing_city; ?>, <?php echo $billing_state; ?>, <?php echo $billing_postcode; ?>,
                                              <?php

                                              $get_country = "select * from countries where country_id='$billing_country'";

                                              $run_country = mysqli_query($con, $get_country);

                                              $row_country = mysqli_fetch_array($run_country);

                                              echo $country_name = $row_country['country_name'];

                                              ?>

                                            <?php } elseif ($is_shipping_address == "no") { ?>

                                              <?php echo $shipping_first_name . " ";
                                              echo $shipping_last_name; ?>, <?php echo $shipping_city; ?>,
                                              <?php echo $shipping_state; ?>, <?php echo $shipping_postcode; ?>,
                                              <?php

                                              $get_country = "select * from countries where country_id='$shipping_country'";

                                              $run_country = mysqli_query($con, $get_country);

                                              $row_country = mysqli_fetch_array($run_country);

                                              echo $country_name = $row_country['country_name'];

                                              ?>

                                            <?php } elseif ($is_shipping_address == "none") { ?>

                                                Shipping None ----

                                            <?php } ?>

                                          </strong><br>

                                        <?php

                                        if ($is_shipping_address != "none") {

                                          if (!empty($shipping_type)) {

                                            ?>

                                              <span class="text-muted"> Via <?php echo ucwords($shipping_type); ?> </span>

                                          <?php }
                                        } ?>

                                      </td>

                                      <td><?php echo $customer_email; ?></td>

                                      <td bgcolor="yellow">#<?php echo $invoice_no; ?></td>

                                      <td><?php echo $order_date; ?></td>

                                      <td>

                                          <strong>Rs:<?php echo $order_total; ?></strong><br>

                                          <span class="text-muted"> Via <?php echo ucwords($payment_method); ?> </span>

                                      </td>

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

                                          <div class="dropdown">

                                              <button class="btn btn-success dropdown-toggle" data-toggle="dropdown">

                                                  <span class="caret"></span>

                                              </button>

                                              <ul class="dropdown-menu dropdown-menu-right">

                                                  <li>

                                                      <a href="index.php?view_order_id=<?php echo $order_id; ?>"
                                                         target="blank">

                                                          <i class="fa fa-pencil"></i> View / Edit

                                                      </a>

                                                  </li>

                                                  <li>

                                                      <a href="index.php?order_delete=<?php echo $order_id; ?>"
                                                         class="bg-danger">

                                                          <i class="fa fa-trash-o"></i> Delete

                                                      </a>

                                                  </li>

                                              </ul>

                                          </div>

                                      </td>

                                  </tr>

                                <?php

                              }

                            }

                            ?>

                            </tbody><!-- tbody Ends -->

                        </table><!-- table table-bordered table-hover table-striped Ends -->

                    </div><!-- table-responsive Ends -->

                    <div style="text-align: center;"><!-- center Starts -->

                        <ul class="pagination"><!-- pagination Starts -->

                          <?php

                          if ((isset($_REQUEST["customer_id"]) and isset($_REQUEST["vendor_id"])) or isset($_REQUEST["order_status"])) {

                            if (isset($_REQUEST["order_status"])) {

                              if ($_REQUEST["order_status"] != "all") {

                                $order_status = $_REQUEST["order_status"];

                                $filter_url .= "&order_status=$order_status";

                              }

                            }

                            if (empty($vendor_id)) {

                              $select_orders = "select * from orders $select_filter_where";

                            } else {

                              $select_orders = "
select * from orders INNER JOIN vendor_orders ON vendor_orders.order_id = orders.order_id and vendor_orders.vendor_id='$vendor_id' $select_filter_where";

                            }

                          } else {

                            $select_orders = "select * from orders";

                          }

                          //Now select all from table

                          $run_orders = mysqli_query($con, $select_orders);

                          // Count the total records

                          $count_orders = mysqli_num_rows($run_orders);

                          //Using ceil function to divide the total records on per page

                          $total_pages = ceil($count_orders / $per_page);

                          //Going to first page

                          echo "<li class='page-item'><a href='index.php?view_orders=1$filter_url' class='page-link'>First Page</a></li>";

                          for ($i = max(1, $page - 3); $i <= min($page + 3, $total_pages); $i++) {

                            if ($i == $page) {

                              $active = "active";

                            } else {

                              $active = "";

                            }

                            echo "<li class='page-item $active'><a href='index.php?view_orders=$i$filter_url' class='page-link'>$i</a></li>";

                          }

                          // Going to last page

                          echo "<li class='page-item'><a href='index.php?view_orders=$total_pages$filter_url' class='page-link'>Last Page</a></li>";
                          ?>
                        </ul><!-- pagination Ends -->

                    </div><!-- center Ends -->
                </div><!-- panel-body Ends -->
            </div><!-- panel panel-default Ends -->
        </div><!-- col-lg-12 Ends -->
    </div><!-- 2 row Ends -->

<?php } ?>