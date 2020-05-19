<?php
if (!isset($_SESSION['customer_email'])) {
  echo "<script> window.open('../checkout.php','_self'); </script>";
}

$customer_email = $_SESSION['customer_email'];
$select_customer = "select * from customers where customer_email='$customer_email'";
$run_customer = mysqli_query($con, $select_customer);
$row_customer = mysqli_fetch_array($run_customer);
$customer_id = $row_customer['customer_id'];
$select_payment_settings = "select * from payment_settings";
$run_payment_setttings = mysqli_query($con, $select_payment_settings);
$row_payment_settings = mysqli_fetch_array($run_payment_setttings);
$minimum_withdraw_limit = $row_payment_settings['minimum_withdraw_limit'];
$days_before_withdraw = $row_payment_settings['days_before_withdraw'];
$select_vendor_account = "select * from vendor_accounts where vendor_id='$customer_id'";
$run_vendor_account = mysqli_query($con, $select_vendor_account);
$row_vendor_account = mysqli_fetch_array($run_vendor_account);
$current_balance = $row_vendor_account['current_balance'];
$pending_clearance = $row_vendor_account['pending_clearance'];
$withdrawals = $row_vendor_account['withdrawals'];
$month_earnings = $row_vendor_account['month_earnings'];
$select_store_settings = "select * from store_settings where vendor_id='$customer_id'";
$run_store_settings = mysqli_query($con, $select_store_settings);
$row_store_settings = mysqli_fetch_array($run_store_settings);
$paypal_email = $row_store_settings["paypal_email"];
?>

<div class="row"><!-- 2 row Starts -->
    <div class="col-lg-12"><!-- col-lg-12 Starts -->
        <div class="panel panel-default"><!-- panel panel-default Starts -->
            <div class="panel-heading"><!-- panel-heading Starts -->
                <h3 class="panel-title"><!-- panel-title Starts -->
                    <i class="fa fa-money fa-fw"></i> Payments Report
                </h3><!-- panel-title Ends -->
            </div><!-- panel-heading Ends -->
            <div class="panel-body"><!-- panel-body Starts -->
                <div class="table-responsive"><!-- table-responsive Starts -->
                    <table class="table table-bordered table-hover table-striped">
                        <!-- table table-bordered table-hover table-striped Starts -->
                        <tbody class="text-center">
                        <tr>
                            <td>
                                <h4 style="margin-top:0px;"> Current Balance </h4>
                                <h3> Rs:<?php echo $current_balance; ?> </h3>
                            </td>

                            <td>
                                <h4 style="margin-top:0px;"> Pending Clearance </h4>
                                <h3> Rs:<?php echo $pending_clearance; ?> </h3>
                            </td>

                            <td>
                                <h4 style="margin-top:0px;"> Withdrawals </h4>
                                <h3> Rs:<?php echo $withdrawals; ?> </h3>

                            </td>

                        </tr>

                        <tr>
                            <td>
                                <h4 style="margin-top:0px;"> Earned This Month </h4>
                                <h3> Rs:<?php echo $month_earnings; ?> </h3>
                            </td>

                            <td>
                                <h4 style="margin-top:0px;"> Minimum Withdraw Amount </h4>
                                <h3> Rs:<?php echo $minimum_withdraw_limit; ?> </h3>
                            </td>

                            <td>
                                <h4 style="margin-top:0px;"> Withdraw Threshold </h4>
                                <h3> <?php echo $days_before_withdraw; ?> Days </h3>
                            </td>
                        </tr>
                        </tbody>
                    </table><!-- table table-bordered table-hover table-striped Ends -->
                </div><!-- table-responsive Ends -->
            </div><!-- panel-body Ends -->
        </div><!-- panel panel-default Ends -->
    </div><!-- col-lg-12 Ends -->
</div><!-- 2 row Ends -->

<?php if ($current_balance >= $minimum_withdraw_limit) { ?>
    <button class="btn btn-success" data-toggle="modal" data-target="#paypal_withdraw_modal">
        Withdraw To <i class="fa fa-paypal"></i> Bank Account
    </button>
<?php } else { ?>

    <button class="btn btn-success"
            onclick="return alert('You Must Have Minimum Rs:<?php echo $minimum_withdraw_limit; ?> Available Balance To Withdraw Commissions To Your Bank Account.');">
        Withdraw To <i class="fa fa-paypal"></i> Bank Account
    </button>

<?php } ?>

<br><br>

