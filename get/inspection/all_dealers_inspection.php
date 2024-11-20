<?php
//fetch.php  
include("../../config.php");


$access_key = '03201232927';

$pass = $_GET["key"];
$from = $_GET["from"];
$to = $_GET["to"];
// $id=$_GET["id"];
if ($pass != '') {
    if ($pass == $access_key) {
        // $sql_query1 = "SELECT *,us.name as user_name,dd.name as dealer_name,
        // CASE
        //         WHEN it.status = 0 THEN 'Pending'
        //         WHEN it.status = 1 THEN 'Complete'
        //         WHEN it.status = 2 THEN 'Cancel'
        //         END AS current_status
        //  FROM inspector_task as it
        //         join dealers as dd on dd.id=it.dealer_id
        //         join users as us on us.id=it.user_id;";

        $sql_query1 = "SELECT it.id as task_id,
        it.*,
        dd.*,
        us.name as user_name,
        dd.name as dealer_name,
        usz.name as zm_name,
        ust.name as tm_name,
        usa.name as asm_name,
        it.created_at as task_create_time,tr.created_at as visit_close_time,
        CASE
            WHEN it.status = 0 AND DATE(it.time) = CURDATE() THEN 'Pending'
            WHEN it.status = 0 AND DATE(it.time) < CURDATE() THEN 'Late'
            WHEN it.status = 0 AND DATE(it.time) > CURDATE() THEN 'Upcoming'
            WHEN it.status = 1 THEN 'Complete'
            WHEN it.status = 2 THEN 'Cancel'
        END AS current_status,
        CASE 
        WHEN (SELECT COUNT(*) FROM inspector_task_reschedule WHERE task_id = it.id) > 0 
        THEN 'Rescheduled' 
        ELSE 'Not Scheduled' 
    END AS schedule_status
    FROM inspector_task AS it
    JOIN dealers AS dd ON dd.id = it.dealer_id
    JOIN users AS us ON us.id = it.user_id
    JOIN users AS usz ON usz.id = dd.zm
    JOIN users AS ust ON ust.id = dd.tm
    JOIN users AS usa ON usa.id = dd.asm 
	left join inspector_task_response as tr on tr.task_id=it.id where it.time>='$from' and it.time<='$to'";

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