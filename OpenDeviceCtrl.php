<?php

@require_once 'config.php';
//get params
$openID = @$_GET['openID'] ? $_GET['openID'] : exit(json_encode($error));
$type = @(strlen($_GET['type']) >= 1) ? $_GET['type'] : exit(json_encode($error));
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
$check_adm = "SELECT access_ctrl FROM device WHERE device_id = '$device_id';";
$check_adm_query = mysqli_query($mysql_link, $check_adm);
$check_user = "SELECT a_access, p_access FROM user_device WHERE u_openid = '$openID' AND device_id = '$device_id';";
$check_user_query = mysqli_query($mysql_link, $check_user);
//var_dump($check_user_query);
$check_result = mysqli_fetch_assoc($check_user_query);
if($check_user_query -> num_rows == 0) {
	exit(json_encode($no));
}
$check_adm_result = mysqli_fetch_assoc($check_adm_query);
if($check_adm_result["access_ctrl"] == 1) {
    if($check_result['p_access'] == 0 && $check_result['a_access'] == 0) {
        exit(json_encode($no));
    }
}
if($type == 1 || $type == 2) {
	$degree = ($type == 1) ? '0' : '100';
	$open_device = "UPDATE device_ctrl SET position_ctrl = $degree WHERE device_id = '$device_id';";
	$open_result = mysqli_query($mysql_link, $open_device);
	$degree_ctrl = "UPDATE device_state SET position = $degree WHERE device_id = '$device_id';";
	$query_degree = mysqli_query($mysql_link, $degree_ctrl);
	$change_open = "UPDATE device_info SET position = $degree WHERE device_id = '$device_id';";
	$change_open_query = mysqli_query($mysql_link, $change_open);	
} else {
	$open_result = TRUE;
	$query_degree = TRUE;
	$change_open_query = TRUE;
}
$open_device = "UPDATE device_ctrl SET open_ctrl = $type WHERE device_id = '$device_id';";
$query_result = mysqli_query($mysql_link, $open_device);
$change_state = "UPDATE device_state SET state = $type WHERE device_id = '$device_id';";
$change_state_query = mysqli_query($mysql_link, $change_state);
$array_result = ($query_result && $change_state_query && $open_result && $query_degree && $change_open_query) ? $ok : $error;
if($query_result) {
	switch($type)
	{
		case 0:
			$content = '停止';
			break;
		case 1:
			$content = '关闭';
			break;
		case 2:
			$content = '打开';
			break;
		case 3:
			$content = '置为ESD';
			break;
		default:
			$content = "进行了未知操作，操作码为" . $type;
			break;
	}
	@include 'getPhone.php';
	$log = "{$u_phone}已将此设备{$content}";
	$time = date('Y-m-d H:i:s');
	$insert_log = "INSERT INTO user_log VALUES('$device_id', 1, '$log', '$time');";
	mysqli_query($mysql_link, $insert_log);
}
//output result
exit(json_encode($array_result));
?>
