<?php

@require_once 'config.php';
//get params
$openID = @$_GET['openID'] ? $_GET['openID'] : '';
$device_id = @$_GET['device_id'] ? $_GET['device_id'] : '';
$team = @$_GET['team'] ? $_GET['team'] : '';
$team_name = @$_GET['team_name'] ? $_GET['team_name'] : '';

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
	$array_result = array('result' => '0');
	var_dump(mysqli_error($mysql_link));
} else {
	$array_result = array('result' => '1');
}

//output result
exit(json_encode($array_result));
?>
