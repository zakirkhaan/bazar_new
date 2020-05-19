<?php

session_start();

include("../includes/db.php");

if (!isset($_SESSION['admin_email'])) {

  echo "<script>window.open('../login.php','_self')</script>";

}

if (!isset($_SERVER['HTTP_REFERER'])) {

  echo "<script> window.open('../index.php?dashboard','_self'); </script>";

}

if (isset($_SERVER['HTTP_REFERER'])) {

  $random_id = mysqli_real_escape_string($con, $_POST['random_id']);

  $http_referer = substr($_SERVER['HTTP_REFERER'], strpos($_SERVER['HTTP_REFERER'], "?") + 1);

  if ($http_referer == "insert_product" or $http_referer == "edit_product=$random_id" or $http_referer == "insert_bundle" or $http_referer == "edit_bundle=$random_id") {

    ?>


    <?php

    $random_id = mysqli_real_escape_string($con, $_POST['random_id']);

    $attribute_name = mysqli_real_escape_string($con, $_POST['attribute_name']);

    $attribute_values = mysqli_real_escape_string($con, $_POST['attribute_values']);

    $insert_product_attribute = "insert into product_attributes (product_id,attribute_name,attribute_values) values ('$random_id','$attribute_name','$attribute_values')";

    $run_product_attribute = mysqli_query($con, $insert_product_attribute);

    ?>


  <?php }
} ?>