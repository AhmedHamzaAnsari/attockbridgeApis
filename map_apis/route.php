<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
ini_set('max_execution_time', -1);
date_default_timezone_set("Asia/Karachi");
include("../config_apis.php");
if (isset($_GET['accesskey'])) {
	$access_key_received = $_GET['accesskey'];
	$vehi = $_GET['vehi'];
	$access_key = "12345";
	if ($access_key_received == $access_key) {
		// get all category data from category table
		$sql_query = "SELECT CAST(latitude as double) as lat,CAST(longitude as double) as lng FROM `positions_log` where device_id = $vehi and time >= now() - interval 2 hour ;";

		$result = $connect->query($sql_query) or die("Error :" . mysqli_error());

		$users = array();
		while ($user = $result->fetch_assoc()) {

			$users[] = array(
				'lat' => floatval($user['lat']),
				'lng' => floatval($user['lng']),
			);
		}

		// create json output
		$output = json_encode($users);
	} else {
		die('accesskey is incorrect.');
	}
} else {
	die('accesskey is required.');
}

//Output the output.
echo $output;

// include_once('../includes/close_database.php');
?>