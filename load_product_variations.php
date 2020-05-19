<?php
session_start();
include("includes/db.php");
include("functions/functions.php");


if(isset($_POST["product_id"])){
    $product_id = mysqli_real_escape_string($con, $_POST["product_id"]);
    $select_product = "select * from products where product_id='$product_id'";
    $run_product = mysqli_query($con,$select_product);
    $row_product = mysqli_fetch_array($run_product);
    $status = $row_product['status'];
    $default_variation_id = mysqli_real_escape_string($con, $_POST["default_variation_id"]);
    $variation_ids = mysqli_real_escape_string($con, $_POST["variation_ids"]);
    $selected_attributes = $_POST["selected_attributes"];
    if(isset($_POST["variation_meta_array"])){
        $variation_meta_array = $_POST["variation_meta_array"];
        $new_variation_meta_array = array();
        $variation_meta_variation_ids = array();
        $new_array_i = 0;
        $loop_number = 0;
        foreach($variation_meta_array as $array_id => $variation_meta){
            $new_array_i++;
            $loop_number++;
            $new_variation_meta_array[$new_array_i] = $variation_meta;
            $variation_meta = implode(" and ", $new_variation_meta_array[$new_array_i]);
            $select_variations_meta = "select DISTINCT variation_id from variations_meta where $variation_meta and variation_id IN ($variation_ids) and not variation_id='$default_variation_id'";
            $run_variations_meta = mysqli_query($con,$select_variations_meta);
            $i = 0;
            while($row_variations_meta = mysqli_fetch_array($run_variations_meta)){
                $i++;
                $variation_id = $row_variations_meta["variation_id"];
                if($loop_number == 1){
                    $variation_meta_variation_ids[$new_array_i][$i] = $variation_id;
                }else{
                    $prev_array_id = $loop_number-1;
                    if(in_array($variation_id, $variation_meta_variation_ids[$prev_array_id])){
                        $variation_meta_variation_ids[$new_array_i][$i] = $variation_id;
                    }
                }
            }
        }
        $array_end = end($variation_meta_variation_ids);
        $current_variation_id = array_values($array_end)["0"];
}
?>

<?php

  $attribute_i = 0;

  $select_product_attributes = "select * from product_attributes where product_id='$product_id'";

  $run_product_attributes = mysqli_query($con, $select_product_attributes);

  while ($row_product_attributes = mysqli_fetch_array($run_product_attributes)) {

    $attribute_i++;

    $attribute_name = $row_product_attributes["attribute_name"];

    $meta_key = str_replace(' ', '_', strtolower($attribute_name));

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

        foreach ($attribute_variation_meta_array as $array_id => $variation_meta) {

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

      <div class="form-group"><!-- form-group Starts -->

          <label class="col-lg-4 col-md-3 control-label"> <?php echo $attribute_name; ?> </label>

          <div class="col-lg-6 col-md-9"><!-- col-lg-6 col-md-9 Starts -->

              <select name="<?php echo $meta_key; ?>" class="form-control attribute-select" required>

                  <option value=""> Choose an option</option>

                <?php

                if (isset($attribute_variation_ids)) {

                  echo $select_variations_meta = "select DISTINCT meta_value from variations_meta where variation_id IN ($attribute_variation_ids) and meta_key='$meta_key'";

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

  ?>

  <?php if (isset($_POST["variation_meta_array"])) { ?>

        <input type="hidden" name="variation_id" value="<?php echo $current_variation_id; ?>">

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
                        product_id: <?php echo $product_id; ?>,
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

  <?php

  if (isset($_POST["variation_meta_array"])) {

    foreach ($selected_attributes as $attribute) {

      if (empty($attribute)) {

        $all_attributes_selected = false;

        break;

      } else {

        $all_attributes_selected = true;

      }

    }

    if ($all_attributes_selected) {

      $select_product_variation = "select * from product_variations where product_id='$product_id' and variation_id='$current_variation_id'";

      $run_product_variation = mysqli_query($con, $select_product_variation);

      $row_product_variation = mysqli_fetch_array($run_product_variation);

      $variation_product_img1 = $row_product_variation["product_img1"];

      $variation_product_price = $row_product_variation["product_price"];

      $variation_product_psp_price = $row_product_variation["product_psp_price"];

      $select_product_stock = "select * from products_stock where product_id='$product_id' and variation_id='$current_variation_id'";

      $run_product_stock = mysqli_query($con, $select_product_stock);

      $row_product_stock = mysqli_fetch_array($run_product_stock);

      $enable_stock = $row_product_stock["enable_stock"];

      $stock_status = $row_product_stock["stock_status"];

      $stock_quantity = $row_product_stock["stock_quantity"];

      $allow_backorders = $row_product_stock["allow_backorders"];

      ?>

        <script>

            $(document).ready(function () {

              <?php

              if($status == "product"){

              if($variation_product_psp_price == 0){

              ?>

                var product_price = "Product Price : Rs:<?php echo $variation_product_price; ?>";

              <?php }else{ ?>

                var product_price = "Product Price : <del> Rs:<?php echo $variation_product_price; ?> </del><br>";

                product_price += "Product Sale Price : Rs:<?php echo $variation_product_psp_price; ?>";

              <?php

              }

              }elseif($status == "bundle"){

              if($variation_product_psp_price == 0){

              ?>

                var product_price = "Bundle Price : Rs:<?php echo $variation_product_price; ?>";

              <?php }else{ ?>

                var product_price = "Bundle Price : <del> Rs:<?php echo $variation_product_price; ?> </del><br>";

                product_price += "Bundle Sale Price : Rs:<?php echo $variation_product_psp_price; ?>";

              <?php

              }

              }

              ?>

                $(".price").html(product_price);

                $("input[name='product_qty']").val("1");

              <?php if($enable_stock == "yes" and $allow_backorders == "no"){ ?>

                $("input[name='product_qty']").attr("max", "<?php echo $stock_quantity; ?>");

              <?php }elseif($enable_stock == "yes" and ($allow_backorders == "yes" or $allow_backorders == "notify")){ ?>

                $("input[name='product_qty']").prop("max", true);

              <?php }elseif($enable_stock == "no"){ ?>

                $("input[name='product_qty']").prop("max", true);

              <?php } ?>


                $(".stock-caption").text("");

              <?php if( ($enable_stock == "yes" or $enable_stock == "no") and $stock_status == "outofstock" ){  ?>

                $(".stock-caption").text("Out of stock ");

                $(".text-center.buttons").hide();

              <?php

              }elseif( $enable_stock == "no" and ($stock_status == "instock" or $stock_status == "onbackorder") ){

              ?>

                $(".text-center.buttons").show();

              <?php

              }elseif( $enable_stock == "yes" and ($stock_status == "instock" or $stock_status == "onbackorder") ){

              ?>

                $(".text-center.buttons").show();

              <?php if($stock_quantity > 0 and ($allow_backorders == "yes" or $allow_backorders == "no")){ ?>

                $(".stock-caption").text("<?php echo $stock_quantity; ?> items in stock ");

              <?php }elseif($stock_quantity > 0 and $allow_backorders == "notify"){ ?>

                $(".stock-caption").text("<?php echo $stock_quantity; ?> items in stock (can be backordered) ");

              <?php }elseif($stock_quantity < 1 and $allow_backorders == "notify"){ ?>

                $(".stock-caption").text(" Available on backorder ");

              <?php } ?>

              <?php } ?>


            });

        </script>

    <?php } else { ?>

        <script>

            $(document).ready(function () {

                $(".price").html("");

                $(".stock-caption").text("");

            });

        </script>

      <?php

    }

  }

  ?>

<?php } ?>