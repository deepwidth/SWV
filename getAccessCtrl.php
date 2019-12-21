<?php

@require_once 'config.php';
//get params
$openID = @$_GET['openID'] ? $_GET['openID'] : '';
$cPassword = @$_GET['cpassword'] ? $_GET['cpassword'] : '';
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
$get_access = "SELECT c_pass FROM device WHERE device_id = '$device_id';";
$query_result = mysqli_query($mysql_link, $get_access);

$result = mysqli_fetch_assoc($query_result);
if($result['c_pass'] == $cPassword) {
	$alter_user_device = "UPDATE user_device SET p_access = 1 WHERE device_id = '$device_id' AND u_openid = '$openID';";
	$alter_result = mysqli_query($mysql_link, $alter_user_device);
	$array_result = ($alter_result == FALSE) ? array('result' => '0') : array('result' => '1');
} else {
	$array_result = array('result' => '0');
}

//output result
exit(json_encode($array_result));
?>
