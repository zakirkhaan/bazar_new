<?php if (!isset($_SESSION['admin_email'])) { echo "<script>window.open('login.php','_self')</script>"; } else { ?>

    <div class="row"><!-- 1 row Starts -->
        <div class="col-lg-12"><!-- col-lg-12 Starts -->
            <ol class="breadcrumb"><!-- breadcrumb Starts -->
                <li class="active">
                    <i class="fa fa-dashboard"></i> Dashboard / Insert Review
                </li>
            </ol><!-- breadcrumb Ends -->
        </div><!-- col-lg-12 Ends -->
    </div><!-- 1 row Ends -->

    <div class="row"><!-- 2 row Starts -->
        <div class="col-lg-12"><!-- col-lg-12 Starts -->
            <div class="panel panel-default"><!-- panel panel-default Starts -->
                <div class="panel-heading"><!-- panel-heading Starts -->
                    <h3 class="panel-title"><!-- panel-title Starts -->
                        <i class="fa fa-money fa-fw"></i> Insert Review
                    </h3><!-- panel-title Ends -->
                </div><!-- panel-heading Ends -->

                <div class="panel-body"><!-- panel-body Starts -->
                    <form class="form-horizontal" action="" method="post"><!-- form-horizontal Starts -->
                        <div class="form-group"><!-- form-group Starts -->
                            <label class="col-md-3 control-label"> Select A Customer: </label>
                            <div class="col-md-6">
                                <select name="customer_id" class="form-control" required>
                                    <option value="" class="hidden"> Select A Customer</option>

                                  <?php

                                  $select_customers = "select * from customers";

                                  $run_customers = mysqli_query($con, $select_customers);

                                  while ($row_customers = mysqli_fetch_array($run_customers)) {

                                    $customer_id = $row_customers['customer_id'];

                                    $customer_name = $row_customers['customer_name'];

                                    echo "<option value='$customer_id'>$customer_name</option>";

                                  }

                                  ?>

                                </select>

                            </div>

                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"> Select A Product/Bundle: </label>

                            <div class="col-md-6">

                                <select name="product_id" class="form-control" required>

                                    <option value="" class="hidden"> Select A Product/Bundle</option>

                                    <optgroup label="Select Product">

                                      <?php

                                      $select_products = "select * from products where status='product'";

                                      $run_products = mysqli_query($con, $select_products);

                                      while ($row_products = mysqli_fetch_array($run_products)) {

                                        $product_id = $row_products['product_id'];

                                        $product_title = $row_products['product_title'];

                                        echo "<option value='$product_id'>$product_title</option>";

                                      }

                                      ?>

                                    </optgroup>

                                    <optgroup label="Select Bundle">

                                      <?php

                                      $select_bundles = "select * from products where status='bundle'";

                                      $run_bundles = mysqli_query($con, $select_bundles);

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

                        <div id="review-variations-div"><!-- review-variations-div Starts -->

                        </div><!-- review-variations-div Ends -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"> Review Title: </label>

                            <div class="col-md-6">

                                <input type="text" name="review_title" class="form-control" required>

                            </div>

                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"> Review Rating: </label>

                            <div class="col-md-6">

                                <input type="hidden" id="rating" name="review_rating" class="rating-loading"
                                       data-size="sm" required>

                                <script>

                                    $(document).ready(function () {

                                        $("#rating").rating({

                                            step: 1,

                                            starCaptions: {
                                                1: 'I hate it',
                                                2: 'I do not like it',
                                                3: 'It is ok',
                                                4: 'I like it',
                                                5: 'It is perfect!'
                                            },

                                            starCaptionClasses: {
                                                1: 'btn btn-danger',
                                                2: 'btn btn-warning',
                                                3: 'btn btn-info',
                                                4: 'btn btn-primary',
                                                5: 'btn btn-success'
                                            },

                                            clearCaptionClass: "btn btn-default"

                                        });

                                    });

                                </script>

                            </div>

                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"> Review Content/Comment: </label>

                            <div class="col-md-6">

                                <textarea name="review_content" rows="5" class="form-control" required></textarea>

                            </div>

                        </div><!-- form-group Ends -->

                        <div class="form-group"><!-- form-group Starts -->

                            <label class="col-md-3 control-label"> </label>

                            <div class="col-md-6">

                                <input type="submit" name="submit" value="Insert Review"
                                       class="btn btn-success form-control">

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

                    url: "load_review_variations.php",

                    data: {product_id: product_id},

                    success: function (data) {

                        $("#review-variations-div").html(data);

                    }

                });

            });

        });

    </script>

  <?php

  if (isset($_POST['submit'])) {

    $customer_id = mysqli_real_escape_string($con, $_POST["customer_id"]);

    $product_id = mysqli_real_escape_string($con, $_POST["product_id"]);

    $variation_id = mysqli_real_escape_string($con, $_POST["variation_id"]);

    $review_title = mysqli_real_escape_string($con, $_POST["review_title"]);

    $review_rating = mysqli_real_escape_string($con, $_POST["review_rating"]);

    $review_content = mysqli_real_escape_string($con, $_POST["review_content"]);

    $review_date = date("F d, Y");

    $insert_review = "insert into reviews (customer_id,product_id,review_title,review_rating,review_content,review_date,review_status) values ('$customer_id','$product_id','$review_title','$review_rating','$review_content','$review_date','active')";

    $run_review = mysqli_query($con, $insert_review);

    $insert_review_id = mysqli_insert_id($con);

    if ($run_review) {

      if (!empty($variation_id)) {

        $insert_variation_id_meta = "insert into reviews_meta (review_id,meta_key,meta_value) values ('$insert_review_id','variation_id','$variation_id')";

        $run_variation_id_meta = mysqli_query($con, $insert_variation_id_meta);

        $select_variations_meta = "select * from variations_meta where variation_id='$variation_id'";

        $run_variations_meta = mysqli_query($con, $select_variations_meta);

        while ($row_variations_meta = mysqli_fetch_array($run_variations_meta)) {

          $meta_key = $row_variations_meta["meta_key"];

          $meta_value = $row_variations_meta["meta_value"];

          $insert_reviews_meta = "insert into reviews_meta (review_id,meta_key,meta_value) values ('$insert_review_id','$meta_key','$meta_value')";

          $run_reviews_meta = mysqli_query($con, $insert_reviews_meta);
        }
      }
      echo "<script>alert('Your Review Has Been Inserted Sccessfully.');window.open('index.php?view_reviews','_self');</script>";
    }
  }
  ?>


<?php } ?>