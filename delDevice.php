<?php

@require_once 'config.php';
//get params
$openID = @$_GET['openID'] ? $_GET['openID'] : exit(json_encode($error));
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
@include 'getPhone.php';
$get_admin = "SELECT adm_openid FROM device WHERE device_id = '$device_id';";
$get_admin_query = mysqli_query($mysql_link, $get_admin);
$admin_result = mysqli_fetch_assoc($get_admin_query);
if($admin_result['adm_openid'] == $openID) {
	$cancel_adm = "UPDATE device SET access_ctrl = 0, adm_openid = NULL WHERE device_id = '$device_id';";
	$cancel_result = mysqli_query($mysql_link, $cancel_adm);
	$log = $u_phone . '删除了自己的管理员权限';
    $time = date('Y-m-d H:i:s');
    $insert_log = "INSERT INTO user_log VALUES('$device_id', 5, '$log', '$time');";
    mysqli_query($mysql_link, $insert_log);
}
$user_query = "DELETE FROM user_device WHERE u_openid = '$openID' AND device_id = '$device_id';";
$query_result = mysqli_query($mysql_link, $user_query);
$array_result = ($query_result == FALSE) ? $error : $ok;
if($query_result != FALSE) {
	$log = $u_phone . '删除了此设备';
	$time = date('Y-m-d H:i:s');
	$insert_log = "INSERT INTO user_log VALUES('$device_id', 7, '$log', '$time');";
	mysqli_query($mysql_link, $insert_log);
}
//output result
exit(json_encode($array_result));
?>
