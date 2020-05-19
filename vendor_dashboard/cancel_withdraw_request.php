<?php

if (!isset($_SESSION['customer_email'])) {
  echo "<script> window.open('../checkout.php','_self'); </script>";
}
$customer_email = $_SESSION['customer_email'];
$get_customer = "select * from customers where customer_email='$customer_email'";
$run_customer = mysqli_query($con, $get_customer);
$row_customer = mysqli_fetch_array($run_customer);
$customer_id = $row_customer['customer_id'];

if (isset($_GET['cancel_withdraw_request'])) {
  $withdraw_id = mysqli_real_escape_string($con, $_GET['cancel_withdraw_request']);
  $update_vendor_withdraw = "update vendor_withdraws set status='cancelled' where withdraw_id='$withdraw_id' and vendor_id='$customer_id'";
  $run_vendor_withdraw = mysqli_query($con, $update_vendor_withdraw);
  if ($run_vendor_withdraw) {
    echo "<script>alert(' Your Withdraw Request Has Been Cancelled Successfully. ');window.open('index.php?withdraw','_self');</script>";
  }
}
?>