<div class="panel panel-default"><!-- panel panel-default Starts -->
    <div class="panel-heading"><!-- panel-heading Starts -->
        <h3 class="panel-title"><!-- panel-title Starts -->
            <i class="fa fa-money fa-fw"></i> Payments
        </h3><!-- panel-title Ends -->

    </div><!-- panel-heading Ends -->

    <div class="panel-body"><!-- panel-body Starts -->

        <div class="table-responsive"><!-- table-responsive Starts -->

            <table class="table table-bordered table-hover table-striped">
                <!-- table table-bordered table-hover table-striped Starts -->

                <thead>

                <tr>
                    <th> Number:</th>
                    <th> Order Invoice:</th>
                    <th> Commission Amount:</th>
                    <th> Shipping Amount:</th>
                    <th> Total Amount:</th>
                    <th> Status:</th>
                </tr>

                </thead>

                <tbody>

                <?php

                $i = 0;
                $select_vendor_commissions = "select * from vendor_commissions where vendor_id='$vendor_id' order by 1 desc";
                $run_vendor_commissions = mysqli_query($con, $select_vendor_commissions);
                while ($row_vendor_commissions = mysqli_fetch_array($run_vendor_commissions)) {
                  $i++;
                  $commission_id = $row_vendor_commissions['commission_id'];

                  $order_id = $row_vendor_commissions['order_id'];

                  $commission_paid_date = $row_vendor_commissions['commission_paid_date'];

                  $commission_status = $row_vendor_commissions['commission_status'];

                  $select_vendor_order = "select * from vendor_orders where id='$order_id' and vendor_id='$vendor_id'";

                  $run_vendor_order = mysqli_query($con, $select_vendor_order);

                  $row_vendor_order = mysqli_fetch_array($run_vendor_order);

                  $invoice_no = $row_vendor_order['invoice_no'];

                  $net_amount = $row_vendor_order['net_amount'];

                  $shipping_cost = $row_vendor_order['shipping_cost'];

                  $total_amount = $net_amount + $shipping_cost;

                  ?>

                    <tr>

                        <td> <?php echo $i; ?> </td>

                        <td>

                            <a href="index.php?view_order_id=<?php echo $order_id; ?>"> #<?php echo $invoice_no; ?> </a>

                        </td>

                        <td> Rs:<?php echo $net_amount; ?> </td>

                        <td> Rs:<?php echo $shipping_cost; ?> </td>

                        <td> Rs:<?php echo $total_amount; ?> </td>

                      <?php if ($commission_status == "pending") { ?>

                          <td class="text-danger">
                              <strong> Commission Pending Clearance </strong>
                          </td>

                      <?php } else { ?>

                          <td class="text-success">

                              <strong> Commission <?php echo ucwords($commission_status); ?> </strong>

                          </td>

                      <?php } ?>

                    </tr>

                <?php } ?>

                </tbody>

            </table><!-- table table-bordered table-hover table-striped Ends -->

        </div><!-- table-responsive Ends -->


    </div><!-- panel-body Ends -->

</div><!-- panel panel-default Ends -->

</div><!-- col-lg-12 Ends -->

</div><!-- 2 row Ends -->


<div id="paypal_withdraw_modal" class="modal fade"><!-- paypal_withdraw_modal modal fade Starts -->

    <div class="modal-dialog"><!-- modal-dialog Starts -->

        <div class="modal-content"><!-- modal-content Starts -->

            <div class="modal-header"><!-- modal-header Starts -->

                <button type="button" class="close" data-dismiss="modal">

                    <span>&times;</span>

                </button>

                <h4 class="modal-title"> Withdraw Commissions To Bank Account </h4>

            </div><!-- modal-header Ends -->

            <div class="modal-body"><!-- modal-body Starts -->

                <div style="text-align: center;"><!-- center Starts -->

                  <?php if (empty($paypal_email)) { ?>
                      <p class="lead">
                          For Withdraw Commissions To Your Bank Account Please Add Your PayPal Account Email In

                          <a href="index.php?store_settings">
                              Store Settings Tab
                          </a>

                      </p>

                  <?php } else { ?>

                      <p class="lead">

                          Your Commissions Will Be Sent To Follwing Bank Account Email:

                          <br> <strong> <?php echo $paypal_email; ?> </strong>

                      </p>

                      <form class="form-horizontal" action="paypal_payouts.php" method="post">
                          <!-- withdraw form Starts -->
                          <div class="form-group"><!-- form-group Starts -->
                              <label class="col-md-3 control-label"> Withdraw Amount: </label>
                              <div class="col-md-7">
                                  <div class="input-group">
                                      <span class="input-group-addon">Rs:</span>

                                      <input type="text" name="amount" class="form-control"
                                             min="<?php echo $minimum_withdraw_limit; ?>"
                                             max="<?php echo $current_balance; ?>"
                                             placeholder="<?php echo $minimum_withdraw_limit; ?> Minimum" required>
                                  </div>

                              </div>

                          </div><!-- form-group Ends -->

                          <div class="form-group"><!-- form-group Starts -->

                              <label class="col-md-3 control-label"></label>

                              <div class="col-md-7">

                                  <input type="submit" name="withdraw" value="Withdraw"
                                         class="btn btn-primary form-control">

                              </div>

                          </div><!-- form-group Ends -->

                      </form><!-- withdraw form Ends -->

                  <?php } ?>

                </div><!-- center Ends -->

            </div><!-- modal-body Ends -->

            <div class="modal-footer"><!-- modal-footer Starts -->

                <button class="btn btn-default" data-dismiss="modal">
                    Close
                </button>

            </div><!-- modal-footer Ends -->

        </div><!-- modal-content Ends -->

    </div><!-- modal-dialog Ends -->

</div><!-- paypal_withdraw_modal modal fade Ends -->
