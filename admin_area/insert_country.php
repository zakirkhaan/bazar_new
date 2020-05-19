<?php if (!isset($_SESSION['admin_email'])) { echo "<script>window.open('login.php','_self')</script>"; } else { ?>
<link rel="stylesheet" href="css/chosen.min.css">
    <div class="row"><!-- 1 row Starts -->
        <div class="col-lg-12"><!-- col-lg-12 Starts -->
            <ol class="breadcrumb"><!-- breadcrumb Starts -->
                <li class="active">
                    <i class="fa fa-dashboard"></i> Dashboard / Insert Country
                </li>
            </ol><!-- breadcrumb Ends -->
        </div><!-- col-lg-12 Ends -->
    </div><!-- 1 row Ends -->

    <div class="row"><!-- 2 row Starts -->
        <div class="col-lg-12"><!-- col-lg-12 Starts -->
            <div class="panel panel-default"><!-- panel panel-default Starts -->
                <div class="panel-heading"><!-- panel-heading Starts -->
                    <h3 class="panel-title"><!-- panel-title Starts -->
                        <i class="fa fa-money fa-fw"> </i> Insert Country
                    </h3><!-- panel-title Ends -->
                </div><!-- panel-heading Ends -->

                <div class="panel-body"><!-- panel-body Starts -->
                    <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                        <div class="form-group"><!-- form-group Starts -->
                            <label class="col-md-3 control-label"> Country Name </label>
                            <div class="col-md-7">
                                <input type="text" name="country_name" class="form-control">
                            </div>
                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->
                            <label class="col-md-3 control-label"> </label>
                            <div class="col-md-7">
                                <input type="submit" name="submit" class="form-control btn btn-primary" value="Insert Country">
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
      $country_name = mysqli_real_escape_string($con, $_POST['country_name']);
      $insert_country = "insert into countries (country_name) values ('$country_name')";
      $run_country = mysqli_query($con, $insert_country);

    if ($run_country) {
        echo "<script>alert('Your New Country Has Been Inserted.')</script>";
        echo "<script>window.open('index.php?view_countries','_self')</script>";
    }
  }
?>
<?php } ?>