<?php
if (!isset($_SESSION['admin_email'])) {
  echo "<script>window.open('login.php','_self')</script>";
} else {
?>

<?php
  if (isset($_GET['delete_shipping_zone'])) {
    $delete_id = $_GET['delete_shipping_zone'];
    $delete_zone = "delete from zones where zone_id='$delete_id'";
    $run_zone = mysqli_query($con, $delete_zone);

    if ($run_zone) {
      $delete_zones_locations = "delete from zones_locations where zone_id='$delete_id'";
      $run_zones_locations = mysqli_query($con, $delete_zones_locations);
      echo "<script>alert('Your One Shipping Zone Has Been Deleted.')</script>";
      echo "<script>window.open('index.php?view_shipping_zones','_self')</script>";
    }
  }
?>
<?php } ?>