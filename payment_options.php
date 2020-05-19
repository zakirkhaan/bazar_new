<div class="box"><!-- box Starts -->

<?php

$session_email = $_SESSION['customer_email'];

$select_customer = "select * from customers where customer_email='$session_email'";

$run_customer = mysqli_query($con,$select_customer);

$row_customer = mysqli_fetch_array($run_customer);

$customer_id = $row_customer['customer_id'];


?>

<h1 class="text-center">Payment Options For You</h1>

<p class="lead text-center">

<a href="order.php?c_id=<?php echo $customer_id; ?>">Pay Off line</a>

</p>

<center><!-- center Starts -->

<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post"><!-- form Starts -->

<input type="hidden" name="business" value="saady@gmail.com">

<input type="hidden" name="cmd" value="_cart">

<input type="hidden" name="upload" value="1">

<input type="hidden" name="currency_code" value="PHP">

<input type="hidden" name="return" value="http://localhost/second_section_course/paypal_order.php?c_id=<?php echo $customer_id; ?>">

<input type="hidden" name="cancel_return" value="http://localhost/second_section_course/index.php">

<?php

$i = 0;

$total = 0;

$ip_add = getRealUserIp();

$get_cart = "select * from cart where ip_add='$ip_add'";

$run_cart = mysqli_query($con,$get_cart);

while($row_cart = mysqli_fetch_array($run_cart)){

$pro_id = $row_cart['p_id'];

$pro_qty = $row_cart['qty'];

$pro_price = $row_cart['p_price'];

$sub_total = $pro_price*$pro_qty;

$total += $sub_total;

$get_products = "select * from products where product_id='$pro_id'";

$run_products = mysqli_query($con,$get_products);

$row_products = mysqli_fetch_array($run_products);

$product_title = $row_products['product_title'];

$i++;

?>

<input type="hidden" name="item_name_<?php echo $i; ?>" value="<?php echo $product_title; ?>" >

<input type="hidden" name="item_number_<?php echo $i; ?>" value="<?php echo $i; ?>" >

<input type="hidden" name="amount_<?php echo $i; ?>" value="<?php echo $pro_price; ?>" >

<input type="hidden" name="quantity_<?php echo $i; ?>" value="<?php echo $pro_qty; ?>" >

<?php } ?>

<input type="image" name="submit" width="500" height="230" src="images/paypal.png" >

</form><!-- form Ends -->

<?php
 
$total *= 100;

require_once('stripe_config.php');

?>

<script src="js/jquery.min.js"></script>

<form action="stripe_charge.php" method="post" >

   <input 
     type="image" 
	 width="500" height="230" src="images/stripe.png"
     class="stripe-submit" 
	 data-name="Computerfever.com"
	 data-description="Pay With Credit Card"
	 data-image="images/stripe-logo.png"
     data-key="<?php echo $stripe['publishable_key']; ?>" 
     data-amount="<?php echo $total; ?>"
     data-currency="usd"	 
     data-email="<?php echo $session_email; ?>">
     <input type="hidden" name="buyer_id" value="<?php echo $customer_id; ?>" >
    <input type="hidden" name="email" value="<?php echo $session_email; ?>" >
    <input type="hidden" name="amount" value="<?php echo $total; ?>" >

  <script>
  $(document).ready(function() {
      $('.stripe-submit').on('click', function(event) {
          event.preventDefault();
          var $button = $(this),
              $form = $button.parents('form');
          var opts = $.extend({}, $button.data(), {
              token: function(result) {
                  $form.append($('<input>').attr({ type: 'hidden', name: 'stripeToken', value: result.id })).submit();
              }
          });
          StripeCheckout.open(opts);
      });
  });
  </script>

</form>

</center><!-- center Ends -->

</div><!-- box Ends -->