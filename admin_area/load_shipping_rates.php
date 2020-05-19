<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['admin_email'])) {
    echo "<script>window.open('login.php','_self')</script>";

} else {
    $type_id = $_GET["type_id"];
    if (isset($_GET["zone_id"])) {
        $zone_id = $_GET["zone_id"];
//get shipping rates for zones/
        $get_shipping_rates = "SELECT s.*,IF ((SELECT COUNT(shipping_weight) FROM shipping 
                                WHERE shipping_type = s.shipping_type AND shipping_zone = s.shipping_zone 
                                AND shipping_weight < s.shipping_weight ORDER BY shipping_weight DESC LIMIT 0, 1) > 0,
                                (SELECT shipping_weight FROM shipping WHERE shipping_type = s.shipping_type
                                AND shipping_zone = s.shipping_zone AND shipping_weight < s.shipping_weight 
                                ORDER BY shipping_weight DESC LIMIT 0, 1 ) + 0.01, 0) AS shipping_weight_from
                               FROM shipping s WHERE s.shipping_type = $type_id AND s.shipping_zone = $zone_id 
                               ORDER BY s.shipping_weight ASC ";

    } elseif (isset($_GET["country_id"])) {
        $country_id = $_GET["country_id"];
//get shipping rates for country
        $get_shipping_rates = "SELECT s.*, IF (( SELECT COUNT(shipping_weight) FROM shipping 
                                WHERE shipping_type = s.shipping_type AND shipping_country = s.shipping_country 
                                AND shipping_weight < s.shipping_weight ORDER BY shipping_weight DESC LIMIT 0, 1) > 0,
                                ( SELECT shipping_weight FROM shipping WHERE shipping_type = s.shipping_type 
                                AND shipping_country = s.shipping_country AND shipping_weight < s.shipping_weight
                                ORDER BY shipping_weight DESC LIMIT 0, 1 ) + 0.01, 0 ) AS shipping_weight_from 
                                FROM shipping s WHERE s.shipping_type = $type_id AND s.shipping_country = $country_id 
                                ORDER BY s.shipping_weight ASC ";
    }
    $run_shipping_rates = mysqli_query($con, $get_shipping_rates);
    while ($row_shipping_rates = mysqli_fetch_array($run_shipping_rates)) {
        $shipping_id = $row_shipping_rates['shipping_id'];
        $shipping_type = $row_shipping_rates['shipping_type'];
        $shipping_weight = $row_shipping_rates['shipping_weight'];
        $shipping_weight_from = $row_shipping_rates['shipping_weight_from'];
        $shipping_cost = $row_shipping_rates['shipping_cost'];
?>

      <tr>
          <td><?php echo $shipping_weight_from; ?> <small>Kg</small></td>
          <td><?php echo $shipping_weight; ?> <small>Kg</small></td>
          <td>Rs:<?php echo $shipping_cost; ?></td>

          <td>
              <a href="#" id="delete_shipping_rate_<?php echo $shipping_id; ?>">
                  <i class="fa fa-trash-o"></i> Delete
              </a>
          </td>

<script>
     $(document).ready(function () {
         $("#delete_shipping_rate_<?php echo $shipping_id; ?>").click(function () {
             $.ajax({
                 method: "POST",
                 url: "delete_shipping_rate.php",
                 data: {delete_id: <?php echo $shipping_id; ?>, type_id: <?php echo $type_id; ?> }
             });
         });
     });
</script>
      </tr>
<?php }
} ?>