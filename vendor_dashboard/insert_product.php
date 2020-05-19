<?php

if (!isset($_SESSION['customer_email'])) {

  echo "<script> window.open('../checkout.php','_self'); </script>";

}

$customer_email = $_SESSION['customer_email'];

$get_customer = "select * from customers where customer_email='$customer_email'";

$run_customer = mysqli_query($con, $get_customer);

$row_customer = mysqli_fetch_array($run_customer);

$customer_id = $row_customer['customer_id'];

$select_general_settings = "select * from general_settings";

$run_general_settings = mysqli_query($con, $select_general_settings);

$row_general_settings = mysqli_fetch_array($run_general_settings);

$product_vendor_status = $row_general_settings["new_product_status"];

?>

<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>

<script>tinymce.init({selector: '#product_desc,#product_features'});</script>

<div class="row"><!-- 2 row Starts -->

    <div class="col-lg-12"><!-- col-lg-12 Starts -->

        <div class="panel panel-default"><!-- panel panel-default Starts -->

            <div class="panel-heading"><!-- panel-heading Starts -->

                <h3 class="panel-title">

                    <i class="fa fa-money fa-fw"></i> Insert Product

                </h3>

            </div><!-- panel-heading Ends -->

            <div class="panel-body"><!-- panel-body Starts -->

                <form id="insert_product_form" method="post" enctype="multipart/form-data">
                    <!-- form-horizontal Starts -->

                    <div class="row"><!-- 2 row Starts -->

                        <div class="col-md-9"><!-- col-md-9 Starts -->

                            <div class="form-group"><!-- form-group Starts -->

                                <label> Product Title </label>

                                <input type="text" name="product_title" class="form-control" required>

                            </div><!-- form-group Ends -->

                            <div class="form-group"><!-- form-group Starts -->

                                <label> Product Seo Description </label>

                                <textarea name="product_seo_desc" class="form-control" maxlength="230"
                                          placeholder="Most search engines use a maximum of 230 chars for the description."></textarea>

                            </div><!-- form-group Ends -->

                            <div class="form-group"><!-- form-group Starts -->

                                <label> Product Url </label>

                                <input type="text" name="product_url" class="form-control" required>

                                <br>

                                <p style="font-size:15px; font-weight:bold;">
                                    Product Url Example : navy-blue-t-shirt
                                </p>

                            </div><!-- form-group Ends -->

                            <div class="form-group"><!-- form-group Starts -->

                                <label> Product Tabs </label>

                                <ul class="nav nav-tabs"><!-- nav nav-tabs Starts -->

                                    <li class="active">

                                        <a data-toggle="tab" href="#description"> Product Description </a>

                                    </li>

                                    <li>

                                        <a data-toggle="tab" href="#features"> Product Features </a>

                                    </li>

                                    <li>

                                        <a data-toggle="tab" href="#video"> Sounds And Videos </a>

                                    </li>

                                </ul><!-- nav nav-tabs Ends -->

                                <div class="tab-content"><!-- tab-content Starts -->

                                    <div id="description" class="tab-pane fade in active">
                                        <!-- description tab-pane fade in active Starts -->

                                        <br>
                                        <textarea name="product_desc" class="form-control" rows="15"
                                                  id="product_desc"></textarea>

                                    </div><!-- description tab-pane fade in active Ends -->

                                    <div id="features" class="tab-pane fade in">
                                        <!-- features tab-pane fade in Starts -->

                                        <br>
                                        <textarea name="product_features" class="form-control" rows="15"
                                                  id="product_features"></textarea>

                                    </div><!-- features tab-pane fade in Ends -->

                                    <div id="video" class="tab-pane fade in"><!-- video tab-pane fade in Starts -->

                                        <br>
                                        <textarea name="product_video" class="form-control" rows="15"></textarea>

                                    </div><!-- video tab-pane fade in Ends -->

                                </div><!-- tab-content Ends -->

                            </div><!-- form-group Ends -->

                            <div class="form-group" id="product_weight"><!-- form-group Starts -->

                                <label> Product Weight <small> (kg)</small> </label>

                                <input type="text" name="product_weight" class="form-control">

                            </div><!-- form-group Ends -->

                            <div class="form-group" id="product_price"><!-- form-group Starts -->

                                <label> Product Price </label>

                                <input type="text" name="product_price" class="form-control" required>

                            </div><!-- form-group Ends -->

                            <div class="form-group" id="product_psp_price"><!-- form-group Starts -->

                                <label> Product Sale Price </label>

                                <input type="text" name="psp_price" class="form-control">

                            </div><!-- form-group Ends -->

                        </div><!-- col-md-9 Ends -->

                        <div class="col-md-3"><!-- col-md-3 Starts -->

                            <div class="form-group"><!-- form-group Starts -->

                                <label> Select A Product Type </label>

                                <select class="form-control" name="product_type"><!-- select manufacturer Starts -->

                                    <option value="physical_product"> (Physical Product) Simple Product</option>

                                    <option value="digital_product"> (Digital Product) Downloadable Product</option>

                                    <option value="variable_product"> (Variable Product) Advanced Product</option>

                                </select><!-- select manufacturer Ends -->

                            </div><!-- form-group Ends -->

                            <div class="form-group"><!-- form-group Starts -->

                                <label> Select A Manufacturer </label>

                                <select class="form-control" name="manufacturer"><!-- select manufacturer Starts -->

                                    <option> Select A Manufacturer</option>

                                  <?php

                                  $get_manufacturer = "select * from manufacturers";
                                  $run_manufacturer = mysqli_query($con, $get_manufacturer);
                                  while ($row_manufacturer = mysqli_fetch_array($run_manufacturer)) {
                                    $manufacturer_id = $row_manufacturer['manufacturer_id'];
                                    $manufacturer_title = $row_manufacturer['manufacturer_title'];

                                    echo "<option value='$manufacturer_id'>
$manufacturer_title
</option>";

                                  }

                                  ?>

                                </select><!-- select manufacturer Ends -->

                            </div><!-- form-group Ends -->

                            <div class="form-group"><!-- form-group Starts -->

                                <label> Product Category </label>

                                <select name="product_cat" class="form-control">

                                    <option> Select a Product Category</option>

                                  <?php

                                  $get_p_cats = "select * from product_categories";

                                  $run_p_cats = mysqli_query($con, $get_p_cats);

                                  while ($row_p_cats = mysqli_fetch_array($run_p_cats)) {

                                    $p_cat_id = $row_p_cats['p_cat_id'];

                                    $p_cat_title = $row_p_cats['p_cat_title'];

                                    echo "<option value='$p_cat_id' >$p_cat_title</option>";

                                  }

                                  ?>

                                </select>

                            </div><!-- form-group Ends -->

                            <div class="form-group"><!-- form-group Starts -->

                                <label> Category </label>

                                <select name="cat" class="form-control">

                                    <option> Select a Category</option>

                                  <?php

                                  $get_cat = "select * from categories";

                                  $run_cat = mysqli_query($con, $get_cat);

                                  while ($row_cat = mysqli_fetch_array($run_cat)) {

                                    $cat_id = $row_cat['cat_id'];

                                    $cat_title = $row_cat['cat_title'];

                                    echo "<option value='$cat_id'>$cat_title</option>";

                                  }

                                  ?>

                                </select>

                            </div><!-- form-group Ends -->

                            <div class="form-group"><!-- form-group Starts -->

                                <label> Product Image 1 </label>

                                <input type="file" name="product_img1" class="form-control" required>

                            </div><!-- form-group Ends -->

                            <div class="form-group"><!-- form-group Starts -->

                                <label> Product Image 2 </label>

                                <input type="file" name="product_img2" class="form-control">

                            </div><!-- form-group Ends -->

                            <div class="form-group"><!-- form-group Starts -->

                                <label> Product Image 3 </label>

                                <input type="file" name="product_img3" class="form-control">

                            </div><!-- form-group Ends -->

                            <div class="form-group"><!-- form-group Starts -->

                                <label> Product Keywords </label>

                                <input type="text" name="product_keywords" class="form-control">

                            </div><!-- form-group Ends -->

                            <div class="form-group"><!-- form-group Starts -->

                                <label> Product Label </label>

                                <input type="text" name="product_label" class="form-control">

                            </div><!-- form-group Ends -->

                        </div><!-- col-md-3 Ends -->

                    </div><!-- 2 row Ends -->

                    <div class="form-group" id="product-stock-management"><!-- form-group Starts -->

                        <label> Product Inventory Stock Management </label>

                        <div class="panel panel-default"><!-- panel panel-default Starts -->

                            <div class="panel-heading"><!-- panel-heading Starts -->

                                <strong> Inventory - Stock Options </strong>

                            </div><!-- panel-heading Ends -->

                            <div class="panel-body"><!--panel-body Starts -->

                                <div class="row"><!-- row Starts -->

                                    <div class="col-sm-6" id="stock-status"><!-- col-sm-6 Starts -->

                                        <div class="form-group"><!-- form-group Starts -->

                                            <label> Stock Status </label>

                                            <select class="form-control" name="stock_status" required>
                                                <!-- select manufacturer Starts -->

                                                <option value="instock">In stock</option>

                                                <option value="outofstock">Out of stock</option>

                                                <option value="onbackorder">On backorder</option>

                                            </select><!-- select manufacturer Ends -->

                                        </div><!-- form-group Ends -->

                                    </div><!-- col-sm-6 Ends -->

                                    <div class="col-sm-6"><!-- col-sm-6 Starts -->

                                        <div class="form-group"><!-- form-group Starts -->

                                            <label> Enable stock management at product level? </label>

                                            <div class="radio">

                                                <label>
                                                    <input type="radio" name="enable_stock" value="yes" required> Yes
                                                </label>

                                                <label>
                                                    <input type="radio" name="enable_stock" value="no" checked required>
                                                    No
                                                </label>

                                            </div>

                                        </div><!-- form-group Ends -->

                                    </div><!-- col-sm-6 Ends -->

                                </div><!-- row Ends -->

                                <div class="row" id="stock-management-row"><!-- row Starts -->

                                    <div class="col-sm-6"><!-- col-sm-6 Starts -->

                                        <div class="form-group"><!-- form-group Starts -->

                                            <label> Stock Quantity </label>

                                            <input type="number" name="stock_quantity" value="0" class="form-control"
                                                   required>

                                        </div><!-- form-group Ends -->

                                    </div><!-- col-sm-6 Ends -->

                                    <div class="col-sm-6"><!-- col-sm-6 Starts -->

                                        <div class="form-group"><!-- form-group Starts -->

                                            <label> Allow backorders? </label>

                                            <select class="form-control" name="allow_backorders" required>
                                                <!-- select manufacturer Starts -->

                                                <option value="no">Do not allow</option>

                                                <option value="notify">Allow, but notify customer</option>

                                                <option value="yes">Allow</option>

                                            </select><!-- select manufacturer Ends -->

                                        </div><!-- form-group Ends -->

                                    </div><!-- col-sm-6 Ends -->

                                </div><!-- row Ends -->

                            </div><!--panel-body Ends -->

                        </div><!-- panel panel-default Ends -->

                    </div><!-- form-group Ends -->


                </form><!-- form-horizontal Ends -->

                <div class="form-group" id="variable_product_options"><!-- form-group Starts -->

                    <label> Variable Product Options </label>

                    <div class="panel panel-default"><!-- panel panel-default Starts -->

                        <div class="panel-heading"><!-- panel-heading Starts -->

                            <ul class="nav nav-tabs"><!-- nav nav-tabs Starts -->

                                <li class="active">

                                    <a data-toggle="tab" href="#product_attributes"> Product Attributes </a>

                                </li>

                                <li>

                                    <a data-toggle="tab" href="#product_variations"> Product Variations </a>

                                </li>

                            </ul><!-- nav nav-tabs Ends -->

                        </div><!-- panel-heading Ends -->

                        <div class="panel-body"><!--panel-body Starts -->

                            <div class="tab-content"><!-- tab-content Starts -->

                                <div id="product_attributes" class="tab-pane fade in active">
                                    <!-- product_attributes tab-pane fade in active Starts -->

                                    <form id="insert_attribute_form" method="post"><!-- form Starts -->

                                        <div class="row"><!-- row Starts -->

                                            <div class="col-sm-4"><!-- col-sm-4 Starts -->

                                                <div class="form-group"><!-- form-group Starts -->

                                                    <label> Name: </label>

                                                    <input type="text" name="attribute_name" class="form-control"
                                                           required>

                                                </div><!-- form-group Ends -->

                                            </div><!-- col-sm-4 Ends -->

                                            <div class="col-sm-8"><!-- col-sm-8 Starts -->

                                                <div class="form-group"><!-- form-group Starts -->

                                                    <label> Value(s): </label>

                                                    <textarea name="attribute_values" class="form-control"
                                                              placeholder="Enter some attributes by '|' separating values."
                                                              required></textarea>

                                                </div><!-- form-group Ends -->

                                            </div><!-- col-sm-8 Ends -->

                                        </div><!-- row Ends -->

                                        <div class="form-group"><!-- form-group Starts -->

                                            <input type="submit" value="Insert Product Attribute"
                                                   class="btn btn btn-primary">

                                        </div><!-- form-group Ends -->

                                    </form><!-- form Ends -->

                                    <table class="table table-hover table-bordered table-striped">
                                        <!-- table table-hover table-bordered table-striped Starts -->

                                        <thead><!-- thead Starts -->

                                        <tr>

                                            <th>Attribute Name:</th>
                                            <th>Attribute Value(s):</th>
                                            <th>Actions:</th>

                                        </tr>

                                        </thead><!-- thead Ends -->

                                        <tbody><!-- tbody Starts -->

                                        <?php

                                        $random_id = 152;

                                        $select_product_attributes = "select * from product_attributes where product_id='$random_id'";

                                        $run_product_attributes = mysqli_query($con, $select_product_attributes);

                                        while ($row_product_attributes = mysqli_fetch_array($run_product_attributes)) {

                                          $attribute_id = $row_product_attributes["attribute_id"];

                                          $attribute_name = $row_product_attributes["attribute_name"];

                                          $attribute_values = $row_product_attributes["attribute_values"];

                                          ?>

                                            <tr id="tr-attribute-<?php echo $attribute_id; ?>">

                                                <td>

                                                    <div class="edit"
                                                         data-attribute="<?php echo $attribute_id; ?>"><?php echo $attribute_name; ?></div>

                                                    <input type="text" id="attribute-name"
                                                           class="input-edit form-control"
                                                           data-attribute="<?php echo $attribute_id; ?>"
                                                           value="<?php echo $attribute_name; ?>">

                                                </td>

                                                <td>

                                                    <div class="edit"
                                                         data-attribute="<?php echo $attribute_id; ?>"><?php echo $attribute_values; ?></div>

                                                    <input type="text" id="attribute-values"
                                                           class="input-edit form-control"
                                                           data-attribute="<?php echo $attribute_id; ?>"
                                                           value="<?php echo $attribute_values; ?>">

                                                </td>

                                                <td>

                                                    <div class="btn-group">

                                                        <a href="#"
                                                           class="edit-product-attribute btn btn-primary btn-sm"
                                                           data-attribute="<?php echo $attribute_id; ?>">

                                                            <i class="fa fa-pencil"></i> Edit

                                                        </a>

                                                        <a href="#"
                                                           class="save-product-attribute btn btn-success btn-sm"
                                                           data-attribute="<?php echo $attribute_id; ?>">

                                                            <i class="fa fa-floppy-o"></i> Save

                                                        </a>

                                                        <a href="#"
                                                           class="delete-product-attribute btn btn-danger btn-sm"
                                                           data-attribute="<?php echo $attribute_id; ?>">

                                                            <i class="fa fa-trash-o"></i> Delete

                                                        </a>

                                                    </div>

                                                </td>

                                            </tr>

                                        <?php } ?>

                                        </tbody><!-- tbody Ends -->

                                    </table><!-- table table-hover table-bordered table-striped Ends -->

                                </div><!-- product_attributes tab-pane fade in active Ends -->

                                <div id="product_variations" class="tab-pane fade in">
                                    <!-- product_variations tab-pane fade in Starts -->

                                    <form id="product-variations-form" method="post" enctype="multipart/form-data">
                                        <!-- form Starts -->

                                        <div class="form-group row"><!-- form-group Starts -->

                                            <label class="col-sm-3 control-label"> Default Form Values: </label>

                                            <div class="col-sm-9"><!-- col-sm-10 Starts -->

                                                <div class="row" id="default_form_values">
                                                    <!-- row default_form_values Starts -->


                                                </div><!-- row default_form_values Ends -->

                                                <span class="help-block">

