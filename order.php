<?php
session_start();
include("includes/db.php");
include("functions/functions.php");
?>

<?php
if (isset($_SESSION["customer_email"])) {
  $customer_email = $_SESSION['customer_email'];
  $get_customer = "select * from customers where customer_email='$customer_email'";
  $run_customer = mysqli_query($con, $get_customer);
  $row_customer = mysqli_fetch_array($run_customer);
  $customer_id = $row_customer['customer_id'];
  $select_payment_settings = "select * from payment_settings";
  $run_payment_setttings = mysqli_query($con, $select_payment_settings);
  $row_payment_settings = mysqli_fetch_array($run_payment_setttings);
  $commission_percentage = $row_payment_settings['commission_percentage'];

  function get_percentage_amount($amount, $percentage)
  {
    $calculate_percentage = ($percentage / 100) * $amount;
    return $amount - $calculate_percentage;
  }
  $physical_products = array();
  $vendors_ids = array();
  $vendors_total = array();
  $ip_add = getRealUserIp();
  $select_cart = "select * from cart where ip_add='$ip_add'";
  $run_cart = mysqli_query($con, $select_cart);
  while ($row_cart = mysqli_fetch_array($run_cart)) {
    $product_id = $row_cart['p_id'];
    $product_qty = $row_cart['qty'];
    $product_price = $row_cart['p_price'];
    $product_type = $row_cart['product_type'];
    $sub_total = $product_price * $product_qty;
    $get_product = "select * from products where product_id='$product_id'";
    $run_product = mysqli_query($con, $get_product);
    $row_product = mysqli_fetch_array($run_product);
    $vendor_id = $row_product['vendor_id'];
    if ($product_type == "physical_product") {
      if (!isset($physical_products[$vendor_id])) {
        $physical_products[$vendor_id] = array();
      }
      array_push($physical_products[$vendor_id], $product_id);
    }
    if (!empty($vendor_id)) {
      if (!in_array($vendor_id, $vendors_ids)) {
        array_push($vendors_ids, $vendor_id);
      }
      @$vendors_total["$vendor_id"] += $sub_total;
    }
  }
  $amount = $_POST['amount'];
  if (count($physical_products) > 0) {
    $shipping_types = $_SESSION['shipping_types'];
    $shipping_cost = $_SESSION['shipping_cost'];
    $is_shipping_address = $_SESSION['is_shipping_address'];
    if (count($physical_products) == 1) {
      foreach ($vendors_ids as $vendor_id) {
        if (isset($physical_products[$vendor_id])) {
          $shipping_type = $shipping_types["$vendor_id"]["shipping_type"];
        }
      }
      $select_shipping_type = "select * from shipping_type where type_id='$shipping_type'";
      $run_shipping_type = mysqli_query($con, $select_shipping_type);
      $row_shipping_type = mysqli_fetch_array($run_shipping_type);
      $shipping_type_name = $row_shipping_type['type_name'];
    } else {
      $shipping_type_name = "";
    }
  }
  $get_customers_addresses = "select * from customers_addresses where customer_id='$customer_id'";
  $run_customers_addresses = mysqli_query($con, $get_customers_addresses);
  $row_addresses = mysqli_fetch_array($run_customers_addresses);
  $billing_first_name = $row_addresses["billing_first_name"];
  $billing_last_name = $row_addresses["billing_last_name"];
  $billing_country = $row_addresses["billing_country"];
  $billing_address_1 = $row_addresses["billing_address_1"];
  $billing_address_2 = $row_addresses["billing_address_2"];
  $billing_state = $row_addresses["billing_state"];
  $billing_city = $row_addresses["billing_city"];
  $billing_postcode = $row_addresses["billing_postcode"];
  $shipping_first_name = $row_addresses["shipping_first_name"];
  $shipping_last_name = $row_addresses["shipping_last_name"];
  $shipping_country = $row_addresses["shipping_country"];
  $shipping_address_1 = $row_addresses["shipping_address_1"];
  $shipping_address_2 = $row_addresses["shipping_address_2"];
  $shipping_state = $row_addresses["shipping_state"];
  $shipping_city = $row_addresses["shipping_city"];
  $shipping_postcode = $row_addresses["shipping_postcode"];
  date_default_timezone_set("Asia/Karachi");
  $order_date = date("F d, Y h:i");
  $payment_method = "pay offline";
  $status = "pending";
  $invoice_no = mt_rand();
  if (isset($_SESSION["order_note"])) {
    $order_note = $_SESSION["order_note"];
  } else {
    $order_note = "";
  }
  if (count($physical_products) > 0) {
    $insert_order = "insert into orders (customer_id,invoice_no,shipping_type,shipping_cost,payment_method,order_date,order_total,order_note,order_status) values ('$customer_id','$invoice_no','$shipping_type_name','$shipping_cost','$payment_method','$order_date','$amount','$order_note','$status')";
  } else {
    $insert_order = "insert into orders (customer_id,invoice_no,shipping_type,shipping_cost,payment_method,order_date,order_total,order_note,order_status) values ('$customer_id','$invoice_no','','','$payment_method','$order_date','$amount','$order_note','$status')";
  }
  $run_order = mysqli_query($con, $insert_order);
  $insert_order_id = mysqli_insert_id($con);
  if ($run_order) {
    foreach ($vendors_ids as $vendor_id) {
      if (strpos($vendor_id, "admin_") !== false) {
      } else {
      }
      if (isset($shipping_types["$vendor_id"])) {
        $shipping_type = $shipping_types["$vendor_id"]["shipping_type"];
        $shipping_cost = $shipping_types["$vendor_id"]["shipping_cost"];
        $select_shipping_type = "select * from shipping_type where type_id='$shipping_type'";
        $run_shipping_type = mysqli_query($con, $select_shipping_type);
        $row_shipping_type = mysqli_fetch_array($run_shipping_type);
        $shipping_type_name = $row_shipping_type['type_name'];
      } else {
        $shipping_cost = 0;
        $shipping_type_name = "";
      }
      $invoice_no = mt_rand();
      $order_total = $vendors_total["$vendor_id"] + $shipping_cost;
      $net_amount = get_percentage_amount($vendors_total["$vendor_id"], $commission_percentage);
      $insert_vendor_order = "insert into vendor_orders (order_id,vendor_id,invoice_no,shipping_type,shipping_cost,order_total,net_amount,order_status) values ('$insert_order_id','$vendor_id','$invoice_no','$shipping_type_name','$shipping_cost','$order_total','$net_amount','$status')";
      $run_vendor_order = mysqli_query($con, $insert_vendor_order);
    }
    if (count($physical_products) > 0) {
      if ($is_shipping_address == "yes") {
        $insert_orders_addresses = "insert into orders_addresses (order_id,billing_first_name,billing_last_name,billing_country,billing_address_1,billing_address_2,billing_state,billing_city,billing_postcode,is_shipping_address) values ('$insert_order_id','$billing_first_name','$billing_last_name','$billing_country','$billing_address_1','$billing_address_2','$billing_state','$billing_city','$billing_postcode','yes')";
      } elseif ($is_shipping_address == "no") {
        $insert_orders_addresses = "insert into orders_addresses (order_id,billing_first_name,billing_last_name,billing_country,billing_address_1,billing_address_2,billing_state,billing_city,billing_postcode,is_shipping_address,shipping_first_name,shipping_last_name,shipping_country,shipping_address_1,shipping_address_2,shipping_state,shipping_city,shipping_postcode) values ('$insert_order_id','$billing_first_name','$billing_last_name','$billing_country','$billing_address_1','$billing_address_2','$billing_state','$billing_city','$billing_postcode','no','$shipping_first_name','$shipping_last_name','$shipping_country','$shipping_address_1','$shipping_address_2','$shipping_state','$shipping_city','$shipping_postcode')";
      }

    } else {

      $insert_orders_addresses = "insert into orders_addresses (order_id,billing_first_name,billing_last_name,billing_country,billing_address_1,billing_address_2,billing_state,billing_city,billing_postcode,is_shipping_address) values ('$insert_order_id','$billing_first_name','$billing_last_name','$billing_country','$billing_address_1','$billing_address_2','$billing_state','$billing_city','$billing_postcode','none')";

    }

    $run_orders_addresses = mysqli_query($con, $insert_orders_addresses);

    if ($run_orders_addresses) {

      $select_cart = "select * from cart where ip_add='$ip_add'";

      $run_cart = mysqli_query($con, $select_cart);

      while ($row_cart = mysqli_fetch_array($run_cart)) {

        $cart_id = $row_cart['cart_id'];

        $pro_id = $row_cart['p_id'];

        $pro_price = $row_cart['p_price'];

        $pro_qty = $row_cart['qty'];

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

        $stock_quantity = $row_product_stock["stock_quantity"];

        $update_stock_quantity = $stock_quantity - $pro_qty;

        $allow_backorders = $row_product_stock["allow_backorders"];

        if ($enable_stock == "yes" and $update_stock_quantity > 0) {

          $stock_status = "instock";

        } elseif ($enable_stock == "yes" and $allow_backorders == "no" and $update_stock_quantity < 1) {

          $stock_status = "outofstock";

        } elseif ($enable_stock == "yes" and ($allow_backorders == "yes" or $allow_backorders == "notify") and $update_stock_quantity < 1) {

          $stock_status = "onbackorder";

        }

        if ($enable_stock == "yes") {

          if ($count_cart_meta == 0) {

            $update_product_stock = "update products_stock set stock_status='$stock_status',stock_quantity='$update_stock_quantity' where product_id='$pro_id'";

            $run_update_product_stock = mysqli_query($con, $update_product_stock);

          } else {

            $update_product_stock = "update products_stock set stock_status='$stock_status',stock_quantity='$update_stock_quantity' where product_id='$pro_id' and variation_id='$variation_id'";

            $run_update_product_stock = mysqli_query($con, $update_product_stock);

          }

        }

        $insert_order_item = "insert into orders_items (order_id,product_id,price,qty) values ('$insert_order_id','$pro_id','$pro_price','$pro_qty')";

        $run_order_item = mysqli_query($con, $insert_order_item);

        $item_id = mysqli_insert_id($con);

        $select_cart_meta = "select * from cart_meta where ip_add='$ip_add' and cart_id='$cart_id'";

        $run_cart_meta = mysqli_query($con, $select_cart_meta);

        while ($row_cart_meta = mysqli_fetch_array($run_cart_meta)) {

          $product_id = $row_cart_meta["product_id"];

          $meta_key = $row_cart_meta["meta_key"];

          $meta_value = $row_cart_meta["meta_value"];

          $insert_orders_items_meta = "insert into orders_items_meta (order_id,item_id,product_id,meta_key,meta_value) values ('$insert_order_id','$item_id','$product_id','$meta_key','$meta_value')";

          $run_orders_items_meta = mysqli_query($con, $insert_orders_items_meta);

        }

        $note_content = "stock reduced from $stock_quantity to $update_stock_quantity.";

        $insert_order_note = "insert into orders_notes (order_id,item_id,note_content,note_date) values ('$insert_order_id','$item_id','$note_content','$order_date')";

        $run_order_note = mysqli_query($con, $insert_order_note);

      }

      $delete_cart = "delete from cart where ip_add='$ip_add'";

      $run_cart_delete = mysqli_query($con, $delete_cart);

      if ($run_cart_delete) {

        $delete_cart_meta = "delete from cart_meta where ip_add='$ip_add'";

        $run_delete_cart_meta = mysqli_query($con, $delete_cart_meta);

        if ($run_delete_cart_meta) {

          echo "<script>alert('Your Order Has Been Submitted Successfully, Thanks.');window.open('order_received.php?order_id=$insert_order_id','_self');</script>";
        }
      }
    }
  }
}
?>