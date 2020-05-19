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

    $random_id = mysqli_real_escape_string($con, $_POST['random_id']);

    $variation_attributes = array();

    $select_product_attributes = "select * from product_attributes where product_id='$random_id'";

    $run_product_attributes = mysqli_query($con, $select_product_attributes);

    while ($row_product_attributes = mysqli_fetch_array($run_product_attributes)) {

      $attribute_name = str_replace(' ', '_', strtolower($row_product_attributes["attribute_name"]));

      $attribute_values = explode("|", $row_product_attributes["attribute_values"]);

      $variation_attributes[$attribute_name] = $attribute_values;

    }

    function possible_combinations_function($arrays)
    {

//returned array...

      $possible_combinations = array();

      if (sizeof($arrays) > 0) {

        $size = 1;

      } else {

        $size = 0;

      }

      foreach ($arrays as $array) {

        $size = $size * sizeof($array);

      }

      for ($i = 0; $i < $size; $i++) {

        $possible_combinations[$i] = array();

        foreach ($arrays as $key => $value) {

          $current = current($arrays[$key]);

          $possible_combinations[$i][$key] = $current;

        }

        foreach ($arrays as $key => $value) {

//if next returns true, then break

          if (next($arrays[$key])) {

            break;

          } else {

//if next returns false, then reset and go on with previuos array...
            reset($arrays[$key]);

          }

        }

      }

      return $possible_combinations;

    }

    $possible_combinations = possible_combinations_function($variation_attributes);

    foreach ($possible_combinations as $combination_array) {

      $insert_product_variation = "insert into product_variations (product_id) values ('$random_id')";

      $run_product_variation = mysqli_query($con, $insert_product_variation);

      $insert_variation_id = mysqli_insert_id($con);

      foreach ($combination_array as $attribute_name => $attribute_value) {

        $insert_variation_meta = "insert into variations_meta (variation_id,meta_key,meta_value) values ('$insert_variation_id','$attribute_name','$attribute_value')";

        $run_variation_meta = mysqli_query($con, $insert_variation_meta);

      }

    }

    ?>

  <?php }
} ?>