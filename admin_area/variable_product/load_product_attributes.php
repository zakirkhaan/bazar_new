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

    $select_product_attributes = "select * from product_attributes where product_id='$random_id'";

    $run_product_attributes = mysqli_query($con, $select_product_attributes);

    while ($row_product_attributes = mysqli_fetch_array($run_product_attributes)) {

      $attribute_id = $row_product_attributes["attribute_id"];

      $attribute_name = $row_product_attributes["attribute_name"];

      $attribute_values = $row_product_attributes["attribute_values"];

      ?>

        <tr id="tr-attribute-<?php echo $attribute_id; ?>">

            <td>

                <div class="edit" data-attribute="<?php echo $attribute_id; ?>"><?php echo $attribute_name; ?></div>

                <input type="text" id="attribute-name" class="input-edit form-control"
                       data-attribute="<?php echo $attribute_id; ?>" value="<?php echo $attribute_name; ?>">

            </td>

            <td>

                <div class="edit" data-attribute="<?php echo $attribute_id; ?>"><?php echo $attribute_values; ?></div>

                <input type="text" id="attribute-values" class="input-edit form-control"
                       data-attribute="<?php echo $attribute_id; ?>" value="<?php echo $attribute_values; ?>">

            </td>

            <td>

                <div class="btn-group">

                    <a href="#" class="edit-product-attribute btn btn-primary btn-sm"
                       data-attribute="<?php echo $attribute_id; ?>">

                        <i class="fa fa-pencil"></i> Edit

                    </a>

                    <a href="#" class="save-product-attribute btn btn-success btn-sm"
                       data-attribute="<?php echo $attribute_id; ?>">

                        <i class="fa fa-floppy-o"></i> Save

                    </a>

                    <a href="#" class="delete-product-attribute btn btn-danger btn-sm"
                       data-attribute="<?php echo $attribute_id; ?>">

                        <i class="fa fa-trash-o"></i> Delete

                    </a>

                </div>

            </td>

        </tr>

    <?php } ?>

      <script>

          $(document).ready(function () {

//Edit Product Attribute Code Starts

              $(".input-edit").hide();

              $(".save-product-attribute").hide();

              $(".edit-product-attribute").on('click', function (event) {

                  event.preventDefault();

                  var attribute_id = $(this).data("attribute");

                  $(".edit").show();

                  $(".input-edit").hide();

                  $(".edit[data-attribute='" + attribute_id + "']").hide();

                  $(".input-edit[data-attribute='" + attribute_id + "']").show().focus();

                  $(".edit-product-attribute[data-attribute='" + attribute_id + "']").hide();

                  $(".save-product-attribute[data-attribute='" + attribute_id + "']").show();

              });

//Edit Product Attribute Code Ends

//Update Save Product Attribute Code Starts

              $(".save-product-attribute").on('click', function (event) {

                  event.preventDefault();

                  var attribute_id = $(this).data("attribute");

                  $(".edit[data-attribute='" + attribute_id + "']").show();

                  $(".input-edit[data-attribute='" + attribute_id + "']").hide();

                  $(".edit-product-attribute[data-attribute='" + attribute_id + "']").show();

                  $(".save-product-attribute[data-attribute='" + attribute_id + "']").hide();

                  var attribute_name = $("#attribute-name[data-attribute='" + attribute_id + "']").val();

                  var attribute_values = $("#attribute-values[data-attribute='" + attribute_id + "']").val();

                  $("#attribute-name[data-attribute='" + attribute_id + "']").prev(".edit").text(attribute_name);

                  $("#attribute-values[data-attribute='" + attribute_id + "']").prev(".edit").text(attribute_values);

                  var random_id = <?php echo $random_id; ?>;

                  $.ajax({

                      method: "POST",

                      url: "variable_product/update_product_attribute.php",

                      data: {
                          random_id: random_id,
                          attribute_id: attribute_id,
                          attribute_name: attribute_name,
                          attribute_values: attribute_values
                      }

                  });

              });

//Update Save Product Attribute Code Ends

//Delete Product Attribute Code Starts

              $(".delete-product-attribute").on('click', function (event) {

                  event.preventDefault();

                  var attribute_id = $(this).data("attribute");

                  $("#tr-attribute-" + attribute_id).remove();

                  var random_id = <?php echo $random_id; ?>;

                  $.ajax({

                      method: "POST",

                      url: "variable_product/delete_product_attribute.php",

                      data: {random_id: random_id, attribute_id: attribute_id}

                  });

              });

//Delete Product Attribute Code Ends

          });

      </script>

  <?php }
} ?>