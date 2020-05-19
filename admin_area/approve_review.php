<?php
if (!isset($_SESSION['admin_email'])) {
  echo "<script>window.open('login.php','_self')</script>";
} else {
  ?>
  <?php
  if (isset($_GET['approve_review'])) {
    $review_id = $_GET['approve_review'];
    $update_review_status = "update reviews set review_status='active' where review_id='$review_id'";
    $run_review_status = mysqli_query($con, $update_review_status);

    if ($run_review_status) {
      echo "<script>alert('Your Review Has Been Approved Sccessfully.');window.open('index.php?view_reviews','_self');</script>";
    }
  }
  ?>
<?php } ?>