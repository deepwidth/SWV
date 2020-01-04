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
$user_query = "SELECT u_phone, u_openid, u_img FROM user WHERE u_openid = '$openID';";
$query_result = mysqli_query($mysql_link, $user_query);

$result = mysqli_fetch_assoc($query_result);
if(empty($result)) {
	$array_result = array(
	'phone' => 0,
	'openID' => 0,
	'imgUrl' => 0
	);
} else {
	$array_result = array(
		'phone' => $result['u_phone'],
		'openID' => $result['u_openid'],
		'imgUrl' => $result['u_img']
	);
}

//output result
exit(json_encode($array_result));
?>
