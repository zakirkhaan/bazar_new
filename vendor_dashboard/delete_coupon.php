<?php
if (!isset($_SESSION['customer_email'])) {
  echo "<script> window.open('../checkout.php','_self'); </script>";
}

$customer_email = $_SESSION['customer_email'];
$get_customer = "select * from customers where customer_email='$customer_email'";
$run_customer = mysqli_query($con, $get_customer);
$row_customer = mysqli_fetch_array($run_customer);

$customer_id = $row_customer['customer_id'];
if (isset($_GET['delete_coupon'])) {
  $delete_id = $_GET['delete_coupon'];
  $delete_coupon = "delete from coupons where vendor_id='$customer_id' and coupon_id='$delete_id'";
  $run_delete = mysqli_query($con, $delete_coupon);
  if ($run_delete) {
    echo "<script>alert('One Coupon Has Been Deleted Successfully.');window.open('index.php?coupons','_self');</script>";
  }
}
?>