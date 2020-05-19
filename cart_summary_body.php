<?php
session_start();
include("includes/db.php");
include("functions/functions.php");
?>

<?php

$ip_add = getRealUserIp();

$total = 0;

$total_weight = array();

$physical_products = array();

$vendors_ids = array();

$select_cart = "select * from cart where ip_add='$ip_add'";

$run_cart = mysqli_query($con, $select_cart);

while ($row_cart = mysqli_fetch_array($run_cart)) {

  $product_id = $row_cart['p_id'];

  $product_qty = $row_cart['qty'];

  $product_price = $row_cart['p_price'];

  $product_weight = $row_cart['product_weight'];

  $product_type = $row_cart['product_type'];

  $get_products = "select * from products where product_id='$product_id'";

  $run_products = mysqli_query($con, $get_products);

  while ($row_products = mysqli_fetch_array($run_products)) {

    $vendor_id = $row_products['vendor_id'];

    if ($product_type == "physical_product") {

      array_push($physical_products, $product_id);

    }

    if (!empty($vendor_id)) {

      if (!in_array($vendor_id, $vendors_ids)) {

        array_push($vendors_ids, $vendor_id);

      }

    }

    $sub_total_weight = $product_weight * $product_qty;

    @$total_weight[$vendor_id] += $sub_total_weight;

    $sub_total = $product_price * $product_qty;

    $total += $sub_total;

  }

}

?>

<tr>

    <td> Order Subtotal:</td>

    <th> Rs:<?php echo $total; ?>.00</th>

</tr>

