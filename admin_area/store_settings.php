<?php

if (!isset($_SESSION['admin_email'])) {
  echo "<script>window.open('login.php','_self')</script>";

} else {

  $admin_email = $_SESSION['admin_email'];
  $select_admin = "select * from admins where admin_email='$admin_email'";
  $run_admin = mysqli_query($con, $select_admin);
  $row_admin = mysqli_fetch_array($run_admin);
  $admin_id = "admin_" . $row_admin['admin_id'];
  $select_store_settings = "select * from store_settings where vendor_id='$admin_id'";
  $run_store_settings = mysqli_query($con, $select_store_settings);
  $row_store_settings = mysqli_fetch_array($run_store_settings);
  @$store_cover_image = $row_store_settings["store_cover_image"];
  @$store_profile_image = $row_store_settings["store_profile_image"];
  @$store_name = $row_store_settings["store_name"];
  @$store_country = $row_store_settings["store_country"];
  @$store_address_1 = $row_store_settings["store_address_1"];
  @$store_address_2 = $row_store_settings["store_address_2"];
  @$store_state = $row_store_settings["store_state"];
  @$store_city = $row_store_settings["store_city"];
  @$store_postcode = $row_store_settings["store_postcode"];
  @$paypal_email = $row_store_settings["paypal_email"];
  @$phone_no = $row_store_settings["phone_no"];
  @$store_email = $row_store_settings["store_email"];
  ?>

    <div class="row"><!-- 1 row Starts -->

        <div class="col-lg-12"><!-- col-lg-12 Starts -->

            <ol class="breadcrumb"><!-- breadcrumb Starts -->

                <li class="active">

                    <i class="fa fa-dashboard"></i> Dashboard / Store Settings

                </li>

            </ol><!-- breadcrumb Ends -->

        </div><!-- col-lg-12 Ends -->

    </div><!-- 1 row Ends -->

    <div class="row"><!-- 2 row Starts -->

        <div class="col-lg-12"><!-- col-lg-12 Starts -->

            <div class="panel panel-default"><!-- panel panel-default Starts -->

                <div class="panel-heading"><!-- panel-heading Starts -->

                    <h3 class="panel-title"><!-- panel-title Starts -->

                        <i class="fa fa-money fa-fw"> </i> Store Settings

                    </h3><!-- panel-title Ends -->

                </div><!-- panel-heading Ends -->

                <div class="panel-body"><!-- panel-body Starts -->

                    <form class="form-horizontal payment-settings" method="post" enctype="multipart/form-data">
                        <!-- form-horizontal Starts -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"> Cover/Banner Image </label>

                            <div class="col-md-7">

                                <input type="file" name="cover_image" class="form-control">

                                <br>

                              <?php if (empty($store_cover_image)) { ?>

                                  <img src="../images/no-image.jpg" width="70" height="70">

                              <?php } else { ?>

                                  <img src="../images/<?php echo $store_cover_image; ?>" width="70" height="70">

                              <?php } ?>

                            </div>

                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"> Profile Image </label>

                            <div class="col-md-7">

                                <input type="file" name="profile_image" class="form-control">

                                <br>

                              <?php if (empty($store_profile_image)) { ?>

                                  <img src="../images/no-image.jpg" width="70" height="70">

                              <?php } else { ?>

                                  <img src="../images/<?php echo $store_profile_image; ?>" width="70" height="70">

                              <?php } ?>

                            </div>

                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label">Store Name</label>

                            <div class="col-md-7">

                                <input type="text" name="store_name" class="form-control"
                                       value="<?php echo $store_name; ?>" required>

                            </div>

                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label">Store Address</label>

                            <div class="col-md-7"><!-- col-md-7 Starts -->

                                <div class="form-group"><!-- form-group Starts -->

                                    <label> Country: </label>

                                    <select name="store_country" class="form-control" required>

                                        <option value=""> Select A Country</option>

                                      <?php

                                      $get_countries = "select * from countries";

                                      $run_countries = mysqli_query($con, $get_countries);

                                      while ($row_country = mysqli_fetch_array($run_countries)) {

                                        $country_id = $row_country['country_id'];

                                        $country_name = $row_country['country_name'];

                                        ?>

                                          <option value="<?php echo $country_id; ?>"

                                            <?php

                                            if ($store_country == $country_id) {
                                              echo "selected";
                                            }

                                            ?>

                                          >

                                            <?php echo $country_name; ?>

                                          </option>

                                        <?php

                                      }

                                      ?>

                                    </select>

                                </div><!-- form-group Ends -->

                                <div class="form-group"><!-- form-group Starts -->

                                    <label> Address 1: </label>

                                    <input type="text" name="store_address_1" class="form-control"
                                           value="<?php echo $store_address_1; ?>" required>

                                </div><!-- form-group Ends -->

                                <div class="form-group"><!-- form-group Starts -->

                                    <label> Address 2 (optional): </label>

                                    <input type="text" name="store_address_2" class="form-control"
                                           value="<?php echo $store_address_2; ?>">

                                </div><!-- form-group Ends -->

                                <div class="form-group"><!-- form-group Starts -->

                                    <label> State / County: </label>

                                    <input type="text" name="store_state" class="form-control"
                                           value="<?php echo $store_state; ?>" required>

                                </div><!-- form-group Ends -->

                                <div class="form-group"><!-- form-group Starts -->

                                    <label> City / Town: </label>

                                    <input type="text" name="store_city" class="form-control"
                                           value="<?php echo $store_city; ?>" required>

                                </div><!-- form-group Ends -->

                                <div class="form-group"><!-- form-group Starts -->

                                    <label> Postcode / Zip : </label>

                                    <input type="text" name="store_postcode" class="form-control"
                                           value="<?php echo $store_postcode; ?>" required>

                                </div><!-- form-group Ends -->


                            </div><!-- col-md-7 Ends -->

                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"> Phone No </label>

                            <div class="col-md-7">

                                <input type="number" name="phone_no" class="form-control"
                                       value="<?php echo $phone_no; ?>">

                            </div>

                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"> Store Email </label>

                            <div class="col-md-7">

                                <label class="control-label">

                                    <input type="checkbox" name="store_email"
                                           value="yes" <?php if ($store_email == "yes") {
                                      echo "checked";
                                    } ?>>

                                    Show email address in store

                                </label>

                            </div>

                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"></label>

                            <div class="col-md-7">

                                <input type="submit" name="submit" value="Update Save Settings"
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

    $cover_image = mysqli_real_escape_string($con, $_FILES['cover_image']['name']);

    $profile_image = mysqli_real_escape_string($con, $_FILES['profile_image']['name']);

    $cover_image_tmp = $_FILES['cover_image']['tmp_name'];

    $profile_image_tmp = $_FILES['profile_image']['tmp_name'];

    $store_name = mysqli_real_escape_string($con, $_POST["store_name"]);

    $store_country = mysqli_real_escape_string($con, $_POST["store_country"]);

    $store_address_1 = mysqli_real_escape_string($con, $_POST["store_address_1"]);

    $store_address_2 = mysqli_real_escape_string($con, $_POST["store_address_2"]);

    $store_state = mysqli_real_escape_string($con, $_POST["store_state"]);

    $store_city = mysqli_real_escape_string($con, $_POST["store_city"]);

    $store_postcode = mysqli_real_escape_string($con, $_POST["store_postcode"]);

    $phone_no = mysqli_real_escape_string($con, $_POST["phone_no"]);

    if (isset($_POST["store_email"])) {

      $store_email = mysqli_real_escape_string($con, $_POST["store_email"]);

    } else {

      $store_email = "no";

    }

    $allowed = array('jpeg', 'jpg', 'gif', 'png');

    $cover_image_extension = pathinfo($cover_image, PATHINFO_EXTENSION);

    $profile_image_extension = pathinfo($profile_image, PATHINFO_EXTENSION);

    if (empty($cover_image)) {

      $cover_image = $store_cover_image;

    } else {

      if (!in_array($cover_image_extension, $allowed)) {

        echo "<script> alert('Your Cover/Banner Image File Extension Is Not Supported.'); </script>";

        $cover_image = "";

      } else {

        move_uploaded_file($cover_image_tmp, "../images/$cover_image");

      }

    }

    if (empty($profile_image)) {

      $profile_image = $store_profile_image;

    } else {

      if (!in_array($profile_image_extension, $allowed)) {

        echo "<script> alert('Your Profile Image File Extension Is Not Supported.'); </script>";

        $profile_image = "";

      } else {

        move_uploaded_file($profile_image_tmp, "../images/$profile_image");

      }

    }

    $update_store_settings = "update store_settings set store_cover_image='$cover_image',store_profile_image='$profile_image',store_name='$store_name',store_country='$store_country',store_address_1='$store_address_1',store_address_2='$store_address_2',store_state='$store_state',store_city='$store_city',store_postcode='$store_postcode',phone_no='$phone_no',store_email='$store_email' where vendor_id='$admin_id'";

    $run_store_settings = mysqli_query($con, $update_store_settings);

    if ($run_store_settings) {
      echo "<script>alert(' Your Store Settings Has Been Updated Successfully. ');window.open('index.php?store_settings','_self');</script>";
    }
  }

  ?>

<?php } ?>