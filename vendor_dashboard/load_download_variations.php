<?php

session_start();

include("../includes/db.php");

if (!isset($_SESSION['customer_email'])) {

  echo "<script> window.open('../../checkout.php','_self'); </script>";

}

$customer_email = $_SESSION['customer_email'];

$select_customer = "select * from customers where customer_email='$customer_email'";

$run_customer = mysqli_query($con, $select_customer);

$row_customer = mysqli_fetch_array($run_customer);

$customer_id = $row_customer['customer_id'];

$product_id = mysqli_real_escape_string($con, $_POST['product_id']);

$select_product = "select * from products where product_id='$product_id' and vendor_id='$customer_id'";

$run_product = mysqli_query($con, $select_product);

$row_product = mysqli_fetch_array($run_product);

$product_type = $row_product['product_type'];

if ($product_type == "variable_product") {

  ?>

    <div class="form-group"><!-- form-group Starts -->

        <label class="col-md-3 control-label"> Select A Variation </label>

        <div class="col-md-6">

            <select name="variation_id" class="form-control" required>

                <option value=""> Select A Variation</option>

              <?php

              $select_product_variations = "select * from product_variations where product_id='$product_id' and product_type='digital_product'";

              $run_product_variations = mysqli_query($con, $select_product_variations);

              while ($row_product_variations = mysqli_fetch_array($run_product_variations)) {

                $variation_id = $row_product_variations["variation_id"];

                echo "<option value='$variation_id'>Variation #$variation_id</option>";

              }

              ?>

            </select>

        </div>

    </div><!-- form-group Ends -->

<?php } ?>
