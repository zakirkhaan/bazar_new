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

$type_id = mysqli_real_escape_string($con, $_POST['type_id']);

$shipping_weight = mysqli_real_escape_string($con, $_POST['shipping_weight']);

$shipping_cost = mysqli_real_escape_string($con, $_POST['shipping_cost']);

if (isset($_POST['zone_id'])) {

  $zone_id = $_POST['zone_id'];

  $insert_shipping_rate = "insert into shipping (shipping_type,shipping_zone,shipping_weight,shipping_cost) values ('$type_id','$zone_id','$shipping_weight','$shipping_cost')";

  $run_insert_shipping_rate = mysqli_query($con, $insert_shipping_rate);

} elseif (isset($_POST['country_id'])) {

  $country_id = $_POST['country_id'];

  $insert_shipping_rate = "insert into shipping (shipping_type,shipping_country,shipping_weight,shipping_cost) values ('$type_id','$country_id','$shipping_weight','$shipping_cost')";

  $run_insert_shipping_rate = mysqli_query($con, $insert_shipping_rate);

}

?>