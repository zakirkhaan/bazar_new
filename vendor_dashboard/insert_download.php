<?php

if (!isset($_SESSION['customer_email'])) {

  echo "<script> window.open('../checkout.php','_self'); </script>";

}

$customer_email = $_SESSION['customer_email'];

$select_customer = "select * from customers where customer_email='$customer_email'";

$run_customer = mysqli_query($con, $select_customer);

$row_customer = mysqli_fetch_array($run_customer);

$customer_id = $row_customer['customer_id'];

?>


<div class="row"><!-- 2 row Starts --->

    <div class="col-lg-12"><!-- col-lg-12 Starts -->

        <div class="panel panel-default"><!-- panel panel-default Starts -->

            <div class="panel-heading"><!-- panel-heading Starts -->

                <h3 class="panel-title"><!-- panel-title Starts -->

                    <i class="fa fa-money fa-fw"> </i> Insert Download

                </h3><!-- panel-title Ends -->

            </div><!-- panel-heading Ends -->

            <div class="panel-body"><!--panel-body Starts -->

                <form class="form-horizontal" method="post" enctype="multipart/form-data">
                    <!-- form-horizontal Starts -->

                    <div class="form-group"><!-- form-group Starts -->

                        <label class="col-md-3 control-label">Download Title</label>

                        <div class="col-md-6">

                            <input type="text" name="download_title" class="form-control" required>

                        </div>

                    </div><!-- form-group Ends -->

                    <div class="form-group"><!-- form-group Starts -->

                        <label class="col-md-3 control-label">Select A Product/Bundle </label>

                        <div class="col-md-6">

                            <select name="product_id" class="form-control" required>

                                <optgroup label="Select Product">

                                  <?php

                                  $get_products = "select * from products where (product_type='digital_product' or product_type='variable_product') and vendor_id='$customer_id' and status='product'";

                                  $run_products = mysqli_query($con, $get_products);

                                  while ($row_products = mysqli_fetch_array($run_products)) {

                                    $product_id = $row_products['product_id'];

                                    $product_title = $row_products['product_title'];

                                    echo "<option value='$product_id'>$product_title</option>";

                                  }

                                  ?>

                                </optgroup>

                                <optgroup label="Select Bundle">

                                  <?php

                                  $get_bundles = "select * from products where (product_type='digital_product' or product_type='variable_product') and vendor_id='$customer_id' and status='bundle'";

                                  $run_bundles = mysqli_query($con, $get_bundles);

                                  while ($row_bundles = mysqli_fetch_array($run_bundles)) {

                                    $product_id = $row_bundles['product_id'];

                                    $product_title = $row_bundles['product_title'];

                                    echo "<option value='$product_id'>$product_title</option>";

                                  }

                                  ?>

                                </optgroup>

                            </select>

                        </div>

                    </div><!-- form-group Ends -->

                    <div id="download-variations-div"><!-- download-variations-div Starts -->

                    </div><!-- download-variations-div Ends -->

                    <div class="form-group"><!-- form-group Starts -->

                        <label class="col-md-3 control-label">Download File</label>

                        <div class="col-md-6">

                            <input type="file" name="download_file" class="form-control">

                        </div>

                    </div><!-- form-group Ends -->

                    <div class="form-group"><!-- form-group Starts -->

                        <label class="col-md-3 control-label"></label>

                        <div class="col-md-6">

                            <input type="submit" name="submit" value="Insert Download"
                                   class="btn btn-primary form-control">

                        </div>

                    </div><!-- form-group Ends -->

                </form><!-- form-horizontal Ends -->

            </div><!--panel-body Ends -->

        </div><!-- panel panel-default Ends -->

    </div><!-- col-lg-12 Ends -->

</div><!-- 2 row Ends --->

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

  move_uploaded_file($download_file_tmp, "../admin_area/downloads_uploads/$download_file");

  $insert_download = "insert into downloads (vendor_id,product_id,variation_id,download_title,download_file) values ('$customer_id','$product_id','$variation_id','$download_title','$download_file')";

  $run_download = mysqli_query($con, $insert_download);

  if ($run_download) {

    echo "

<script>

alert('New Download Has Been Inserted Successfully.');

window.open('index.php?downloads','_self');

</script>

";

  }

}

?>