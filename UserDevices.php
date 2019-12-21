<?php

@require_once 'config.php';
//get params
$openID = @$_GET['openID'] ? $_GET['openID'] : '';

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
$user_query = "SELECT device_id, serial_num, connect_state, type, name,
				show_name, remark, imgurl, access_ctrl, adm_openid, user_device.team,
				device_group.team_name, state, error, position, ctrl_type, accuracy
				FROM device, user_device, device_group, device_ctrl 
				WHERE device_id in
				(	SELECT device_id
					FROM user_device
					WHERE u_openid = '$openID ';) AND
					device_group.team = user_device.team;";
$query_result = mysqli_query($mysql_link, $user_query);

$i = 1;
$result = mysqli_fetch_assoc($query_result);
do{
	$array_result[$i] = array(
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
		'team' => $result['user_device.team'],
		'team_name' => $result['device_group.team_name'],
		'state' => $result['state'],
		'error' => $result['error'],
		'position' => $result['position'],
		'ctrl_type' => $result['ctrl_type'],
		'accurcy' => $result['accurcy']
	}
	$result = mysqli_fetch_assoc($query_result);
}while(!empty($result));

//output result
exit(json_encode($array_result));
?>
