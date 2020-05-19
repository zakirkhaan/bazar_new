<?php
if (!isset($_SESSION['admin_email'])) {
    echo "<script>window.open('login.php','_self')</script>";
} else {
    $select_payment_settings = "select * from payment_settings";
    $run_payment_settings = mysqli_query($con, $select_payment_settings);
    $row_payment_settings = mysqli_fetch_array($run_payment_settings);
    $commission_percentage = $row_payment_settings["commission_percentage"];
    $minimum_withdraw_limit = $row_payment_settings["minimum_withdraw_limit"];
    $days_before_withdraw = $row_payment_settings["days_before_withdraw"];
    $enable_paypal = $row_payment_settings['enable_paypal'];
    $paypal_email = $row_payment_settings['paypal_email'];
    $paypal_sandbox = $row_payment_settings['paypal_sandbox'];
    $paypal_currency_code = $row_payment_settings['paypal_currency_code'];
    $paypal_app_client_id = $row_payment_settings['paypal_app_client_id'];
    $paypal_app_client_secret = $row_payment_settings['paypal_app_client_secret'];
    $enable_stripe = $row_payment_settings['enable_stripe'];
    $stripe_secret_key = $row_payment_settings['stripe_secret_key'];
    $stripe_publishable_key = $row_payment_settings['stripe_publishable_key'];
    $stripe_currency_code = $row_payment_settings['stripe_currency_code'];
?>
    <div class="row"><!-- 1 row Starts -->
        <div class="col-lg-12"><!-- col-lg-12 Starts -->
            <ol class="breadcrumb"><!-- breadcrumb Starts -->
                <li class="active">
                    <i class="fa fa-dashboard"></i> Dashboard / Payment Settings
                </li>
            </ol><!-- breadcrumb Ends -->
        </div><!-- col-lg-12 Ends -->
    </div><!-- 1 row Ends -->

    <div class="row"><!-- 2 row Starts -->
        <div class="col-lg-12"><!-- col-lg-12 Starts -->
            <div class="panel panel-default"><!-- panel panel-default Starts -->
                <div class="panel-heading"><!-- panel-heading Starts -->
                    <h3 class="panel-title"><!-- panel-title Starts -->
                        <i class="fa fa-money fa-fw"></i> Update Payment Settings
                    </h3><!-- panel-title Ends -->
                </div><!-- panel-heading Ends -->

                <div class="panel-body"><!-- panel-body Starts -->
                    <form class="form-horizontal payment-settings" method="post"><!-- form-horizontal Starts -->
                        <div class="form-group"><!-- form-group Starts -->
                            <label class="col-md-3 control-label"> Admin Commission Percentage : </label>
                            <div class="col-md-7">
                                <input type="text" name="commission_percentage" class="form-control"  value="<?php echo $commission_percentage; ?>" required>
                                <span id="helpBlock" class="help-block">Commission Percentage Amount you get from vendor sales.</span>
                            </div>
                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->
                            <label class="col-md-3 control-label"> Minimum Withdraw Limit : </label>
                            <div class="col-md-7">
                                <input type="text" name="minimum_withdraw_limit" class="form-control"  value="<?php echo $minimum_withdraw_limit; ?>" required>
                                <span id="helpBlock" class="help-block">Minimum balance required to make a withdraw request. Value 0 to set no minimum limits.
                                </span>
                            </div>
                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->
                            <label class="col-md-3 control-label"> Days Before Availble For Withdraw : </label>
                            <div class="col-md-7">
                                <input type="text" name="days_before_withdraw" class="form-control"
                                       value="<?php echo $days_before_withdraw; ?>" required>
                                <span id="helpBlock" class="help-block">Days, ( Make Shopkeeper order  to withdraw his commision)<br>
                                    NUMBER OF DAYS BEFORE Shopkeeper Commission FROM Orders CAN BE AVAILABLE FOR WITHDRAWAL BY Him
                                </span>
                            </div>
                        </div><!-- form-group Ends -->

                        <hr>
                        <h3>Bank Account</h3>
                        <hr>

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"> Enable Bank Account : </label>

                            <div class="col-md-7">

                                <select name="enable_paypal" class="form-control">

                                    <option value="1" <?php if ($enable_paypal == 1) {
                                      echo "selected";
                                    } ?>> Yes
                                    </option>

                                    <option value="0" <?php if ($enable_paypal == 0) {
                                      echo "selected";
                                    } ?>> No
                                    </option>

                                </select>

                                <span id="helpBlock" class="help-block">
                                    ALLOW Customers TO PAY USING Bank Account.
                                </span>
                            </div>
                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->
                            <label class="col-md-3 control-label"> Bank Email Or account No: </label>
                            <div class="col-md-7">
                                <input type="text" name="paypal_email" class="form-control" value="<?php echo $paypal_email; ?>">
                                <span id="helpBlock" class="help-block">
                                    Enter Bussiness Email For Receiving Payments And Sending Earnings To ShopKeepers  Accounts.</span>
                            </div>

                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label">  Bank Sandbox Or Iban : </label>

                            <div class="col-md-7">
                                <label class="control-label">
                                    <input type="radio" name="paypal_sandbox"
                                           value="1" <?php if ($paypal_sandbox == 1) {
                                      echo "checked";
                                    } ?> required> On
                                </label>

                                <label class="control-label">
                                    <input type="radio" name="paypal_sandbox"
                                           value="0" <?php if ($paypal_sandbox == 0) {
                                      echo "checked";
                                    } ?> required> Off

                                </label>

                            </div>

                        </div><!-- form-group Ends -->

                        <div class="form-group"><!--- form-group Starts --->

                            <label class="col-md-3 control-label">  Currency Code : </label>

                            <div class="col-md-7">

                                <input type="text" name="paypal_currency_code" class="form-control"
                                       value="<?php echo $paypal_currency_code; ?>">

                                <span id="helpBlock" class="help-block">

<!--CURRENCY USED FOR PAYPAL PAYMENTS <a href="https://developer.paypal.com/docs/classic/api/currency_codes/"-->
<!--                                     target="_blank">CLICK HERE TO GET ALL PAYPAL CURRENCY CODES</a>-->

</span>

                            </div>

                        </div><!--- form-group Ends --->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"> Bank Payout : </label>

                            <div class="col-md-7">

                                <div class="form-group"><!-- form-group Starts -->

                                    <label> Bank App Client ID: </label>

                                    <input type="text" name="paypal_app_client_id" class="form-control"
                                           value="<?php echo $paypal_app_client_id; ?>" required>

                                </div><!-- form-group Ends -->

                                <div class="form-group"><!-- form-group Starts -->

                                    <label> Bank App Client Secret: </label>

                                    <input type="text" name="paypal_app_client_secret" class="form-control"
                                           value="<?php echo $paypal_app_client_secret; ?>" required>

                                </div><!-- form-group Ends -->

                            </div>

                        </div><!-- form-group Ends -->

                        <hr>
                        <h3>Stripe</h3>
                        <hr>

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"> Enable Stripe : </label>

                            <div class="col-md-7">

                                <select name="enable_stripe" class="form-control">

                                    <option value="yes" <?php if ($enable_stripe == "yes") {
                                      echo "selected";
                                    } ?>> Yes
                                    </option>

                                    <option value="no" <?php if ($enable_stripe == "no") {
                                      echo "selected";
                                    } ?>> No
                                    </option>

                                </select>

                                <span id="helpBlock" class="help-block">

ALLOW Customers TO PAY USING Stripe.

</span>

                            </div>

                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"> Stripe Secret Key : </label>

                            <div class="col-md-7">

                                <input type="text" name="stripe_secret_key" class="form-control"
                                       value="<?php echo $stripe_secret_key; ?>">

                                <span id="helpBlock" class="help-block">

YOUR STRIPE SECRET KEY ASSIGNED TO YOU.

</span>

                            </div>

                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"> Stripe Publishable Key : </label>

                            <div class="col-md-7">

                                <input type="text" name="stripe_publishable_key" class="form-control"
                                       value="<?php echo $stripe_publishable_key; ?>">

                                <span id="helpBlock" class="help-block">

YOUR STRIPE PUBLISHABLE KEY ASSIGNED TO YOU.

</span>

                            </div>

                        </div><!-- form-group Ends -->

                        <div class="form-group"><!--- form-group Starts --->

                            <label class="col-md-3 control-label"> Stripe Currency Code : </label>

                            <div class="col-md-7">

                                <input type="text" name="stripe_currency_code" class="form-control"
                                       value="<?php echo $stripe_currency_code; ?>">

                                <span id="helpBlock" class="help-block">

CURRENCY USED FOR STRIPE PAYMENTS <a href="https://stripe.com/docs/currencies" target="_blank"> CLICK HERE TO GET ALL STRIPE CURRENCY ISO CODES </a> 

</span>

                            </div>

                        </div><!--- form-group Ends --->

                        <div class="form-group"><!--- form-group Starts --->

                            <label class="col-md-3 control-label"></label>

                            <div class="col-md-7">

                                <input type="submit" name="update_payment_settings" class="btn btn-primary form-control"
                                       value="Update Payment Settings">

                            </div>

                        </div><!--- form-group Ends --->

                    </form><!-- form-horizontal Ends -->

                </div><!-- panel-body Ends -->

            </div><!-- panel panel-default Ends -->

        </div><!-- col-lg-12 Ends -->

    </div><!-- 4 row Ends -->

  <?php

  if (isset($_POST['update_payment_settings'])) {

    $commission_percentage = mysqli_real_escape_string($con, $_POST['commission_percentage']);

    $minimum_withdraw_limit = mysqli_real_escape_string($con, $_POST['minimum_withdraw_limit']);

    $days_before_withdraw = mysqli_real_escape_string($con, $_POST['days_before_withdraw']);

    $enable_paypal = mysqli_real_escape_string($con, $_POST['enable_paypal']);

    $paypal_email = mysqli_real_escape_string($con, $_POST['paypal_email']);

    $paypal_currency_code = mysqli_real_escape_string($con, $_POST['paypal_currency_code']);

    $paypal_sandbox = mysqli_real_escape_string($con, $_POST['paypal_sandbox']);

    $paypal_app_client_id = mysqli_real_escape_string($con, $_POST['paypal_app_client_id']);

    $paypal_app_client_secret = mysqli_real_escape_string($con, $_POST['paypal_app_client_secret']);

    $enable_stripe = mysqli_real_escape_string($con, $_POST['enable_stripe']);

    $stripe_secret_key = mysqli_real_escape_string($con, $_POST['stripe_secret_key']);

    $stripe_publishable_key = mysqli_real_escape_string($con, $_POST['stripe_publishable_key']);

    $stripe_currency_code = mysqli_real_escape_string($con, $_POST['stripe_currency_code']);

    $update_payment_settings = "update payment_settings set commission_percentage='$commission_percentage',minimum_withdraw_limit='$minimum_withdraw_limit',days_before_withdraw='$days_before_withdraw',enable_paypal='$enable_paypal',paypal_email='$paypal_email',paypal_currency_code='$paypal_currency_code',paypal_sandbox='$paypal_sandbox',paypal_app_client_id='$paypal_app_client_id',paypal_app_client_secret='$paypal_app_client_secret',enable_stripe='$enable_stripe',stripe_secret_key='$stripe_secret_key',stripe_publishable_key='$stripe_publishable_key',stripe_currency_code='$stripe_currency_code'";

    $run_stripe_settings = mysqli_query($con, $update_payment_settings);

    if ($run_stripe_settings) {

      echo "<script>alert('Your Stripe Settings Have Been Updated Successfully.');window.open('index.php?payment_settings','_self');</script>";

    }

  }

  ?>

<?php } ?>