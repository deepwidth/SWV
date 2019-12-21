<?php

@require_once 'config.php';
//get params
$openID = @$_GET['openID'] ? $_GET['openID'] : '';
$device_id = @$_GET['device_id'] ? $_GET['device_id'] : '';
$source = @$_GET['source'] ? $_GET['source'] : '';
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
if($source == '2') {
	if(@$_GET['a_openID']) {
		$a_openID = $_GET['a_openID'];
	} else {
		$array_result = array( 'result' => '0');
		exit(json_encode($array_result));
	}
} else {
	$a_openID = '';
}

$user_query = "INSERT INTO user_device(u_openid, device_id, source, a_openid) VALUES('$openID', '$device_id', $source, '$a_openID');";
$query_result = mysqli_query($mysql_link, $user_query);
if($query_result == FALSE) {
	$result = array('result' => '0');
} else {
	$result = array('result' => '1');
}

//output result
exit(json_encode($result));
?>
