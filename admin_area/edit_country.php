<?php
if (!isset($_SESSION['admin_email'])) {echo "<script>window.open('login.php','_self')</script>";} else {?>

<?php
  $country_id = $_GET["edit_country"];
  $get_country = "select * from countries where country_id='$country_id'";
  $run_country = mysqli_query($con, $get_country);
  $row_country = mysqli_fetch_array($run_country);
  $country_name = $row_country['country_name'];
?>

<link rel="stylesheet" href="css/chosen.min.css">
    <div class="row"><!-- 1 row Starts -->
        <div class="col-lg-12"><!-- col-lg-12 Starts -->
            <ol class="breadcrumb"><!-- breadcrumb Starts -->
                <li class="active">
                    <i class="fa fa-dashboard"></i> Dashboard / Edit Country
                </li>
            </ol><!-- breadcrumb Ends -->
        </div><!-- col-lg-12 Ends -->
    </div><!-- 1 row Ends -->

    <div class="row"><!-- 2 row Starts -->
        <div class="col-lg-12"><!-- col-lg-12 Starts -->
            <div class="panel panel-default"><!-- panel panel-default Starts -->
                <div class="panel-heading"><!-- panel-heading Starts -->
                    <h3 class="panel-title"><!-- panel-title Starts -->
                        <i class="fa fa-money fa-fw"> </i> Edit Country
                    </h3><!-- panel-title Ends -->
                </div><!-- panel-heading Ends -->

                <div class="panel-body"><!-- panel-body Starts -->
                    <form class="form-horizontal" action="" method="post" enctype="multipart/form-data"><!-- form-horizontal Starts -->
                        <div class="form-group"><!-- form-group Starts -->
                            <label class="col-md-3 control-label"> Country Name </label>
                            <div class="col-md-7">
                                <input type="text" name="country_name" class="form-control" value="<?php echo $country_name; ?>">
                            </div>
                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->
                            <label class="col-md-3 control-label"> </label>
                            <div class="col-md-7">
                                <input type="submit" name="submit" class="form-control btn btn-primary" value="Update Country">
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
    var config = {'.chosen-select': {}};
        for (var selector in config) {
            $(selector).chosen(config[selector]);
        }
</script>

<?php
  if (isset($_POST['submit'])) {
      $country_name = mysqli_real_escape_string($con, $_POST['country_name']);
      $update_country = "update countries set country_name='$country_name' where country_id='$country_id'";
      $run_update_country = mysqli_query($con, $update_country);

    if ($run_update_country) {
        echo "<script>alert('Your Country Has Been Updated Successfully.')</script>";
        echo "<script>window.open('index.php?view_countries','_self')</script>";
    }
  }

?>

<?php } ?>