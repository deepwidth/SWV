<?php

@require_once 'config.php';
//get params
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
$user_query = "SELECT u_openid, device_id, source, a_access, p_access, a_openid FROM user_device WHERE device_id = '$device_id';";
$query_result = mysqli_query($mysql_link, $user_query);
$result = mysqli_fetch_assoc($query_result);
while(!empty($result)) {
	$openID = $result['u_openid'];
	$a_openID = $result['a_openid'];
	include 'getPhone.php';
	$array_result[] = array(
		'u_openid' => $result['u_openid'],
		'device_id' => $result['device_id'],
		'source' => $result['source'],
		'a_access' => $result['a_access'],
		'p_access' => $result['p_access'],
		'a_openid' => $result['a_openid'],
		'u_phone' => $u_phone,
		'a_phone' => $a_phone
	);
	$result = mysqli_fetch_assoc($query_result);
}
//output result
exit(json_encode($array_result));
?>
