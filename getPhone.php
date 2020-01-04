<?php

@require_once 'config.php';

//main function execute
if(isset($openID)) {
	$user_query = "SELECT u_phone FROM user WHERE u_openid = '$openID';";
	$query_result = mysqli_query($mysql_link, $user_query);
	$result = mysqli_fetch_assoc($query_result);
	if(!empty($result)) {
		$u_phone = $result['u_phone'];
	} else {
		$u_phone = $openID;
	}
}

if(isset($a_openID)) {
	$user_query = "SELECT u_phone FROM user WHERE u_openid = '$a_openID';";
	$query_result = mysqli_query($mysql_link, $user_query);
	$result = mysqli_fetch_assoc($query_result);
	if(!empty($result)) {
		$a_phone = $result['u_phone'];
	} else {
		$a_phone = $a_openID;
	}
}

if(isset($device_id)) {
	$get_show_name = "SELECT show_name FROM device WHERE device_id = '$device_id';";
	$get_query = mysqli_query($mysql_link, $get_show_name);
	$result = mysqli_fetch_assoc($get_query);
	if(!empty($result)) {
		$device_name = $result['show_name'];
	} else {
		$device_name = $device_id;
	}
}
?>
