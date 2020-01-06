<?php

@require_once 'config.php';
//get params
$openID = @$_GET['openID'] ? $_GET['openID'] : exit(json_encode($error));
$cPassword = @$_GET['cPassword'] ? $_GET['cPassword'] : exit(json_encode($error));
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
$check_user = "SELECT adm_openid FROM device WHERE device_id = '$device_id';";
$check_user_query = mysqli_query($mysql_link, $check_user);
$check_result = mysqli_fetch_assoc($check_user_query);
if($check_result['adm_openid'] != $openID) {
	exit(json_encode($no));
}

$check_passwd = "SELECT c_pass FROM device WHERE device_id = '$device_id';";
$check_passwd_query = mysqli_query($mysql_link, $check_passwd);
$result_passwd = mysqli_fetch_assoc($check_passwd_query);
if(!empty($result_passwd) && $result_passwd['c_pass'] == $cPassword) {
	exit(json_encode($ok));
}

$open_device = "UPDATE device SET c_pass = $cPassword  WHERE device_id = '$device_id';";
$query_result = mysqli_query($mysql_link, $open_device);
if($query_result) {
	$update_pass_others = "UPDATE user_device SET p_access = 0 WHERE device_id = '$device_id' AND u_openid != '$openID';";
	$update_query = mysqli_query($mysql_link, $update_pass_others);
	$array_result = ($update_query == FALSE) ? $error : $ok;
}
if($query_result) {
	@include 'getPhone.php';
	$content = '控制密码';
	$log = "$u_phone" . '修改了此设备' . $content;
	$time = date('Y-m-d H:i:s');
	$insert_log = "INSERT INTO user_log VALUES('$device_id', 4, '$log', '$time');";
	mysqli_query($mysql_link, $insert_log);
}
//output result
exit(json_encode($array_result));
?>
