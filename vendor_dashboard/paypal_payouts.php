<?php
session_start();
include("includes/db.php");
include("functions/functions.php");

if (!isset($_SESSION['customer_email'])) {
  echo "<script> window.open('../checkout.php','_self'); </script>";
}

$customer_email = $_SESSION['customer_email'];
$select_customer = "select * from customers where customer_email='$customer_email'";
$run_customer = mysqli_query($con, $select_customer);
$row_customer = mysqli_fetch_array($run_customer);
$customer_id = $row_customer['customer_id'];
$select_payment_settings = "select * from payment_settings";
$run_payment_setttings = mysqli_query($con, $select_payment_settings);
$row_payment_settings = mysqli_fetch_array($run_payment_setttings);
$minimum_withdraw_limit = $row_payment_settings['minimum_withdraw_limit'];
//$paypal_email = $row_payment_settings['paypal_email'];
$paypal_sandbox = $row_payment_settings['paypal_sandbox'];
$paypal_currency_code = $row_payment_settings['paypal_currency_code'];
$paypal_app_client_id = $row_payment_settings['paypal_app_client_id'];
$paypal_app_client_secret = $row_payment_settings['paypal_app_client_secret'];

if ($paypal_sandbox == "on") {
  $mode = "sandbox";

} elseif ($paypal_sandbox == "off") {
  $mode = "live";
}

require_once('vendor/autoload.php');

use PayPal\Api\Currency;
use PayPal\Api\PayoutItem;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

//Api Setup

$api = new ApiContext(

  new OAuthTokenCredential(

    "$paypal_app_client_id",

    "$paypal_app_client_secret"

  )

);

$api->setConfig([

  "mode" => "$mode"

]);

if (isset($_POST['withdraw'])) {

  $select_store_settings = "select * from store_settings where vendor_id='$customer_id'";

  $run_store_settings = mysqli_query($con, $select_store_settings);

  $row_store_settings = mysqli_fetch_array($run_store_settings);

  $receiver_paypal_email = $row_store_settings["paypal_email"];

  $select_vendor_account = "select * from vendor_accounts where vendor_id='$customer_id'";

  $run_vendor_account = mysqli_query($con, $select_vendor_account);

  $row_vendor_account = mysqli_fetch_array($run_vendor_account);

  $current_balance = $row_vendor_account['current_balance'];

  $amount = $_POST['amount'];

  if ($amount > $minimum_withdraw_limit or $amount == $minimum_withdraw_limit) {

    if ($amount < $current_balance or $amount == $current_balance) {

      $payouts = new PayPal\Api\Payout();

      $senderBatchHeader = new PayPal\Api\PayoutSenderBatchHeader();

      $senderBatchHeader->setSenderBatchId(uniqid())
        ->setEmailSubject("You Have Bank Payout Payment From bazar.com.");

      $senderItem = new PayoutItem();

      $senderItem->setRecipientType("Email")
        ->setReceiver("$receiver_paypal_email")
        ->setAmount(new Currency(
          '{
	"value":"' . $amount . '",
	"currency":"' . $paypal_currency_code . '"
}'
        ));

      $payouts->setSenderBatchHeader($senderBatchHeader)
        ->addItem($senderItem);

// ### Create Payout

      try {

//$payouts->create(null, $api);

        if ($payouts->create(null, $api)) {
          $update_vendor_account = "update vendor_accounts set current_balance=current_balance-$amount,withdrawals=withdrawals+$amount where vendor_id='$customer_id'";
          $run_vendor_account = mysqli_query($con, $update_vendor_account);
          if ($run_vendor_account) {
            echo "<script>alert('Congrats, Your Money Rs:$amount Has Been Sent To Your Bank Account Successfully.');window.open('index.php?payments','_self');</script>";
          }
        }
      } catch (Exception $ex) {
        echo "<script>alert('Sorry An error occurred During Sending Your Money To Your Bank Account.');window.open('index.php?payments','_self');</script>";
      }
    } else {
      echo "<script>alert('Your Enter Amount Is To Higher Then Your Current Balance.');window.open('index.php?payments','_self');</script>";
    }

  } else {

    echo "<script>alert('Your Enter Amount Is Less Then Our Minimum Withdrawal Amount Rs:$minimum_withdraw_limit.');window.open('index.php?payments','_self');</script>";
  }
}
?>