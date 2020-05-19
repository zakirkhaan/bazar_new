<?php

@session_start();

if (!isset($_SESSION['customer_email'])) {

  echo "<script>window.open('../checkout.php','_self')</script>";

}

$customer_session = $_SESSION['customer_email'];

$get_customer = "select * from customers where customer_email='$customer_session'";

$run_customer = mysqli_query($con, $get_customer);

$row_customer = mysqli_fetch_array($run_customer);

$customer_id = $row_customer['customer_id'];

$get_customers_addresses = "select * from customers_addresses where customer_id='$customer_id'";

$run_customers_addresses = mysqli_query($con, $get_customers_addresses);

$row_addresses = mysqli_fetch_array($run_customers_addresses);

$billing_first_name = $row_addresses["billing_first_name"];

$billing_last_name = $row_addresses["billing_last_name"];

$billing_address_1 = $row_addresses["billing_address_1"];

$billing_address_2 = $row_addresses["billing_address_2"];

$billing_city = $row_addresses["billing_city"];

$billing_postcode = $row_addresses["billing_postcode"];

$billing_country = $row_addresses["billing_country"];

$billing_state = $row_addresses["billing_state"];

$shipping_first_name = $row_addresses["shipping_first_name"];

$shipping_last_name = $row_addresses["shipping_last_name"];

$shipping_address_1 = $row_addresses["shipping_address_1"];

$shipping_address_2 = $row_addresses["shipping_address_2"];

$shipping_city = $row_addresses["shipping_city"];

$shipping_postcode = $row_addresses["shipping_postcode"];

$shipping_country = $row_addresses["shipping_country"];

$shipping_state = $row_addresses["shipping_state"];

?>

    <h2> Billing Address </h2>

    <form method="post" enctype="multipart/form-data"><!--- form Starts -->

        <div class="row"><!-- row Starts -->

            <div class="col-sm-6">

                <div class="form-group"><!-- form-group Starts -->

                    <label> First name : </label>

                    <input type="text" name="billing_first_name" class="form-control" required
                           value="<?php echo $billing_first_name; ?>">

                </div><!-- form-group Ends -->

            </div>

            <div class="col-sm-6">

                <div class="form-group"><!-- form-group Starts -->

                    <label> Last name : </label>

                    <input type="text" name="billing_last_name" class="form-control" required
                           value="<?php echo $billing_last_name; ?>">

                </div><!-- form-group Ends -->

            </div>

        </div><!-- row Ends -->

        <div class="form-group"><!-- form-group Starts -->

            <label> Country : </label>

            <select name="billing_country" class="form-control">

                <option value=""> Select A Country</option>

              <?php

              $get_countries = "select * from countries";

              $run_countries = mysqli_query($con, $get_countries);

              while ($row_countries = mysqli_fetch_array($run_countries)) {

                $country_id = $row_countries['country_id'];

                $country_name = $row_countries['country_name'];

                ?>

                  <option value="<?php echo $country_id; ?>"

                    <?php

                    if ($billing_country == $country_id) {
                      echo "selected";
                    }

                    ?>>

                    <?php echo $country_name; ?>

                  </option>

              <?php } ?>

            </select>

        </div><!-- form-group Ends -->

        <div class="form-group"><!-- form-group Starts -->

            <label> Address 1 : </label>

            <input type="text" name="billing_address_1" class="form-control" required
                   value="<?php echo $billing_address_1; ?>">

        </div><!-- form-group Ends -->

        <div class="form-group"><!-- form-group Starts -->

            <label> Address 2 : </label>

            <input type="text" name="billing_address_2" class="form-control" required
                   value="<?php echo $billing_address_2; ?>">

        </div><!-- form-group Ends -->

        <div class="row"><!-- row Starts -->

            <div class="col-sm-6">

                <div class="form-group"><!-- form-group Starts -->

                    <label> State / County : </label>

                    <input type="text" name="billing_state" class="form-control" required
                           value="<?php echo $billing_state; ?>">

                </div><!-- form-group Ends -->

            </div>

            <div class="col-sm-6">

                <div class="form-group"><!-- form-group Starts -->

                    <label> Town / City : </label>

                    <input type="text" name="billing_city" class="form-control" required
                           value="<?php echo $billing_city; ?>">

                </div><!-- form-group Ends -->

            </div>

        </div><!-- row Ends -->

        <div class="form-group"><!-- form-group Starts -->

            <label> Postcode / ZIP : </label>

            <input type="text" name="billing_postcode" class="form-control" required
                   value="<?php echo $billing_postcode; ?>">

        </div><!-- form-group Ends -->

        <div class="form-group"><!-- form-group Starts -->

            <input type="submit" name="update_billing_address" value="Update Billing Address"
                   class="form-control btn btn-success">

        </div><!-- form-group Ends -->

    </form>

    <h2> Shipping Address </h2>

    <form method="post" enctype="multipart/form-data"><!--- form Starts -->

        <div class="row"><!-- row Starts -->

            <div class="col-sm-6">

                <div class="form-group"><!-- form-group Starts -->

                    <label> First name : </label>

                    <input type="text" name="shipping_first_name" class="form-control" required
                           value="<?php echo $shipping_first_name; ?>">

                </div><!-- form-group Ends -->

            </div>

            <div class="col-sm-6">

                <div class="form-group"><!-- form-group Starts -->

                    <label> Last name : </label>

                    <input type="text" name="shipping_last_name" class="form-control" required
                           value="<?php echo $shipping_last_name; ?>">

                </div><!-- form-group Ends -->

            </div>

        </div><!-- row Ends -->

        <div class="form-group"><!-- form-group Starts -->

            <label> Country : </label>

            <select name="shipping_country" class="form-control">

                <option value=""> Select A Country</option>

              <?php

              $get_countries = "select * from countries";

              $run_countries = mysqli_query($con, $get_countries);

              while ($row_countries = mysqli_fetch_array($run_countries)) {

                $country_id = $row_countries['country_id'];

                $country_name = $row_countries['country_name'];

                ?>

                  <option value="<?php echo $country_id; ?>"

                    <?php

                    if ($shipping_country == $country_id) {
                      echo "selected";
                    }

                    ?>>

                    <?php echo $country_name; ?>

                  </option>

              <?php } ?>

            </select>

        </div><!-- form-group Ends -->

        <div class="form-group"><!-- form-group Starts -->

            <label> Address 1 : </label>

            <input type="text" name="shipping_address_1" class="form-control" required
                   value="<?php echo $shipping_address_1; ?>">

        </div><!-- form-group Ends -->

        <div class="form-group"><!-- form-group Starts -->

            <label> Address 2 : </label>

            <input type="text" name="shipping_address_2" class="form-control" required
                   value="<?php echo $shipping_address_2; ?>">

        </div><!-- form-group Ends -->

        <div class="row"><!-- row Starts -->

            <div class="col-sm-6">

                <div class="form-group"><!-- form-group Starts -->

                    <label> State / County : </label>

                    <input type="text" name="shipping_state" class="form-control" required
                           value="<?php echo $shipping_state; ?>">

                </div><!-- form-group Ends -->

            </div>

            <div class="col-sm-6">

                <div class="form-group"><!-- form-group Starts -->

                    <label> Town / City : </label>

                    <input type="text" name="shipping_city" class="form-control" required
                           value="<?php echo $shipping_city; ?>">

                </div><!-- form-group Ends -->

            </div>

        </div><!-- row Ends -->

        <div class="form-group"><!-- form-group Starts -->

            <label> Postcode / ZIP : </label>

            <input type="text" name="shipping_postcode" class="form-control" required
                   value="<?php echo $shipping_postcode; ?>">

        </div><!-- form-group Ends -->

        <div class="form-group"><!-- form-group Starts -->

            <input type="submit" name="update_shipping_address" value="Update Shipping Address"
                   class="form-control btn btn-success">

        </div><!-- form-group Ends -->

    </form>

    <script>

        $(".chosen-select").chosen({});

    </script>

