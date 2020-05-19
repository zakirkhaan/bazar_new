<?php

if (!isset($_SESSION['customer_email'])) {
  echo "<script> window.open('../checkout.php','_self'); </script>";
}

$customer_email = $_SESSION['customer_email'];
$get_customer = "select * from customers where customer_email='$customer_email'";
$run_customer = mysqli_query($con, $get_customer);
$row_customer = mysqli_fetch_array($run_customer);
$customer_id = $row_customer['customer_id'];

function echo_active_class($review_status)
{
  if ((!isset($_GET['review_status']) and $review_status == "all") or (isset($_GET['review_status']) and $review_status == $_GET['review_status'])) {
    echo "active-link";
  }
}

$products_ids = array();
$select_products = "select * from products where vendor_id='$customer_id'";
$run_products = mysqli_query($con, $select_products);

while ($row_products = mysqli_fetch_array($run_products)) {
  $product_id = $row_products["product_id"];
  $product_views = $row_products["product_views"];
  array_push($products_ids, $product_id);
}

function get_reviews_status_count($review_status)
{
  global $con;
  global $products_ids;
  $reviews_products_ids = implode(",", $products_ids);
  if (empty($review_status)) {
    $select_reviews = "select * from reviews where product_id in ($reviews_products_ids) and not review_status='trash'";
  } else {
    $select_reviews = "select * from reviews where product_id in ($reviews_products_ids) and review_status='$review_status'";
  }
  $run_reviews = mysqli_query($con, $select_reviews);
  echo $count_reviews = mysqli_num_rows($run_reviews);
}
?>

<div class="row"><!-- 2 row Starts -->
    <div class="col-lg-12"><!-- col-lg-12 Starts -->
        <div class="panel panel-default"><!-- panel panel-default Starts -->
            <div class="panel-heading"><!-- panel-heading Starts -->
                <h3 class="panel-title"><!-- panel-title Starts -->
                    <i class="fa fa-money fa-fw"></i> View Reviews
                </h3><!-- panel-title Ends -->
            </div><!-- panel-heading Ends -->
            <div class="panel-body"><!-- panel-body Starts -->
                <a href="index.php?reviews&review_status=all" class="link-separator <?php echo_active_class("all"); ?>">
                    All (<?php get_reviews_status_count(""); ?>)
                </a>
                <a class="link-separator">|</a>
                <a href="index.php?reviews&review_status=pending"
                   class="link-separator <?php echo_active_class("pending"); ?>">

                    Pending (<?php get_reviews_status_count("pending"); ?>)

                </a>

                <a class="link-separator">|</a>

                <a href="index.php?reviews&review_status=active"
                   class="link-separator <?php echo_active_class("active"); ?>">

                    Approved (<?php get_reviews_status_count("active"); ?>)

                </a>

                <a class="link-separator">|</a>

                <a href="index.php?reviews&review_status=spam"
                   class="link-separator <?php echo_active_class("spam"); ?>">

                    Spam (<?php get_reviews_status_count("spam"); ?>)

                </a>

                <br><br>

                <div class="table-responsive"><!-- table-responsive Starts -->

                    <table class="table table-bordered table-hover table-striped">
                        <!-- table table-bordered table-hover table-striped Starts -->

                        <thead><!-- thead Starts -->

                        <tr>

                            <th> Number:</th>

                            <th> Review Product:</th>

                            <th> Review Content:</th>

                            <th> Review Rating:</th>

                            <th> Actions:</th>

                        </tr>

                        </thead><!-- thead Ends -->

                        <tbody><!-- tbody Starts -->

                        <?php

                        $products_ids = array();

                        $select_products = "select * from products where vendor_id='$customer_id'";

                        $run_products = mysqli_query($db, $select_products);

                        while ($row_products = mysqli_fetch_array($run_products)) {

                          $product_id = $row_products["product_id"];

                          array_push($products_ids, $product_id);

                        }

                        $product_ids = implode(",", $products_ids);

                        if (!isset($_GET['review_status']) or $_GET['review_status'] == "all") {

                          $select_product_reviews = "select * from reviews where product_id in ($product_ids) and not review_status='trash' order by 1 desc";

                        } elseif (isset($_GET['review_status'])) {

                          $review_status = $_GET['review_status'];

                          $select_product_reviews = "select * from reviews where product_id in ($product_ids) and review_status='$review_status' order by 1 desc";

                        }

                        $run_product_reviews = mysqli_query($con, $select_product_reviews);

                        $i = 0;

                        while ($row_product_reviews = mysqli_fetch_array($run_product_reviews)) {

                          $i++;

                          $review_id = $row_product_reviews['review_id'];

                          $product_id = $row_product_reviews['product_id'];

                          $customer_id = $row_product_reviews['customer_id'];

                          $review_title = $row_product_reviews['review_title'];

                          $review_rating = $row_product_reviews['review_rating'];

                          $review_content = $row_product_reviews['review_content'];

                          $review_date = $row_product_reviews['review_date'];

                          $review_status = $row_product_reviews['review_status'];

                          $select_product = "select * from products where product_id='$product_id'";

                          $run_product = mysqli_query($con, $select_product);

                          $row_product = mysqli_fetch_array($run_product);

                          $product_title = substr($row_product['product_title'], 0, 60);

                          $select_customer = "select * from customers where customer_id='$customer_id'";

                          $run_customer = mysqli_query($con, $select_customer);

                          $row_customer = mysqli_fetch_array($run_customer);

                          $review_customer_name = $row_customer['customer_name'];

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

                                    By <span class="text-muted"><?php echo $review_customer_name; ?> </span>

                                    <br> <span class="text-muted"> Submitted on <?php echo $review_date; ?> </span>

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
                                          <?php if ($review_status != "spam") { ?>
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
                                            <li>
                                                <a href="index.php?change_review_status=<?php echo $review_id; ?>&status=trash">
                                                    <i class="fa fa-trash-o"></i> Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div><!-- dropdown Ends -->
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody><!-- tbody Ends -->
                    </table><!-- table table-bordered table-hover table-striped Ends -->
                </div><!-- table-responsive Ends -->
            </div><!-- panel-body Ends -->
        </div><!-- panel panel-default Ends -->
    </div><!-- col-lg-12 Ends -->
</div><!-- 2 row Ends -->