These are the product attributes that will be pre-selected on the frontend.

</span>

                                            </div><!-- col-sm-9 Ends -->

                                        </div><!-- form-group Ends -->

                                        <hr class="variation-hr">

                                        <div class="form-group row"><!-- form-group Starts -->

                                            <label class="col-sm-1 control-label"> Actions: </label>

                                            <div class="col-sm-10"><!-- col-sm-10 Starts -->

                                                <select class="form-control" id="action_select">
                                                    <!-- select manufacturer Starts -->

                                                    <option value="add_variation"> Add A New Variation</option>

                                                    <option value="create_variations_from_attributes"> Create New
                                                        Variations From All Attributes
                                                    </option>

                                                    <option value="delete_all_variations"> Delete All Variations
                                                    </option>

                                                </select><!-- select manufacturer Ends -->

                                            </div><!-- col-sm-10 Ends -->

                                            <div class="col-sm-1"><!-- col-sm-1 Starts -->

                                                <button type="button" id="go_button"
                                                        class="btn btn-success form-control"> Go
                                                </button>

                                            </div><!-- col-sm-1 Ends -->

                                        </div><!-- form-group Ends -->

                                        <div class="product-variations-div"><!-- product-variations-div Starts -->


                                        </div><!-- product-variations-div Ends -->

                                        <hr class="variation-hr">

                                        <div class="form-group"><!-- form-group Starts -->

                                            <input type="submit" value="Save Product Variations"
                                                   class="btn btn btn-success">

                                        </div><!-- form-group Ends -->

                                    </form><!-- form Ends -->

                                    <div class="ajax-response-div"></div>

                                </div><!-- product_variations tab-pane fade in Ends -->

                            </div><!-- tab-content Ends -->

                        </div><!--panel-body Ends -->

                    </div><!-- panel panel-default Ends -->

                </div><!-- form-group Ends -->

                <div class="form-group"><!-- form-group Starts -->

                    <input type="submit" name="submit" value="Insert Product" form="insert_product_form"
                           class="btn btn-primary form-control">

                </div><!-- form-group Ends -->

            </div><!-- panel-body Ends -->

        </div><!-- panel panel-default Ends -->

    </div><!-- col-lg-12 Ends -->

