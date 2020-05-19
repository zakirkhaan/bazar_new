<?php
if (!isset($_SESSION['admin_email'])) {
  echo "<script>window.open('login.php','_self')</script>";
} else {
?>

<?php
  if (isset($_GET['delete_download'])) {
    $download_id = $_GET['delete_download'];
    $select_download = "select * from downloads where download_id='$download_id'";
    $run_download = mysqli_query($con, $select_download);
    $row_download = mysqli_fetch_array($run_download);
    $download_file = "downloads_uploads/" . $row_download['download_file'];
    $delete_download = "delete from downloads where download_id='$download_id'";
    $run_delete = mysqli_query($con, $delete_download);

    if (unlink($download_file)) {
      if ($run_delete) {
        echo "<script>alert('One Download Has Been Deleted Successfully.');window.open('index.php?view_downloads','_self');</script>";
      }
    }
  }
  ?>
<?php } ?>