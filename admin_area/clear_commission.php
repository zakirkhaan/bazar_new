<?php
if (!isset($_SESSION['admin_email'])) {
  echo "<script>window.open('login.php','_self')</script>";
} else {
  ?>

<?php
  if (isset($_GET['clear_commission'])) {
    $commission_id = $_GET['clear_commission'];
    $update_vendor_commission = "update vendor_commissions set commission_status='cleared' where commission_id='$commission_id'";
    $update_run_vendor_commission = mysqli_query($con, $update_vendor_commission);

    if ($update_run_vendor_commission) {
      $select_vendor_commission = "select * from vendor_commissions where commission_id='$commission_id'";
      $run_vendor_commission = mysqli_query($con, $select_vendor_commission);
      $row_vendor_commission = mysqli_fetch_array($run_vendor_commission);
      $vendor_id = $row_vendor_commission['vendor_id'];
      $order_id = $row_vendor_commission['order_id'];
      $select_vendor_order = "select * from vendor_orders where id='$order_id' and vendor_id='$vendor_id'";
      $run_vendor_order = mysqli_query($con, $select_vendor_order);
      $row_vendor_order = mysqli_fetch_array($run_vendor_order);
      $shipping_cost = $row_vendor_order['shipping_cost'];
      $net_amount = $row_vendor_order['net_amount'];
      $commission_amount = $net_amount + $shipping_cost;
      $update_vendor_account = "update vendor_accounts set pending_clearance=pending_clearance-$commission_amount,current_balance=current_balance+$commission_amount where vendor_id='$vendor_id'";
      $run_vendor_account = mysqli_query($con, $update_vendor_account);

      if ($run_vendor_account) {
        echo "<script>alert('Your Commission Has Been Cleared Successfully And Vendor Can Withdraw This Commission Amount. ');window.open('index.php?vendors_commissions','_self');</script>";
      }
    }
  }
  ?>

<?php } ?>