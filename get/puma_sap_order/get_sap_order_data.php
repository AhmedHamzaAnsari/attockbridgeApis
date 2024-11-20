<?php
//fetch.php  
include("../../config.php");


$access_key = '03201232927';

$pass = $_GET["key"];
// $id=$_GET["id"];
if ($pass != '') {
    if ($pass == $access_key) {
        $from = $_GET["from"];
        $to = $_GET["to"];


         $sql_query1 = "SELECT si.*,dc.id as vehicle_id,dc.name as vehicle_name,IF(dc.name IS NOT NULL, 'With-Tracker', 'Without-Tracker') AS tracker_status,oi.carrier_desc as depot_name,dc.id as uniqueId,'' as end_time,pp.name as product_name FROM order_info as oi
        left join devicesnew as dc on TRIM(SUBSTRING_INDEX(dc.name, ' ', 1))=oi.vehicle 
        join order_sales_invoice as si on si.order_no=oi.order_no
        join all_products as pp on pp.sap_no=oi.item
        where oi.created_at>='$from' and oi.created_at<='$to' group by oi.order_no order by oi.id desc";

        $result1 = $db->query($sql_query1) or die("Error :" . mysqli_error());

        $thread = array();
        while ($user = $result1->fetch_assoc()) {
            $thread[] = $user;
        }
        echo json_encode($thread);

    } else {
        echo 'Wrong Key...';
    }

} else {
    echo 'Key is Required';
}

?>