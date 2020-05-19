<?php session_start(); include("includes/db.php"); if (!isset($_SESSION['admin_email'])) { echo "<script>window.open('login.php','_self')</script>";
 } else {
   $shipping_weight = mysqli_real_escape_string($con, $_POST['shipping_weight']);
   $shipping_cost = mysqli_real_escape_string($con, $_POST['shipping_cost']);
   $type_id = $_POST["type_id"];

  if (isset($_POST["zone_id"])) {
    $zone_id = $_POST["zone_id"];
    $insert_shipping = "insert into shipping (shipping_type,shipping_zone,shipping_weight,shipping_cost) values ('$type_id','$zone_id','$shipping_weight','$shipping_cost')";
    $run_shipping = mysqli_query($con, $insert_shipping);

  } elseif (isset($_POST["country_id"])) {
    $country_id = $_POST["country_id"];
    $insert_shipping = "insert into shipping (shipping_type,shipping_country,shipping_weight,shipping_cost) values ('$type_id','$country_id','$shipping_weight','$shipping_cost')";
    $run_shipping = mysqli_query($con, $insert_shipping);
  }
 }
 ?>