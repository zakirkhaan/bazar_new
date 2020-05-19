<?php
if (!isset($_SESSION['admin_email'])) {
  echo "<script>window.open('login.php','_self')</script>";
} else {
  $admin_session = $_SESSION['admin_email'];
  $get_admin = "select * from admins where admin_email='$admin_session'";
  $run_admin = mysqli_query($con, $get_admin);
  $row_admin = mysqli_fetch_array($run_admin);
  $admin_id = $row_admin['admin_id'];
?>

 <?php
  if (isset($_GET['order_delete'])) {
    $delete_id = $_GET['order_delete'];
    $delete_order = "insert into hide_admin_orders (admin_id,order_id) values ('$admin_id','$delete_id')";
    $run_delete = mysqli_query($con, $delete_order);

    if ($run_delete) {
      echo "<script>alert('Order Has Been Deleted.')</script>";
      echo "<script>window.open('index.php?view_orders','_self')</script>";
    }
  }
?>
<?php } ?>