<?php

if (!isset($_SESSION['admin_email'])) {

  echo "<script>window.open('login.php','_self')</script>";

} else {

  $filter_where = "";

  $filter_url = "";

  function check_and_function($filter_where)
  {

    if (!empty($filter_where)) {

      return "and";

    }

  }

  if (isset($_REQUEST["customer_id"]) and isset($_REQUEST["product_id"]) and isset($_REQUEST["review_rating"])) {

    $customer_id = mysqli_real_escape_string($con, $_REQUEST["customer_id"]);

    $product_id = mysqli_real_escape_string($con, $_REQUEST["product_id"]);

    $review_rating = mysqli_real_escape_string($con, $_REQUEST["review_rating"]);

    if (!empty($customer_id)) {

      $filter_where .= " customer_id='$customer_id' ";

    }

    if (!empty($product_id)) {

      $and = check_and_function($filter_where);

      $filter_where .= "$and product_id='$product_id' ";

    }

    if (!empty($review_rating)) {

      $and = check_and_function($filter_where);

      $filter_where .= "$and review_rating='$review_rating' ";

    }

    $filter_url = "&customer_id=$customer_id&product_id=$product_id&review_rating=$review_rating";

  }

  function get_reviews_status_count($review_status)
  {

    global $con;

    global $filter_where;

    $reviews_count_where = $filter_where;

    if ($review_status != "all") {

      if (!empty($reviews_count_where)) {

        $reviews_count_where .= " and review_status='$review_status'";

      } else {

        $reviews_count_where .= "review_status='$review_status'";

      }

    }

    if (empty($reviews_count_where) and $review_status == "all") {

      $select_reviews = "select * from reviews";

    } else {

      $select_reviews = "select * from reviews where $reviews_count_where";

    }

    $run_reviews = mysqli_query($con, $select_reviews);

    echo $count_reviews = mysqli_num_rows($run_reviews);

  }

  function echo_active_class($review_status)
  {

    if ((!isset($_REQUEST['review_status']) and $review_status == "all") or (isset($_REQUEST['review_status']) and $review_status == $_REQUEST['review_status'])) {

      echo "active-link";

    }

  }

  ?>

    <div class="row"><!-- 1 row Starts -->

        <div class="col-lg-12"><!-- col-lg-12 Starts -->

            <ol class="breadcrumb"><!-- breadcrumb Starts -->

                <li class="active">

                    <i class="fa fa-dashboard"></i> Dashboard / View Reviews

                </li>

            </ol><!-- breadcrumb Ends -->

        </div><!-- col-lg-12 Ends -->

    </div><!-- 1 row Ends -->

    <div class="row"><!-- 2 row Starts -->

        <div class="col-lg-12"><!-- col-lg-12 Starts -->

            <div class="panel panel-default"><!-- panel panel-default Starts -->

                <div class="panel-body"><!-- panel-body Starts -->

                    <h3 style="margin-top:0px;"> Filter Reviews </h3>

                    <form method="post" action="index.php?view_reviews=1"><!--- form Starts --->

                        <div class="row"><!-- row Starts -->

                            <div class="col-md-3 col-sm-6"><!-- col-md-3 col-sm-6 Starts -->

                                <div class="form-group"><!--- form-group Starts --->

                                    <label> Select A Customer : </label>

                                    <select name="customer_id" class="form-control">

                                        <option value=""> Select A Review Customer</option>

                                      <?php

                                      $select_customers = "select * from customers";

                                      $run_customers = mysqli_query($con, $select_customers);

                                      while ($row_customers = mysqli_fetch_array($run_customers)) {

                                        $customer_id = $row_customers['customer_id'];

                                        $customer_name = $row_customers['customer_name'];

                                        if (@$_REQUEST["customer_id"] == $customer_id) {

                                          echo "<option value='$customer_id' selected>$customer_name</option>";

                                        } else {

                                          echo "<option value='$customer_id'>$customer_name</option>";

                                        }

                                      }

                                      ?>

                                    </select>

                                </div><!--- form-group Ends --->

                            </div><!-- col-md-3 col-sm-6 Ends -->

                            <div class="col-md-3 col-sm-6"><!-- col-md-3 col-sm-6 Starts -->

                                <div class="form-group"><!--- form-group Starts --->

                                    <label> Select A Product/Bundle: </label>

                                    <select name="product_id" class="form-control">

                                        <option value=""> Select A Review Product/Bundle</option>

                                        <optgroup label="Select Product">

                                          <?php

                                          $select_products = "select * from products where status='product'";

                                          $run_products = mysqli_query($con, $select_products);

                                          while ($row_products = mysqli_fetch_array($run_products)) {

                                            $product_id = $row_products['product_id'];

                                            $product_title = $row_products['product_title'];

                                            if (@$_REQUEST["product_id"] == $product_id) {

                                              echo "<option value='$product_id' selected>$product_title</option>";

                                            } else {

                                              echo "<option value='$product_id'>$product_title</option>";

                                            }

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

                                </div><!--- form-group Ends --->

                            </div><!-- col-md-3 col-sm-6 Ends -->

                            <div class="col-md-3 col-sm-6"><!-- col-md-3 col-sm-6 Starts -->

                                <div class="form-group"><!--- form-group Starts --->

                                    <label> Select A Rating: </label>

                                    <select name="review_rating" class="form-control">

                                        <option value=""> Select A Review Rating</option>

                                        <option value="1" <?php if (isset($_REQUEST["review_rating"]) and $_REQUEST["review_rating"] == 1) {
                                          echo "selected";
                                        } ?>> 1 Star
                                        </option>

                                        <option value="2" <?php if (isset($_REQUEST["review_rating"]) and $_REQUEST["review_rating"] == 2) {
                                          echo "selected";
                                        } ?>> 2 Stars
                                        </option>

                                        <option value="3" <?php if (isset($_REQUEST["review_rating"]) and $_REQUEST["review_rating"] == 3) {
                                          echo "selected";
                                        } ?>> 3 Stars
                                        </option>

                                        <option value="4" <?php if (isset($_REQUEST["review_rating"]) and $_REQUEST["review_rating"] == 4) {
                                          echo "selected";
                                        } ?>> 4 Stars
                                        </option>

                                        <option value="5" <?php if (isset($_REQUEST["review_rating"]) and $_REQUEST["review_rating"] == 5) {
                                          echo "selected";
                                        } ?>> 5 Stars
                                        </option>

                                    </select>

                                </div><!--- form-group Ends --->

                            </div><!-- col-md-3 col-sm-6 Ends -->

                          <?php if (isset($_REQUEST["review_status"])) { ?>

                              <input type="hidden" name="review_status"
                                     value="<?php echo $_REQUEST["review_status"]; ?>">

                          <?php } ?>

                            <div class="col-md-3 col-sm-6"><!-- col-md-3 col-sm-6 Starts -->

                                <label></label>

                                <button type="submit" class="btn btn-success form-control"> Filter Reviews</button>

                            </div><!-- col-md-3 col-sm-6 Ends -->

                        </div><!-- row Ends -->

                    </form><!--- form Ends --->

                </div><!-- panel-body Ends -->

            </div><!-- panel panel-default Ends -->

        </div><!-- col-lg-12 Ends -->

    </div><!-- 2 row Ends -->

    <div class="row"><!-- 2 row Starts -->

        <div class="col-lg-12"><!-- col-lg-12 Starts -->

            <div class="panel panel-default"><!-- panel panel-default Starts -->

                <div class="panel-heading"><!-- panel-heading Starts -->

                    <h3 class="panel-title"><!-- panel-title Starts -->

                        <i class="fa fa-money fa-fw"></i> View Reviews

                    </h3><!-- panel-title Ends -->

                </div><!-- panel-heading Ends -->

                <div class="panel-body"><!-- panel-body Starts -->

                    <a href="index.php?view_reviews=1&review_status=all<?php echo $filter_url; ?>"
                       class="link-separator <?php echo_active_class("all"); ?>">

                        All (<?php get_reviews_status_count("all"); ?>)

                    </a>

                    <a class="link-separator">|</a>

                    <a href="index.php?view_reviews=1&review_status=pending<?php echo $filter_url; ?>"
                       class="link-separator <?php echo_active_class("pending"); ?>">

                        Pending (<?php get_reviews_status_count("pending"); ?>)

                    </a>

                    <a class="link-separator">|</a>

                    <a href="index.php?view_reviews=1&review_status=active<?php echo $filter_url; ?>"
                       class="link-separator <?php echo_active_class("active"); ?>">

                        Approved (<?php get_reviews_status_count("active"); ?>)

                    </a>

                    <a class="link-separator">|</a>

                    <a href="index.php?view_reviews=1&review_status=spam<?php echo $filter_url; ?>"
                       class="link-separator <?php echo_active_class("spam"); ?>">

                        Spam (<?php get_reviews_status_count("spam"); ?>)

                    </a>

                    <a class="link-separator">|</a>

                    <a href="index.php?view_reviews=1&review_status=trash<?php echo $filter_url; ?>"
                       class="link-separator <?php echo_active_class("trash"); ?>">

                        Trash (<?php get_reviews_status_count("trash"); ?>)

                    </a>

                    <br><br>

                    <div class="table-responsive"><!-- table-responsive Starts -->

                        <table class="table table-bordered table-hover table-striped">
                            <!-- table-bordered table-hover table-striped Starts -->

                            <thead><!-- thead Starts -->

                            <tr>

                                <th>Number:</th>

                                <th>Review Product:</th>

                                <th>Review Content:</th>

                                <th>Review Rating:</th>

                                <th>Date:</th>

                                <th>Actions:</th>

                            </tr>

                            </thead><!-- thead Ends -->

                            <tbody><!-- tbody Starts -->

                            <?php

                            $per_page = 10;

                            if (!empty($_GET["view_reviews"])) {

                              $page = $_GET["view_reviews"];

                            } else {

                              $page = 1;

                            }

                            // Page will start from 0 and Multiple by Per Page
                            $start_from = ($page - 1) * $per_page;

                            //Selecting the data from table but with limit

                            if (

                              (isset($_REQUEST["customer_id"]) and isset($_REQUEST["product_id"]) and isset($_REQUEST["review_rating"]))

                              or isset($_REQUEST["review_status"])

                            ) {

                              if (isset($_REQUEST["review_status"])) {

                                if ($_REQUEST["review_status"] != "all") {

                                  $review_status = $_REQUEST["review_status"];

                                  if (empty($filter_where)) {

                                    $filter_where .= "review_status='$review_status'";

                                  } else {

                                    $filter_where .= "and review_status='$review_status'";

                                  }

                                }

                              }

                              if (empty($filter_where)) {

                                $select_product_reviews = "select * from reviews order by 1 desc LIMIT $start_from,$per_page";

                              } else {

                                $select_product_reviews = "select * from reviews where $filter_where order by 1 desc LIMIT $start_from,$per_page";

                              }

                            } else {

                              $select_product_reviews = "select * from reviews order by 1 desc LIMIT $start_from,$per_page";

                            }

                            $run_product_reviews = mysqli_query($con, $select_product_reviews);

                            $count_product_reviews = mysqli_num_rows($run_product_reviews);

                            if ($count_product_reviews == 0) {

                              ?>

                                <tr>

                                    <td colspan="8" class="text-center">

                                        <h3> Sorry, We Have Not Found Any Reviews. </h3>

                                    </td>

                                </tr>

                              <?php

                            }

                            $i = $start_from;

                            while ($row_product_reviews = mysqli_fetch_array($run_product_reviews)) {

                              $review_id = $row_product_reviews['review_id'];

                              $product_id = $row_product_reviews['product_id'];

                              $customer_id = $row_product_reviews['customer_id'];

                              $review_title = $row_product_reviews['review_title'];

                              $review_rating = $row_product_reviews['review_rating'];

                              $review_content = $row_product_reviews['review_content'];

                              $review_date = $row_product_reviews['review_date'];

                              $review_status = $row_product_reviews['review_status'];

                              $get_product = "select * from products where product_id='$product_id'";

                              $run_product = mysqli_query($con, $get_product);

                              $row_product = mysqli_fetch_array($run_product);

                              $product_title = substr($row_product['product_title'], 0, 60);

                              $select_customer = "select * from customers where customer_id='$customer_id'";

                              $run_customer = mysqli_query($con, $select_customer);

                              $row_customer = mysqli_fetch_array($run_customer);

                              $review_customer_name = $row_customer['customer_name'];

                              $review_customer_image = $row_customer['customer_image'];

                              $i++;

                              ?>

                                <tr>

                                    <td><?php echo $i; ?></td>

                                    <td bgcolor="#ebebeb">

                                        <strong> <?php echo $product_title; ?> </strong> <br>

                                        <span class="review-meta">

<?php

$review_meta = "";

$select_review_meta = "select * from reviews_meta where review_id='$review_id' and meta_key!='variation_id'";

$run_review_meta = mysqli_query($con, $select_review_meta);

while ($row_review_meta = mysqli_fetch_array($run_review_meta)) {

  $meta_key = ucwords($row_review_meta["meta_key"]);

  $meta_value = $row_review_meta["meta_value"];

  $review_meta .= "$meta_key: $meta_value | ";

}

echo rtrim($review_meta, "| ");

?>

</span>

                                    </td>

                                    <td width="320">

                                        <strong> <?php echo $review_title; ?> </strong> <br>

                                        By <span class="text-muted"><?php echo $review_customer_name; ?></span>

                                        <p><?php echo $review_content; ?></p>

                                    </td>

                                    <td width="120">

                                      <?php echo $review_rating; ?> &nbsp;

                                      <?php for ($review_rating_i = 0; $review_rating_i < $review_rating; $review_rating_i++) { ?>

                                          <img class="rating" src="../images/star_full_small.png">

                                      <?php } ?>

                                      <?php for ($review_rating_i = $review_rating; $review_rating_i < 5; $review_rating_i++) { ?>

                                          <img class="rating" src="../images/star_blank_small.png">

                                      <?php } ?>

                                    </td>

                                    <td><?php echo $review_date; ?></td>

                                    <td>

                                        <div class="dropdown"><!-- dropdown Starts -->

                                            <button class="btn btn-success dropdown-toggle" data-toggle="dropdown">

                                                <span class="caret"></span>

                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-right">

                                              <?php if ($review_status == "pending") { ?>

                                                  <li>

                                                      <a href="index.php?change_review_status=<?php echo $review_id; ?>&status=active">

                                                          <i class="fa fa-thumbs-up"></i> Approve

                                                      </a>

                                                  </li>

                                              <?php } elseif ($review_status == "active") { ?>

                                                  <li>

                                                      <a href="index.php?change_review_status=<?php echo $review_id; ?>&status=pending">

                                                          <i class="fa fa-thumbs-down"></i> Unapprove

                                                      </a>

                                                  </li>

                                              <?php } ?>

                                              <?php if ($review_status == "active" or $review_status == "pending") { ?>

                                                  <li>

                                                      <a href="index.php?change_review_status=<?php echo $review_id; ?>&status=spam">

                                                          <i class="fa fa-ban"></i> Spam

                                                      </a>

                                                  </li>

                                              <?php } elseif ($review_status == "spam") { ?>

                                                  <li>

                                                      <a href="index.php?change_review_status=<?php echo $review_id; ?>&status=unspam">

                                                          <i class="fa fa-ban"></i> Not Spam

                                                      </a>

                                                  </li>

                                              <?php } ?>

                                              <?php if ($review_status != "trash") { ?>

                                                  <li>

                                                      <a href="index.php?edit_review=<?php echo $review_id; ?>">

                                                          <i class="fa fa-pencil"></i> Edit

                                                      </a>

                                                  </li>

                                                  <li>

                                                      <a href="index.php?change_review_status=<?php echo $review_id; ?>&status=trash">

                                                          <i class="fa fa-trash-o"></i> Trash

                                                      </a>

                                                  </li>

                                              <?php } elseif ($review_status == "trash") { ?>

                                                  <li>

                                                      <a href="index.php?change_review_status=<?php echo $review_id; ?>&status=restore">

                                                          <i class="fa fa-arrow-left"></i> Restore Review

                                                      </a>

                                                  </li>

                                                  <li>

                                                      <a href="index.php?delete_review=<?php echo $review_id; ?>">

                                                          <i class="fa fa-trash-o"></i> Delete Permanently

                                                      </a>

                                                  </li>

                                              <?php } ?>

                                            </ul>

                                        </div><!-- dropdown Ends -->

                                    </td>

                                </tr>

                            <?php } ?>

                            </tbody><!-- tbody Ends -->

                        </table><!-- table-bordered table-hover table-striped Ends -->

                    </div><!-- table-responsive Ends -->

                    <div style="text-align: center;"><!-- center Starts -->

                        <ul class="pagination"><!-- pagination Starts -->

                          <?php

                          if (
                            (isset($_REQUEST["customer_id"]) and isset($_REQUEST["product_id"]) and isset($_REQUEST["review_rating"]))

                            or isset($_REQUEST["review_status"])
                          ) {

                            if (isset($_REQUEST["review_status"])) {

                              if ($_REQUEST["review_status"] != "all") {

                                $review_status = $_REQUEST["review_status"];

                                $filter_url .= "&review_status=$review_status";

                              }

                            }

                            if (empty($filter_where)) {

                              $select_reviews = "select * from reviews";

                            } else {

                              $select_reviews = "select * from reviews where $filter_where";

                            }

                          } else {

                            $select_reviews = "select * from reviews order by 1 desc";

                          }

                          $run_reviews = mysqli_query($con, $select_reviews);

                          // Count the total records

                          $count_reviews = mysqli_num_rows($run_reviews);

                          //Using ceil function to divide the total records on per page

                          $total_pages = ceil($count_reviews / $per_page);

                          //Going to first page

                          echo "

<li class='page-item'>

<a href='index.php?view_reviews=1$filter_url' class='page-link'>

First Page

</a>

</li>

";

                          for ($i = max(1, $page - 3); $i <= min($page + 3, $total_pages); $i++) {

                            if ($i == $page) {

                              $active = "active";

                            } else {

                              $active = "";

                            }

                            echo "<li class='page-item $active'><a href='index.php?view_reviews=$i$filter_url' class='page-link'>$i</a></li>";

                          }

                          // Going to last page

                          echo "<li class='page-item'><a href='index.php?view_reviews=$total_pages$filter_url' class='page-link'>Last Page</a></li>";

                          ?>

                        </ul><!-- pagination Ends -->

                    </div><!-- center Ends -->

                </div><!-- panel-body Ends -->

            </div><!-- panel panel-default Ends -->

        </div><!-- col-lg-12 Ends -->

    </div><!-- 2 row Ends -->


<?php } ?>