<?php

@require_once 'config.php';
//get params
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
$user_query = "SELECT device.device_id, serial_num, connect_state, type, name, show_name, remark, imgurl, access_ctrl,
				adm_openid, user_device.team, device_group.team_name, device_info.state, error, device_info.position, 
				open_ctrl, ctrl_type, device_ctrl.accuracy 
				FROM device, user_device, device_group, device_ctrl,device_state,device_info 
				WHERE device.device_id in 
				( SELECT device_id FROM user_device WHERE u_openid = '$openID') 
				AND device_group.team = user_device.team
				AND device_group.u_openid = '$openID'
				AND user_device.device_id = device.device_id
				AND user_device.u_openid = '$openID'
				AND device_ctrl.device_id = device.device_id
				AND device_state.device_id = device.device_id
				AND device_info.device_id = device.device_id;";
$query_result = mysqli_query($mysql_link, $user_query);
$result = mysqli_fetch_assoc($query_result);
while(!empty($result)) {
	$array_result[] = array(
		'device_id' => $result['device_id'],
		'serial_num' => $result['serial_num'],
		'connect_state' => $result['connect_state'],
		'type' => $result['type'],
		'name' => $result['name'],
		'show_name' => $result['show_name'],
		'remark' => $result['remark'],
		'imgurl' => $result['imgurl'],
		'access_ctrl' => $result['access_ctrl'],
		'adm_openid' => $result['adm_openid'],
		'team' => $result['team'],
		'team_name' => $result['team_name'],
		'state' => $result['state'],
		'error' => $result['error'],
		'position' => $result['position'],
		'open_ctrl' => $result['open_ctrl'],
		'ctrl_type' => $result['ctrl_type'],
		'accuracy' => $result['accuracy']
	);
	$result = mysqli_fetch_assoc($query_result);
}

//output result
exit(json_encode($array_result));
?>
