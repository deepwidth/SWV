<?php

@require_once 'config.php';
//get params
$device_id = @$_GET['device_id'] ? $_GET['device_id'] : exit(json_encode($error));

//mysql link
$mysql_link = mysqli_connect($mysql_server, $mysql_user, $mysql_password, $mysql_db_name);
if($mysql_link) {
} else {
	//echo "Error: Unable to connect to MySQL." . PHP_EOL;
	//echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    //echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
	exit;
}

//main function execute
$query_history = "SELECT * FROM user_log WHERE device_id = '$device_id' ORDER BY time DESC;";
$query_result = mysqli_query($mysql_link, $query_history);
$result = mysqli_fetch_assoc($query_result);
while(!empty($result)) {
	$date = mb_substr($result['time'], 0, 10);
	$time = mb_substr($result['time'], 11, 8);
	$array_result["$date"][] = array(
		'device_id' => $result['device_id'],
		'type' => $result['type'],
		'log' => $result['log'],
		'time' => $time
	);
	$result = mysqli_fetch_assoc($query_result);
}
//output result
exit(json_encode($array_result));
?>
