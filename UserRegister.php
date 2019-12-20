<?php

@require_once 'config.php';
//get params
$openID = @$_GET['openID'] ? $_GET['openID'] : '';
$phone = @$_GET['phone'] ? $_GET['phone'] : '';
$imgUrl = @$_GET['imgUrl'] ? $_GET['imgUrl'] : '';
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
$user_query = "INSERT INTO user(u_openid, u_phone, u_img) values('$openID', '$phone', '$imgUrl');";
$query_result = mysqli_query($mysql_link, $user_query);
if($query_result == FALSE) {
	$result = array('result' => '0');
} else {
	$result = array('result' => '1');
}

//output result
exit(json_encode($result));
?>
