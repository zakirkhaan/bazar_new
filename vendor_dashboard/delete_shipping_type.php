<?php
if (!isset($_SESSION['customer_email'])) {
  echo "<script> window.open('../checkout.php','_self'); </script>";
}
$customer_email = $_SESSION['customer_email'];
$select_customer = "select * from customers where customer_email='$customer_email'";
$run_customer = mysqli_query($con, $select_customer);
$row_customer = mysqli_fetch_array($run_customer);
$customer_id = $row_customer['customer_id'];

if (isset($_GET['delete_shipping_type'])) {
  $delete_id = $_GET['delete_shipping_type'];
  $delete_shipping_type = "delete from shipping_type where type_id='$delete_id' and vendor_id='$customer_id'";
  $run_delete_shipping_type = mysqli_query($con, $delete_shipping_type);

  if ($run_delete_shipping_type) {
    $delete_shipping = "delete from shipping where shipping_type='$delete_id'";
    $run_delete_shipping = mysqli_query($con, $delete_shipping);
    echo "<script>alert('Your Shipping Type Has Been Deleted Successfully.');window.open('index.php?shipping_types', '_self');</script>";
  }
}
?>