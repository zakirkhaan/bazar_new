<?php session_start(); include("includes/db.php");

if (isset($_SESSION['admin_email'])) {
    echo "<script>window.open('index.php','_self')</script>";
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="container"><!-- container Starts -->
        <form class="form-login" action="" method="post"><!-- form-login Starts -->
            <h2 class="form-login-heading">Admin Login</h2>
            <input type="text" class="form-control" name="admin_email" placeholder="Email Address" required>
            <input type="password" class="form-control" name="admin_pass" placeholder="Password" required>
            <button class="btn btn-lg btn-primary btn-block" type="submit" name="admin_login">Log in</button>
            <h4 class="forgot-password">
                <a href="forgot_password.php"> Lost your password? Forgot Password </a>
            </h4>
        </form><!-- form-login Ends -->
    </div><!-- container Ends -->
    </body>
    </html>

<?php
//code for admin login
//protected by hashed password
if (isset($_POST['admin_login'])) {
  $admin_email = mysqli_real_escape_string($con, $_POST['admin_email']);
  $admin_pass = mysqli_real_escape_string($con, $_POST['admin_pass']);
  $decrypt_get_admin = "select * from admins where admin_email='$admin_email'";
  $decrypt_run_admin = mysqli_query($con, $decrypt_get_admin);
  $row_decrypt_admin = mysqli_fetch_array($decrypt_run_admin);
  @$hash_password = $row_decrypt_admin["admin_pass"];

  $decrypt_password = password_verify($admin_pass, $hash_password);
//checking if there is no admin//
  if ($decrypt_password == 0) {
    echo "<script>alert('Email Or Password Is Wrong, Please Try Again')</script>";
  } else {
    //if there is an admin with the entered credential's then log him in
    $get_admin = "select * from admins where admin_email='$admin_email' AND admin_pass='$hash_password'";
    $run_admin = mysqli_query($con, $get_admin);

    if ($run_admin) {
      $_SESSION['admin_email'] = $admin_email;
      $_SESSION['loggedin_time'] = time();
      echo "<script>alert('You are Logged in into admin panel')</script>";
      echo "<script>window.open('index.php?dashboard','_self')</script>";

    }

  }

}

?>