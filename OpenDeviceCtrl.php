<?php

@require_once 'config.php';
//get params
$openID = @$_GET['openID'] ? $_GET['openID'] : '';
$type = @(strlen($_GET['type']) >= 1) ? $_GET['type'] : '';
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
$check_adm = "SELECT access_ctrl FROM device WHERE device_id = '$device_id';";
$check_adm_query = mysqli_query($mysql_link, $check_adm);
$check_user = "SELECT a_access, p_access FROM user_device WHERE u_openid = '$openID' AND device_id = '$device_id';";
$check_user_query = mysqli_query($mysql_link, $check_user);
//var_dump($check_user_query);
$check_result = mysqli_fetch_assoc($check_user_query);
if($check_user_query -> num_rows == 0) {
	$array_result = array('result' => '2');
	exit(json_encode($array_result));
}
$check_adm_result = mysqli_fetch_assoc($check_adm_query);
if($check_adm_result["access_ctrl"] == 1) {
    if($check_result['p_access'] == 0 && $check_result['a_access'] == 0) {
        $array_result = array('result' => '2');
        exit(json_encode($array_result));
    }
}

$open_device = "UPDATE device_ctrl SET open_ctrl = $type WHERE device_id = '$device_id';";
$query_result = mysqli_query($mysql_link, $open_device);
$change_state = "UPDATE device_state SET state = $type WHERE device_id = '$device_id';";
$change_state_query = mysqli_query($mysql_link, $change_state);
$array_result = ($query_result == FALSE) ? array('result' => '0') : array('result' => '1');
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
	@require_once 'getPhone.php';
	$log = $u_phone . '已将此设备' . $content;
	$time = date('Y-m-d H:i:s');
	$insert_log = "INSERT INTO user_log VALUES('$device_id', 1, '$log', '$time');";
	mysqli_query($mysql_link, $insert_log);
}
//output result
exit(json_encode($array_result));
?>
