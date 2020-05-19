<?php
session_start();
include("includes/db.php");
include("functions/functions.php");

$customer_email = $_SESSION['customer_email'];

$select_customer = "select * from customers where customer_email='$customer_email'";

$run_customer = mysqli_query($con, $select_customer);

$row_customer = mysqli_fetch_array($run_customer);

$customer_id = $row_customer['customer_id'];

$customer_contact = $row_customer['customer_contact'];

$select_payment_settings = "select * from payment_settings";

$run_payment_settings = mysqli_query($con, $select_payment_settings);

$row_payment_settings = mysqli_fetch_array($run_payment_settings);

$enable_paypal = $row_payment_settings['enable_paypal'];

$paypal_email = $row_payment_settings['paypal_email'];

$paypal_currency_code = $row_payment_settings['paypal_currency_code'];

$paypal_sandbox = $row_payment_settings['paypal_sandbox'];

$enable_stripe = $row_payment_settings['enable_stripe'];

if ($paypal_sandbox == "on") {

  $paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";

} elseif ($paypal_sandbox == "off") {

  $paypal_url = "https://www.paypal.com/cgi-bin/webscr";

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

$physical_products = array();

$ip_add = getRealUserIp();

$select_cart = "select * from cart where ip_add='$ip_add'";

$run_cart = mysqli_query($con, $select_cart);

while ($row_cart = mysqli_fetch_array($run_cart)) {

  $product_id = $row_cart['p_id'];

  $product_type = $row_cart['product_type'];

  if ($product_type == "physical_product") {

    array_push($physical_products, $product_id);

  }

}

$total = $_POST["total"];

$shipping_types = $_POST["shipping_types"];

$shipping_cost = $_POST["shipping_cost"];

$_SESSION["shipping_types"] = $shipping_types;

$_SESSION["shipping_cost"] = $shipping_cost;

foreach ($shipping_types as $vendor_id => $vendor_data) {

  $_SESSION["shipping_type_$vendor_id"] = $vendor_data["shipping_type"];

  $_SESSION["shipping_cost_$vendor_id"] = $vendor_data["shipping_cost"];

}

$payment_method = $_POST["payment_method"];

$total_cart_price = $_POST["total_cart_price"];

?>

<form id="offline_form" action="order.php" method="post"><!-- offline Payment form Starts -->

  <?php if (count($physical_products) > 0) { ?>

      <input type="hidden" name="amount" value="<?php echo $total_cart_price; ?>">

  <?php } else { ?>

      <input type="hidden" name="amount" value="<?php echo $total; ?>">

  <?php } ?>

    <input type="submit" id="offline-submit" name="submit" value="Place Order" class="btn btn-success btn-lg"
           style="border-radius:0px;">

</form><!-- offline Payment form Starts -->


<?php

if ($enable_stripe == "yes") {

  if (count($physical_products) > 0) {

    $stripe_total_amount = $total_cart_price * 100;

  } else {

    $stripe_total_amount = $total;

  }

  include("stripe_config.php");

  ?>

    <form id="stripe_form" action="stripe_charge.php" method="post">

        <input type="hidden" name="total_amount" value="<?php echo $total_cart_price; ?>">

        <input type="hidden" name="stripe_total_amount" value="<?php echo $stripe_total_amount; ?>">

        <input
                type="submit"
                id="stripe-submit"
                class="btn btn-success btn-lg"
                value="Procced With Stripe"
                style="border-radius:0px;"
                data-name="bazar.com"
                data-description="Pay With Credit Card"
                data-image="images/stripe-logo.png"
                data-key="<?php echo $stripe['publishable_key']; ?>"
                data-amount="<?php echo $stripe_total_amount; ?>"
                data-currency="<?php echo $stripe['currency_code']; ?>"
                data-email="<?php echo $customer_email; ?>">

    </form>

<?php } ?>

<?php if ($enable_paypal == "yes") { ?>

    <form id="paypal_form" action="<?php echo $paypal_url; ?>" method="post"><!-- PayPal form Starts -->

        <input type="hidden" name="business" value="<?php echo $paypal_email; ?>">

        <input type="hidden" name="cmd" value="_cart">

        <input type="hidden" name="upload" value="1">

        <input type="hidden" name="currency_code" value="<?php echo $paypal_currency_code; ?>">

      <?php if (count($physical_products) > 0) { ?>

          <input type="hidden" name="return"
                 value="http://localhost/bazar/paypal_order.php?c_id=<?php echo $customer_id; ?>&amount=<?php echo $total_cart_price; ?>">

      <?php } else { ?>

          <input type="hidden" name="return"
                 value="http://localhost/bazar/paypal_order.php?c_id=<?php echo $customer_id; ?>&amount=<?php echo $total; ?>">

      <?php } ?>

        <input type="hidden" name="cancel_return" value="http://localhost/bazar/checkout.php">

      <?php

      $i = 0;

      $get_cart = "select * from cart where ip_add='$ip_add'";

      $run_cart = mysqli_query($con, $get_cart);

      while ($row_cart = mysqli_fetch_array($run_cart)) {

        $pro_id = $row_cart['p_id'];

        $pro_qty = $row_cart['qty'];

        $pro_price = $row_cart['p_price'];

        $get_products = "select * from products where product_id='$pro_id'";

        $run_products = mysqli_query($con, $get_products);

        $row_products = mysqli_fetch_array($run_products);

        $product_title = $row_products['product_title'];

        $i++;

        ?>

          <input type="hidden" name="item_name_<?php echo $i; ?>" value="<?php echo $product_title; ?>">

          <input type="hidden" name="item_number_<?php echo $i; ?>" value="<?php echo $i; ?>">

          <input type="hidden" name="amount_<?php echo $i; ?>" value="<?php echo $pro_price; ?>">

          <input type="hidden" name="quantity_<?php echo $i; ?>" value="<?php echo $pro_qty; ?>">

      <?php } ?>

        <input type="hidden" name="shipping_1" value="<?php echo @$_SESSION["shipping_cost"]; ?>">

        <input type="hidden" name="first_name" value="<?php echo $billing_first_name; ?>">

        <input type="hidden" name="last_name" value="<?php echo $billing_last_name; ?>">

        <input type="hidden" name="address1" value="<?php echo $billing_address_1; ?>">

        <input type="hidden" name="address2" value="<?php echo $billing_address_2; ?>">

        <input type="hidden" name="city" value="<?php echo $billing_city; ?>">

        <input type="hidden" name="state" value="<?php echo $billing_state; ?>">

        <input type="hidden" name="zip" value="<?php echo $billing_postcode; ?>">

        <input type="hidden" name="night_phone_a" value="<?php echo $customer_contact; ?>">

        <input type="hidden" name="email" value="<?php echo $customer_email; ?>">

        <input type="submit" id="paypal-submit" name="submit" value="Procced With PayPal" class="btn btn-success btn-lg"
               style="border-radius:0px;">

    </form><!-- PayPal form Ends -->

<?php } ?>

<script>

    $(document).ready(function () {

      <?php if($payment_method == "pay_offline"){ ?>

        $('#offline_desc').show();

        $('#offline_form').show();

        $('#stripe_desc').hide();

        $('#stripe_form').hide();

        $('#paypal_desc').hide();

        $('#paypal_form').hide();

      <?php }elseif($payment_method == "stripe"){ ?>

        $('#offline_desc').hide();

        $('#offline_form').hide();

        $('#stripe_desc').show();

        $('#stripe_form').show();

        $('#paypal_desc').hide();

        $('#paypal_form').hide();

      <?php }elseif($payment_method == "paypal"){ ?>

        $('#offline_desc').hide();

        $('#offline_form').hide();

        $('#stripe_desc').hide();

        $('#stripe_form').hide();

        $('#paypal_desc').show();

        $('#paypal_form').show();

      <?php } ?>

        $('#paypal').click(function () {

            $('#offline_desc').hide();
            $('#offline_form').hide();
            $('#stripe_desc').hide();
            $('#stripe_form').hide();
            $('#paypal_desc').show();
            $('#paypal_form').show();

        });

        $('#stripe').click(function () {

            $('#offline_desc').hide();
            $('#offline_form').hide();
            $('#paypal_desc').hide();
            $('#paypal_form').hide();
            $('#stripe_desc').show();
            $('#stripe_form').show();

        });

        $('#offline').click(function () {

            $('#stripe_desc').hide();
            $('#stripe_form').hide();
            $('#paypal_desc').hide();
            $('#paypal_form').hide();
            $('#offline_desc').show();
            $('#offline_form').show();

        });


        $('#offline-submit').click(function (event) {

            event.preventDefault();

            $('#shipping-billing-details-form').submit(function (event) {

                event.preventDefault();

                $('#offline-submit').click();

            });

            $('#shipping-billing-form-submit').click();

        });


        $('#stripe-submit').click(function (event) {

            event.preventDefault();

            $('#shipping-billing-details-form').submit(function (event) {

                event.preventDefault();

                var $button = $('#stripe-submit'),
                    $form = $button.parents('form');
                var opts = $.extend({}, $button.data(), {
                    token: function (result) {
                        $form.append($('<input>').attr({
                            type: 'hidden',
                            name: 'stripeToken',
                            value: result.id
                        })).submit();
                    }
                });

                StripeCheckout.open(opts);

            });

            $('#shipping-billing-form-submit').click();

        });


        $('#paypal-submit').click(function (event) {

            event.preventDefault();

            $('#shipping-billing-details-form').submit(function (event) {

                event.preventDefault();

                $('#paypal-submit').click();

            });

            $('#shipping-billing-form-submit').click();

        });


    });

</script>