<?php if (count($physical_products) > 0) { ?>

    <tr>

        <th colspan="2">
            <p class="shipping-header text-muted"> Cart Total Weight: <?php echo array_sum($total_weight); ?> Kg </p>
            <p class="shipping-header text-muted"><i class="fa fa-truck"></i> Shipping: </p>
            <ul class="shipping-ul-list list-unstyled">

              <?php

              if (isset($_SESSION['customer_email'])) {

                $customer_email = $_SESSION['customer_email'];

                $get_customer = "select * from customers where customer_email='$customer_email'";

                $run_customer = mysqli_query($con, $get_customer);

                $row_customer = mysqli_fetch_array($run_customer);

                $customer_id = $row_customer['customer_id'];

                $get_customers_addresses = "select * from customers_addresses where customer_id='$customer_id'";

                $run_customers_addresses = mysqli_query($con, $get_customers_addresses);

                $row_addresses = mysqli_fetch_array($run_customers_addresses);

                $billing_country = $row_addresses["billing_country"];

                $billing_postcode = $row_addresses["billing_postcode"];

                $shipping_country = $row_addresses["shipping_country"];

                $shipping_postcode = $row_addresses["shipping_postcode"];

                foreach ($vendors_ids as $vendor_id) {

                  $shipping_zone_id = "";

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

                  ?>

                    <div class="shipping-vendor-header"> <?php echo $vendor_name; ?> Shipping:</div>

                  <?php

                  if (@$_SESSION["is_shipping_address"] == "yes") {

                    if (empty($billing_country) and empty($billing_postcode)) {

                      echo "

<li> 

<p> 

There are no shipping types available. Please double check your address, or contact us if you need any help. 

</p> 

</li>

";

                    }

                    $select_zones = "select * from zones where vendor_id='$vendor_id' order by zone_order DESC";

                    $run_zones = mysqli_query($con, $select_zones);

                    while ($row_zones = mysqli_fetch_array($run_zones)) {

                      $zone_id = $row_zones['zone_id'];

                      $select_zone_locations = "select DISTINCT zone_id from zones_locations where zone_id='$zone_id' and (location_code='$billing_country' and location_type='country')";

                      $run_zones_locations = mysqli_query($con, $select_zone_locations);

                      $count_zones_locations = mysqli_num_rows($run_zones_locations);

                      if ($count_zones_locations != "0") {

                        $row_zones_locations = mysqli_fetch_array($run_zones_locations);

                        $zone_id = $row_zones_locations["zone_id"];

                        $select_zone_shipping = "select * from shipping where shipping_zone='$zone_id'";

                        $run_zone_shipping = mysqli_query($con, $select_zone_shipping);

                        $count_zone_shipping = mysqli_num_rows($run_zone_shipping);

                        if ($count_zone_shipping != "0") {

                          $select_zone_postcodes = "select * from zones_locations where zone_id='$zone_id' and location_type='postcode'";

                          $run_zones_postcodes = mysqli_query($con, $select_zone_postcodes);

                          $count_zones_postcodes = mysqli_num_rows($run_zones_postcodes);

                          if ($count_zones_postcodes != "0") {

                            while ($row_zones_postcodes = mysqli_fetch_array($run_zones_postcodes)) {

                              $location_code = $row_zones_postcodes["location_code"];

                              if ($location_code == $billing_postcode) {

                                $shipping_zone_id = $zone_id;

                              }

                            }

                          } else {

                            $shipping_zone_id = $zone_id;

                          }

                        }

                      }

                    }

                  } elseif (@$_SESSION["is_shipping_address"] == "no") {

                    if (empty($shipping_country) and empty($shipping_postcode)) {

                      echo "

<li> 

<p> There are no shipping types available. Please double check your address, or contact us if you need any help. </p> 

</li>

";

                    }

                    $select_zones = "select * from zones where vendor_id='$vendor_id' order by zone_order DESC";

                    $run_zones = mysqli_query($con, $select_zones);

                    while ($row_zones = mysqli_fetch_array($run_zones)) {

                      $zone_id = $row_zones['zone_id'];

                      $select_zone_locations = "select DISTINCT zone_id from zones_locations where zone_id='$zone_id' and (location_code='$shipping_country' and location_type='country')";

                      $run_zones_locations = mysqli_query($con, $select_zone_locations);

                      $count_zones_locations = mysqli_num_rows($run_zones_locations);

                      if ($count_zones_locations != "0") {

                        $row_zones_locations = mysqli_fetch_array($run_zones_locations);

                        $zone_id = $row_zones_locations["zone_id"];

                        $select_zone_shipping = "select * from shipping where shipping_zone='$zone_id'";

                        $run_zone_shipping = mysqli_query($con, $select_zone_shipping);

                        $count_zone_shipping = mysqli_num_rows($run_zone_shipping);

                        if ($count_zone_shipping != "0") {

                          $select_zone_postcodes = "select * from zones_locations where zone_id='$zone_id' and location_type='postcode'";

                          $run_zones_postcodes = mysqli_query($con, $select_zone_postcodes);

                          $count_zones_postcodes = mysqli_num_rows($run_zones_postcodes);

                          if ($count_zones_postcodes != "0") {

                            while ($row_zones_postcodes = mysqli_fetch_array($run_zones_postcodes)) {

                              $location_code = $row_zones_postcodes["location_code"];

                              if ($location_code == $shipping_postcode) {

                                $shipping_zone_id = $zone_id;

                              }

                            }

                          } else {

                            $shipping_zone_id = $zone_id;

                          }

                        }

                      }

                    }

                  } else {

                    if (empty($billing_country) and empty($billing_postcode)) {

                      echo "

<li> 

<p> There are no shipping types available. Please double check your address, or contact us if you need any help. </p> 

</li>

";

                    }

                    $select_zones = "select * from zones where vendor_id='$vendor_id' order by zone_order DESC";

                    $run_zones = mysqli_query($con, $select_zones);

                    while ($row_zones = mysqli_fetch_array($run_zones)) {

                      $zone_id = $row_zones['zone_id'];

                      $select_zone_locations = "select DISTINCT zone_id from zones_locations where zone_id='$zone_id' and (location_code='$billing_country' and location_type='country')";

                      $run_zones_locations = mysqli_query($con, $select_zone_locations);

                      $count_zones_locations = mysqli_num_rows($run_zones_locations);

                      if ($count_zones_locations != "0") {

                        $row_zones_locations = mysqli_fetch_array($run_zones_locations);

                        $zone_id = $row_zones_locations["zone_id"];

                        $select_zone_postcodes = "select * from zones_locations where zone_id='$zone_id' and location_type='postcode'";

                        $run_zones_postcodes = mysqli_query($con, $select_zone_postcodes);

                        $count_zones_postcodes = mysqli_num_rows($run_zones_postcodes);

                        if ($count_zones_postcodes != "0") {

                          while ($row_zones_postcodes = mysqli_fetch_array($run_zones_postcodes)) {

                            $location_code = $row_zones_postcodes["location_code"];

                            if ($location_code == $billing_postcode) {

                              $shipping_zone_id = $zone_id;

                            }

                          }

                        } else {

                          $shipping_zone_id = $zone_id;

                        }

                      }

                    }

                  }

                  $shipping_weight = $total_weight[$vendor_id];

                  if (!empty($shipping_zone_id)) {

                    $select_shipping = "
SELECT *,
IF (
$shipping_weight > (
SELECT MAX(shipping_weight)
FROM shipping
WHERE shipping_type = type_id
AND shipping_zone = '$shipping_zone_id'
),
(
SELECT shipping_cost
FROM shipping
WHERE shipping_type = type_id
AND shipping_zone = '$shipping_zone_id'
ORDER BY shipping_weight DESC
LIMIT 0, 1
),
(
SELECT shipping_cost
FROM shipping
WHERE shipping_type = type_id
AND shipping_zone = '$shipping_zone_id'
AND shipping_weight >= '$shipping_weight'
ORDER BY shipping_weight ASC
LIMIT 0, 1
)
) AS shipping_cost
FROM shipping_type
WHERE type_local = 'yes'
and vendor_id='$vendor_id'
ORDER BY type_order ASC
";

                    $run_shipping = mysqli_query($con, $select_shipping);

                    $i = 0;

                    while ($row_shipping = mysqli_fetch_array($run_shipping)) {

                      $i++;

                      $type_id = $row_shipping["type_id"];

                      $type_name = $row_shipping["type_name"];

                      $type_default = $row_shipping["type_default"];

                      $shipping_cost = $row_shipping["shipping_cost"];

                      if (!empty($shipping_cost)) {

                        ?>

                          <li>

                              <input type="radio" name="[<?php echo $vendor_id; ?>][shipping_type]"
                                     value="<?php echo $type_id; ?>" class="shipping_type"
                                     data-shipping_cost="<?php echo $shipping_cost; ?>"

                                <?php

                                if ($type_default == "yes") {

                                  $_SESSION["shipping_type_$vendor_id"] = $type_id;

                                  $_SESSION["shipping_cost_$vendor_id"] = $shipping_cost;

                                  echo "checked";

                                } elseif ($i == 1) {

                                  $_SESSION["shipping_type_$vendor_id"] = $type_id;

                                  $_SESSION["shipping_cost_$vendor_id"] = $shipping_cost;

                                  echo "checked";

                                }

                                ?>>

                              <span class="shipping-type-name">

<?php echo $type_name; ?>: <span class="text-muted"> Rs:<?php echo $shipping_cost; ?> </span>

</span>

                          </li>

                        <?php

                      }

                    }

                  } else {

                    if (!empty($billing_country) or !empty($shipping_country)) {

                      if (@$_SESSION["is_shipping_address"] == "yes") {

                        $select_country_shipping = "select * from shipping where shipping_country='$billing_country'";

                      } elseif (@$_SESSION["is_shipping_address"] == "no") {

                        $select_country_shipping = "select * from shipping where shipping_country='$shipping_country'";

                      } else {

                        $select_country_shipping = "select * from shipping where shipping_country='$billing_country'";

                      }

                      $run_country_shipping = mysqli_query($con, $select_country_shipping);

                      $count_country_shipping = mysqli_num_rows($run_country_shipping);

                      if ($count_country_shipping == "0") {

                        echo "

<li> 

<p> There are no shipping types matched/available for your address, or contact us if you need any help. </p> 

</li>

";

                      } else {

                        if (@$_SESSION["is_shipping_address"] == "yes") {

                          $select_shipping = "
SELECT *,
IF (
$shipping_weight > (
SELECT MAX(shipping_weight)
FROM shipping
WHERE shipping_type = type_id
AND shipping_country = '$billing_country'
),
(
SELECT shipping_cost
FROM shipping
WHERE shipping_type = type_id
AND shipping_country = '$billing_country'
ORDER BY shipping_weight DESC
LIMIT 0, 1
),
(
SELECT shipping_cost
FROM shipping
WHERE shipping_type = type_id
AND shipping_country = '$billing_country'
AND shipping_weight >= '$shipping_weight'
ORDER BY shipping_weight ASC
LIMIT 0, 1
)
) AS shipping_cost
FROM shipping_type
WHERE type_local = 'no'
and vendor_id='$vendor_id'
ORDER BY type_order ASC
";

                        } elseif (@$_SESSION["is_shipping_address"] == "no") {

                          $select_shipping = "
SELECT *,
IF (
$shipping_weight > (
SELECT MAX(shipping_weight)
FROM shipping
WHERE shipping_type = type_id
AND shipping_country = '$shipping_country'
),
(
SELECT shipping_cost
FROM shipping
WHERE shipping_type = type_id
AND shipping_country = '$shipping_country'
ORDER BY shipping_weight DESC
LIMIT 0, 1
),
(
SELECT shipping_cost
FROM shipping
WHERE shipping_type = type_id
AND shipping_country = '$shipping_country'
AND shipping_weight >= '$shipping_weight'
ORDER BY shipping_weight ASC
LIMIT 0, 1
)
) AS shipping_cost
FROM shipping_type
WHERE type_local = 'no'
and vendor_id='$vendor_id'
ORDER BY type_order ASC
";

                        } else {

                          $select_shipping = "
SELECT *,
IF (
$shipping_weight > (
SELECT MAX(shipping_weight)
FROM shipping
WHERE shipping_type = type_id
AND shipping_country = '$billing_country'
),
(
SELECT shipping_cost
FROM shipping
WHERE shipping_type = type_id
AND shipping_country = '$billing_country'
ORDER BY shipping_weight DESC
LIMIT 0, 1
),
(
SELECT shipping_cost
FROM shipping
WHERE shipping_type = type_id
AND shipping_country = '$billing_country'
AND shipping_weight >= '$shipping_weight'
ORDER BY shipping_weight ASC
LIMIT 0, 1
)
) AS shipping_cost
FROM shipping_type
WHERE type_local = 'no'
and vendor_id='$vendor_id'
ORDER BY type_order ASC
";

                        }

                        $run_shipping = mysqli_query($con, $select_shipping);

                        $i = 0;

                        while ($row_shipping = mysqli_fetch_array($run_shipping)) {

                          $i++;

                          $type_id = $row_shipping["type_id"];

                          $type_name = $row_shipping["type_name"];

                          $type_default = $row_shipping["type_default"];

                          $shipping_cost = $row_shipping["shipping_cost"];

                          if (!empty($shipping_cost)) {

                            ?>

                              <li>

                                  <input type="radio" name="[<?php echo $vendor_id; ?>][shipping_type]"
                                         value="<?php echo $type_id; ?>" class="shipping_type"
                                         data-shipping_cost="<?php echo $shipping_cost; ?>"

                                    <?php

                                    if ($type_default == "yes") {

                                      $_SESSION["shipping_type_$vendor_id"] = $type_id;

                                      $_SESSION["shipping_cost_$vendor_id"] = $shipping_cost;

                                      echo "checked";

                                    } elseif ($i == 1) {

                                      $_SESSION["shipping_type_$vendor_id"] = $type_id;

                                      $_SESSION["shipping_cost_$vendor_id"] = $shipping_cost;

                                      echo "checked";

                                    }

                                    ?>>

                                  <span class="shipping-type-name">

<?php echo $type_name; ?>: <span class="text-muted"> Rs:<?php echo $shipping_cost; ?> </span>

</span>

                              </li>

                            <?php

                          }

                        }

                      }

                    }

                  }

                  ?>


                  <?php

                }

              } else {

                echo "

<li> 

<p> There are no shipping types available. Please login to view available shipping types, or contact us if you need any help. </p> 

</li>

";

              }

              ?>

            </ul>

        </th>

    </tr>

  <?php

  $total_shipping_cost = 0;

  if (count($physical_products) > 0) {

    foreach ($vendors_ids as $vendor_id) {

      $total_shipping_cost += $_SESSION["shipping_cost_$vendor_id"];

    }

  }

  $total_cart_price = $total + $total_shipping_cost;

}

?>

<tr>

    <td>Tax:</td>

    <th>Rs:0.00</th>

</tr>

<tr class="total">

    <td>Total:</td>

  <?php if (count($physical_products) > 0) { ?>

      <th class="total-cart-price">Rs:<?php echo $total_cart_price; ?>.00</th>

  <?php } else { ?>

      <th class="total-cart-price">Rs:<?php echo $total; ?>.00</th>

  <?php } ?>

</tr>

<script>

    $(document).ready(function () {

      <?php if(count($physical_products) > 0 ){ ?>

        $(document).on("change", ".shipping_type", function () {

            var total_shipping_cost = Number(0);

          <?php foreach($vendors_ids as $vendor_id){ ?>

            var shipping_cost = Number($("input[name='[<?php echo $vendor_id; ?>][shipping_type]']:checked").data("shipping_cost"));

            total_shipping_cost += shipping_cost;

          <?php } ?>

            var total = Number(<?php echo $total; ?>);

            var total_cart_price = total + total_shipping_cost;

            $(".total-cart-price").html("Rs:" + total_cart_price + ".00");

        });

      <?php } ?>

    });

</script>
