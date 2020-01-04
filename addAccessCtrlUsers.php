<?php

@require_once 'config.php';
//get params
$a_openID = @$_GET['a_openID'] ? $_GET['a_openID'] : '';
$phone = @$_GET['phone'] ? $_GET['phone'] : '';
$device_id = @$_GET['device_id'] ? $_GET['device_id'] : '';
$type = (strlen(@$_GET['type']) == 1) ? $_GET['type'] : '';


//mysql link
$mysql_link = mysqli_connect($mysql_server, $mysql_user, $mysql_password, $mysql_db_name);
if($mysql_link) {
} else {
	//echo "Error: Unable to connect to MySQL." . PHP_EOL;
	//echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    //echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
	exit;
}
$array_nouser = array('result' => '3');
$array_no = array('result' => '2');
$array_ok = array('result' => '1');
$array_error = array('result' => '0');
//main function execute
if($type == 1) {
	$get_admin = "SELECT adm_openid FROM device WHERE device_id = '$device_id';";
	$get_admin_query = mysqli_query($mysql_link, $get_admin);
	$result = mysqli_fetch_assoc($get_admin_query);
	if(!empty($result)) {
		if($result['adm_openid'] != $a_openID) {
			exit(json_encode($array_no));
		}
	} else {
		exit(json_encode($array_no));
	}
}

$check_a_user = "SELECT u_openid FROM user WHERE u_phone = '$phone';";
$check_a_query = mysqli_query($mysql_link, $check_a_user);
$check_result = mysqli_fetch_assoc($check_a_query);
if(empty($check_result)) {
	exit(json_encode($array_nouser));
} else {
	$u_openID = $check_result['u_openid'];
}

$check_log = "SELECT * FROM user_device WHERE device_id = '$device_id' AND u_openid = '$u_openID';";
$check_log_query = mysqli_query($mysql_link, $check_log);
$result = mysqli_fetch_assoc($query_result);
if(!empty($result)) {
	if($result['a_access'] == 0) {
		$update_string = "UPDATE user_device SET a_access = $type WHERE device_id = '$device_id' AND u_openid = '$u_openID';";
		$update_string_query = mysqli_query($mysql_link, $update_string);
	} else {
		$update_string_query = TRUE;
	}
} else {
	$update_string = "INSERT INTO user_device VALUES('$u_openID', '$device_id', 2, $type, 0, '$a_openID', 1);";
	$update_string_query = mysqli_query($mysql_link, $update_string);
}

if($update_string_query != FALSE) {
	@require_once 'getPhone.php';
	$quanxian = ($type == 1) ? "访问控制" : "访问";
	$content = $quanxian . '权限授权给了' . $u_phone;
	$log = $a_phone . '已将设备' . $device_id . $content;
	$time = date('Y-m-d H:i:s');
	$insert_log = "INSERT INTO user_log VALUES('$device_id', 3, '$log', '$time');";
	mysqli_query($mysql_link, $insert_log);
	exit(json_encode($array_ok));
} else {
	exit(json_encode($array_error));
}
//output result
exit(json_encode($array_result));
?>
