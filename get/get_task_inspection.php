<?php
//fetch.php  
include("../config.php");


$access_key = '03201232927';

$pass = $_GET["key"];
if ($pass != '') {
    if ($pass == $access_key) {
        $pre = $_GET["pre"];
        $id = $_GET["user_id"];

        if ($pre == 'ZM') {

            $sql_query1 = "SELECT at.*,us.name as manager_name,dl.name as dealer_name,CASE
            WHEN at.status = 0 THEN 'Pending'
            WHEN at.status = 1 THEN 'Complete'
            WHEN at.status = 2 THEN 'Cancel'
            END AS current_status FROM inspector_task as at 
            join users as us on us.id=at.user_id
            join dealers as dl on dl.id=at.dealer_id ;";
        } elseif ($pre == 'TM') {

            $sql_query1 = "SELECT at.*,us.name as manager_name,dl.name as dealer_name,CASE
            WHEN at.status = 0 THEN 'Pending'
            WHEN at.status = 1 THEN 'Complete'
            WHEN at.status = 2 THEN 'Cancel'
            END AS current_status FROM inspector_task as at 
            join users as us on us.id=at.user_id
            join dealers as dl on dl.id=at.dealer_id where at.user_id=$id;";
        } elseif ($pre == 'ASM') {
            $sql_query1 = "SELECT at.*,us.name as manager_name,dl.name as dealer_name,CASE
            WHEN at.status = 0 THEN 'Pending'
            WHEN at.status = 1 THEN 'Complete'
            WHEN at.status = 2 THEN 'Cancel'
            END AS current_status FROM inspector_task as at 
            join users as us on us.id=at.user_id
            join dealers as dl on dl.id=at.dealer_id where at.user_id=$id;";

        } else {

            $sql_query1 = "SELECT at.*,us.name as manager_name,dl.name as dealer_name,CASE
            WHEN at.status = 0 THEN 'Pending'
            WHEN at.status = 1 THEN 'Complete'
            WHEN at.status = 2 THEN 'Cancel'
            END AS current_status FROM inspector_task as at 
            join users as us on us.id=at.user_id
             join dealers as dl on dl.id=at.dealer_id;";
        }

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