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

    $data = filter_input_array(INPUT_POST);

    $random_id = mysqli_real_escape_string($con, $_POST["random_id"]);

    if (isset($data["default_attributes"])) {

      $default_attributes = $data["default_attributes"];

    }

    if (isset($data["variations"])) {

      $variations = $data["variations"];

      $variations_images = $_FILES["variations"]["name"];

    }

    if (isset($data["default_attributes"])) {

      $select_product_variation = "select * from product_variations where product_id='$random_id' and product_type='default_attributes_variation'";

      $run_product_variation = mysqli_query($con, $select_product_variation);

      $count_product_variation = mysqli_num_rows($run_product_variation);

      if ($count_product_variation == 0) {

        $insert_product_variation = "insert into product_variations (product_id,product_type) values ('$random_id','default_attributes_variation')";

        $run_product_variation = mysqli_query($con, $insert_product_variation);

        $insert_variation_id = mysqli_insert_id($con);

        foreach ($default_attributes as $attribute_name => $attribute_value) {

          $insert_variation_meta = "insert into variations_meta (variation_id,meta_key,meta_value) values ('$insert_variation_id','$attribute_name','$attribute_value')";

          $run_variation_meta = mysqli_query($con, $insert_variation_meta);

        }

      } else {

        $row_product_variation = mysqli_fetch_array($run_product_variation);

        $default_variation_id = $row_product_variation["variation_id"];

        foreach ($default_attributes as $attribute_name => $attribute_value) {

          $select_attribute_variation_meta = "select * from variations_meta where variation_id='$default_variation_id' and meta_key='$attribute_name'";

          $run_attribute_variation_meta = mysqli_query($con, $select_attribute_variation_meta);

          $count_attribute_variation_meta = mysqli_num_rows($run_attribute_variation_meta);

          if ($count_attribute_variation_meta == 0) {

            $insert_variation_meta = "insert into variations_meta (variation_id,meta_key,meta_value) values ('$default_variation_id','$attribute_name','$attribute_value')";

            $run_variation_meta = mysqli_query($con, $insert_variation_meta);

          } else {

            $update_variation_meta = "update variations_meta set meta_value='$attribute_value' where variation_id='$default_variation_id' and meta_key='$attribute_name'";

            $run_update_variation_meta = mysqli_query($con, $update_variation_meta);

          }

        }

      }

    }

    if (isset($data["variations"])) {

      foreach ($variations as $variation_id => $variation_data) {

        $variation_attributes = $variation_data["attributes"];

        $product_price = mysqli_real_escape_string($con, $variation_data["product_price"]);

        $product_psp_price = mysqli_real_escape_string($con, $variation_data["product_psp_price"]);

        $product_weight = mysqli_real_escape_string($con, $variation_data["product_weight"]);

        $product_type = mysqli_real_escape_string($con, $variation_data["product_type"]);

        $stock_status = mysqli_real_escape_string($con, $variation_data['stock_status']);

        $enable_stock = mysqli_real_escape_string($con, $variation_data['enable_stock']);

        $stock_quantity = mysqli_real_escape_string($con, $variation_data['stock_quantity']);

        $allow_backorders = mysqli_real_escape_string($con, $variation_data['allow_backorders']);

        $update_variation = "update product_variations set product_price='$product_price',product_psp_price='$product_psp_price',product_weight='$product_weight',product_type='$product_type' where product_id='$random_id' and variation_id='$variation_id'";

        $run_update_variation = mysqli_query($con, $update_variation);

        $select_variations_meta = "select * from variations_meta where variation_id='$variation_id'";

        $run_variations_meta = mysqli_query($con, $select_variations_meta);

        $count_variations_meta = mysqli_num_rows($run_variations_meta);

        if ($count_variations_meta == 0) {

          foreach ($variation_attributes as $attribute_name => $attribute_value) {

            $insert_variation_meta = "insert into variations_meta (variation_id,meta_key,meta_value) values ('$variation_id','$attribute_name','$attribute_value')";

            $run_variation_meta = mysqli_query($con, $insert_variation_meta);

          }

        } else {

          foreach ($variation_attributes as $attribute_name => $attribute_value) {

            $select_attribute_variation_meta = "select * from variations_meta where variation_id='$variation_id' and meta_key='$attribute_name'";

            $run_attribute_variation_meta = mysqli_query($con, $select_attribute_variation_meta);

            $count_attribute_variation_meta = mysqli_num_rows($run_attribute_variation_meta);

            if ($count_attribute_variation_meta == 0) {

              $insert_variation_meta = "insert into variations_meta (variation_id,meta_key,meta_value) values ('$variation_id','$attribute_name','$attribute_value')";

              $run_variation_meta = mysqli_query($con, $insert_variation_meta);

            } else {

              $update_variation_meta = "update variations_meta set meta_value='$attribute_value' where variation_id='$variation_id' and meta_key='$attribute_name'";

              $run_update_variation_meta = mysqli_query($con, $update_variation_meta);

            }

          }

        }

        $select_product_stock = "select * from products_stock where product_id='$random_id' and variation_id='$variation_id'";

        $run_product_stock = mysqli_query($con, $select_product_stock);

        $count_product_stock = mysqli_num_rows($run_product_stock);

        if ($enable_stock == "yes" and $stock_quantity > 0) {

          $stock_status = "instock";

        } elseif ($enable_stock == "yes" and $allow_backorders == "no" and $stock_quantity < 1) {

          $stock_status = "outofstock";

        } elseif ($enable_stock == "yes" and ($allow_backorders == "yes" or $allow_backorders == "notify") and $stock_quantity < 1) {

          $stock_status = "onbackorder";

        }

        if ($count_product_stock == 0) {

          $insert_product_stock = "insert into products_stock (product_id,variation_id,enable_stock,stock_status,stock_quantity,allow_backorders) values ('$random_id','$variation_id','$enable_stock','$stock_status','$stock_quantity','$allow_backorders')";

          $run_insert_product_stock = mysqli_query($con, $insert_product_stock);

        } else {

          $update_product_stock = "update products_stock set enable_stock='$enable_stock',stock_status='$stock_status',stock_quantity='$stock_quantity',allow_backorders='$allow_backorders' where product_id='$random_id' and variation_id='$variation_id'";

          $run_update_product_stock = mysqli_query($con, $update_product_stock);

        }

      }

      foreach ($variations_images as $variation_id => $product_image) {

        $product_img1 = implode($product_image);

        if (!empty($product_img1)) {

          $tmp_name = implode($_FILES["variations"]["tmp_name"]["$variation_id"]);

          move_uploaded_file($tmp_name, "../admin_area/product_images/$product_img1");

          $update_variation_image = "update product_variations set product_img1='$product_img1' where product_id='$random_id' and variation_id='$variation_id'";

          $run_update_variation_image = mysqli_query($con, $update_variation_image);

        }

      }

    }

    ?>

  <?php }
} ?>