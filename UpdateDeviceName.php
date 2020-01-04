<?php

@require_once 'config.php';
//get params
$openID = @$_GET['openID'] ? $_GET['openID'] : '';
$show_name = @$_GET['show_name'] ? $_GET['show_name'] : '';
$remark = @$_GET['remark'] ? $_GET['remark'] : '';
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

$open_device = "UPDATE device SET remark = '$remark', show_name = '$show_name' WHERE device_id = '$device_id';";
$query_result = mysqli_query($mysql_link, $open_device);
$array_result = ($query_result == FALSE) ? array('result' => '0') : array('result' => '1');
if($query_result) {
	$content = '的名称和备注';
	$log = $openID . '修改了设备' . $device_id . $content;
	$time = date('Y-m-d H:i:s');
	$insert_log = "INSERT INTO user_log VALUES('$device_id', 6, '$log', '$time');";
	mysqli_query($mysql_link, $insert_log);
}
//output result
exit(json_encode($array_result));
?>
