<?php

if (!isset($_SESSION['customer_email'])) {
  echo "<script> window.open('../checkout.php','_self'); </script>";
}
$customer_email = $_SESSION['customer_email'];
$select_customer = "select * from customers where customer_email='$customer_email'";
$run_customer = mysqli_query($con, $select_customer);
$row_customer = mysqli_fetch_array($run_customer);
$customer_id = $row_customer['customer_id'];
$select_store_settings = "select * from store_settings where vendor_id='$customer_id'";
$run_store_settings = mysqli_query($con, $select_store_settings);
$row_store_settings = mysqli_fetch_array($run_store_settings);
$seo_title = $row_store_settings["seo_title"];
$meta_author = $row_store_settings["meta_author"];
$meta_description = $row_store_settings["meta_description"];
$meta_keywords = $row_store_settings["meta_keywords"];
?>

<div class="row"><!-- 2 row Starts -->
    <div class="col-lg-12"><!-- col-lg-12 Starts -->
        <div class="panel panel-default"><!-- panel panel-default Starts -->
            <div class="panel-heading"><!-- panel-heading Starts -->
                <h3 class="panel-title"><!-- panel-title Starts -->
                    <i class="fa fa-money fa-fw"></i> Store Seo Settings
                </h3><!-- panel-title Ends -->
            </div><!-- panel-heading Ends -->
            <div class="panel-body"><!-- panel-body Starts -->
                <form class="form-horizontal" method="post"><!-- form-horizontal Starts -->
                    <div class="form-group"><!-- form-group Starts -->
                        <label class="col-md-3 control-label"> SEO Title : </label>
                        <div class="col-md-6">
                            <input type="text" name="seo_title" class="form-control" value="<?php echo $seo_title; ?>"
                                   required>
                        </div>
                    </div><!-- form-group Ends -->
                    <div class="form-group"><!-- form-group Starts -->
                        <label class="col-md-3 control-label"> Meta Author : </label>
                        <div class="col-md-6">
                            <input type="text" name="meta_author" class="form-control"
                                   value="<?php echo $meta_author; ?>" required>
                        </div>
                    </div><!-- form-group Ends -->
                    <div class="form-group"><!-- form-group Starts -->
                        <label class="col-md-3 control-label"> Meta Description : </label>
                        <div class="col-md-6">
                            <input type="text" name="meta_description" class="form-control"
                                   value="<?php echo $meta_description; ?>">
                        </div>
                    </div><!-- form-group Ends -->
                    <div class="form-group"><!-- form-group Starts -->
                        <label class="col-md-3 control-label"> Meta Keywords : </label>

                        <div class="col-md-6">
                            <input type="text" name="meta_keywords" class="form-control"
                                   value="<?php echo $meta_keywords; ?>">
                        </div>
                    </div><!-- form-group Ends -->
                    <div class="form-group"><!-- form-group Starts -->
                        <label class="col-md-3 control-label"></label>
                        <div class="col-md-6">
                            <input type="submit" name="submit" value="Save Settings"
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
  $seo_title = mysqli_real_escape_string($con, $_POST['seo_title']);
  $meta_author = mysqli_real_escape_string($con, $_POST['meta_author']);
  $meta_description = mysqli_real_escape_string($con, $_POST['meta_description']);
  $meta_keywords = mysqli_real_escape_string($con, $_POST['meta_keywords']);
  $update_store_settings = "update store_settings set seo_title='$seo_title',meta_author='$meta_author',meta_description='$meta_description',meta_keywords='$meta_keywords' where vendor_id='$customer_id'";
  $run_store_settings = mysqli_query($con, $update_store_settings);

  if ($run_store_settings) {
      echo "<script>alert(' Your Store Seo Settings Has Been Saved Successfully. ');window.open('index.php?seo_settings','_self');</script>";
  }
}

?>






