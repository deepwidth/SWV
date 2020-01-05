<?php

@require_once 'config.php';
//get params
$openID = @$_GET['openID'] ? $_GET['openID'] : exit(json_encode($error));
$cPassword = @$_GET['cpassword'] ? $_GET['cpassword'] : exit(json_encode($error));
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
$get_access = "SELECT c_pass FROM device WHERE device_id = '$device_id';";
$query_result = mysqli_query($mysql_link, $get_access);

$result = mysqli_fetch_assoc($query_result);
if($result['c_pass'] == $cPassword) {
	$alter_user_device = "UPDATE user_device SET p_access = 1 WHERE device_id = '$device_id' AND u_openid = '$openID';";
	$alter_result = mysqli_query($mysql_link, $alter_user_device);
	if($alter_result != FALSE) {
		@include 'getPhone.php';
    	$log = "{$u_phone}已获取此设备控制权限";
    	$time = date('Y-m-d H:i:s');
    	$insert_log = "INSERT INTO user_log VALUES('$device_id', 8, '$log', '$time');";
    	mysqli_query($mysql_link, $insert_log);
	}
	($alter_result == FALSE) ? exit(json_encode($error)) : exit(json_encode($ok));
} else {
	exit(json_encode($error));
}

//output result
?>
