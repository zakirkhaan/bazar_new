<?php

$con = mysqli_connect("localhost","root","","bazar_new");

if(isset($_SESSION["customer_email"])){

$customer_email = $_SESSION['customer_email'];

$select_customer = "select * from customers where customer_email='$customer_email'";

$run_customer = mysqli_query($con,$select_customer);

$row_customer = mysqli_fetch_array($run_customer);

$vendor_id = $row_customer['customer_id'];

$customer_role = $row_customer['customer_role'];

if($customer_role == "vendor"){

$select_vendor_commission = "select * from vendor_commissions where vendor_id='$vendor_id' and commission_status='pending'";

$run_vendor_commission = mysqli_query($con,$select_vendor_commission);

while($row_vendor_commission = mysqli_fetch_array($run_vendor_commission)){

$commission_id = $row_vendor_commission['commission_id'];

$order_id = $row_vendor_commission['order_id'];

$commission_paid_date = new DateTime($row_vendor_commission['commission_paid_date']);

date_default_timezone_set("UTC");

$current_date = new DateTime("now");

$select_vendor_order = "select * from vendor_orders where id='$order_id' and vendor_id='$vendor_id'";

$run_vendor_order = mysqli_query($con,$select_vendor_order);

$row_vendor_order = mysqli_fetch_array($run_vendor_order);

$shipping_cost = $row_vendor_order['shipping_cost'];

$net_amount = $row_vendor_order['net_amount'];

$vendor_commission = $net_amount + $shipping_cost;

if($current_date >= $commission_paid_date){
	
$update_vendor_account = "update vendor_accounts set pending_clearance=pending_clearance-$vendor_commission,current_balance=current_balance+$vendor_commission where vendor_id='$vendor_id'";
	
$run_vendor_account = mysqli_query($con,$update_vendor_account);
	
$update_vendor_commission = "update vendor_commissions set commission_status='cleared' where commission_id='$commission_id' and vendor_id='$vendor_id'";
	
$update_run_vendor_commission = mysqli_query($con,$update_vendor_commission);
	
}


}


}


}



?>