<?php
include("../config.php");

$access_key = '03201232927';

$pass = $_GET["key"];
$pre = $_GET["pre"];
$id = $_GET["user_id"];
$from = $_GET["from"];
$to = $_GET["to"];
$rettype = $_GET["rettype"];

if (!empty($pass)) {
    if ($pass === $access_key) {
        // Determine condition based on privilege level
        $condition = "1 = 1"; // Default fallback condition
        switch ($pre) {
            case 'ZM':
                $condition = "dl.zm = $id";
                break;
            case 'TM':
                $condition = "dl.tm = $id";
                break;
            case 'ASM':
                $condition = "dl.asm = $id";
                break;
        }

        // Additional filter for rettype if needed
        // $rettype_condition = ($rettype !== 'RT') ? "AND dl.rettype = '$rettype'" : '';

        // SQL query
        $sql_query1 = "SELECT 
                om.*, 
                dl.name, 
                dl.zm, 
                dl.tm, 
                dl.asm, 
                dl.region, 
                dl.city, 
                dl.province, 
                dl.district, 
                us.name AS usersnames, 
                dl.credit_limit, 
                dl.rettype_desc, 
                dl.sap_no,
                (SELECT CONCAT(geo.code, '-', geo.consignee_name) 
                 FROM puma_sap_data_trips AS tt
                 JOIN puma_sap_data AS sd ON sd.id = tt.main_id
                 JOIN geofenceing AS geo ON geo.code = sd.depo
                 WHERE tt.salesapNo = om.SaleOrder 
                 GROUP BY tt.salesapNo) AS consignee_name,
                (SELECT sd.is_tracker 
                 FROM puma_sap_data_trips AS tt
                 JOIN puma_sap_data AS sd ON sd.id = tt.main_id
                 WHERE tt.salesapNo = om.SaleOrder 
                 GROUP BY tt.salesapNo) AS is_tracker,
                CASE
                    WHEN om.status = 0 THEN 'Pending'
                    WHEN om.status = 1 THEN 'Pushed'
                    WHEN om.status = 3 THEN 'Complete'
                    WHEN om.status = 2 THEN 'Cancel'
                    WHEN om.status = 4 THEN 'Special Approval'
                    WHEN om.status = 5 THEN 'Forwarded'
                    WHEN om.status = 6 THEN 'Processed'
                END AS current_status,
                (SELECT GROUP_CONCAT(geo.consignee_name SEPARATOR ', ') 
                 FROM dealers_depots AS dd
                 JOIN geofenceing AS geo ON geo.id = dd.depot_id
                 WHERE dd.dealers_id = dl.id) AS dealers_depots
            FROM order_main AS om 
            JOIN dealers AS dl ON dl.id = om.created_by 
            LEFT JOIN users AS us ON us.id = dl.asm 
            WHERE $condition 
                AND om.status = 6 
                AND om.created_at >= '$from' 
                AND om.created_at <= '$to' 
                AND dl.rettype = '$rettype'
            ORDER BY om.id DESC;
        ";

        $result1 = $db->query($sql_query1);

        if (!$result1) {
            die("Error: " . mysqli_error($db));
        }

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