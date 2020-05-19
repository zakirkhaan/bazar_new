<?php if (!isset($_SESSION['admin_email'])) { echo "<script>window.open('login.php','_self')</script>"; } else {?>

<?php
  if (isset($_GET['restore_product'])) {
    $product_id = $_GET['restore_product'];
    $update_product_status = "update products set product_vendor_status='active' where product_id='$product_id'";
    $run_product_status = mysqli_query($con, $update_product_status);

    if ($run_product_status) {
      if (isset($_GET['products'])) {
        echo "<script>alert(' One Product Has Been Restored Successfully And Live To Website. ');window.open('index.php?view_products','_self');</script>";

      } elseif (isset($_GET['bundles'])) {
        echo "<script>alert(' One Bundle Has Been Restored Successfully And Live To Website. ');window.open('index.php?view_bundles','_self');</script>";
      }
    }
  }
?>
<?php } ?>