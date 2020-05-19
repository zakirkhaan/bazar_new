<?php
if (!isset($_SESSION['admin_email'])) {

  echo "<script>window.open('login.php','_self')</script>";

} else {

  $edit_zone_id = $_GET["edit_shipping_zone"];

  $get_zones = "select * from zones where zone_id='$edit_zone_id'";

  $run_zones = mysqli_query($con, $get_zones);

  $row_zones = mysqli_fetch_array($run_zones);

  $zone_name = $row_zones['zone_name'];

  ?>

    <link rel="stylesheet" href="css/chosen.min.css">

    <div class="row"><!-- 1 row Starts -->

        <div class="col-lg-12"><!-- col-lg-12 Starts -->

            <ol class="breadcrumb"><!-- breadcrumb Starts -->

                <li class="active">

                    <i class="fa fa-dashboard"></i> Dashboard / Edit Shipping Zone

                </li>

            </ol><!-- breadcrumb Ends -->

        </div><!-- col-lg-12 Ends -->

    </div><!-- 1 row Ends -->

    <div class="row"><!-- 2 row Starts -->

        <div class="col-lg-12"><!-- col-lg-12 Starts -->

            <div class="panel panel-default"><!-- panel panel-default Starts -->

                <div class="panel-heading"><!-- panel-heading Starts -->

                    <h3 class="panel-title"><!-- panel-title Starts -->

                        <i class="fa fa-money fa-fw"> </i> Edit Shipping Zone

                    </h3><!-- panel-title Ends -->

                </div><!-- panel-heading Ends -->

                <div class="panel-body"><!-- panel-body Starts -->

                    <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                        <!-- form-horizontal Starts -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"> Zone Name </label>

                            <div class="col-md-7">

                                <input type="text" name="zone_name" class="form-control"
                                       value="<?php echo $zone_name; ?>">

                            </div>

                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"> Zone Regions </label>

                            <div class="col-md-7">

                                <select data-placeholder="Select Zone Regions" name="zone_countries[]"
                                        class="form-control chosen-select" multiple>

                                  <?php

                                  $get_zones_locations = "select * from zones_locations where zone_id='$edit_zone_id' and location_type='country'";

                                  $run_zones_locations = mysqli_query($con, $get_zones_locations);

                                  while ($row_zones_locations = mysqli_fetch_array($run_zones_locations)) {

                                    $location_code = $row_zones_locations["location_code"];

                                    $location_type = $row_zones_locations["location_type"];

                                    $get_country = "select * from countries where country_id='$location_code'";

                                    $run_country = mysqli_query($con, $get_country);

                                    $row_country = mysqli_fetch_array($run_country);

                                    $country_name = $row_country['country_name'];

                                    echo "<option value='$location_code' selected>$country_name</option>";

                                  }

                                  $get_countries = "select * from countries";

                                  $run_countries = mysqli_query($con, $get_countries);

                                  while ($row_countries = mysqli_fetch_array($run_countries)) {

                                    $country_id = $row_countries['country_id'];

                                    $country_name = $row_countries['country_name'];

                                    echo "<option value='$country_id'>$country_name</option>";

                                  }

                                  ?>

                                </select>

                            </div>

                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"> Limit to specific ZIP/postcodes </label>

                            <div class="col-md-7">
                                <textarea name="zone_post_codes" class="form-control" placeholder="List 1 Postcode Per Line" rows="5">
<?php

$result = "";

$get_zones_locations = "select * from zones_locations where zone_id='$edit_zone_id' and location_type='postcode'";

$run_zones_locations = mysqli_query($con, $get_zones_locations);

while ($row_zones_locations = mysqli_fetch_array($run_zones_locations)) {

  $location_code = $row_zones_locations["location_code"];

  $result .= "$location_code\n";

}

echo rtrim($result, "\n");

?>
</textarea>

                                <p class="help-block">Postcodes containing wildcards (e.g. CB23*) and fully numeric
                                    ranges (e.g. 90210...99000) are also supported.</p>

                            </div>

                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"> </label>

                            <div class="col-md-7">

                                <input type="submit" name="update" class="form-control btn btn-primary"
                                       value="Update Shipping Zone">

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

        $(".chosen-select").chosen();

    </script>

  <?php

  if (isset($_POST['update'])) {

    $zone_name = mysqli_real_escape_string($con, $_POST['zone_name']);

    $zone_countries = $_POST['zone_countries'];

    $insert_zone = "update zones set zone_name='$zone_name' where zone_id='$edit_zone_id'";

    $run_zone = mysqli_query($con, $insert_zone);

    $delete_zones_locations = "delete from zones_locations where zone_id='$edit_zone_id'";

    $run_zones_locations = mysqli_query($con, $delete_zones_locations);

    foreach ($zone_countries as $country) {

      $country_code = mysqli_real_escape_string($con, $country);

      $insert_zone_location = "insert into zones_locations (zone_id,location_code,location_type) values ('$edit_zone_id','$country','country')";

      $run_zone_location = mysqli_query($con, $insert_zone_location);

    }

    if (!empty($_POST['zone_post_codes'])) {

      if (strpos($_POST['zone_post_codes'], "\n")) {

        $post_codes = explode("\n", $_POST['zone_post_codes']);

      } else {

        $post_codes = array($_POST['zone_post_codes']);

      }

      foreach ($post_codes as $post_code) {

        $location_code = mysqli_real_escape_string($con, trim($post_code));

        $insert_zone_location = "insert into zones_locations (zone_id,location_code,location_type) values ('$edit_zone_id','$location_code','postcode')";

        $run_zone_location = mysqli_query($con, $insert_zone_location);

      }

    }

    if ($run_zone) {

      echo "<script>alert('Your One Shipping Zone Has Been Updated.')</script>";

      echo "<script>window.open('index.php?view_shipping_zones','_self')</script>";

    }

  }

  ?>

<?php } ?>