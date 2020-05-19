<?php
session_start();
include("includes/db.php");
include("functions/functions.php");
$vendor_id = $_REQUEST['vendor_id'];
switch ($_REQUEST['sAction']) {
  default :
    getProducts($vendor_id);
    break;
  case'getPaginator';
    getPaginator($vendor_id);
    break;
}
?>