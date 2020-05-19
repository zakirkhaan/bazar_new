<div style="margin-bottom:20px; text-align: center;"><!-- center Starts -->

<?php
$get_icons = "select * from icons where icon_product='$pro_id'";
$run_icons = mysqli_query($con,$get_icons);
while($row_icons = mysqli_fetch_array($run_icons)){
$icon_image = $row_icons['icon_image'];
echo "<img src='admin_area/icon_images/$icon_image' width='45' height='45' > &nbsp;&nbsp;&nbsp; ";
}
?>

</div><!-- center Ends -->