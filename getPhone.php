<?php

@require_once 'config.php';

//main function execute
if(isset($openID)) {
	$user_query_ = "SELECT u_phone FROM user WHERE u_openid = '$openID';";
	$query_result_ = mysqli_query($mysql_link, $user_query_);
	$result_ = mysqli_fetch_assoc($query_result_);
	if(!empty($result_)) {
		$u_phone = $result_['u_phone'];
	} else {
		$u_phone = $openID;
	}
}

if(isset($a_openID)) {
	$user_query_ = "SELECT u_phone FROM user WHERE u_openid = '$a_openID';";
	$query_result_ = mysqli_query($mysql_link, $user_query_);
	$result_ = mysqli_fetch_assoc($query_result_);
	if(!empty($result_)) {
		$a_phone = $result_['u_phone'];
	} else {
		$a_phone = $a_openID;
	}
}

if(isset($device_id)) {
	$get_show_name = "SELECT show_name FROM device WHERE device_id = '$device_id';";
	$get_query_ = mysqli_query($mysql_link, $get_show_name);
	$result_ = mysqli_fetch_assoc($get_query_);
	if(!empty($result_)) {
		$device_name = $result_['show_name'];
	} else {
		$device_name = $device_id;
	}
}
?>
