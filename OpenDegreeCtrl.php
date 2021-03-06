<?php

@require_once 'config.php';
//get params
$openID = @$_GET['openID'] ? $_GET['openID'] : exit(json_encode($error));
$degree = (strlen(@$_GET['degree']) > 0) ? $_GET['degree'] : exit(json_encode($error));
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
//检查阀门是否开启
$check_open = "SELECT open_ctrl FROM device_ctrl WHERE device_id = '$device_id';";
$check_open_query = mysqli_query($mysql_link, $check_open);
$open_result = mysqli_fetch_assoc($check_open_query);
if(!empty($open_result) && $open_result['open_ctrl'] == 0) {
	exit(json_encode($no_User));
}
//检查阀门是否启用访问控制权限
$check_adm = "SELECT access_ctrl FROM device WHERE device_id = '$device_id';";
$check_adm_query = mysqli_query($mysql_link, $check_adm);
$check_user = "SELECT a_access, p_access FROM user_device WHERE u_openid = '$openID' AND device_id = '$device_id';";
$check_user_query = mysqli_query($mysql_link, $check_user);
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
//阀门自动打开与关闭
$type = ($degree == 0) ? 1 : 2;
$open_device = "UPDATE device_ctrl SET position_ctrl = $degree, open_ctrl = $type WHERE device_id = '$device_id';";
$query_result = mysqli_query($mysql_link, $open_device);
$degree_ctrl = "UPDATE device_state SET position = $degree WHERE device_id = '$device_id';";
$query_degree = mysqli_query($mysql_link, $degree_ctrl);
$change_open = "UPDATE device_info SET position = $degree WHERE device_id = '$device_id';";
$change_open_query = mysqli_query($mysql_link, $change_open);
if($query_result) {
	@include 'getPhone.php';
	$content = '开度设置到了' . $degree;
	$log = $u_phone . '已将此设备' . $content;
	$time = date('Y-m-d H:i:s');
	$insert_log = "INSERT INTO user_log VALUES('$device_id', 2, '$log', '$time');";
	mysqli_query($mysql_link, $insert_log);
}
//output result
($query_result == FALSE || $query_degree == FALSE || $change_open_query == FALSE) ? exit(json_encode($error)) : exit(json_encode($ok));
?>
