<?php
//fetch.php  
include("../config.php");


$access_key = '03201232927';

$pass = $_GET["key"];
if ($pass != '') {
    $id = $_GET["id"];
    if ($pass == $access_key) {
        $sql_query1 = "SELECT od.*,dl.name as dealer_name,us.name as username,ll.created_at as approved_time FROM omcs_dealers AS od 
        join omcs_dealers_log as ll on ll.main_id=od.id
                join dealers as dl on dl.id=od.old_dealer_id
                join users as us on us.id=od.created_by
                where od.status=1 order by od.id desc;";

        $result1 = $db->query($sql_query1) or die("Error :" . mysqli_error($db));

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