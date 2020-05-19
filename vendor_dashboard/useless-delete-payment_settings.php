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
?>
<div class="row"><!-- 2 row Starts -->
    <div class="col-lg-12"><!-- col-lg-12 Starts -->
        <div class="panel panel-default"><!-- panel panel-default Starts -->
            <div class="panel-heading"><!-- panel-heading Starts -->
                <h3 class="panel-title"><!-- panel-title Starts -->
                    <i class="fa fa-money fa-fw"></i> Payment Settings
                </h3><!-- panel-title Ends -->
            </div><!-- panel-heading Ends -->

            <div class="panel-body"><!-- panel-body Starts -->
                <p class="lead">
                    These are the withdraw methods available for you. Please update your payment information below to
                    submit withdraw requests and get your store payments seamlessly.
                </p>

                <form class="form-horizontal store-settings-form" method="post"><!-- form-horizontal Starts -->
                    <div class="form-group"><!-- form-group Starts -->
                        <label class="col-md-3 control-label"> PayPal Email </label>
                        <div class="col-md-6">
                            <input type="email" name="paypal_email" class="form-control"
                                   value="<?php echo $paypal_email; ?>" required>
                        </div>
                    </div><!-- form-group Ends -->

                    <div class="form-group"><!-- form-group Starts -->
                        <label class="col-md-3 control-label"> Skrill Email </label>
                        <div class="col-md-6">
                            <input type="email" name="skrill_email" class="form-control"
                                   value="<?php echo $skrill_email; ?>" required>
                        </div>
                    </div><!-- form-group Ends -->

                    <div class="form-group"><!-- form-group Starts -->
                        <label class="col-md-3 control-label"> Bank Transfer </label>
                        <div class="col-md-6"><!-- col-md-6 Starts -->
                            <div class="form-group"><!-- form-group Starts -->
                                <label> Your bank account name: </label>
                                <input type="text" name="bank_account_name" class="form-control"
                                       value="<?php echo $bank_account_name; ?>" required>
                            </div><!-- form-group Ends -->

                            <div class="form-group"><!-- form-group Starts -->
                                <label> Your bank account number: </label>
                                <input type="text" name="bank_account_number" class="form-control"
                                       value="<?php echo $bank_account_number; ?>" required>
                            </div><!-- form-group Ends -->

                            <div class="form-group"><!-- form-group Starts -->
                                <label> Name of bank: </label>
                                <input type="text" name="bank_name" class="form-control"
                                       value="<?php echo $bank_name; ?>" required>
                            </div><!-- form-group Ends -->

                            <div class="form-group"><!-- form-group Starts -->
                                <label> Address of your bank: </label>
                                <input type="text" name="bank_address" class="form-control"
                                       value="<?php echo $bank_address; ?>" required>
                            </div><!-- form-group Ends -->

                            <div class="form-group"><!-- form-group Starts -->
                                <label> Routing number: </label>
                                <input type="text" name="routing_number" class="form-control"
                                       value="<?php echo $routing_number; ?>" required>
                            </div><!-- form-group Ends -->

                            <div class="form-group"><!-- form-group Starts -->
                                <label> IBAN number: </label>
                                <input type="text" name="iban_number" class="form-control"
                                       value="<?php echo $iban_number; ?>" required>
                            </div><!-- form-group Ends -->

                            <div class="form-group"><!-- form-group Starts -->
                                <label> Swift code: </label>
                                <input type="text" name="swift_code" class="form-control"
                                       value="<?php echo $swift_code; ?>" required>
                            </div><!-- form-group Ends -->
                        </div><!-- col-md-6 Ends -->
                    </div><!-- form-group Ends -->

                    <div class="form-group"><!-- form-group Starts -->
                        <label class="col-md-3 control-label"></label>
                        <div class="col-md-6">
                            <input type="submit" name="submit" value="Save Settings"
                                   class="btn btn-success form-control">
                        </div>
                    </div><!-- form-group Ends -->
                </form><!-- form-horizontal Ends -->
            </div><!-- panel-body Ends -->
        </div><!-- panel panel-default Ends -->
    </div><!-- col-lg-12 Ends -->
</div><!-- 2 row Ends -->


<?php
if (isset($_POST['submit'])) {
  $paypal_email = mysqli_real_escape_string($con, $_POST['paypal_email']);
  $update_vendor_payment_settings = "update vendor_payment_settings set paypal_email='$paypal_email' where vendor_id='$customer_id'";
  $run_vendor_payment_settings = mysqli_query($con, $update_vendor_payment_settings);
  if ($run_vendor_payment_settings) {
    echo "<script>alert(' Your Payment Settings Has Been Saved Successfully. ');window.open('index.php?payment_settings','_self');</script>";
  }
}
?>


