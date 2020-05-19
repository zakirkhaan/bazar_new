<?php

if (!isset($_SESSION['customer_email'])) {

  echo "<script> window.open('../checkout.php','_self'); </script>";

}

$customer_email = $_SESSION['customer_email'];

$select_customer = "select * from customers where customer_email='$customer_email'";

$run_customer = mysqli_query($con, $select_customer);

$row_customer = mysqli_fetch_array($run_customer);

$customer_id = $row_customer['customer_id'];

?>

<div class="row"><!-- 2 row Starts -->

    <div class="col-lg-12"><!-- col-lg-12 Starts -->

        <div class="panel panel-default"><!-- panel panel-default Starts -->

            <div class="panel-heading"><!-- panel-heading Starts -->

                <h3 class="panel-title"><!-- panel-title Starts -->

                    <i class="fa fa-money fa-fw"></i> Insert Shipping Type

                </h3><!-- panel-title Ends -->

            </div><!-- panel-heading Ends -->

            <div class="panel-body"><!-- panel-body Starts -->

                <form class="form-horizontal" method="post"><!-- form-horizontal Starts -->

                    <div class="form-group"><!-- form-group Starts -->

                        <label class="col-md-3 control-label"> Type Name </label>

                        <div class="col-md-7">

                            <input type="text" name="type_name" class="form-control" required>

                        </div>

                    </div><!-- form-group Ends -->

                    <div class="form-group"><!-- form-group Starts -->

                        <label class="col-md-3 control-label"> Type Default </label>

                        <div class="col-md-7">

                            <label>

                                <input type="radio" name="type_default" value="yes" required> Yes

                            </label>

                            <label>

                                <input type="radio" name="type_default" value="no" required> No

                            </label>

                        </div>

                    </div><!-- form-group Ends -->

                    <div class="form-group"><!-- form-group Starts -->

                        <label class="col-md-3 control-label"> Type Local </label>

                        <div class="col-md-7">

                            <label>

                                <input type="radio" name="type_local" value="yes" required> Yes

                            </label>

                            <label>

                                <input type="radio" name="type_local" value="no" required> No

                            </label>

                        </div>

                    </div><!-- form-group Ends -->

                    <div class="form-group"><!-- form-group Starts -->

                        <label class="col-md-3 control-label"> </label>

                        <div class="col-md-7">

                            <input type="submit" name="submit" value="Insert Shipping Type"
                                   class="form-control btn btn-success">

                        </div>

                    </div><!-- form-group Ends -->

                </form><!-- form-horizontal Ends -->

            </div><!-- panel-body Ends -->

        </div><!-- panel panel-default Ends -->

    </div><!-- col-lg-12 Ends -->

</div><!-- 2 row Ends -->

<?php

if (isset($_POST['submit'])) {

  $type_name = mysqli_real_escape_string($con, $_POST['type_name']);

  $type_default = mysqli_real_escape_string($con, $_POST['type_default']);

  $type_local = mysqli_real_escape_string($con, $_POST['type_local']);

  if ($type_default == "yes") {

    $update_type_deafult = "update shipping_types set type_default='no' where vendor_id='$customer_id' and type_local='$type_local'";

    $run_update_type_deafult = mysqli_query($con, $update_type_deafult);

  }

  $select_type_order = "select max(type_order) AS type_order from shipping_type where vendor_id='$customer_id' and type_local='$type_local'";

  $run_type_order = mysqli_query($con, $select_type_order);

  $row_type_order = mysqli_fetch_array($run_type_order);

  $type_order = $row_type_order['type_order'] + 1;

  $insert_shipping_type = "insert into shipping_type (vendor_id,type_name,type_default,type_local,type_order) values ('$customer_id','$type_name','$type_default','$type_local','$type_order')";

  $run_insert_shipping_type = mysqli_query($con, $insert_shipping_type);

  if ($run_insert_shipping_type) {

    echo "

<script>

alert('New Shipping Type Has Been Inserted Successfully.');

window.open('index.php?shipping_types', '_self');

</script>

";

  }

}

?>

