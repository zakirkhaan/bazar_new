<?php

if ($pro_type == "variable_product") {

  $select_product_variations = "select * from product_variations where product_id='$pro_id' and not product_type='default_attributes_variation'";

  $run_product_variations = mysqli_query($con, $select_product_variations);

  $count_product_variations = mysqli_num_rows($run_product_variations);

  if ($count_product_variations != 0) {

    $select_default_variation = "select * from product_variations where product_id='$pro_id' and product_type='default_attributes_variation'";

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

      $select_default_variations_meta = "select * from variations_meta where variation_id='$default_variation_id' order by 1 desc";

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

    $select_product_variations = "select * from product_variations where product_id='$pro_id' and not product_type='default_attributes_variation'";

    $run_product_variations = mysqli_query($con, $select_product_variations);

    while ($row_product_variations = mysqli_fetch_array($run_product_variations)) {

      $variation_id = $row_product_variations["variation_id"];

      array_push($product_variations, $variation_id);

    }

    $variation_ids = implode(",", $product_variations);

    $attribute_i = 0;

    ?>

      <div id="variable-product-div"><!-- variable-product-div Starts -->

        <?php

        $select_product_attributes = "select * from product_attributes where product_id='$pro_id'";

        $run_product_attributes = mysqli_query($con, $select_product_attributes);

        while ($row_product_attributes = mysqli_fetch_array($run_product_attributes)) {

          $attribute_i++;

          $attribute_name = $row_product_attributes["attribute_name"];

          $meta_key = str_replace(' ', '_', strtolower($attribute_name));

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

            <div class="form-group"><!-- form-group Starts -->

                <label class="col-lg-4 col-md-3 control-label"> <?php echo $attribute_name; ?> </label>

                <div class="col-lg-6 col-md-9"><!-- col-lg-6 col-md-9 Starts -->

                    <select name="<?php echo $meta_key; ?>" class="form-control attribute-select" required>

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

                </div><!-- col-lg-6 col-md-9 Ends -->

            </div><!-- form-group Ends -->

          <?php

        }

        $variation_meta_variation_ids = array();

        $loop_number = 0;

        foreach ($default_variation_meta_array as $array_id => $variation_meta) {

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

          $current_variation_id = array_values($array_end)["0"];

        }

        ?>

        <?php if ($array_end) { ?>

            <input type="hidden" name="variation_id" value="<?php echo $current_variation_id; ?>">

        <?php } ?>

          <script>

              $(document).ready(function () {

                  $(".attribute-select").on("change", function () {

                      var i = 0;

                      var product_attributes = {};

                      var selected_attributes = {};

                    <?php

                    $select_product_attributes = "select * from product_attributes where product_id='$pro_id'";

                    $run_product_attributes = mysqli_query($con, $select_product_attributes);

                    while($row_product_attributes = mysqli_fetch_array($run_product_attributes)){

                    $attribute_id = $row_product_attributes["attribute_id"];

                    $meta_key = str_replace(' ', '_', strtolower($row_product_attributes["attribute_name"]));

                    ?>

                      var i = i + 1;

                      product_attributes[i] = {};

                      if ($("select[name='<?php echo $meta_key; ?>']").val() != "") {

                          var attribute_key = "meta_key='<?php echo $meta_key; ?>'";

                          var attribute_value = "meta_value='" + $("select[name='<?php echo $meta_key; ?>']").val() + "'";

                          product_attributes[i]["meta_key"] = attribute_key;

                          product_attributes[i]["meta_value"] = attribute_value;

                      }

                      var selected_option = $("select[name='<?php echo $meta_key; ?>']").val();

                      selected_attributes["<?php echo $meta_key; ?>"] = selected_option;

                    <?php } ?>

                      $(".box").addClass("table-loader");

                      var variation_ids = "<?php echo $variation_ids; ?>";

                      $.ajax({

                          method: "POST",

                          url: "load_product_variations.php",

                          data: {
                              product_id: <?php echo $pro_id; ?>,
                              default_variation_id: <?php echo $default_variation_id; ?>,
                              variation_ids: variation_ids,
                              selected_attributes: selected_attributes,
                              variation_meta_array: product_attributes
                          },

                          success: function (data) {

                              $("#variable-product-div").html(data);

                              $(".box").removeClass("table-loader");

                          }

                      });

                  });

              });

          </script>

      </div><!-- variable-product-div Ends -->

    <?php

    if ($array_end) {

      $select_product_stock = "select * from products_stock where product_id='$pro_id' and variation_id='$current_variation_id'";

      $run_product_stock = mysqli_query($con, $select_product_stock);

      $row_product_stock = mysqli_fetch_array($run_product_stock);

      $enable_stock = $row_product_stock["enable_stock"];

      $stock_status = $row_product_stock["stock_status"];

      $stock_quantity = $row_product_stock["stock_quantity"];

      $allow_backorders = $row_product_stock["allow_backorders"];

    }

  }

}

?>