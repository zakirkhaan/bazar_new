<?php
if (!isset($_SESSION['admin_email'])) {
  echo "<script>window.open('login.php','_self')</script>";
} else {
?>

<?php
  if (isset($_GET['delete_note'])) {
    $note_id = $_GET['delete_note'];
    $order_id = $_GET['order_id'];
    $delete_note = "delete from orders_notes where note_id='$note_id' and order_id='$order_id'";
    $run_delete_note = mysqli_query($con, $delete_note);

    if ($run_delete_note) {
      echo "<script>alert('Your Order Note Has Been Deleted Successfully.');window.open('index.php?view_order_id=$order_id', '_self');</script>";
    }
  }
  ?>
<?php } ?>