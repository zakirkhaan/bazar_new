<?php

@session_start();

if (!isset($_SESSION['customer_email'])) {

  echo "<script>window.open('../checkout.php','_self')</script>";

}

?>

<div style="text-align: center;"><!-- center Starts -->

    <h1> My Wishlist </h1>

    <p class="lead"> Your all Wishlist Products on one place. </p>

</div><!-- center Ends -->

<hr>

<div class="table-responsive"><!-- table-responsive Starts -->

    <table class="table table-bordered table-hover"><!-- table table-bordered table-hover Starts -->

        <thead>

        <tr>

            <th> Wishlist No:</th>

            <th colspan="2"> Wishlist Product</th>

            <th> Delete Wishlist</th>

        </tr>

        </thead>

        <tbody>

        <?php

        $customer_session = $_SESSION['customer_email'];

        $get_customer = "select * from customers where customer_email='$customer_session'";

        $run_customer = mysqli_query($con, $get_customer);

        $row_customer = mysqli_fetch_array($run_customer);

        $customer_id = $row_customer['customer_id'];

        $i = 0;

        $get_wishlist = "select * from wishlist where customer_id='$customer_id'";

        $run_wishlist = mysqli_query($con, $get_wishlist);

        while ($row_wishlist = mysqli_fetch_array($run_wishlist)) {

          $wishlist_id = $row_wishlist['wishlist_id'];

          $product_id = $row_wishlist['product_id'];

          $get_products = "select * from products where product_id='$product_id'";

          $run_products = mysqli_query($con, $get_products);

          $row_products = mysqli_fetch_array($run_products);

          $product_title = $row_products['product_title'];

          $product_url = $row_products['product_url'];

          $product_img1 = $row_products['product_img1'];

          $i++;

          ?>

            <tr>

                <td width="100"> <?php echo $i; ?> </td>

                <td>

                    <img src="../admin_area/product_images/<?php echo $product_img1; ?>" width="60" height="60">

                </td>

                <td>

                    <a class="h4" href="../<?php echo $product_url; ?>">

                      <?php echo $product_title; ?>

                    </a>

                    <p class="cart-product-meta">

                      <?php

                      $wishlist_meta = "";

                      $select_wishlist_meta = "select * from wishlist_meta where wishlist_id='$wishlist_id' and not meta_key='variation_id'";

                      $run_wishlist_meta = mysqli_query($con, $select_wishlist_meta);

                      while ($row_wishlist_meta = mysqli_fetch_array($run_wishlist_meta)) {

                        $meta_key = ucwords($row_wishlist_meta["meta_key"]);

                        $meta_value = $row_wishlist_meta["meta_value"];

                        $wishlist_meta .= "$meta_key: <span class='text-muted'> $meta_value </span>, ";

                      }

                      echo rtrim($wishlist_meta, ", ");

                      ?>

                    </p>

                </td>

                <td>

                    <a href="my_account.php?delete_wishlist=<?php echo $wishlist_id; ?>" class="btn btn-primary">

                        <i class="fa fa-trash-o"> </i> Delete

                    </a>

                </td>

            </tr>

        <?php } ?>

        </tbody>

    </table><!-- table table-bordered table-hover Ends -->

</div><!-- table-responsive Ends -->