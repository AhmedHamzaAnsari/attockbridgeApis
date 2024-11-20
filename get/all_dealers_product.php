<?php
//fetch.php  
include("../config.php");


$access_key = '03201232927';

$pass = $_GET["key"];
if ($pass != '') {
    if ($pass == $access_key) {
        $sql_query1 = "SELECT p.*,pp.id as product_id,dl.name as dealer_name,pp.name as product_name FROM dealers_products as p
        join all_products as pp on p.name=pp.name 
        join dealers as dl on dl.id=p.dealer_id
        where dl.rettype='RT'
        order by p.update_time desc;";

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