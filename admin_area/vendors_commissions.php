<?php
if (!isset($_SESSION['admin_email'])) {
  echo "<script>window.open('login.php','_self')</script>";

} else {

  function select_commission_status($commission_status) {
    if ($commission_status == @$_REQUEST["commission_status"]) {
      echo "selected";
    }
  }
?>

    <div class="row"><!-- 1 row Starts -->
        <div class="col-lg-12"><!-- col-lg-12 Starts -->
            <ol class="breadcrumb"><!-- breadcrumb Starts -->
                <li class="active">
                    <i class="fa fa-dashboard"></i> Dashboard / View Shopkeepers Commissions
                </li>
            </ol><!-- breadcrumb Ends -->
        </div><!-- col-lg-12 Ends -->
    </div><!-- 1 row Ends -->

    <div class="row"><!-- 2 row Starts -->
        <div class="col-lg-12"><!-- col-lg-12 Starts -->
            <div class="panel panel-default"><!-- panel panel-default Starts -->
                <div class="panel-body"><!-- panel-body Starts -->
                    <h3 style="margin-top:0px;"> Filter ShopKeepers Commissions </h3>

                    <form method="post" action="index.php?vendors_commissions=1"><!--- form Starts --->
                        <div class="row"><!-- row Starts -->
                            <div class="col-md-3 col-sm-6"><!-- col-md-3 col-sm-6 Starts -->
                                <div class="form-group"><!--- form-group Starts --->
                                    <label> Filter by Shopkeeper : </label>
                                    <select name="vendor_id" class="form-control">
                                        <option value=""> Select A Commission Vendor</option>
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

                            <div class="col-md-3 col-sm-6"><!-- col-md-4 col-sm-6 Starts -->
                                <div class="form-group"><!--- form-group Starts --->
                                    <label> Filter by status : </label>
                                    <select name="commission_status" class="form-control">
                                        <option value=""> Select A Commission Status</option>
                                        <option value="pending" <?php select_commission_status("pending"); ?>> Pending
                                            Clearance
                                        </option>

                                        <option value="cleared" <?php select_commission_status("cleared"); ?>> Cleared</option>
                                    </select>
                                </div><!--- form-group Ends --->
                            </div><!-- col-md-3 col-sm-6 Ends -->

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
                        <i class="fa fa-money fa-fw"> </i> View Shopkeeper Commissions
                    </h3><!-- panel-title Ends -->
                </div><!-- panel-heading Ends -->

                <div class="panel-body"><!-- panel-body Starts -->
                    <div class="table-responsive"><!-- table-responsive Starts -->
                        <table class="table table-hover table-bordered table-striped">
                            <thead><!-- thead Starts -->
                            <tr>
                                <th> Number:</th>
                                <th> Vendor:</th>
                                <th> Order Invoice:</th>
                                <th> Total Amount:</th>
                                <th> Paid/Cleared Date:</th>
                                <th> Status:</th>
                                <th> Actions:</th>
                            </tr>
                            </thead><!-- thead Ends -->

                            <tbody><!-- tbody Starts -->
                            <?php
                            $per_page = 15;
                            if (!empty($_GET["vendors_commissions"])) {
                              $page = $_GET["vendors_commissions"];

                            } else {

                              $page = 1;

                            }

                            // Page will start from 0 and Multiple by Per Page
                            $start_from = ($page - 1) * $per_page;

                            //Selecting the data from table but with limit

                            $i = 0;

                            if (isset($_REQUEST["vendor_id"]) and isset($_REQUEST["commission_status"])) {
                              $vendor_id = mysqli_real_escape_string($con, $_REQUEST["vendor_id"]);
                              $commission_status = mysqli_real_escape_string($con, $_REQUEST["commission_status"]);
                              $filter_where = "";
                              if (!empty($vendor_id)) {
                                $filter_where .= "where vendor_id='$vendor_id' ";
                              }

                              if (!empty($commission_status)) {
                                if (!empty($filter_where)) {
                                  $filter_where .= "and commission_status='$commission_status'";
                                } else {
                                  $filter_where .= "where commission_status='$commission_status'";
                                }
                              }
                              $select_vendor_commissions = "select * from vendor_commissions $filter_where order by 1 desc LIMIT $start_from,$per_page";
                            } else {
                              $select_vendor_commissions = "select * from vendor_commissions order by 1 desc LIMIT $start_from,$per_page";
                            }

                            $run_vendor_commissions = mysqli_query($con, $select_vendor_commissions);
                            $count_vendor_commissions = mysqli_num_rows($run_vendor_commissions);
                            if ($count_vendor_commissions == 0) {
                              ?>

                                <tr>
                                    <td colspan="8" class="text-center">
                                        <h3> Sorry, We Have Not Found Any Shopkeeper Commissions. </h3>
                                    </td>
                                </tr>
                              <?php
                            }
                            while ($row_vendor_commissions = mysqli_fetch_array($run_vendor_commissions)) {
                              $i++;
                              $commission_id = $row_vendor_commissions['commission_id'];
                              $vendor_id = $row_vendor_commissions['vendor_id'];
                              $sub_order_id = $row_vendor_commissions['order_id'];
                              $commission_paid_date = $row_vendor_commissions['commission_paid_date'];
                              $commission_status = $row_vendor_commissions['commission_status'];
                              $select_vendor_order = "select * from vendor_orders where id='$sub_order_id' and vendor_id='$vendor_id'";
                              $run_vendor_order = mysqli_query($con, $select_vendor_order);
                              $row_vendor_order = mysqli_fetch_array($run_vendor_order);
                              @$order_id = $row_vendor_order['order_id'];
                              @$invoice_no = $row_vendor_order['invoice_no'];
                              @$net_amount = $row_vendor_order['net_amount'];
                              @$shipping_cost = $row_vendor_order['shipping_cost'];
                              @$total_amount = $net_amount + $shipping_cost;

                              if (strpos($vendor_id, "admin_") !== false) {
                                $admin_id = trim($vendor_id, "admin_");
                                $select_admin = "select * from admins where admin_id='$admin_id'";
                                $run_admin = mysqli_query($con, $select_admin);
                                $row_admin = mysqli_fetch_array($run_admin);
                                $vendor_name = $row_admin['admin_name'];
                              } else {
                                $select_customer = "select * from customers where customer_id='$vendor_id'";
                                $run_customer = mysqli_query($con, $select_customer);
                                $row_customer = mysqli_fetch_array($run_customer);
                                $vendor_name = $row_customer['customer_name'];
                              }
                              ?>

                                <tr>
                                    <td> <?php echo $i; ?> </td>
                                    <td> <?php echo $vendor_name; ?> </td>
                                    <td>
                                        <a href="index.php?view_order_id=<?php echo $order_id; ?>?sub_order_id=<?php echo $sub_order_id; ?>">
                                            #<?php echo $invoice_no; ?>
                                        </a>

                                    </td>

                                    <td> Rs:<?php echo $net_amount; ?> </td>

                                    <td bgcolor="yellow">
                                        <strong> <?php echo $commission_paid_date; ?> </strong>
                                    </td>

                                  <?php if ($commission_status == "pending") { ?>
                                      <td class="text-danger">
                                          <strong> Commission Pending Clearance </strong>
                                      </td>

                                  <?php } else { ?>

                                      <td class="text-success">
                                          <strong> Commission <?php echo ucwords($commission_status); ?> </strong>
                                      </td>

                                  <?php } ?>

                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                                <span class="caret"></span>
                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-right">
                                              <?php if ($commission_status == "pending") { ?>
                                                  <li>
                                                      <a href="index.php?clear_commission=<?php echo $commission_id; ?>"
                                                         onclick="return confirm('Do You Really Want To Clear/Paid This Commission , After Commission Cleared, Vendor Can Withdraw Commission Amount.');">
                                                          <i class="fa fa-usd"></i> Clear/Paid Commission
                                                      </a>
                                                  </li>

                                                  <li>
                                                      <a href="index.php?delete_commission=<?php echo $commission_id; ?>" class="bg-danger">
                                                          <i class="fa fa-trash-o"></i> Delete Commission
                                                      </a>
                                                  </li>
                                              <?php } ?>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody><!-- tbody Ends -->
                        </table><!-- table table-hover table-bordered table-striped Ends -->
                    </div><!-- table-responsive Ends -->
                    <div style="text-align: center;"><!-- center Starts -->
                        <ul class="pagination"><!-- pagination Starts -->
                          <?php
                          $filter_url = "";
                          if (isset($_REQUEST["vendor_id"]) and isset($_REQUEST["commission_status"])) {
                            $select_vendor_commissions = "select * from vendor_commissions $filter_where order by 1 desc";
                            $filter_url .= "&vendor_id=$vendor_id&commission_status=$commission_status";
                          } else {
                            $select_vendor_commissions = "select * from vendor_commissions order by 1 desc";
                          }

                          //Now select all from table
                          $run_vendor_commissions = mysqli_query($con, $select_vendor_commissions);
                          // Count the total records
                          $count_vendor_commissions = mysqli_num_rows($run_vendor_commissions);
                          //Using ceil function to divide the total records on per page
                          $total_pages = ceil($count_vendor_commissions / $per_page);
                          //Going to first page
                          echo " <li class='page-item'><a href='index.php?vendors_commissions=1$filter_url' class='page-link'>First Page</a></li>";

                          for ($i = max(1, $page - 3); $i <= min($page + 3, $total_pages); $i++) {

                            if ($i == $page) {

                              $active = "active";

                            } else {

                              $active = "";

                            }

                            echo "<li class='page-item $active'><a href='index.php?vendors_commissions=$i$filter_url' class='page-link'>$i</a></li>";
                          }
                          // Going to last page

                          echo "<li class='page-item'><a href='index.php?vendors_commissions=$total_pages$filter_url' class='page-link'>Last Page</a></li>";
                          ?>
                        </ul><!-- pagination Ends -->
                    </div><!-- center Ends -->
                </div><!-- panel-body Ends -->
            </div><!-- panel panel-default Ends -->
        </div><!-- col-lg-12 Ends -->
    </div><!-- 2 row Ends -->

<?php } ?>