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

    $select_product_variations = "select * from product_variations where product_id='$random_id' and not product_type='default_attributes_variation' order by 1 DESC";

    $run_product_variations = mysqli_query($con, $select_product_variations);

    $count_product_variations = mysqli_num_rows($run_product_variations);

    if ($count_product_variations != 0) {

      while ($row_product_variations = mysqli_fetch_array($run_product_variations)) {

        $variation_id = $row_product_variations["variation_id"];

        $product_img1 = $row_product_variations["product_img1"];

        $product_price = $row_product_variations["product_price"];

        $product_psp_price = $row_product_variations["product_psp_price"];

        $product_weight = $row_product_variations["product_weight"];

        $product_type = $row_product_variations["product_type"];

        $select_product_stock = "select * from products_stock where product_id='$random_id' and variation_id='$variation_id'";

        $run_product_stock = mysqli_query($con, $select_product_stock);

        $count_product_stock = mysqli_num_rows($run_product_stock);

        if ($count_product_stock == 0) {

          $enable_stock = "no";

          $stock_status = "";

          $stock_quantity = 0;

          $allow_backorders = "";

        } else {

          $row_product_stock = mysqli_fetch_array($run_product_stock);

          $enable_stock = $row_product_stock["enable_stock"];

          $stock_status = $row_product_stock["stock_status"];

          $stock_quantity = $row_product_stock["stock_quantity"];

          $allow_backorders = $row_product_stock["allow_backorders"];

        }

        ?>

          <div class="single-variation-row" id="variation_<?php echo $variation_id; ?>">
              <!-- single-variation-row Starts -->

              <hr class="variation-hr">

              <strong> #<?php echo $variation_id; ?> </strong>

              <div class="variation-attributes"><!-- variation-attributes Starts -->

                <?php

                $select_product_attributes = "select * from product_attributes where product_id='$random_id'";

                $run_product_attributes = mysqli_query($con, $select_product_attributes);

                while ($row_product_attributes = mysqli_fetch_array($run_product_attributes)) {

                  $attribute_id = $row_product_attributes["attribute_id"];

                  $attribute_name = $row_product_attributes["attribute_name"];

                  $attribute_values = explode("|", $row_product_attributes["attribute_values"]);

                  $meta_key = str_replace(' ', '_', strtolower($attribute_name));

                  ?>

                    <select class="form-control"
                            name="variations[<?php echo $variation_id; ?>][attributes][<?php echo $meta_key; ?>]">
                        <!-- attribute select Starts -->

                        <option value=""> Select A <?php echo $attribute_name; ?> </option>

                      <?php

                      foreach ($attribute_values as $attribute_value) {

                        $select_variations_meta = "select * from variations_meta where variation_id='$variation_id' and meta_key='$meta_key'";

                        $run_variations_meta = mysqli_query($con, $select_variations_meta);

                        $row_variations_meta = mysqli_fetch_array($run_variations_meta);

                        $meta_value = $row_variations_meta["meta_value"];

                        if ($attribute_value == $meta_value) {

                          echo "<option selected>$attribute_value</option>";

                        } else {

                          echo "<option>$attribute_value</option>";

                        }

                      }

                      ?>

                    </select><!-- attribute select Ends -->

                <?php } ?>

              </div><!-- variation-attributes Ends -->

              <div class="variation-actions"><!-- variation-actions Starts -->

                  <button class="btn btn-default btn-sm" title="Expand Variation" data-toggle="collapse"
                          href="#variation_collapse_<?php echo $variation_id; ?>">

                      <i class="fa fa-arrow-down"></i>

                  </button>

                  <a href="#" id="delete_product_variation_<?php echo $variation_id; ?>" class="btn btn-danger btn-sm">

                      <i class="fa fa-trash-o"></i> Delete

                  </a>

              </div><!-- variation-actions Ends -->

              <div class="variation-row-expand collapse" id="variation_collapse_<?php echo $variation_id; ?>">
                  <!-- variation-row-expand Starts -->

                  <div class="row"><!-- row Starts -->

                      <div class="col-md-6"><!-- col-md-6 Starts -->

                          <div class="form-group"><!-- form-group Starts -->

                              <label> Product Image 1 </label>

                              <div class="row"><!-- row Starts -->

                                  <div class="col-sm-8"><!-- col-sm-8 Starts -->

                                      <input type="file" name="variations[<?php echo $variation_id; ?>][product_img1]"
                                             class="form-control">

                                  </div><!-- col-sm-8 Ends -->

                                  <div class="col-sm-4"><!-- col-sm-4 Starts -->

                                      <img src="../admin_area/product_images/<?php if (empty($product_img1)) {
                                        echo "no-image.jpg";
                                      } else {
                                        echo $product_img1;
                                      } ?>" width="70" height="70">

                                    <?php if (!empty($product_img1)) { ?>

                                        <button style="margin-top:10px;" class="btn btn-primary btn-sm"
                                                id="product_img1_remove_<?php echo $variation_id; ?>">

                                            Remove

                                        </button>

                                    <?php } ?>

                                  </div><!-- col-sm-4 Ends -->

                              </div><!-- row Ends -->

                          </div><!-- form-group Ends -->

                          <div class="form-group"><!-- form-group Starts -->

                              <label> Product Price </label>

                              <input type="text" name="variations[<?php echo $variation_id; ?>][product_price]"
                                     class="form-control" required value="<?php echo $product_price; ?>">

                          </div><!-- form-group Ends -->

                      </div><!-- col-md-6 Ends -->

                      <div class="col-md-6"><!-- col-md-6 Starts -->

                          <div class="form-group"><!-- form-group Starts -->

                              <label> Select A Product Type </label>

                              <select class="form-control"
                                      name="variations[<?php echo $variation_id; ?>][product_type]">
                                  <!-- select manufacturer Starts -->

                                <?php if ($product_type == "physical_product") { ?>

                                    <option value="physical_product" selected> (Physical Product) Simple Product
                                    </option>

                                    <option value="digital_product"> (Digital Product) Downloadable Product</option>

                                <?php } elseif ($product_type == "digital_product") { ?>

                                    <option value="digital_product" selected> (Digital Product) Downloadable Product
                                    </option>

                                    <option value="physical_product"> (Physical Product) Simple Product</option>

                                <?php } else { ?>

                                    <option value="physical_product"> (Physical Product) Simple Product</option>

                                    <option value="digital_product"> (Digital Product) Downloadable Product</option>

                                <?php } ?>

                              </select><!-- select manufacturer Ends -->

                          </div><!-- form-group Ends -->

                          <div class="form-group"><!-- form-group Starts -->

                              <label> Product Sale Price </label>

                              <input type="text" name="variations[<?php echo $variation_id; ?>][product_psp_price]"
                                     class="form-control" value="<?php echo $product_psp_price; ?>">

                          </div><!-- form-group Ends -->

                          <div class="form-group"><!-- form-group Starts -->

                              <label> Product Weight <small> (kg)</small> </label>

                              <input type="text" name="variations[<?php echo $variation_id; ?>][product_weight]"
                                     class="form-control" value="<?php echo $product_weight; ?>">

                          </div><!-- form-group Ends -->

                      </div><!-- col-md-6 Ends -->

                  </div><!-- row Ends -->

                  <div class="row"><!-- row Starts -->

                      <div class="col-sm-6" id="stock-status-<?php echo $variation_id; ?>"><!-- col-sm-6 Starts -->

                          <div class="form-group"><!-- form-group Starts -->

                              <label> Stock Status </label>

                              <select class="form-control" name="variations[<?php echo $variation_id; ?>][stock_status]"
                                      required><!-- select manufacturer Starts -->

                                <?php if ($stock_status == "instock") { ?>

                                    <option value="instock" selected>In stock</option>

                                    <option value="outofstock">Out of stock</option>

                                    <option value="onbackorder">On backorder</option>

                                <?php } elseif ($stock_status == "outofstock") { ?>

                                    <option value="instock">In stock</option>

                                    <option value="outofstock" selected>Out of stock</option>

                                    <option value="onbackorder">On backorder</option>

                                <?php } elseif ($stock_status == "onbackorder") { ?>

                                    <option value="instock">In stock</option>

                                    <option value="outofstock">Out of stock</option>

                                    <option value="onbackorder" selected>On backorder</option>

                                <?php } else { ?>

                                    <option value="instock">In stock</option>

                                    <option value="outofstock">Out of stock</option>

                                    <option value="onbackorder">On backorder</option>

                                <?php } ?>

                              </select><!-- select manufacturer Ends -->

                          </div><!-- form-group Ends -->

                      </div><!-- col-sm-6 Ends -->

                      <div class="col-sm-6"><!-- col-sm-6 Starts -->

                          <div class="form-group"><!-- form-group Starts -->

                              <label> Enable stock management at variation level? </label>

                              <div class="radio">

                                  <label>

                                      <input type="radio" name="variations[<?php echo $variation_id; ?>][enable_stock]"
                                             value="yes" <?php if ($enable_stock == "yes") {
                                        echo "checked";
                                      } ?> required> Yes

                                  </label>

                                  <label>

                                      <input type="radio" name="variations[<?php echo $variation_id; ?>][enable_stock]"
                                             value="no" <?php if ($enable_stock == "no") {
                                        echo "checked";
                                      } ?> required> No

                                  </label>

                              </div>

                          </div><!-- form-group Ends -->

                      </div><!-- col-sm-6 Ends -->

                  </div><!-- row Ends -->

                  <div class="row" id="stock-management-row-<?php echo $variation_id; ?>"><!-- row Starts -->

                      <div class="col-sm-6"><!-- col-sm-6 Starts -->

                          <div class="form-group"><!-- form-group Starts -->

                              <label> Stock Quantity </label>

                              <input type="number" name="variations[<?php echo $variation_id; ?>][stock_quantity]"
                                     value="<?php echo $stock_quantity; ?>" class="form-control" required>

                          </div><!-- form-group Ends -->

                      </div><!-- col-sm-6 Ends -->

                      <div class="col-sm-6"><!-- col-sm-6 Starts -->

                          <div class="form-group"><!-- form-group Starts -->

                              <label> Allow backorders? </label>

                              <select class="form-control"
                                      name="variations[<?php echo $variation_id; ?>][allow_backorders]" required>
                                  <!-- select manufacturer Starts -->

                                <?php if ($allow_backorders == "no") { ?>

                                    <option value="no" selected>Do not allow</option>

                                    <option value="notify">Allow, but notify customer</option>

                                    <option value="yes">Allow</option>

                                <?php } elseif ($allow_backorders == "notify") { ?>

                                    <option value="no">Do not allow</option>

                                    <option value="notify" selected>Allow, but notify customer</option>

                                    <option value="yes">Allow</option>

                                <?php } elseif ($allow_backorders == "yes") { ?>

                                    <option value="no">Do not allow</option>

                                    <option value="notify">Allow, but notify customer</option>

                                    <option value="yes" selected>Allow</option>

                                <?php } else { ?>

                                    <option value="no">Do not allow</option>

                                    <option value="notify">Allow, but notify customer</option>

                                    <option value="yes">Allow</option>

                                <?php } ?>

                              </select><!-- select manufacturer Ends -->

                          </div><!-- form-group Ends -->

                      </div><!-- col-sm-6 Ends -->

                  </div><!-- row Ends -->

              </div><!-- variation-row-expand Ends -->

              <script>

                  $(document).ready(function () {

//Product Stock Management Code Starts

                    <?php if($enable_stock == "no"){ ?>

                      $("#stock-management-row-<?php echo $variation_id; ?>").hide();

                    <?php }elseif($enable_stock == "yes"){ ?>

                      $("#stock-status-<?php echo $variation_id; ?>").hide();

                    <?php } ?>

                      $("input[name='variations[<?php echo $variation_id; ?>][enable_stock]']").click(function () {

                          var radio_value = $(this).val();

                          if (radio_value == "yes") {

                              $("#stock-status-<?php echo $variation_id; ?>").hide();

                              $("#stock-management-row-<?php echo $variation_id; ?>").show();

                          } else if (radio_value == "no") {

                              $("#stock-status-<?php echo $variation_id; ?>").show();

                              $("#stock-management-row-<?php echo $variation_id; ?>").hide();

                          }

                      });

//Product Stock Management Code Ends

                      $("#variation_collapse_<?php echo $variation_id; ?>").on("show.bs.collapse", function () {

                          $("button[href='#variation_collapse_<?php echo $variation_id; ?>']").html("<i class='fa fa-arrow-up'></i> ");

                      });

                      $("#variation_collapse_<?php echo $variation_id; ?>").on("hide.bs.collapse", function () {

                          $("button[href='#variation_collapse_<?php echo $variation_id; ?>']").html("<i class='fa fa-arrow-down'></i> ");

                      });

                      $("#delete_product_variation_<?php echo $variation_id; ?>").click(function (event) {

                          event.preventDefault();

                          $(".product-variations-div").addClass("wait-loader");

                          $("#variation_<?php echo $variation_id; ?>").remove();

                          var random_id = <?php echo $random_id; ?>;

                          var variation_id = <?php echo $variation_id; ?>;

                          $.ajax({

                              method: "POST",

                              url: "variable_product/delete_product_variation.php",

                              data: {random_id: random_id, variation_id: variation_id},

                              success: function () {

                                  $(".product-variations-div").removeClass("wait-loader");

                              }

                          });

                      });

                  });

              </script>

          </div><!-- single-variation-row Ends -->

        <?php

      }

    }

    ?>


  <?php }
} ?>