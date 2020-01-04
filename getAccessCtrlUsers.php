<?php

@require_once 'config.php';
//get params
$device_id = @$_GET['device_id'] ? $_GET['device_id'] : '';

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
$user_query = "SELECT u_openid, device_id, source, a_access, p_access, a_openid FROM user_device WHERE device_id = '$device_id';";
$query_result = mysqli_query($mysql_link, $user_query);

$result = mysqli_fetch_assoc($query_result);
do{
	$array_result[] = $result;
	$result = mysqli_fetch_assoc($query_result);
}while(!empty($result));

//output result
exit(json_encode($array_result));
?>
