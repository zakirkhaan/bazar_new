<?php
if (!isset($_SESSION['admin_email'])) {
  echo "<script>window.open('login.php','_self')</script>";
} else {
  ?>

  <?php
  if (isset($_GET['delete_bundle'])) {
    $delete_id = $_GET['delete_bundle'];
    $select_product = "select * from products where product_id='$delete_id'";
    $run_product = mysqli_query($con, $select_product);
    $row_product = mysqli_fetch_array($run_product);
    $product_type = $row_product['product_type'];
    $delete_product = "delete from products where product_id='$delete_id'";
    $run_delete = mysqli_query($con, $delete_product);
    $delete_rel = "delete from bundle_product_relation where bundle_id='$delete_id'";
    $run_rel = mysqli_query($con, $delete_rel);

    if ($run_rel) {
      if ($product_type == "variable_product") {
        $delete_product_attribute = "delete from product_attributes where product_id='$delete_id'";
        $run_product_attribute = mysqli_query($con, $delete_product_attribute);
        $select_product_variations = "select * from product_variations where product_id='$delete_id'";
        $run_product_variations = mysqli_query($con, $select_product_variations);
        while ($row_product_variations = mysqli_fetch_array($run_product_variations)) {
          $variation_id = $row_product_variations["variation_id"];
          $delete_variations_meta = "delete from variations_meta where variation_id='$variation_id'";
          $run_variations_meta = mysqli_query($con, $delete_variations_meta);
        }
        $delete_product_variations = "delete from product_variations where product_id='$delete_id'";
        $run_product_variations = mysqli_query($con, $delete_product_variations);
      }
      $delete_product_stock = "delete from products_stock where product_id='$delete_id'";
      $run_delete_product_stock = mysqli_query($con, $delete_product_stock);
      echo "<script>alert(' One Bundle Has Been Deleted Permanently Successfully. ');window.open('index.php?view_bundles','_self');</script>";
    }
  }
  ?>
<?php } ?>