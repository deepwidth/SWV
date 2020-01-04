<?php

@require_once 'config.php';
//get params
$openID = @$_GET['openID'] ? $_GET['openID'] : exit(json_encode($error));
$device_id = @$_GET['device_id'] ? $_GET['device_id'] : exit(json_encode($error));
$a_openID = @$_GET['a_openID'] ? $_GET['a_openID'] : exit(json_encode($error));
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
if($openID == $a_openID) {
	exit(json_encode($no_User));
}

$user_query = "SELECT adm_openid FROM device WHERE device_id = '$device_id';";
$query_result = mysqli_query($mysql_link, $user_query);
$result = mysqli_fetch_assoc($query_result);

if(empty($result) || $result['adm_openid'] != $a_openID) {
	exit(json_encode($no));
} else {
	$del = "DELETE FROM user_device WHERE device_id = '$device_id' AND u_openid = '$openID';";
	$del_query = mysqli_query($mysql_link, $del);
	if($del) {
		@require 'getPhone.php';
		$log = $a_phone."已经删除了".$u_phone."对于此设备的所有权限";
		$time = date('Y-m-d H:i:s');
		$insert_log = "INSERT INTO user_log VALUES('$device_id', 3, '$log', '$time');";
		mysqli_query($mysql_link, $insert_log);
		exit(json_encode($ok));
	} else {
		exit(json_encode($error));
	}
}
exit(json_encode($error));
?>