</div><!-- 2 row Ends -->

<script>

    $(document).ready(function () {

//Product Stock Management Code Starts

        $("#stock-management-row").hide();

        $("input[name='enable_stock']").click(function () {

            var radio_value = $(this).val();

            if (radio_value == "yes") {

                $("#stock-status").hide();

                $("#stock-management-row").show();

            } else if (radio_value == "no") {

                $("#stock-status").show();

                $("#stock-management-row").hide();

            }

        });

//Product Stock Management Code Ends

//Change Product Type Function Code Starts

        function change_product_type() {

            var product_type = $("select[name='product_type']").val();

            if (product_type == "physical_product") {

                $("#product_weight").show();

                $("#product_price").show();

                $("#product_psp_price").show();

                $('#product_weight input,#product_price input,#product_psp_price input').prop("disabled", false);

                $("#product-stock-management").show();

                $('#product-stock-management input,#product-stock-management select').prop("disabled", false);

                $("#variable_product_options").hide();

            } else if (product_type == "digital_product") {

                $("#product_weight").hide();

                $("#product_price").show();

                $("#product_psp_price").show();

                $('#product_weight input,#product_price input,#product_psp_price input').prop("disabled", false);

                $("#product-stock-management").show();

                $('#product-stock-management input,#product-stock-management select').prop("disabled", false);

                $("#variable_product_options").hide();

            } else if (product_type == "variable_product") {

                $("#product_weight").hide();

                $("#product_price").hide();

                $("#product_psp_price").hide();

                $('#product_weight input,#product_price input,#product_psp_price input').prop("disabled", true);

                $("#product-stock-management").hide();

                $('#product-stock-management input,#product-stock-management select').prop("disabled", true);

                $("#variable_product_options").show();

            }

        }

//Change Product Type Function Code Ends

        change_product_type();

        $("select[name='product_type']").change(function () {

            change_product_type();

        });

//Load Product Attributes Function Code Starts

        function load_product_attributes() {

            $.ajax({

                method: "POST",

                url: "variable_product/load_product_attributes.php",

                data: {random_id: <?php echo $random_id; ?> },

                success: function (data) {

                    $("table tbody").html(data);

                    $("table").removeClass("wait-loader");

                }

            });

        }

//Load Product Attributes Function Code Ends

//Insert New Product Attribute Code Starts

        $("#insert_attribute_form").submit(function (event) {

            event.preventDefault();

            $("table").addClass("wait-loader");

            $.ajax({

                method: "POST",

                url: "variable_product/insert_product_attribute.php",

                data: $('#insert_attribute_form').serialize() + "&random_id=<?php echo $random_id; ?>",

                success: function () {

                    $("#insert_attribute_form").find("input[type=text],textarea").val("");

                    load_product_attributes();

                }

            });

        });

//Insert New Product Attribute Code Ends

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

//Load Product Variations Default Form Values Function Code Starts

        function load_variations_default_form_values() {

            $.ajax({

                method: "POST",

                url: "variable_product/load_default_form_values.php",

                data: {random_id: <?php echo $random_id; ?> },

                success: function (data) {

                    $("#default_form_values").html(data);

                }

            });

        }

//Load Product Variations Default Form Values Function Code Ends

//Load Product Variations Function Code Starts

        function load_product_variations() {

            $.ajax({

                method: "POST",

                url: "variable_product/load_product_variations.php",

                data: {random_id: <?php echo $random_id; ?> },

                success: function (data) {

                    $(".product-variations-div").html(data);

                    $(".product-variations-div").removeClass("wait-loader");

                }

            });

        }

//Load Product Variations Function Code Ends

//Click Product Variations Tab Code Starts

        $("a[href='#product_variations']").click(function () {

            $(".product-variations-div").addClass("wait-loader");

            load_variations_default_form_values();

            load_product_variations();

        });

//Click Product Variations Tab Code Ends

//Save Update Product Variations Function Code Starts

        function save_update_product_variations() {

            var form = document.getElementById("product-variations-form");

            var form_data = new FormData(form);

            form_data.append("random_id", <?php echo $random_id; ?>);

            $.ajax({

                method: "POST",

                url: "variable_product/update_all_variations.php",

                data: form_data,

                contentType: false,

                cache: false,

                processData: false,

                success: function (data) {

                    $(".ajax-response-div").html(data);

                    $(".product-variations-div").removeClass("wait-loader");

                }

            });


        }

//Save Update Product Variations Function Code Ends

//Product Variations Actions Go Button Code Starts

        $("#go_button").click(function () {

            var action_select = $("#action_select").val();

            if (action_select == "add_variation") {

                $(".product-variations-div").addClass("wait-loader");

                save_update_product_variations();

                $(".product-variations-div").addClass("wait-loader");

                $.ajax({

                    method: "POST",

                    url: "variable_product/insert_product_variation.php",

                    data: {random_id: <?php echo $random_id; ?> },

                    success: function () {

                        load_product_variations();

                    }

                });

            } else if (action_select == "create_variations_from_attributes") {

                var confirm_action = confirm("Are you sure you want to link all variations? This will create a new variation for each and every possible combination of variation attributes (max 50 per run).");

                if (confirm_action == true) {

                    $(".product-variations-div").addClass("wait-loader");

                    save_update_product_variations();

                    $(".product-variations-div").addClass("wait-loader");

                    $.ajax({

                        method: "POST",

                        url: "variable_product/create_variations_from_attributes.php",

                        data: {random_id: <?php echo $random_id; ?> },

                        success: function (data) {

                            $(".ajax-response-div").html(data);

                            load_product_variations();

                            load_variations_default_form_values();

                        }

                    });

                }

            } else if (action_select == "delete_all_variations") {

                var confirm_action = confirm("Are you sure you want to delete all variations? This cannot be undone.");

                if (confirm_action == true) {

                    $(".product-variations-div").addClass("wait-loader");

                    $.ajax({

                        method: "POST",

                        url: "variable_product/delete_all_variations.php",

                        data: {random_id: <?php echo $random_id; ?> },

                        success: function () {

                            load_product_variations();

                            load_variations_default_form_values();

                        }

                    });

                }

            }

        });

//Product Variations Actions Go Button Code Ends

//Save Update Submit From Of Product Variations Code Starts

        $("#product-variations-form").submit(function (event) {

            event.preventDefault();

            $(".product-variations-div").addClass("wait-loader");

            save_update_product_variations();

            load_variations_default_form_values();

        });

//Save Update Submit From Of Product Variations Code Ends

    });

