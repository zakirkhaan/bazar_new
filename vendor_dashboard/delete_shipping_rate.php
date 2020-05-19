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
if (isset($_POST['shipping_id'])) {
  $shipping_id = $_POST['shipping_id'];
  $type_id = $_POST['type_id'];
  $delete_shipping_rate = "delete from shipping where shipping_id='$shipping_id' and shipping_type='$type_id'";
  $run_delete_shipping_rate = mysqli_query($con, $delete_shipping_rate);
}
?>