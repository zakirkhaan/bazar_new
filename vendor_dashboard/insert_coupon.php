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


<div class="row"><!-- 2 row Starts --->

    <div class="col-lg-12"><!-- col-lg-12 Starts -->

        <div class="panel panel-default"><!-- panel panel-default Starts -->

            <div class="panel-heading"><!-- panel-heading Starts -->

                <h3 class="panel-title"><!-- panel-title Starts -->

                    <i class="fa fa-money fa-fw"> </i> Insert Coupon

                </h3><!-- panel-title Ends -->

            </div><!-- panel-heading Ends -->

            <div class="panel-body"><!--panel-body Starts -->

                <form class="form-horizontal" method="post"><!-- form-horizontal Starts -->

                    <div class="form-group"><!-- form-group Starts -->

                        <label class="col-md-3 control-label"> Coupon Title </label>

                        <div class="col-md-6">

                            <input type="text" name="coupon_title" class="form-control">

                        </div>

                    </div><!-- form-group Ends -->

                    <div class="form-group"><!-- form-group Starts -->

                        <label class="col-md-3 control-label"> Coupon Price </label>

                        <div class="col-md-6">

                            <input type="text" name="coupon_price" class="form-control">

                        </div>

                    </div><!-- form-group Ends -->

                    <div class="form-group"><!-- form-group Starts -->

                        <label class="col-md-3 control-label"> Coupon Code </label>

                        <div class="col-md-6">

                            <input type="text" name="coupon_code" class="form-control">

                        </div>

                    </div><!-- form-group Ends -->

                    <div class="form-group"><!-- form-group Starts -->

                        <label class="col-md-3 control-label"> Coupon Limit </label>

                        <div class="col-md-6">

                            <input type="number" name="coupon_limit" value="1" class="form-control">

                        </div>

                    </div><!-- form-group Ends -->

                    <div class="form-group"><!-- form-group Starts -->

                        <label class="col-md-3 control-label">Select coupon for Product Or bundle</label>

                        <div class="col-md-6">

                            <select name="product_id" class="form-control">

                                <optgroup label="Select Product">

                                  <?php

                                  $select_products = "select * from products where vendor_id='$customer_id' and product_vendor_status='active' and status='product'";

                                  $run_products = mysqli_query($con, $select_products);

                                  while ($row_products = mysqli_fetch_array($run_products)) {

                                    $product_id = $row_products["product_id"];

                                    $product_title = $row_products["product_title"];

                                    echo "<option value='$product_id'>$product_title</option>";

                                  }

                                  ?>

                                </optgroup>

                                <optgroup label="Select Bundle">

                                  <?php

                                  $select_bundles = "select * from products where vendor_id='$customer_id' and product_vendor_status='active' and status='bundle'";

                                  $run_bundles = mysqli_query($con, $select_bundles);

                                  while ($row_bundles = mysqli_fetch_array($run_bundles)) {

                                    $product_id = $row_bundles["product_id"];

                                    $product_title = $row_bundles["product_title"];

                                    echo "<option value='$product_id'>$product_title</option>";

                                  }

                                  ?>
                                </optgroup>

                            </select>

                        </div>

                    </div><!-- form-group Ends -->

                    <div class="form-group"><!-- form-group Starts -->

                        <label class="col-md-3 control-label"> </label>

                        <div class="col-md-6">

                            <input type="submit" name="submit" class=" btn btn-primary form-control"
                                   value=" Insert Coupon ">

                        </div>

                    </div><!-- form-group Ends -->

                </form><!-- form-horizontal Ends -->

            </div><!--panel-body Ends -->

        </div><!-- panel panel-default Ends -->

    </div><!-- col-lg-12 Ends -->

</div><!-- 2 row Ends --->

<?php

if (isset($_POST['submit'])) {

  $coupon_title = $_POST['coupon_title'];

  $coupon_price = $_POST['coupon_price'];

  $coupon_code = $_POST['coupon_code'];

  $coupon_limit = $_POST['coupon_limit'];

  $product_id = $_POST['product_id'];

  $coupon_used = 0;

  $select_coupons = "select * from coupons where product_id='$product_id' or coupon_code='$coupon_code'";

  $run_coupons = mysqli_query($con, $select_coupons);

  $check_coupons = mysqli_num_rows($run_coupons);

  if ($check_coupons == 1) {

    echo "
<script>
alert('Coupon Code or Product Already Added Choose another Coupon code or Product');
</script>
";

  } else {

    $insert_coupon = "insert into coupons (vendor_id,product_id,coupon_title,coupon_price,coupon_code,coupon_limit,coupon_used) values ('$customer_id','$product_id','$coupon_title','$coupon_price','$coupon_code','$coupon_limit','$coupon_used')";

    $run_coupon = mysqli_query($con, $insert_coupon);

    if ($run_coupon) {

      echo "

<script>

alert('New Coupon Has Been Inserted Successfully.');

window.open('index.php?coupons','_self');

</script>

";

    }

  }

}

?>