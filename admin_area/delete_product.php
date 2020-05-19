<?php
if (!isset($_SESSION['admin_email'])) {
  echo "<script>window.open('login.php','_self')</script>";
} else {
?>

<?php
  if (isset($_GET['delete_product'])) {
    $delete_id = $_GET['delete_product'];
    $select_product = "select * from products where product_id='$delete_id'";
    $run_product = mysqli_query($con, $select_product);
    $row_product = mysqli_fetch_array($run_product);
    $product_type = $row_product['product_type'];
    $delete_product = "delete from products where product_id='$delete_id'";
    $run_delete = mysqli_query($con, $delete_product);

    if ($run_delete) {
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
      $delete_customers_history = "delete from customers_history where product_id='$delete_id'";
      $run_customers_history = mysqli_query($con, $delete_customers_history);
      echo "<script>alert(' One Product Has Been Deleted Permanently Successfully. ');window.open('index.php?view_products','_self');</script>";
    }
  }
?>
<?php } ?>