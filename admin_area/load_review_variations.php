<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['admin_email'])) {
  echo "<script>window.open('login.php','_self')</script>";

} else {
?>
<?php
  $product_id = mysqli_real_escape_string($con, $_POST['product_id']);
  $get_product = "select * from products where product_id='$product_id'";
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
                $select_product_variations = "select * from product_variations where product_id='$product_id' and not product_type='default_attributes_variation'";
                $run_product_variations = mysqli_query($con, $select_product_variations);
                while ($row_product_variations = mysqli_fetch_array($run_product_variations)) {
                  $variation_id = $row_product_variations["variation_id"];
                  echo "<option value='$variation_id'>Variation #$variation_id</option>";
                }
                ?>
              </select>
          </div>
      </div><!-- form-group Ends -->

<?php } else { ?>
      <input type="hidden" name="variation_id" value="">
<?php } ?>
<?php } ?>
