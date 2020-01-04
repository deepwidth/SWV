<?php

@require_once 'config.php';
//get params
$openID = @$_GET['openID'] ? $_GET['openID'] : exit(json_encode($error));
$phone = @$_GET['phone'] ? $_GET['phone'] : exit(json_encode($error));
$imgUrl = @$_GET['imgUrl'] ? $_GET['imgUrl'] : exit(json_encode($error));
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
$insert_team = "INSERT INTO device_group(u_openid, team, team_name) VALUES('$openID', 1, 'GreatTeam');";
$insert_query = mysqli_query($mysql_link, $insert_team);
if($insert_query == FALSE) {
	exit(json_encode($error));
} else {
	exit(json_encode($ok));
}
?>
