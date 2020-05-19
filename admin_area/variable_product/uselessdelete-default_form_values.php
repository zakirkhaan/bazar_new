<?php

session_start();

include("../includes/db.php");

if (!isset($_SESSION['admin_email'])) {

  echo "<script>window.open('../login.php','_self')</script>";

} else {

  ?>

  <?php

  $product_id = mysqli_real_escape_string($con, $_POST["product_id"]);

  $default_variation_id = mysqli_real_escape_string($con, $_POST["default_variation_id"]);

  $variation_ids = mysqli_real_escape_string($con, $_POST["variation_ids"]);

  if (isset($_POST["variation_meta_array"])) {

    $variation_meta_array = $_POST["variation_meta_array"];

  }

  $selected_attributes = $_POST["selected_attributes"];

  $attribute_i = 0;

  $select_product_attributes = "select * from product_attributes where product_id='$product_id'";

  $run_product_attributes = mysqli_query($con, $select_product_attributes);

  while ($row_product_attributes = mysqli_fetch_array($run_product_attributes)) {

    $attribute_i++;

    $attribute_name = $row_product_attributes["attribute_name"];

    $meta_key = str_replace(' ', '_', strtolower($row_product_attributes["attribute_name"]));

    $default_meta_value = $selected_attributes[$meta_key];

    if (isset($_POST["variation_meta_array"])) {

      $attribute_variation_meta_array = $variation_meta_array;

      if (!empty($default_meta_value)) {

        unset($attribute_variation_meta_array[$attribute_i]);

        array_unshift($attribute_variation_meta_array, "");

        unset($attribute_variation_meta_array[0]);

      }

      if (!empty($attribute_variation_meta_array)) {

        $new_attribute_variation_meta_array = array();

        $variation_meta_variation_ids = array();

        $new_array_i = 0;

        $loop_number = 0;

        foreach ($attribute_variation_meta_array as $variation_meta) {

          $new_array_i++;

          $loop_number++;

          $new_attribute_variation_meta_array[$new_array_i] = $variation_meta;

          $variation_meta = implode(" and ", $new_attribute_variation_meta_array[$new_array_i]);

          $select_variations_meta = "select DISTINCT variation_id from variations_meta where $variation_meta and variation_id IN ($variation_ids) and not variation_id='$default_variation_id'";

          $run_variations_meta = mysqli_query($con, $select_variations_meta);

          $i = 0;

          while ($row_variations_meta = mysqli_fetch_array($run_variations_meta)) {

            $i++;

            $variation_id = $row_variations_meta["variation_id"];

            if ($loop_number == 1) {

              $variation_meta_variation_ids[$new_array_i][$i] = $variation_id;

            } else {

              $prev_array_id = $loop_number - 1;

              if (in_array($variation_id, $variation_meta_variation_ids[$prev_array_id])) {

                $variation_meta_variation_ids[$new_array_i][$i] = $variation_id;

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

    }

    ?>

      <div class="col-sm-4"><!-- col-sm-4 Starts -->

          <select class="form-control attribute-select" name="default_attributes[<?php echo $meta_key; ?>]">
              <!-- select manufacturer Starts -->

              <option value=""> Choose an option</option>

            <?php

            if (isset($attribute_variation_ids)) {

              echo $select_variations_meta = "select DISTINCT meta_value from variations_meta where variation_id IN ($attribute_variation_ids) and meta_key='$meta_key'";

            } else {

              echo $select_variations_meta = "select DISTINCT meta_value from variations_meta where variation_id IN ($variation_ids) and meta_key='$meta_key'";

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

              $select_product_attributes = "select * from product_attributes where product_id='$product_id'";

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

                    url: "default_form_values.php",

                    data: {
                        product_id: <?php echo $product_id; ?>,
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

<?php } ?>