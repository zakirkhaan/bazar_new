<?php
session_start();
include("includes/db.php");
include("functions/functions.php");
?>

<?php

$ip_add = getRealUserIp();

$total = 0;

$physical_products = array();

$select_cart = "select * from cart where ip_add='$ip_add'";

$run_cart = mysqli_query($con, $select_cart);

while ($row_cart = mysqli_fetch_array($run_cart)) {

  $cart_id = $row_cart['cart_id'];

  $pro_id = $row_cart['p_id'];

  $pro_qty = $row_cart['qty'];

  $only_price = $row_cart['p_price'];

  $product_weight = $row_cart['product_weight'];

  $product_type = $row_cart['product_type'];

  $get_products = "select * from products where product_id='$pro_id'";

  $run_products = mysqli_query($con, $get_products);

  while ($row_products = mysqli_fetch_array($run_products)) {

    $vendor_id = $row_products['vendor_id'];

    $product_url = $row_products['product_url'];

    $product_title = $row_products['product_title'];

    $product_img1 = $row_products['product_img1'];

    if ($product_type == "physical_product") {

      array_push($physical_products, $pro_id);

    }

    $sub_total = $only_price * $pro_qty;

    $_SESSION['pro_qty'] = $pro_qty;

    $total += $sub_total;

    $sub_total_weight = $product_weight * $pro_qty;

    if (strpos($vendor_id, "admin_") !== false) {

      $admin_id = trim($vendor_id, "admin_");

      $get_admin = "select * from admins where admin_id='$admin_id'";

      $run_admin = mysqli_query($con, $get_admin);

      $row_admin = mysqli_fetch_array($run_admin);

      $vendor_name = $row_admin['admin_name'];

    } else {

      $get_customer = "select * from customers where customer_id='$vendor_id'";

      $run_customer = mysqli_query($con, $get_customer);

      $row_customer = mysqli_fetch_array($run_customer);

      $vendor_name = $row_customer['customer_name'];

    }

    $select_cart_meta = "select * from cart_meta where ip_add='$ip_add' and cart_id='$cart_id' and product_id='$pro_id' and meta_key='variation_id'";

    $run_cart_meta = mysqli_query($con, $select_cart_meta);

    $count_cart_meta = mysqli_num_rows($run_cart_meta);

    if ($count_cart_meta == 0) {

      $select_product_stock = "select * from products_stock where product_id='$pro_id'";

    } else {

      $row_cart_meta = mysqli_fetch_array($run_cart_meta);

      $variation_id = str_replace("#", "", $row_cart_meta["meta_value"]);

      $select_product_stock = "select * from products_stock where product_id='$pro_id' and variation_id='$variation_id'";

    }

    $run_product_stock = mysqli_query($con, $select_product_stock);

    $row_product_stock = mysqli_fetch_array($run_product_stock);

    $enable_stock = $row_product_stock["enable_stock"];

    $stock_status = $row_product_stock["stock_status"];

    $stock_quantity = $row_product_stock["stock_quantity"];

    $allow_backorders = $row_product_stock["allow_backorders"];

    ?>

      <tr><!-- tr Starts -->

          <td>

              <img src="admin_area/product_images/<?php echo $product_img1; ?>">

          </td>

          <td width="350">

              <a href="<?php echo $product_url; ?>" target="blank" class="bold"> <?php echo $product_title; ?> </a>

              <p class="cart-product-meta">

                <?php

                $cart_meta = "";

                $select_cart_meta = "select * from cart_meta where ip_add='$ip_add' and cart_id='$cart_id' and product_id='$pro_id' and not meta_key='variation_id'";

                $run_cart_meta = mysqli_query($con, $select_cart_meta);

                while ($row_cart_meta = mysqli_fetch_array($run_cart_meta)) {

                  $meta_key = ucwords($row_cart_meta["meta_key"]);

                  $meta_value = $row_cart_meta["meta_value"];

                  $cart_meta .= "$meta_key: <span class='text-muted'> $meta_value </span>, ";

                }

                echo rtrim($cart_meta, ", ");

                ?>

              </p>

              <p style="margin-top:8px;"><strong> Vendor : </strong> <?php echo $vendor_name; ?> </p>

          </td>

          <td>

            <?php if ($enable_stock == "yes" and $allow_backorders == "no") { ?>

                <input type="text" name="quantity" value="<?php echo $_SESSION['pro_qty']; ?>"
                       data-cart_id="<?php echo $cart_id; ?>" data-product_id="<?php echo $pro_id; ?>" min="1"
                       max="<?php echo $stock_quantity; ?>" class="quantity form-control">

            <?php } elseif ($enable_stock == "yes" and ($allow_backorders == "yes" or $allow_backorders == "notify")) { ?>

                <input type="text" name="quantity" value="<?php echo $_SESSION['pro_qty']; ?>"
                       data-cart_id="<?php echo $cart_id; ?>" data-product_id="<?php echo $pro_id; ?>" min="1"
                       class="quantity form-control">

            <?php } elseif ($enable_stock == "no") { ?>

                <input type="text" name="quantity" value="<?php echo $_SESSION['pro_qty']; ?>"
                       data-cart_id="<?php echo $cart_id; ?>" data-product_id="<?php echo $pro_id; ?>" min="1"
                       class="quantity form-control">

            <?php } ?>

          </td>

          <td>

              Rs:<?php echo $only_price; ?>.00

          </td>

          <td>
              <input type="checkbox" name="remove[]" value="<?php echo $pro_id; ?>">
          </td>

          <td>

              Rs:<?php echo $sub_total; ?>.00

          </td>

      </tr><!-- tr Ends -->

  <?php }
} ?>