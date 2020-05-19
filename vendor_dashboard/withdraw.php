<?php

if (!isset($_SESSION['customer_email'])) {

  echo "<script> window.open('../checkout.php','_self'); </script>";

}

$customer_email = $_SESSION['customer_email'];

$select_customer = "select * from customers where customer_email='$customer_email'";

$run_customer = mysqli_query($con, $select_customer);

$row_customer = mysqli_fetch_array($run_customer);

$customer_id = $row_customer['customer_id'];

$select_vendor_payment_settings = "select * from vendor_payment_settings where vendor_id='$customer_id'";

$run_vendor_payment_settings = mysqli_query($con, $select_vendor_payment_settings);

$row_vendor_payment_settings = mysqli_fetch_array($run_vendor_payment_settings);

$paypal_email = $row_vendor_payment_settings["paypal_email"];

function echo_active_class($status)
{

  if ((!isset($_GET['status']) and $status == "pending") or (isset($_GET['status']) and $status == $_GET['status'])) {

    echo "active-link";

  }

}

?>

<div class="row"><!-- 2 row Starts -->

    <div class="col-lg-12"><!-- col-lg-12 Starts -->

        <div class="panel panel-default"><!-- panel panel-default Starts -->

            <div class="panel-heading"><!-- panel-heading Starts -->

                <h3 class="panel-title"><!-- panel-title Starts -->

                    <i class="fa fa-money fa-fw"></i> Withdraw Requests

                </h3><!-- panel-title Ends -->

            </div><!-- panel-heading Ends -->

            <div class="panel-body"><!-- panel-body Starts -->

                <p class="lead">

                    These are the withdraw methods available for you. Please update your payment information below to
                    submit withdraw requests and get your store payments seamlessly.

                </p>

                <a href="index.php?withdraw&status=pending"
                   class="link-separator <?php echo_active_class("pending"); ?>">

                    Pending Requests

                </a>

                <a class="link-separator">|</a>

                <a href="index.php?withdraw&status=approved"
                   class="link-separator <?php echo_active_class("approved"); ?>">

                    Approved Requests

                </a>

                <a class="link-separator">|</a>

                <a href="index.php?withdraw&status=cancelled"
                   class="link-separator <?php echo_active_class("cancelled"); ?>">

                    Cancelled Requests

                </a>

                <a href="index.php?withdraw_request" class="btn btn-success pull-right"> + Make A Withdraw Request </a>

                <br><br>


                <div class="table-responsive"><!-- table-responsive Starts -->

                    <table class="table table-bordered table-hover table-striped">
                        <!-- table table-bordered table-hover table-striped Starts -->

                        <thead>

                        <tr>

                            <th> Number:</th>

                            <th> Amount:</th>

                            <th> Method:</th>

                            <th> Date:</th>

                            <th> Status:</th>

                          <?php if (!isset($_GET['status']) or $_GET['status'] == "pending") { ?>

                              <th> Cancel:</th>

                          <?php } ?>

                        </tr>

                        </thead>

                        <tbody>

                        <?php

                        if (!isset($_GET['status']) or $_GET['status'] == "pending") {

                          echo $select_vendor_withdraws = "select * from vendor_withdraws where vendor_id='$customer_id' and status='pending'";

                        } elseif (isset($_GET['status'])) {

                          $status = $_GET['status'];

                          echo $select_vendor_withdraws = "select * from vendor_withdraws where vendor_id='$customer_id' and status='$status'";

                        }

                        $run_vendor_withdraws = mysqli_query($con, $select_vendor_withdraws);
                        $i = 0;
                        while ($row_vendor_withdraws = mysqli_fetch_array($run_vendor_withdraws)) {
                          $i++;
                          $withdraw_id = $row_vendor_withdraws['withdraw_id'];
                          $amount = $row_vendor_withdraws['amount'];
                          $method = $row_vendor_withdraws['method'];
                          $date = $row_vendor_withdraws['date'];
                          $status = $row_vendor_withdraws['status'];
                          ?>
                            <tr>
                                <td> <?php echo $i; ?> </td>
                                <td> Rs:<?php echo $amount; ?> </td>
                                <td> <?php echo ucwords($method); ?> </td>
                                <td> <?php echo $date; ?> </td>
                                <td> <?php echo ucwords($status); ?> </td>
                              <?php if (!isset($_GET['status']) or $_GET['status'] == "pending") { ?>
                                  <td>
                                      <a href="index.php?cancel_withdraw_request=<?php echo $withdraw_id; ?>">
                                          <i class="fa fa-ban"></i> Cancel
                                      </a>
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

