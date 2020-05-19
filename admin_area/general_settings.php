<?php
if (!isset($_SESSION['admin_email'])) {
    echo "<script>window.open('login.php','_self')</script>";
} else {
    $select_general_settings = "select * from general_settings";
    $run_general_settings = mysqli_query($con, $select_general_settings);
    $row_general_settings = mysqli_fetch_array($run_general_settings);
    $site_title = $row_general_settings["site_title"];
    $meta_author = $row_general_settings["meta_author"];
    $meta_description = $row_general_settings["meta_description"];
    $meta_keywords = $row_general_settings["meta_keywords"];
    $enable_vendor = $row_general_settings["enable_vendor"];
    $new_product_status = $row_general_settings["new_product_status"];
    $edited_product_status = $row_general_settings["edited_product_status"];
    $order_status_change = $row_general_settings["order_status_change"];
?>
    <div class="row"><!-- 1 row Starts -->
        <div class="col-lg-12"><!-- col-lg-12 Starts -->
            <ol class="breadcrumb"><!-- breadcrumb Starts -->
                <li class="active">
                    <i class="fa fa-dashboard"></i> Dashboard / General Settings
                </li>
            </ol><!-- breadcrumb Ends -->
        </div><!-- col-lg-12 Ends -->
    </div><!-- 1 row Ends -->

    <div class="row"><!-- 2 row Starts -->
        <div class="col-lg-12"><!-- col-lg-12 Starts -->
            <div class="panel panel-default"><!-- panel panel-default Starts -->

                <div class="panel-heading"><!-- panel-heading Starts -->

                    <h3 class="panel-title"><!-- panel-title Starts -->

                        <i class="fa fa-money fa-fw"> </i> General Settings

                    </h3><!-- panel-title Ends -->

                </div><!-- panel-heading Ends -->

                <div class="panel-body"><!-- panel-body Starts -->

                    <form class="form-horizontal" method="post"><!-- form-horizontal Starts -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"> Site/Seo Title : </label>

                            <div class="col-md-7">

                                <input type="text" name="site_title" class="form-control"
                                       value="<?php echo $site_title; ?>" required>

                            </div>

                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"> Meta Author : </label>

                            <div class="col-md-7">

                                <input type="text" name="meta_author" class="form-control"
                                       value="<?php echo $meta_author; ?>">

                            </div>

                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"> Meta Description : </label>

                            <div class="col-md-7">

                                <input type="text" name="meta_description" class="form-control"
                                       value="<?php echo $meta_description; ?>">

                            </div>

                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"> Meta Keywords : </label>

                            <div class="col-md-7">

                                <input type="text" name="meta_keywords" class="form-control"
                                       value="<?php echo $meta_keywords; ?>">

                            </div>

                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"> Enable Vendor Registration: </label>

                            <div class="col-md-7">

                                <label class="control-label">

                                    <input type="radio" name="enable_vendor"
                                           value="yes" <?php if ($enable_vendor == "yes") {
                                      echo "checked";
                                    } ?> required> Yes

                                </label>

                                <label class="control-label">

                                    <input type="radio" name="enable_vendor"
                                           value="no" <?php if ($enable_vendor == "no") {
                                      echo "checked";
                                    } ?> required> No

                                </label>

                                <span id="helpBlock" class="help-block">This option means whether vendors are able to register on your website are not.</span>
                            </div>
                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"> New Product Status : </label>

                            <div class="col-md-7">

                                <select class="form-control" name="new_product_status">
                                    <!-- select manufacturer Starts -->

                                    <option value="active" <?php if ($new_product_status == "active") {
                                      echo "selected";
                                    } ?>> (Active) Published
                                    </option>

                                    <option value="pending" <?php if ($new_product_status == "pending") {
                                      echo "selected";
                                    } ?>> (Pending) Pending Approvel
                                    </option>

                                </select><!-- select manufacturer Ends -->

                                <span id="helpBlock" class="help-block">Product status when a vendor creates a product.</span>
                            </div>

                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"> Edited Product Status : </label>

                            <div class="col-md-7">

                                <label class="control-label">

                                    <input type="checkbox" name="edited_product_status"
                                           value="yes" <?php if ($edited_product_status == "yes") {
                                      echo "checked";
                                    } ?>>

                                    Set Product status as pending approvel when a vendor edits or updates a product.

                                </label>

                            </div>

                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"> Order Status Change : </label>

                            <div class="col-md-7">

                                <label class="control-label">

                                    <input type="checkbox" name="order_status_change"
                                           value="yes" <?php if ($order_status_change == "yes") {
                                      echo "checked";
                                    } ?>> Vendor can update/change order status.

                                </label>

                            </div>

                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"> </label>

                            <div class="col-md-7">

                                <input type="submit" name="submit" class="form-control btn btn-primary"
                                       value="Update General Settings">

                            </div>

                        </div><!-- form-group Ends -->

                    </form><!-- form-horizontal Ends -->

                </div><!-- panel-body Ends -->

            </div><!-- panel panel-default Ends -->

        </div><!-- col-lg-12 Ends -->

    </div><!-- 2 row Ends -->

  <?php

  if (isset($_POST['submit'])) {

    $site_title = mysqli_real_escape_string($con, $_POST['site_title']);

    $meta_author = mysqli_real_escape_string($con, $_POST['meta_author']);

    $meta_description = mysqli_real_escape_string($con, $_POST['meta_description']);

    $meta_keywords = mysqli_real_escape_string($con, $_POST['meta_keywords']);

    $enable_vendor = mysqli_real_escape_string($con, $_POST['enable_vendor']);

    $new_product_status = mysqli_real_escape_string($con, $_POST['new_product_status']);

    if (isset($_POST["edited_product_status"])) {

      $edited_product_status = mysqli_real_escape_string($con, $_POST['edited_product_status']);

    } else {

      $edited_product_status = "no";

    }

    if (isset($_POST["order_status_change"])) {

      $order_status_change = mysqli_real_escape_string($con, $_POST['order_status_change']);

    } else {

      $order_status_change = "no";

    }

    $update_general_settings = "update general_settings set site_title='$site_title',meta_author='$meta_author',meta_description='$meta_description',meta_keywords='$meta_keywords',order_status_change='$order_status_change',enable_vendor='$enable_vendor',new_product_status='$new_product_status',edited_product_status='$edited_product_status',order_status_change='$order_status_change'";

    $run_general_settings = mysqli_query($con, $update_general_settings);

    if ($run_general_settings) {

      echo "<script>alert(' Your General Settings Has Been Updated Successfully. ');window.open('index.php?general_settings','_self');</script>";
    }
  }
?>
<?php } ?>