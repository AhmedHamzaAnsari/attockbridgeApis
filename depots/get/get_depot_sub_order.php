<?php
//fetch.php  
include ("../../config.php");


$access_key = '03201232927';

$pass = $_GET["key"];
$id = $_GET["id"];
if ($pass != '') {
    if ($pass == $access_key) {
        $sql_query1 = "SELECT os.*,dd.name as depot_name,pp.name as product_name,om.depot_id
        FROM depots_order_main AS om
        JOIN depots_order_sub AS os ON os.main_id = om.id
        JOIN all_products AS pp ON pp.id = os.porduct_id
        join depots as dd on dd.id=om.depot_id where om.id='$id'";

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