<?php

if (!isset($_SESSION['customer_email'])) {
  echo "<script> window.open('../checkout.php','_self'); </script>";
}

if (isset($_GET["change_review_status"])) {
  $review_id = $_GET["change_review_status"];
  $review_status = $_GET["status"];

  if ($review_status != "unspam") {
    $update_review_status = "update reviews set review_status='$review_status' where review_id='$review_id'";

  } else {
    $update_review_status = "update reviews set review_status='active' where review_id='$review_id'";
  }

  $run_review_status = mysqli_query($con, $update_review_status);
  if ($run_review_status) {
    if ($review_status == "active") {
      $alert_text = "Your Review Has Been Approved Successfully.";

    } elseif ($review_status == "pending") {
      $alert_text = "Your Review Has Been Unapproved Successfully,And Moved To Pending.";

    } elseif ($review_status == "spam") {
      $alert_text = "Your Review Has Been Moved To Spam Successfully.";

    } elseif ($review_status == "unspam") {
      $alert_text = "Your Review Has Been Unspam And Approved Successfully.";

    } elseif ($review_status == "trash") {
      $alert_text = "Your Review Has Been Deleted Sccessfully.";

    }

    echo "<script>alert('$alert_text');window.open('index.php?reviews','_self');</script>";
  }
}
?>
