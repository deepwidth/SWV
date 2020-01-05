<!doctype html>
	<head>
		<meta charset="UTF-8">
		<style type="text/css">
		.divForm{
		position: absolute;/*绝对定位*/
		width: 300px;
		height: 800px;

		border: 1px solid white;
		text-align: right;
		top: 50%;
		left: 50%;
		margin-top: -200px;
		margin-left: -150px;
		}
		</style>
	</head>
	<body>
		<div class="divForm">
		   <form action="ad.php" method="get">
		    欢迎添加设备,device_id值不能相同<br/>
		    device_id:<input type="text" name="device_id" value="100001"/>
			show_name:<input type="text" name="show_name" value="Great device"/>
			remark:<input type="text" name="remark" value="remark"/><br/>
		 	serial_num:<input type="text" name="serial_num" value="100001"/>
			net_address:<input type="text" name="net_address" value="100001"/><br/>
			connect_state:<input type="text" name="connect_state" value="1"/><br/>
			type:<input type="text" name="type" value="1"/><br/>
			name:<input type="text" name="name" value="another device"/><br/>
			imgurl:<input type="text" name="imgurl"/><br/>
			d_pass:<input type="text" name="d_pass" value="passwdFromMaking"><br/>
			access_ctrl:<input type="text" name="access_ctrl" value="0"/><br/>
		    <input type="submit" value="提交"/>
		   </form>
		    </div>
	</body>
</html>

<?php
$device_id = @$_GET['device_id'] ? $_GET['device_id'] : '';
$remark = @$_GET['remark'] ? $_GET['remark'] : '';
$serial_num = @$_GET['serial_num'] ? $_GET['serial_num'] : '';
$net_address = @$_GET['net_address'] ? $_GET['net_address'] : '';
$connect_state = @$_GET['connect_state'] ? $_GET['connect_state'] : '';
$type = @$_GET['type'] ? $_GET['type'] : '';
$name = @$_GET['name'] ? $_GET['name'] : '';
$show_name = @$_GET['show_name'] ? $_GET['show_name'] : '';
$imgurl = @$_GET['imgurl'] ? $_GET['imgurl'] : '';
$d_pass = @$_GET['d_pass'] ? $_GET['d_pass'] : '';
$access_ctrl = @$_GET['access_ctrl'] ? $_GET['access_ctrl'] : '';
$assem_date = date('Y-m-d H:i:s');
@require_once 'config.php';
$mysql_link = mysqli_connect($mysql_server, $mysql_user, $mysql_password, $mysql_db_name);

if($mysql_link) {
} else {
	//echo "Error: Unable to connect to MySQL." . PHP_EOL;
	//echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
	//echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
	exit;
}

$user_query = "INSERT INTO device(device_id, remark, serial_num, net_address, connect_state, type, name,
						show_name, imgurl, assem_date, d_pass, access_ctrl) 
				values('$device_id', '$remark', '$serial_num', '$net_address', '$connect_state', '$type', '$name', '$show_name', '$imgurl',
					'$assem_date', '$d_pass', '$access_ctrl');";
if($_GET == null) {
	exit;
}
$add_result = mysqli_query($mysql_link, $user_query);
include_once 'addParams.php';
if($add_result == FALSE) {
	echo "add device failed";
} else {
	echo "add device succeed!" . '<br/>';
}

