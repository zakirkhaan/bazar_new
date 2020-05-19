<?php
session_start();
include("includes/db.php");
include("functions/functions.php");
if (!isset($_SESSION['customer_email'])) {
  echo "<script> window.open('../checkout.php','_self'); </script>";
}

$customer_email = $_SESSION['customer_email'];
$select_customer = "select * from customers where customer_email='$customer_email'";
$run_customer = mysqli_query($con, $select_customer);
$row_customer = mysqli_fetch_array($run_customer);
$customer_id = $row_customer['customer_id'];
$i = 0;
$types_ids = $_POST['types_ids'];
$type_local = $_POST['type_local'];
foreach ($types_ids as $type_id) {
  $i++;
  $update_type_order = "update shipping_type set type_order='$i' where type_id='$type_id' and type_local='$type_local' and vendor_id='$customer_id'";
  $run_update_type_order = mysqli_query($con, $update_type_order);
}
?>
