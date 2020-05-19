<?php
if (!isset($_SESSION['admin_email'])) {
  echo "<script>window.open('login.php','_self')</script>";
} else {
?>
  <?php
  if (isset($_GET['delete_commission'])) {
    $commission_id = $_GET['delete_commission'];
    $select_vendor_commission = "select * from vendor_commissions where commission_id='$commission_id'";
    $run_vendor_commission = mysqli_query($con, $select_vendor_commission);
    $row_vendor_commission = mysqli_fetch_array($run_vendor_commission);
    $vendor_id = $row_vendor_commission['vendor_id'];
    $order_id = $row_vendor_commission['order_id'];
    $delete_vendor_commission = "delete from vendor_commissions where commission_id='$commission_id' and commission_status='pending'";
    $delete_run_vendor_commission = mysqli_query($con, $delete_vendor_commission);

    if ($delete_run_vendor_commission) {
      $update_vendor_order = "update vendor_orders set order_status='processing' where id='$order_id' and vendor_id='$vendor_id'";
      $run_update_vendor_order = mysqli_query($con, $update_vendor_order);
      $select_vendor_order = "select * from vendor_orders where id='$order_id' and vendor_id='$vendor_id'";
      $run_vendor_order = mysqli_query($con, $select_vendor_order);
      $row_vendor_order = mysqli_fetch_array($run_vendor_order);
      $shipping_cost = $row_vendor_order['shipping_cost'];
      $net_amount = $row_vendor_order['net_amount'];
      $commission_amount = $net_amount + $shipping_cost;
      $update_pending_clearnace = "update vendor_accounts set pending_clearance=pending_clearance-$commission_amount,month_earnings=month_earnings-$commission_amount where vendor_id='$vendor_id'";
      $run_pending_clearnace = mysqli_query($con, $update_pending_clearnace);

      if ($run_pending_clearnace) {
        echo "<script>alert('Your Commission Has Been Deleted Successfully And Commission Order Status Changed To : (Processing) And Vendor Can Not Withdraw This Commission Amount. ');window.open('index.php?vendors_commissions','_self');</script>";
      }
    }
  }
  ?>
<?php } ?>