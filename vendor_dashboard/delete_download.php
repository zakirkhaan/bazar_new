<?php
if (!isset($_SESSION['customer_email'])) {
  echo "<script> window.open('../checkout.php','_self'); </script>";
}

$customer_email = $_SESSION['customer_email'];
$get_customer = "select * from customers where customer_email='$customer_email'";
$run_customer = mysqli_query($con, $get_customer);
$row_customer = mysqli_fetch_array($run_customer);
$customer_id = $row_customer['customer_id'];

if (isset($_GET['delete_download'])) {
  $download_id = $_GET['delete_download'];
  $select_download = "select * from downloads where download_id='$download_id' and vendor_id='$customer_id'";
  $run_download = mysqli_query($con, $select_download);
  $row_download = mysqli_fetch_array($run_download);
  $download_file = "../admin_area/downloads_uploads/" . $row_download['download_file'];
  $delete_download = "delete from downloads where download_id='$download_id' and vendor_id='$customer_id'";
  $run_delete = mysqli_query($con, $delete_download);

  if (unlink($download_file)) {
    if ($run_delete) {
      echo "<script>alert('One Download Has Been Deleted Successfully.');window.open('index.php?downloads','_self');</script>";
    }
  }
}
?>