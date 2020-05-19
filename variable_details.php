<?php session_start();
include("includes/db.php");
include("functions/functions.php");
?>

<?php
$get_product = "select * from products where product_id='34'";
$run_product = mysqli_query($con, $get_product);
$check_product = mysqli_num_rows($run_product);
if ($check_product == 0) {
  echo "<script> window.open('index.php','_self') </script>";
} else {
  $row_product = mysqli_fetch_array($run_product);
  $p_cat_id = $row_product['p_cat_id'];
  $pro_id = $row_product['product_id'];
  $pro_title = $row_product['product_title'];
  $pro_price = $row_product['product_price'];
  $pro_desc = $row_product['product_desc'];
  $pro_img1 = $row_product['product_img1'];
  $pro_img2 = $row_product['product_img2'];
  $pro_img3 = $row_product['product_img3'];
  $pro_label = $row_product['product_label'];
  $pro_psp_price = $row_product['product_psp_price'];
  $pro_features = $row_product['product_features'];
  $pro_video = $row_product['product_video'];
  $status = $row_product['status'];
  $pro_url = $row_product['product_url'];
  $pro_type = $row_product['product_type'];

  if ($pro_label == "") {
    $product_label = "";
  } else {
    $product_label = "<a class='label sale' href='#' style='color:black;'>
            <div class='thelabel'>$pro_label</div>   
            <div class='label-background'> </div></a>";
  }

  $pro_keywords = $row_product['product_keywords'];
  $pro_seo_desc = $row_product['product_seo_desc'];
  $vendor_id = $row_product['vendor_id'];
  if (strpos($vendor_id, "admin_") !== false) {
    $admin_id = trim($vendor_id, "admin_");
    $select_admin = "select * from admins where admin_id='$admin_id'";
    $run_admin = mysqli_query($con, $select_admin);
    $row_admin = mysqli_fetch_array($run_admin);
    $vendor_name = $row_admin['admin_name'];
  } else {
    $select_customer = "select * from customers where customer_id='$vendor_id'";
    $run_customer = mysqli_query($con, $select_customer);
    $row_customer = mysqli_fetch_array($run_customer);
    $vendor_name = $row_customer['customer_name'];
  }
  $get_p_cat = "select * from product_categories where p_cat_id='$p_cat_id'";
  $run_p_cat = mysqli_query($con, $get_p_cat);
  $row_p_cat = mysqli_fetch_array($run_p_cat);
  $p_cat_title = $row_p_cat['p_cat_title'];
  ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title> <?php echo $pro_title; ?> </title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="<?php echo $pro_seo_desc; ?>">
        <meta name="keywords" content="<?php echo $pro_keywords; ?>">
        <meta name="author" content="<?php echo $vendor_name; ?>">
        <link href="http://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
        <link href="styles/bootstrap.min.css" rel="stylesheet">
        <link href="styles/style.css" rel="stylesheet">
        <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <script src="js/jquery.min.js"></script>
    </head>
    <body>
    <div id="content"><!-- content Starts -->
        <div class="container"><!-- container Starts -->
            <br><br><br><br>
            <div class="col-md-12"><!-- col-md-12 Starts -->
                <div class="row" id="productMain"><!-- row Starts -->
                    <div class="col-sm-12"><!-- col-sm-6 Starts -->
                        <div class="box"><!-- box Starts -->
                            <form action="" method="post" class="form-horizontal"><!-- form-horizontal Starts -->
                              <?php if ($pro_type == "variable_product"){ ?>
                                <div id="variable-product-div"><!-- variable-product-div Starts -->
                                  <?php
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
                                      $select_default_variations_meta = "select * from variations_meta where variation_id='$default_variation_id'";
                                      $run_default_variations_meta = mysqli_query($con, $select_default_variations_meta);
                                      $i = 0;
                                      while ($row_default_variations_meta = mysqli_fetch_array($run_default_variations_meta)) {
                                        $meta_key = $row_default_variations_meta["meta_key"];
                                        $meta_value = $row_default_variations_meta["meta_value"];
                                        $default_attributes[$meta_key] = $meta_value;
                                        if (!empty($meta_value)) {
                                          $i++;
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
                                    $select_product_attributes = "select * from product_attributes where product_id='$pro_id'";
                                    $run_product_attributes = mysqli_query($con, $select_product_attributes);
                                    while ($row_product_attributes = mysqli_fetch_array($run_product_attributes)) {
                                      $attribute_i++;
                                      $attribute_name = $row_product_attributes["attribute_name"];
                                      $meta_key = strtolower($attribute_name);
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
                                      ?>
                                        <div class="form-group"><!-- form-group Starts -->
                                            <label class="col-lg-4 col-md-3 control-label"> <?php echo $attribute_name; ?> </label>
                                            <div class="col-lg-6 col-md-9"><!-- col-lg-6 col-md-9 Starts -->
                                                <select name="<?php echo $meta_key; ?>"
                                                        class="form-control attribute-select" required>
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
                                  }
                                  ?>
                                  <?php if ($array_end) { ?>
                                      <input type="hidden" name="variation_id"
                                             value="<?php echo $current_variation_id; ?>">
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
                                              $meta_key = strtolower($row_product_attributes["attribute_name"]);
                                              ?>
                                                if ($("select[name='<?php echo $meta_key; ?>']").val() != "") {

                                                    var i = i + 1;

                                                    product_attributes[i] = {};

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

                                              // change_variation_id ajax post request code Starts

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

                                           // change_variation_id ajax post request code Ends

                                            });

                                        });

                                    </script>

                                  <?php } ?>

                                    <div class="form-group"><!-- form-group Starts -->

                                      <?php if ($status == "product") { ?>

                                          <label class="col-lg-4 col-md-3 control-label">Product Quantity </label>

                                      <?php } elseif ($status == "bundle") { ?>

                                          <label class="col-lg-4 col-md-3 control-label">Bundle Quantity </label>

                                      <?php } ?>

                                        <div class="col-lg-6 col-md-9"><!-- col-lg-6 col-md-9 Starts -->

                                            <input type="number" name="product_qty" class="form-control" value="1"
                                                   min="1" max="10" required>

                                        </div><!-- col-lg-6 col-md-9 Ends -->

                                    </div><!-- form-group Ends -->

                                  <?php

                                  if ($pro_type == "physical_product" or $pro_type == "digital_product") {

                                    if ($status == "product") {

                                      if ($pro_psp_price == 0) {

                                        echo "<p class='price'>Product Price : Rs:$pro_price</p>";
                                      } else {
                                        echo "<p class='price'>Product Price : <del> Rs:$pro_price </del><br>Product Sale Price : $$pro_psp_price</p>";
                                      }

                                    } elseif ($status == "bundle") {

                                      if ($pro_psp_price == 0) {

                                        echo "<p class='price'>Bundle Price : Rs:$pro_price</p>";
                                      } else {
                                        echo "<p class='price'>Bundle Price : <del> Rs:$pro_price </del><br>Bundle Sale Price : Rs:$pro_psp_price</p>";
                                      }

                                    }

                                  } elseif ($pro_type == "variable_product") {

                                    if ($array_end) {

                                      foreach ($default_attributes as $attribute) {

                                        if (empty($attribute)) {

                                          $all_attributes_selected = false;
                                          break;

                                        } else {

                                          $all_attributes_selected = true;

                                        }

                                      }

                                      if ($all_attributes_selected) {

                                        $select_product_variation = "select * from product_variations where product_id='$pro_id' and variation_id='$current_variation_id'";

                                        $run_product_variation = mysqli_query($con, $select_product_variation);

                                        $row_product_variation = mysqli_fetch_array($run_product_variation);

                                        $variation_product_img1 = $row_product_variation["product_img1"];

                                        $variation_product_price = $row_product_variation["product_price"];

                                        $variation_product_psp_price = $row_product_variation["product_psp_price"];

                                        if ($status == "product") {

                                          if ($variation_product_psp_price == 0) {

                                            echo "<p class='price'>Product Price : Rs:$variation_product_price</p>";
                                          } else {
                                            echo "<p class='price'>Product Price : <del> Rs:$variation_product_price </del><br>Product Sale Price : Rs:$variation_product_psp_price</p>";
                                          }
                                        } elseif ($status == "bundle") {
                                          if ($pro_psp_price == 0) {
                                            echo "<p class='price'>Bundle Price : Rs:$pro_price</p>";
                                          } else {
                                            echo "<p class='price'>Bundle Price : <del> Rs:$pro_price </del><br>Bundle Sale Price : Rs:$pro_psp_price</p>";
                                          }
                                        }
                                      }
                                    }
                                  }
                                  ?>

                                  <?php if ($pro_type == "variable_product"){ ?>
                                </div><!-- variable-product-div Ends -->
                            <?php } ?>
                                <p class="text-center buttons"><!-- text-center buttons Starts -->
                                    <button class="btn btn-primary" type="submit" name="add_cart">
                                        <i class="fa fa-shopping-cart"></i> Add to Cart
                                    </button>
                                    <button class="btn btn-primary" type="submit" name="add_wishlist">
                                        <i class="fa fa-heart"></i> Add to Wishlist
                                    </button>
                                  <?php
                                  if (isset($_POST['add_cart'])) {
                                    $ip_add = getRealUserIp();
                                    $p_id = $pro_id;
                                    $product_qty = $_POST['product_qty'];
                                    $check_product = "select * from cart where ip_add='$ip_add' AND p_id='$p_id'";
                                    $run_check = mysqli_query($con, $check_product);
                                    if (mysqli_num_rows($run_check) > 0) {
                                      echo "<script>alert('This Product is already added in cart')</script>";
                                      echo "<script>window.open('$pro_url','_self')</script>";
                                    } else {
                                      $select_price = "select * from products where product_id='$p_id'";
                                      $run_price = mysqli_query($con, $select_price);
                                      $row_price = mysqli_fetch_array($run_price);
                                      $pro_price = $row_price['product_price'];
                                      $pro_psp_price = $row_price['product_psp_price'];
                                      $pro_label = $row_price['product_label'];
                                      if ($pro_psp_price != 0) {
                                        $product_price = $pro_psp_price;
                                      } else {
                                        $product_price = $pro_price;
                                      }
                                      if ($pro_type == "physical_product" or $pro_type == "digital_product") {
                                        $query = "insert into cart (p_id,ip_add,qty,p_price) values ('$p_id','$ip_add','$product_qty','$product_price')";
                                        $run_query = mysqli_query($db, $query);
                                      } elseif ($pro_type == "variable_product") {
                                        $select_product_variations = "select * from product_variations where product_id='$pro_id' and not product_type='default_attributes_variation'";
                                        $run_product_variations = mysqli_query($con, $select_product_variations);
                                        $count_product_variations = mysqli_num_rows($run_product_variations);
                                        if ($count_product_variations != 0) {
                                          $select_product_attributes = "select * from product_attributes where product_id='$p_id'";
                                          $run_product_attributes = mysqli_query($con, $select_product_attributes);
                                          while ($row_product_attributes = mysqli_fetch_array($run_product_attributes)) {
                                            $attribute_name = $row_product_attributes["attribute_name"];
                                            $meta_key = strtolower($attribute_name);
                                            $meta_value = $_POST[$meta_key];
                                            $insert_cart_meta = "insert into cart_meta (ip_add,product_id,meta_key,meta_value) values ('$ip_add','$p_id','$meta_key','$meta_value')";
                                            $run_cart_meta = mysqli_query($con, $insert_cart_meta);
                                          }
                                        }
                                      }
                                      echo "<script>window.open('$pro_url','_self')</script>";
                                    }
                                  }
                                  if (isset($_POST['add_wishlist'])) {
                                    if (!isset($_SESSION['customer_email'])) {
                                      echo "<script>alert('You Must Login To Add Product In Wishlist')</script>";
                                      echo "<script>window.open('checkout.php','_self')</script>";
                                    } else {
                                      $customer_session = $_SESSION['customer_email'];
                                      $get_customer = "select * from customers where customer_email='$customer_session'";
                                      $run_customer = mysqli_query($con, $get_customer);
                                      $row_customer = mysqli_fetch_array($run_customer);
                                      $customer_id = $row_customer['customer_id'];
                                      $select_wishlist = "select * from wishlist where customer_id='$customer_id' AND product_id='$pro_id'";
                                      $run_wishlist = mysqli_query($con, $select_wishlist);
                                      $check_wishlist = mysqli_num_rows($run_wishlist);
                                      if ($check_wishlist == 1) {
                                        echo "<script>alert('This Product Has Been already Added In Wishlist')</script>";
                                        echo "<script>window.open('$pro_url','_self')</script>";
                                      } else {
                                        $insert_wishlist = "insert into wishlist (customer_id,product_id) values ('$customer_id','$pro_id')";
                                        $run_wishlist = mysqli_query($con, $insert_wishlist);
                                        if ($run_wishlist) {
                                          echo "<script> alert('Product Has Inserted Into Wishlist') </script>";
                                          echo "<script>window.open('$pro_url','_self')</script>";
                                        }
                                      }
                                    }
                                  }
                                  ?>
                                </p><!-- text-center buttons Ends -->
                            </form><!-- form-horizontal Ends -->
                        </div><!-- box Ends -->
                    </div><!-- col-sm-6 Ends -->
                </div><!-- row Ends -->
            </div><!-- col-md-12 Ends -->
        </div><!-- container Ends -->
    </div><!-- content Ends -->
    <?php include("includes/footer.php"); ?>
    <script src="js/bootstrap.min.js"></script>
    </body>
    </html>
<?php } ?>