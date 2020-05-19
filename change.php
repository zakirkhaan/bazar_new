<?php
session_start();
include("includes/db.php");
include("functions/functions.php");
?>

<?php

$ip_add = getRealUserIp();

if (isset($_POST['cart_id']) and isset($_POST['product_id'])) {

  if (isset($_POST['shipping_type']) and isset($_POST['shipping_cost'])) {

    $shipping_type = $_POST["shipping_type"];

    $shipping_cost = $_POST["shipping_cost"];

    $_SESSION["shipping_type"] = $shipping_type;

    $_SESSION["shipping_cost"] = $shipping_cost;

  }

  $cart_id = $_POST['cart_id'];

  $product_id = $_POST['product_id'];

  $qty = $_POST['quantity'];

  $change_qty = "update cart set qty='$qty' where cart_id='$cart_id' AND p_id='$product_id' AND ip_add='$ip_add'";

  $run_qty = mysqli_query($con, $change_qty);

  if ($run_qty) {

    echo total_price();

  }

}

?>