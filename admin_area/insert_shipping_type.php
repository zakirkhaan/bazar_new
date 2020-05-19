<?php if (!isset($_SESSION['admin_email'])) { echo "<script>window.open('login.php','_self')</script>"; } else { ?>
<?php
  $admin_email = $_SESSION['admin_email'];
  $select_admin = "select * from admins where admin_email='$admin_email'";
  $run_admin = mysqli_query($con, $select_admin);
  $row_admin = mysqli_fetch_array($run_admin);
  $admin_id = $row_admin['admin_id'];
?>
<link rel="stylesheet" href="css/chosen.min.css">
    <div class="row"><!-- 1 row Starts -->
        <div class="col-lg-12"><!-- col-lg-12 Starts -->
            <ol class="breadcrumb"><!-- breadcrumb Starts -->
                <li class="active">
                    <i class="fa fa-dashboard"></i> Dashboard / Insert Shipping Type
                </li>
            </ol><!-- breadcrumb Ends -->
        </div><!-- col-lg-12 Ends -->
    </div><!-- 1 row Ends -->

    <div class="row"><!-- 2 row Starts -->
        <div class="col-lg-12"><!-- col-lg-12 Starts -->
            <div class="panel panel-default"><!-- panel panel-default Starts -->
                <div class="panel-heading"><!-- panel-heading Starts -->
                    <h3 class="panel-title"><!-- panel-title Starts -->
                        <i class="fa fa-money fa-fw"> </i> Insert Shipping Type
                    </h3><!-- panel-title Ends -->
                </div><!-- panel-heading Ends -->

                <div class="panel-body"><!-- panel-body Starts -->
                    <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">

                        <div class="form-group"><!-- form-group Starts -->
                            <label class="col-md-3 control-label"> Type Name </label>
                            <div class="col-md-7">
                                <input type="text" name="type_name" class="form-control">
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
                                <input type="submit" name="submit" class="form-control btn btn-primary" value="Insert Shipping Type">
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
     var config = {'.chosen-select': {} };
        for (var selector in config) {
            $(selector).chosen(config[selector]);
        }
</script>
<?php
  if (isset($_POST['submit'])) {
      $type_name = mysqli_real_escape_string($con, $_POST['type_name']);
      $type_default = mysqli_real_escape_string($con, $_POST['type_default']);
      $type_local = mysqli_real_escape_string($con, $_POST['type_local']);

      if ($type_default == "yes") {
        $update_type_default = "update shipping_type set type_default='no' where type_local='$type_local'";
        $run_type_default = mysqli_query($con, $update_type_default);
    }
        $insert_shipping_type = "insert into shipping_type (vendor_id,type_name,type_order,type_default,type_local) values ('admin_$admin_id','$type_name','5','$type_default','$type_local')";
        $run_shipping_type = mysqli_query($con, $insert_shipping_type);

    if ($run_shipping_type) {
        echo "<script>alert('New Shipping Type Has Been Inserted.')</script>";
        echo "<script>window.open('index.php?view_shipping_types','_self')</script>";
    }
  }
?>
<?php } ?>