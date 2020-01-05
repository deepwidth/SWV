<?php

@require_once 'config.php';
//get params
$aPassword = @$_GET['apassword'] ? $_GET['apassword'] : exit(json_encode($error));
$device_id = @$_GET['device_id'] ? $_GET['device_id'] : exit(json_encode($error));
$openID = @$_GET['openID'] ? $_GET['openID'] : exit(json_encode($error));

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
$user_query = "SELECT * FROM device WHERE device_id = '$device_id';";
$query_result = mysqli_query($mysql_link, $user_query);
$result = mysqli_fetch_assoc($query_result);

if($result['access_ctrl'] == 1) {
	if($result['adm_openid'] != $openID) {
		$array_result = $no;
	} else {
		if($result['c_pass'] == $aPassword) {
			$array_result = $ok;
		} else {
			$array_result = $error;
		}
	}
} else {
	if($result['c_pass'] == $aPassword) {
		$alter_user_device = "UPDATE user_device set p_access = 1 where device_id = '$device_id' AND u_openid = '$openID';";
		$alter_device = "UPDATE device set adm_openid = '$openID', access_ctrl = 1 WHERE device_id = '$device_id';";
		$query_user_device = mysqli_query($mysql_link, $alter_user_device);
		$query_device = mysqli_query($mysql_link, $alter_device);

		if($query_user_device == FALSE || $query_device == FALSE) {
			$array_result = $no_User;
		} else {
			@include 'getPhone.php';
			$array_result = $ok;
			$log = $u_phone . '已成为此设备的管理员;';
			$time = date('Y-m-d H:i:s');
			$insert_log = "INSERT INTO user_log VALUES('$device_id', 5, '$log', '$time');";
			mysqli_query($mysql_link, $insert_log);
		}
	} else {
		$array_result = $error;
	}
}

exit(json_encode($array_result));
?>
