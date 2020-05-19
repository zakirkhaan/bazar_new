<?php
if (!isset($_SESSION['admin_email'])) {echo "<script>window.open('login.php','_self')</script>";} else {?>

<?php
  if (isset($_GET['edit_download'])) {
      $download_id = $_GET['edit_download'];
      $select_download = "select * from downloads where download_id='$download_id'";
      $run_download = mysqli_query($con, $select_download);
      $row_download = mysqli_fetch_array($run_download);
      $edit_product_id = $row_download['product_id'];
      $edit_variation_id = $row_download['variation_id'];
      $edit_download_title = $row_download['download_title'];
      $edit_download_file = $row_download['download_file'];
  }
?>
    <div class="row"><!-- 1 row Starts -->
        <div class="col-lg-12"><!-- col-lg-12 Starts -->
            <ol class="breadcrumb"><!-- breadcrumb Starts -->
                <li>
                    <i class="fa fa-dashboard"></i> Dashboard / Edit Download
                </li>
            </ol><!-- breadcrumb Ends -->
        </div><!-- col-lg-12 Ends -->
    </div><!-- 1 row Ends -->

    <div class="row"><!-- 2 row Starts -->
        <div class="col-lg-12"><!-- col-lg-12 Starts -->
            <div class="panel panel-default"><!-- panel panel-default Starts -->
                <div class="panel-heading"><!-- panel-heading Starts -->
                    <h3 class="panel-title"><!-- panel-title Starts -->
                        <i class="fa fa-money fa-fw"></i> Edit Download
                    </h3><!-- panel-title Ends -->
                </div><!-- panel-heading Ends -->

                <div class="panel-body"><!-- panel-body Starts -->
                    <form class="form-horizontal" method="post" enctype="multipart/form-data"><!-- form-horizontal Starts -->
                        <div class="form-group"><!-- form-group Starts -->
                            <label class="col-md-3 control-label">Download Title</label>
                            <div class="col-md-6">
                                <input type="text" name="download_title" value="<?php echo $edit_download_title; ?>" class="form-control" required>
                            </div>
                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->
                            <label class="col-md-3 control-label"> Select A Product/Bundle </label>
                            <div class="col-md-6">
                                <select name="product_id" class="form-control" required>
                                    <optgroup label="Select Product">

                                      <?php
                                      $get_products = "select * from products where (product_type='digital_product' or product_type='variable_product') and status='product'";
                                      $run_products = mysqli_query($con, $get_products);
                                      while ($row_products = mysqli_fetch_array($run_products)) {
                                          $product_id = $row_products['product_id'];
                                          $product_title = $row_products['product_title'];

                                          if ($product_id == $edit_product_id) {
                                              echo "<option value='$product_id' selected>$product_title</option>";
                                          } else {
                                              echo "<option value='$product_id'>$product_title</option>";
                                          }
                                      }
                                      ?>
                                    </optgroup>

                                    <optgroup label="Select Bundle">

                                      <?php
                                      $get_bundles = "select * from products where (product_type='digital_product' or product_type='variable_product') and status='bundle'";
                                      $run_bundles = mysqli_query($con, $get_bundles);
                                      while ($row_bundles = mysqli_fetch_array($run_bundles)) {
                                          $product_id = $row_bundles['product_id'];
                                          $product_title = $row_bundles['product_title'];

                                          if ($product_id == $edit_product_id) {
                                              echo "<option value='$product_id' selected>$product_title</option>";
                                          } else {
                                              echo "<option value='$product_id'>$product_title</option>";
                                          }
                                      }
                                      ?>
                                    </optgroup>
                                </select>
                            </div>
                        </div><!-- form-group Ends -->

                        <div id="download-variations-div"><!-- download-variations-div Starts -->

                          <?php
                          if (!empty($edit_variation_id)) {
                            $get_product = "select * from products where product_id='$edit_product_id'";
                            $run_product = mysqli_query($con, $get_product);
                            $row_product = mysqli_fetch_array($run_product);
                            $product_type = $row_product['product_type'];
                            if ($product_type == "variable_product") {
                              ?>

                                <div class="form-group"><!-- form-group Starts -->
                                    <label class="col-md-3 control-label"> Select A Variation </label>
                                    <div class="col-md-6">
                                        <select name="variation_id" class="form-control" required>
                                            <option value=""> Select A Variation</option>

                                          <?php
                                          $select_product_variations = "select * from product_variations where product_id='$edit_product_id' and product_type='digital_product'";
                                          $run_product_variations = mysqli_query($con, $select_product_variations);
                                          while ($row_product_variations = mysqli_fetch_array($run_product_variations)) {
                                            $variation_id = $row_product_variations["variation_id"];
                                            if ($variation_id == $edit_variation_id) {
                                              echo "<option value='$variation_id' selected>Variation #$variation_id</option>";
                                            } else {
                                              echo "<option value='$variation_id'>Variation #$variation_id</option>";
                                            }
                                          }
                                          ?>
                                        </select>
                                    </div>
                                </div><!-- form-group Ends -->
                              <?php
                            }
                          }
                          ?>
                        </div><!-- download-variations-div Ends -->

                        <div class="form-group"><!-- form-group Starts -->
                            <label class="col-md-3 control-label">Download File</label>
                            <div class="col-md-6">
                                <input type="file" name="download_file" class="form-control"> <br>
                                <b><?php echo $edit_download_file; ?></b>
                            </div>
                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-6">
                                <input type="submit" name="submit" value="Update Download" class="btn btn-primary form-control">
                            </div>
                        </div><!-- form-group Ends -->
                    </form><!-- form-horizontal Ends -->
                </div><!-- panel-body Ends -->
            </div><!-- panel panel-default Ends -->
        </div><!-- col-lg-12 Ends -->
    </div><!-- 2 row Ends -->

<script>
     $(document).ready(function () {
         $("select[name='product_id']").on("change", function () {
             var product_id = $(this).val();
             $.ajax({
                 method: "POST",
                 url: "load_download_variations.php",
                 data: {product_id: product_id},
                 success: function (data) {
                     $("#download-variations-div").html(data);
                 }
             });
         });
     });
</script>

<?php

  if (isset($_POST['submit'])) {
      $download_title = $_POST['download_title'];
      $product_id = $_POST['product_id'];
      @$variation_id = $_POST['variation_id'];
      $download_file = $_FILES['download_file']['name'];
      $download_file_tmp = $_FILES['download_file']['tmp_name'];

    if (empty($download_file)) {
        $download_file = $edit_download_file;
    } else {
        move_uploaded_file($download_file_tmp, "downloads_uploads/$download_file");
    }
    $update_download = "update downloads set product_id='$product_id',variation_id='$variation_id',download_title='$download_title',download_file='$download_file' where download_id='$download_id'";
    $run_download = mysqli_query($con, $update_download);

    if ($run_download) {
        echo "<script>alert('Your Download Has Been Updated Successfully.');window.open('index.php?view_downloads','_self');</script>";
    }
  }
?>
<?php } ?>