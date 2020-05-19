<?php if (!isset($_SESSION['admin_email'])) { echo "<script>window.open('login.php','_self')</script>"; } else { ?>

    <div class="row"><!-- 1  row Starts -->
        <div class="col-lg-12"><!-- col-lg-12 Starts -->
            <ol class="breadcrumb"><!-- breadcrumb Starts -->
                <li class="active">
                    <i class="fa fa-dashboard"></i> Dashboard / Insert User
                </li>
            </ol><!-- breadcrumb Ends -->
        </div><!-- col-lg-12 Ends -->
    </div><!-- 1  row Ends -->

    <div class="row"><!-- 2 row Starts -->
        <div class="col-lg-12"><!-- col-lg-12 Starts -->
            <div class="panel panel-default"><!-- panel panel-default Starts -->
                <div class="panel-heading"><!-- panel-heading Starts -->
                    <h3 class="panel-title">
                        <i class="fa fa-money fa-fw"></i> Insert User
                    </h3>
                </div><!-- panel-heading Ends -->

                <div class="panel-body"><!-- panel-body Starts -->
                    <form class="form-horizontal" method="post" enctype="multipart/form-data">
                        <div class="form-group"><!-- form-group Starts -->
                            <label class="col-md-3 control-label">User Name: </label>
                            <div class="col-md-6"><!-- col-md-6 Starts -->
                                <input type="text" name="admin_name" class="form-control" required>
                            </div><!-- col-md-6 Ends -->
                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->
                            <label class="col-md-3 control-label">User Email: </label>
                            <div class="col-md-6"><!-- col-md-6 Starts -->
                                <input type="text" name="admin_email" class="form-control" required>
                            </div><!-- col-md-6 Ends -->

                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label">User Password: </label>

                            <div class="col-md-6"><!-- col-md-6 Starts -->

                                <input type="password" name="admin_pass" class="form-control" required>

                            </div><!-- col-md-6 Ends -->

                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label">User Username: </label>

                            <div class="col-md-6"><!-- col-md-6 Starts -->

                                <input type="password" name="admin_username" class="form-control" required>

                                <span id="helpBlock" class="help-block">
                                    ! Important Username can not be changed once an account is registered.<br>
                                    Note! Your Username Is Used For The Vendor Store URL Example: (http://localhost/bazar/store/[this-text])
                                </span>

                            </div><!-- col-md-6 Ends -->

                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label">User City: </label>
                            <div class="col-md-6"><!-- col-md-6 Starts -->
                                <input type="text" name="admin_country" class="form-control" required>
                            </div><!-- col-md-6 Ends -->
                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->
                            <label class="col-md-3 control-label">User Job: </label>
                            <div class="col-md-6"><!-- col-md-6 Starts -->
                                <input type="text" name="admin_job" class="form-control" required>

                            </div><!-- col-md-6 Ends -->

                        </div><!-- form-group Ends -->


                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label">User Contact: </label>

                            <div class="col-md-6"><!-- col-md-6 Starts -->

                                <input type="text" name="admin_contact" class="form-control" required>

                            </div><!-- col-md-6 Ends -->

                        </div><!-- form-group Ends -->


                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label">User About: </label>

                            <div class="col-md-6"><!-- col-md-6 Starts -->

                                <textarea name="admin_about" class="form-control" rows="3"> </textarea>

                            </div><!-- col-md-6 Ends -->

                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label">User Image: </label>

                            <div class="col-md-6"><!-- col-md-6 Starts -->

                                <input type="file" name="admin_image" class="form-control" required>

                            </div><!-- col-md-6 Ends -->

                        </div><!-- form-group Ends -->


                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"></label>

                            <div class="col-md-6"><!-- col-md-6 Starts -->

                                <input type="submit" name="submit" value="Insert User"
                                       class="btn btn-primary form-control">

                            </div><!-- col-md-6 Ends -->

                        </div><!-- form-group Ends -->


                    </form><!-- form-horizontal Ends -->

                </div><!-- panel-body Ends -->

            </div><!-- panel panel-default Ends -->

        </div><!-- col-lg-12 Ends -->


    </div><!-- 2 row Ends -->

  <?php

  if (isset($_POST['submit'])) {

    $admin_name = $_POST['admin_name'];

    $admin_email = $_POST['admin_email'];

    $admin_pass = $_POST['admin_pass'];

    $encrypted_password = password_hash($admin_pass, PASSWORD_DEFAULT);

    $admin_username = $_POST['admin_username'];

    $admin_country = $_POST['admin_country'];

    $admin_job = $_POST['admin_job'];

    $admin_contact = $_POST['admin_contact'];

    $admin_about = $_POST['admin_about'];

    $admin_image = $_FILES['admin_image']['name'];

    $temp_admin_image = $_FILES['admin_image']['tmp_name'];

    move_uploaded_file($temp_admin_image, "admin_images/$admin_image");

    $select_admin_username = "select * from admins where admin_username='$admin_username'";

    $run_admin_username = mysqli_query($con, $select_admin_username);

    $count_admin_username = mysqli_num_rows($run_admin_username);

    if ($count_admin_username == 1) {

      echo "<script> alert(' Your Enter User Username Is Already Registered, Please Try Another One. '); </script>";

      exit();

    } else {

      $select_customer_username = "select * from customers where customer_username='$admin_username'";

      $run_customer_username = mysqli_query($con, $select_customer_username);

      $count_customer_username = mysqli_num_rows($run_customer_username);

      if ($count_customer_username == 1) {

        echo "<script> alert(' Your Enter User Username Is Already Registered, Please Try Another One. '); </script>";

        exit();

      }

    }

    $insert_admin = "insert into admins (admin_name,admin_email,admin_pass,admin_username,admin_image,admin_contact,admin_country,admin_job,admin_about) values ('$admin_name','$admin_email','$encrypted_password','$admin_username','$admin_image','$admin_contact','$admin_country','$admin_job','$admin_about')";

    $run_admin = mysqli_query($con, $insert_admin);

    $insert_admin_id = mysqli_insert_id($con);

    if ($run_admin) {

      $insert_store_settings = "insert into store_settings (vendor_id) values ('admin_$insert_admin_id')";

      $run_store_settings = mysqli_query($con, $insert_store_settings);

      echo "<script>alert('One User Has Been Inserted successfully')</script>";

      echo "<script>window.open('index.php?view_users','_self')</script>";

    }

  }

  ?>


<?php } ?>