<?php

session_start();

include("../includes/db.php");

if (!isset($_SESSION['customer_email'])) {

  echo "<script> window.open('../../checkout.php','_self'); </script>";

}

if (!isset($_SERVER['HTTP_REFERER'])) {

  echo "<script> window.open('../../checkout.php','_self'); </script>";

}

if (isset($_SERVER['HTTP_REFERER'])) {

  $random_id = mysqli_real_escape_string($con, $_POST['random_id']);

  $http_referer = substr($_SERVER['HTTP_REFERER'], strpos($_SERVER['HTTP_REFERER'], "?") + 1);

  if ($http_referer == "insert_product" or $http_referer == "edit_product=$random_id" or $http_referer == "insert_bundle" or $http_referer == "edit_bundle=$random_id") {

    ?>

    <?php

    if (isset($_POST["random_id"])) {

      $random_id = mysqli_real_escape_string($con, $_POST['random_id']);

      $attribute_id = mysqli_real_escape_string($con, $_POST['attribute_id']);

      $delete_product_attribute = "delete from product_attributes where product_id='$random_id' and attribute_id='$attribute_id'";

      $run_product_attribute = mysqli_query($con, $delete_product_attribute);

    }

    ?>

  <?php }
} ?>