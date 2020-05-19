<?php

if (!isset($_SESSION['admin_email'])) {

  echo "<script>window.open('login.php','_self')</script>";

} else {

  if (isset($_GET['edit_shipping_type'])) {

    $edit_type_id = $_GET['edit_shipping_type'];

    $get_zones = "select * from shipping_type where type_id='$edit_type_id'";

    $run_zones = mysqli_query($con, $get_zones);

    $row_zones = mysqli_fetch_array($run_zones);

    $type_name = $row_zones['type_name'];

    $type_local = $row_zones['type_local'];

    $type_default = $row_zones['type_default'];

  }

  ?>

    <link rel="stylesheet" href="css/chosen.min.css">

    <div class="row"><!-- 1 row Starts -->

        <div class="col-lg-12"><!-- col-lg-12 Starts -->

            <ol class="breadcrumb"><!-- breadcrumb Starts -->

                <li class="active">

                    <i class="fa fa-dashboard"></i> Dashboard / Edit Shipping Type

                </li>

            </ol><!-- breadcrumb Ends -->

        </div><!-- col-lg-12 Ends -->

    </div><!-- 1 row Ends -->

    <div class="row"><!-- 2 row Starts -->

        <div class="col-lg-12"><!-- col-lg-12 Starts -->

            <div class="panel panel-default"><!-- panel panel-default Starts -->

                <div class="panel-heading"><!-- panel-heading Starts -->

                    <h3 class="panel-title"><!-- panel-title Starts -->

                        <i class="fa fa-money fa-fw"> </i> Edit Shipping Type

                    </h3><!-- panel-title Ends -->

                </div><!-- panel-heading Ends -->

                <div class="panel-body"><!-- panel-body Starts -->

                    <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                        <!-- form-horizontal Starts -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"> Type Name </label>

                            <div class="col-md-7">

                                <input type="text" name="type_name" class="form-control"
                                       value="<?php echo $type_name; ?>">

                            </div>

                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"> Type Default </label>

                            <div class="col-md-7">

                              <?php if ($type_default == "yes") { ?>

                                  <label>
                                      <input type="radio" name="type_default" value="yes" checked> Yes
                                  </label>

                                  <label>
                                      <input type="radio" name="type_default" value="no"> No
                                  </label>

                              <?php } elseif ($type_default == "no") { ?>

                                  <label>
                                      <input type="radio" name="type_default" value="yes"> Yes
                                  </label>

                                  <label>
                                      <input type="radio" name="type_default" value="no" checked> No
                                  </label>

                              <?php } ?>

                            </div>

                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"> </label>

                            <div class="col-md-7">

                                <input type="submit" name="update" class="form-control btn btn-primary"
                                       value="Update Shipping Type">

                            </div>

                        </div><!-- form-group Ends -->

                    </form><!-- form-horizontal Ends -->

                </div><!-- panel-body Ends -->

            </div><!-- panel panel-default Ends -->

        </div><!-- col-lg-12 Ends -->

    </div><!-- 2 row Ends -->

    <script src="js/jquery.min.js"></script>

    <script src="js/chosen.jquery.min.js"></script>

    <script>

        var config = {
            '.chosen-select': {}
        };

        for (var selector in config) {
            $(selector).chosen(config[selector]);
        }

    </script>

  <?php

  if (isset($_POST['update'])) {

    $type_name = mysqli_real_escape_string($con, $_POST['type_name']);

    $type_default = mysqli_real_escape_string($con, $_POST['type_default']);

    if ($type_default == "yes") {

      $update_type_default = "update shipping_type set type_default='no' where type_local='$type_local'";

      $run_type_default = mysqli_query($con, $update_type_default);

    }

    $update_shipping_type = "update shipping_type set type_name='$type_name',type_default='$type_default' where type_id='$edit_type_id'";

    $run_shipping_type = mysqli_query($con, $update_shipping_type);

    if ($run_shipping_type) {

      echo "<script>alert('Your Shipping Type Has Been Updated.')</script>";

      echo "<script>window.open('index.php?view_shipping_types','_self')</script>";

    }

  }

  ?>

<?php } ?>