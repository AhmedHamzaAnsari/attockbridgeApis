<?php
//fetch.php  
include ("../config.php");


$access_key = '03201232927';

$pass = $_GET["key"];
$from = $_GET["from"];
$to = $_GET["to"];
$pre = $_GET["pre"];
$id = $_GET["id"];
if ($pass != '') {
    // $id = $_GET["id"];
    if ($pass == $access_key) {
       

        if ($pre == 'ZM') {

            $sql_query1 = "SELECT it.*,us.name, dd.name as dealer_name, CASE
            WHEN it.status = 0 THEN 'Pending'
            WHEN it.status = 1 THEN 'Complete'
            WHEN it.status = 2 THEN 'Cancel'
            
            END AS current_status,
            CASE
            WHEN us.privilege = 'ZM' THEN 'GRM'
            WHEN us.privilege = 'TM' THEN 'RM'
            WHEN us.privilege = 'ASM' THEN 'TM'
            
            END AS privilege,
            tr.created_at as visit_close_time,
            (SELECT id FROM inspector_task where dealer_id=it.dealer_id and id!=it.id and id<it.id order by id desc limit 1) as last_visit_id,
            tr.dealer_sign
            FROM inspector_task as it 
            join users us on us.id=it.user_id  
            left join inspector_task_response as tr on tr.task_id=it.id
            JOIN 
            dealers AS dd ON dd.id = it.dealer_id where it.time>='$from' and it.time<='$to' and dd.zm='$id' group by it.id
            order by it.id desc;";

        } elseif ($pre == 'TM') {

            $sql_query1 = "SELECT it.*,us.name, dd.name as dealer_name, CASE
            WHEN it.status = 0 THEN 'Pending'
            WHEN it.status = 1 THEN 'Complete'
            WHEN it.status = 2 THEN 'Cancel'
            
            END AS current_status,
            CASE
            WHEN us.privilege = 'ZM' THEN 'GRM'
            WHEN us.privilege = 'TM' THEN 'RM'
            WHEN us.privilege = 'ASM' THEN 'TM'
            
            END AS privilege,
            tr.created_at as visit_close_time,
            (SELECT id FROM inspector_task where dealer_id=it.dealer_id and id!=it.id and id<it.id order by id desc limit 1) as last_visit_id,
            tr.dealer_sign
            FROM inspector_task as it 
            join users us on us.id=it.user_id  
            left join inspector_task_response as tr on tr.task_id=it.id
            JOIN 
                dealers AS dd ON dd.id = it.dealer_id where it.time>='$from' and it.time<='$to' and dd.tm='$id' group by it.id
            order by it.id desc";
        } elseif ($pre == 'ASM') {
            $sql_query1 = "SELECT it.*,us.name, dd.name as dealer_name, CASE
            WHEN it.status = 0 THEN 'Pending'
            WHEN it.status = 1 THEN 'Complete'
            WHEN it.status = 2 THEN 'Cancel'
            
            END AS current_status,
            CASE
            WHEN us.privilege = 'ZM' THEN 'GRM'
            WHEN us.privilege = 'TM' THEN 'RM'
            WHEN us.privilege = 'ASM' THEN 'TM'
            
            END AS privilege,
            tr.created_at as visit_close_time,
            (SELECT id FROM inspector_task where dealer_id=it.dealer_id and id!=it.id and id<it.id order by id desc limit 1) as last_visit_id,
            tr.dealer_sign
            FROM inspector_task as it 
            join users us on us.id=it.user_id  
            left join inspector_task_response as tr on tr.task_id=it.id
            JOIN 
                dealers AS dd ON dd.id = it.dealer_id where it.time>='$from' and it.time<='$to' and dd.asm='$id' group by it.id
            order by it.id desc;";

        } else {

            // $sql_query1 = "SELECT at.*,us.name as manager_name,dl.name as dealer_name,CASE
            // WHEN at.status = 0 THEN 'Pending'
            // WHEN at.status = 1 THEN 'Complete'
            // WHEN at.status = 2 THEN 'Cancel'
            // END AS current_status,tm_ns.name as tm_name FROM inspector_task as at 
            // join users as us on us.id=at.user_id
            // join dealers as dl on dl.id=at.dealer_id 
            // join users_asm_tm as uzt on uzt.asm_id=at.created_by 
            // join users as tm_ns on tm_ns.id=uzt.tm_id
            // where  at.time>='$from' and at.time<='$to' group by at.id
            // order by at.id desc;";

            $sql_query1 = "SELECT it.*,us.name, dd.name as dealer_name, CASE
            WHEN it.status = 0 THEN 'Pending'
            WHEN it.status = 1 THEN 'Complete'
            WHEN it.status = 2 THEN 'Cancel'
            
            END AS current_status,
            CASE
            WHEN us.privilege = 'ZM' THEN 'GRM'
            WHEN us.privilege = 'TM' THEN 'RM'
            WHEN us.privilege = 'ASM' THEN 'TM'
            
            END AS privilege,
            tr.created_at as visit_close_time,
            (SELECT id FROM inspector_task where dealer_id=it.dealer_id and id!=it.id and id<it.id and stock_variations_status=1 order by id desc limit 1) as last_visit_id,
            tr.dealer_sign
            FROM inspector_task as it 
            join users us on us.id=it.user_id  
            left join inspector_task_response as tr on tr.task_id=it.id
            JOIN 
                dealers AS dd ON dd.id = it.dealer_id where it.time>='$from' and it.time<='$to' group by it.id
            order by it.id desc";
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