<?php

if (isset($_POST["update_billing_address"])) {

  $billing_first_name = $_POST["billing_first_name"];

  $billing_last_name = $_POST["billing_last_name"];

  $billing_address_1 = $_POST["billing_address_1"];

  $billing_address_2 = $_POST["billing_address_2"];

  $billing_city = $_POST["billing_city"];

  $billing_postcode = $_POST["billing_postcode"];

  $billing_country = $_POST["billing_country"];

  $billing_state = $_POST["billing_state"];

  $update_billing_address = "update customers_addresses set billing_first_name='$billing_first_name',billing_last_name='$billing_last_name',billing_address_1='$billing_address_1',billing_address_2='$billing_address_2',billing_city='$billing_city',billing_postcode='$billing_postcode',billing_country='$billing_country',billing_state='$billing_state' where customer_id='$customer_id'";

  $run_billing_address = mysqli_query($con, $update_billing_address);

  echo "<script>alert('Your Account Billing Address Has Been Updated.')</script>";

  echo "<script>window.open('my_account.php?my_addresses','_self')</script>";

}

if (isset($_POST["update_shipping_address"])) {

  $shipping_first_name = $_POST["shipping_first_name"];

  $shipping_last_name = $_POST["shipping_last_name"];

  $shipping_address_1 = $_POST["shipping_address_1"];

  $shipping_address_2 = $_POST["shipping_address_2"];

  $shipping_city = $_POST["shipping_city"];

  $shipping_postcode = $_POST["shipping_postcode"];

  $shipping_country = $_POST["shipping_country"];

  $shipping_state = $_POST["shipping_state"];

  $update_shipping_address = "update customers_addresses set shipping_first_name='$shipping_first_name',shipping_last_name='$shipping_last_name',shipping_address_1='$shipping_address_1',shipping_address_2='$shipping_address_2',shipping_city='$shipping_city',shipping_postcode='$shipping_postcode',shipping_country='$shipping_country',shipping_state='$shipping_state' where customer_id='$customer_id'";

  $run_shipping_address = mysqli_query($con, $update_shipping_address);

  echo "<script>alert('Your Account Shipping Address Has Been Updated.')</script>";

  echo "<script>window.open('my_account.php?my_addresses','_self')</script>";

}

?>