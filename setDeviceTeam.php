<?php

@require_once 'config.php';
//get params
$openID = @$_GET['openID'] ? $_GET['openID'] : exit(json_encode($error));
$device_id = @$_GET['device_id'] ? $_GET['device_id'] : exit(json_encode($error));
$team = @$_GET['team'] ? $_GET['team'] : exit(json_encode($error));
$team_name = @$_GET['team_name'] ? $_GET['team_name'] : exit(json_encode($error));

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
$update_team = "UPDATE user_device SET team = $team WHERE u_openid = '$openID' AND device_id = '$device_id';";
$get_team = "SELECT * FROM device_group WHERE u_openid = '$openID' AND team = $team;";
$get_team_result = mysqli_query($mysql_link, $get_team);
if($get_team_result->num_rows == 0) {
	$update_team_name = "INSERT INTO device_group(u_openid, team, team_name) VALUES('$openID', $team, '$team_name');";
} else {
	$update_team_name = "UPDATE device_group SET team_name = '$team_name' WHERE u_openid = '$openID' AND team = $team;";
}
$update_team_result = mysqli_query($mysql_link, $update_team);
$update_team_name_result = mysqli_query($mysql_link, $update_team_name);
if($update_team_result == FALSE || $update_team_name_result == FALSE) {
	exit(json_encode($error));
} else {
	exit(json_encode($ok));
}
?>
