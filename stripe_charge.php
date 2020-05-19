<?php session_start();include("includes/db.php"); include("stripe_config.php");?>

<?php
//setting up stripe api key card and currency details for customers//
//which will be changed to visa card or master card in future.
$customer_email = $_SESSION['customer_email'];
$get_customer = "select * from customers where customer_email='$customer_email'";
$run_customer = mysqli_query($con,$get_customer);
$row_customer = mysqli_fetch_array($run_customer);
$customer_id = $row_customer['customer_id'];
$total_amount = $_POST['total_amount'];
$stripe_total_amount = $_POST['stripe_total_amount'];
$token = $_POST['stripeToken'];
$customer = \Stripe\Customer::create(array(
'email' => $customer_email,
'card'  => $token
)); 

$charge = \Stripe\Charge::create(array(
'customer' => $customer->id,
'amount'   => $stripe_total_amount,
'currency' => 'PKR'
));

echo "<script> window.open('stripe_order.php?c_id=$customer_id&amount=$total_amount','_self'); </script>";

?>