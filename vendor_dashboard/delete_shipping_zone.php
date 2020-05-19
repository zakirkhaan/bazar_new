<?php
if (!isset($_SESSION['customer_email'])) {
  echo "<script> window.open('../checkout.php','_self'); </script>";
}
$customer_email = $_SESSION['customer_email'];
$select_customer = "select * from customers where customer_email='$customer_email'";
$run_customer = mysqli_query($con, $select_customer);
$row_customer = mysqli_fetch_array($run_customer);
$customer_id = $row_customer['customer_id'];

if (isset($_GET['delete_shipping_zone'])) {
  $delete_zone_id = $_GET['delete_shipping_zone'];
  $delete_zone = "delete from zones where zone_id='$delete_zone_id' and vendor_id='$customer_id'";
  $run_delete_zone = mysqli_query($con, $delete_zone);

  if ($run_delete_zone) {
    $delete_zones_locations = "delete from zones_locations where zone_id='$delete_zone_id'";
    $run_delete_zones_locations = mysqli_query($con, $delete_zones_locations);
    echo "<script>alert('Your One Shipping Zone Has Been Deleted Successfully.');window.open('index.php?shipping_zones', '_self');</script>";
  }
}
?>