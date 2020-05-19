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

    $random_id = mysqli_real_escape_string($con, $_POST["random_id"]);

    $select_default_variation = "select * from product_variations where product_id='$random_id' and product_type='default_attributes_variation'";

    $run_default_variation = mysqli_query($con, $select_default_variation);

    $count_default_variation = mysqli_num_rows($run_default_variation);

    if ($count_default_variation != 0) {

      $row_default_variation = mysqli_fetch_array($run_default_variation);

      $default_variation_id = $row_default_variation["variation_id"];

    } else {

      $default_variation_id = 0;

    }

    $default_variation_meta_array = array();

    $default_attributes = array();

    if ($count_default_variation != 0) {

      $select_default_variations_meta = "select * from variations_meta where variation_id='$default_variation_id'";

      $run_default_variations_meta = mysqli_query($con, $select_default_variations_meta);

      $i = 0;

      while ($row_default_variations_meta = mysqli_fetch_array($run_default_variations_meta)) {

        $meta_key = $row_default_variations_meta["meta_key"];

        $meta_value = $row_default_variations_meta["meta_value"];

        $default_attributes[$meta_key] = $meta_value;

        $i++;

        if (!empty($meta_value)) {

          $default_variation_meta_array[$i]["meta_key"] = "meta_key='$meta_key'";

          $default_variation_meta_array[$i]["meta_value"] = "meta_value='$meta_value'";

        }

      }

    }

    $product_variations = array();

    $select_product_variations = "select * from product_variations where product_id='$random_id' and not product_type='default_attributes_variation'";

    $run_product_variations = mysqli_query($con, $select_product_variations);

    while ($row_product_variations = mysqli_fetch_array($run_product_variations)) {

      $variation_id = $row_product_variations["variation_id"];

      array_push($product_variations, $variation_id);

    }

    $variation_ids = implode(",", $product_variations);

    $attribute_i = 0;

    $select_product_attributes = "select * from product_attributes where product_id='$random_id'";

    $run_product_attributes = mysqli_query($con, $select_product_attributes);

    while ($row_product_attributes = mysqli_fetch_array($run_product_attributes)) {

      $attribute_i++;

      $meta_key = str_replace(' ', '_', strtolower($row_product_attributes["attribute_name"]));

      $select_default_variation_meta = "select * from variations_meta where variation_id='$default_variation_id' and meta_key='$meta_key'";

      $run_default_variation_meta = mysqli_query($con, $select_default_variation_meta);

      $row_default_variation_meta = mysqli_fetch_array($run_default_variation_meta);

      $default_meta_value = $row_default_variation_meta["meta_value"];

      $attribute_variation_meta_array = $default_variation_meta_array;

      if (!empty($default_meta_value)) {

        unset($attribute_variation_meta_array[$attribute_i]);

        array_unshift($attribute_variation_meta_array, "");

        unset($attribute_variation_meta_array[0]);

      }

      if (!empty($attribute_variation_meta_array)) {

        $variation_meta_variation_ids = array();

        $loop_number = 0;

        foreach ($attribute_variation_meta_array as $array_id => $variation_meta) {

          $loop_number++;

          $variation_meta = implode(" and ", $variation_meta);

          $select_variations_meta = "select DISTINCT variation_id from variations_meta where $variation_meta and variation_id IN ($variation_ids) and not variation_id='$default_variation_id'";

          $run_variations_meta = mysqli_query($con, $select_variations_meta);

          $i = 0;

          while ($row_variations_meta = mysqli_fetch_array($run_variations_meta)) {

            $i++;

            $variation_id = $row_variations_meta["variation_id"];

            if ($loop_number == 1) {

              $variation_meta_variation_ids[$array_id][$i] = $variation_id;

            } else {

              $prev_array_id = $loop_number - 1;

              if (in_array($variation_id, $variation_meta_variation_ids[$prev_array_id])) {

                $variation_meta_variation_ids[$array_id][$i] = $variation_id;

              }

            }

          }

        }

        $array_end = end($variation_meta_variation_ids);

        if ($array_end) {

          $attribute_variation_ids = implode(",", $array_end);

        }

      } else {

        $attribute_variation_ids = $variation_ids;

      }

      ?>

        <div class="col-sm-4"><!-- col-sm-4 Starts -->

            <select class="form-control attribute-select" name="default_attributes[<?php echo $meta_key; ?>]">
                <!-- select manufacturer Starts -->

                <option value=""> Choose an option</option>

              <?php

              if (isset($attribute_variation_ids)) {

                $select_variations_meta = "select DISTINCT meta_value from variations_meta where variation_id IN ($attribute_variation_ids) and meta_key='$meta_key'";

              } else {

                $select_variations_meta = "select DISTINCT meta_value from variations_meta where variation_id IN ($variation_ids) and meta_key='$meta_key'";

              }

              $run_variations_meta = mysqli_query($con, $select_variations_meta);

              while ($row_variations_meta = mysqli_fetch_array($run_variations_meta)) {

                $meta_value = $row_variations_meta["meta_value"];

                if ($default_meta_value == $meta_value) {

                  echo "<option selected>$meta_value</option>";

                } else {

                  echo "<option>$meta_value</option>";

                }

              }

              ?>

            </select>

            <br>

        </div><!-- col-sm-4 Ends -->

    <?php } ?>

      <script>

          $(document).ready(function () {

              $(".attribute-select").on("change", function () {

                  var i = 0;

                  var product_attributes = {};

                  var selected_attributes = {};

                <?php

                $select_product_attributes = "select * from product_attributes where product_id='$random_id'";

                $run_product_attributes = mysqli_query($con, $select_product_attributes);

                while($row_product_attributes = mysqli_fetch_array($run_product_attributes)){

                $attribute_id = $row_product_attributes["attribute_id"];

                $meta_key = str_replace(' ', '_', strtolower($row_product_attributes["attribute_name"]));

                ?>

                  var i = i + 1;

                  product_attributes[i] = {};

                  if ($("select[name='default_attributes[<?php echo $meta_key; ?>]']").val() != "") {

                      var attribute_key = "meta_key='<?php echo $meta_key; ?>'";

                      var attribute_value = "meta_value='" + $("select[name='default_attributes[<?php echo $meta_key; ?>]']").val() + "'";

                      product_attributes[i]["meta_key"] = attribute_key;

                      product_attributes[i]["meta_value"] = attribute_value;

                  }

                  var selected_option = $("select[name='default_attributes[<?php echo $meta_key; ?>]']").val();

                  selected_attributes["<?php echo $meta_key; ?>"] = selected_option;

                <?php } ?>

                  $(".box").addClass("table-loader");

                  var variation_ids = "<?php echo $variation_ids; ?>";

                  $.ajax({

                      method: "POST",

                      url: "variable_product/change_default_form_values.php",

                      data: {
                          product_id: <?php echo $random_id; ?>,
                          default_variation_id: <?php echo $default_variation_id; ?>,
                          variation_ids: variation_ids,
                          selected_attributes: selected_attributes,
                          variation_meta_array: product_attributes
                      },

                      success: function (data) {

                          $("#default_form_values").html(data);

                          $(".box").removeClass("table-loader");

                      }

                  });

              });

          });

      </script>

  <?php }
} ?>