</script>

<?php

if (isset($_POST['submit'])) {

  $product_title = mysqli_real_escape_string($con, $_POST['product_title']);

  $product_seo_desc = mysqli_real_escape_string($con, $_POST['product_seo_desc']);

  $product_url = mysqli_real_escape_string($con, $_POST['product_url']);

  $product_cat = mysqli_real_escape_string($con, $_POST['product_cat']);

  $cat = mysqli_real_escape_string($con, $_POST['cat']);

  $manufacturer_id = mysqli_real_escape_string($con, $_POST['manufacturer']);

  $product_price = mysqli_real_escape_string($con, $_POST['product_price']);

  $product_desc = mysqli_real_escape_string($con, $_POST['product_desc']);

  $product_keywords = mysqli_real_escape_string($con, $_POST['product_keywords']);

  $psp_price = mysqli_real_escape_string($con, $_POST['psp_price']);

  $product_label = mysqli_real_escape_string($con, $_POST['product_label']);

  $product_type = mysqli_real_escape_string($con, $_POST['product_type']);

  $product_features = mysqli_real_escape_string($con, $_POST['product_features']);

  $product_video = mysqli_real_escape_string($con, $_POST['product_video']);

  $product_weight = mysqli_real_escape_string($con, $_POST['product_weight']);

  $stock_status = mysqli_real_escape_string($con, $_POST['stock_status']);

  $enable_stock = mysqli_real_escape_string($con, $_POST['enable_stock']);

  $stock_quantity = mysqli_real_escape_string($con, $_POST['stock_quantity']);

  $allow_backorders = mysqli_real_escape_string($con, $_POST['allow_backorders']);

  $status = "product";

  $product_img1 = $_FILES['product_img1']['name'];
  $product_img2 = $_FILES['product_img2']['name'];
  $product_img3 = $_FILES['product_img3']['name'];

  $temp_name1 = $_FILES['product_img1']['tmp_name'];
  $temp_name2 = $_FILES['product_img2']['tmp_name'];
  $temp_name3 = $_FILES['product_img3']['tmp_name'];

  $allowed = array('jpeg', 'jpg', 'gif', 'png');

  $product_img1_extension = pathinfo($product_img1, PATHINFO_EXTENSION);

  $product_img2_extension = pathinfo($product_img2, PATHINFO_EXTENSION);

  $product_img3_extension = pathinfo($product_img3, PATHINFO_EXTENSION);

  if (!in_array($product_img1_extension, $allowed)) {

    echo "<script> alert('Your Product Image 1 File Extension Is Not Supported.'); </script>";

    $product_img1 = "";

  } else {

    move_uploaded_file($temp_name1, "../admin_area/product_images/$product_img1");

  }

  if (!empty($product_img2)) {

    if (!in_array($product_img2_extension, $allowed)) {

      echo "<script> alert('Your Product Image 2 File Extension Is Not Supported.'); </script>";

      $product_img2 = "";

    } else {

      move_uploaded_file($temp_name2, "../admin_area/product_images/$product_img2");

    }

  }

  if (!empty($product_img3)) {

    if (!in_array($product_img3_extension, $allowed)) {

      echo "<script> alert('Your Product Image 3 File Extension Is Not Supported.'); </script>";

      $product_img3 = "";

    } else {

      move_uploaded_file($temp_name3, "../admin_area/product_images/$product_img3");

    }

  }

  $insert_product = "insert into products (vendor_id,p_cat_id,cat_id,manufacturer_id,date,product_title,product_seo_desc,product_url,product_img1,product_img2,product_img3,product_price,product_psp_price,product_desc,product_features,product_video,product_keywords,product_label,product_type,product_weight,product_vendor_status,status) values ('$customer_id','$product_cat','$cat','$manufacturer_id',NOW(),'$product_title','$product_seo_desc','$product_url','$product_img1','$product_img2','$product_img3','$product_price','$psp_price','$product_desc','$product_features','$product_video','$product_keywords','$product_label','$product_type','$product_weight','$product_vendor_status','$status')";

  $run_product = mysqli_query($con, $insert_product);

  $product_id = mysqli_insert_id($con);

  if ($run_product) {

    if ($product_type != "variable_product") {

      if ($enable_stock == "yes" and $stock_quantity > 0) {

        $stock_status = "instock";

      } elseif ($enable_stock == "yes" and $allow_backorders == "no" and $stock_quantity < 1) {

        $stock_status = "outofstock";

      } elseif ($enable_stock == "yes" and ($allow_backorders == "yes" or $allow_backorders == "notify") and $stock_quantity < 1) {

        $stock_status = "onbackorder";

      }

      $insert_product_stock = "insert into products_stock (product_id,enable_stock,stock_status,stock_quantity,allow_backorders) values ('$product_id','$enable_stock','$stock_status','$stock_quantity','$allow_backorders')";

      $run_insert_product_stock = mysqli_query($con, $insert_product_stock);

    }

    $update_product_stock = "update products_stock set product_id='$product_id' where product_id='$random_id'";

    $run_update_product_stock = mysqli_query($con, $update_product_stock);

    $update_product_attributes = "update product_attributes set product_id='$product_id' where product_id='$random_id'";

    $run_product_attributes = mysqli_query($con, $update_product_attributes);

    $update_product_variations = "update product_variations set product_id='$product_id' where product_id='$random_id'";

    $run_product_variations = mysqli_query($con, $update_product_variations);

    echo "

<script>

alert(' Your Product Has Been Inserted Successfully. ');

window.open('index.php?products','_self');

</script>

";

  }

}

?>
