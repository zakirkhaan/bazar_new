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
$zones_ids = $_POST['zones_ids'];

foreach ($zones_ids as $zone_id) {
  $i++;
  $update_zone_order = "update zones set zone_order='$i' where zone_id='$zone_id' and vendor_id='$customer_id'";
  $run_update_zone_order = mysqli_query($con, $update_zone_order);
}
?>