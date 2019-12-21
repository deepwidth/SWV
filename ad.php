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
		 	serial_num:<input type="text" name="serial_num" value="100001"/>
			net_address:<input type="text" name="net_address" value="100001"/>
			connect_state:<input type="text" name="connect_state" value="1"/>
			type:<input type="text" name="type" value="1"/><br/>
			name:<input type="text" name="name" value="another device"/>
			show_name:<input type="text" name="show_name" value="Great device"/>
			imgurl:<input type="text" name="imgurl"/>
			assem_date:<input type="text" name="assem_date" value="2019-12-21 14:45:54"/>
			d_pass:<input type="text" name="d_pass" value="passwdFromMaking"/>
			access_ctrl:<input type="text" name="access_ctrl" value="0"/>
		    <input type="submit" value="提交"/>
		   </form>
		    </div>
	</body>
</html>

<?php
$device_id = @$_GET['device_id'] ? $_GET['device_id'] : '';
$serial_num = @$_GET['serial_num'] ? $_GET['serial_num'] : '';
$net_address = @$_GET['net_address'] ? $_GET['net_address'] : '';
$connect_state = @$_GET['connect_state'] ? $_GET['connect_state'] : '';
$type = @$_GET['type'] ? $_GET['type'] : '';
$name = @$_GET['name'] ? $_GET['name'] : '';
$show_name = @$_GET['show_name'] ? $_GET['show_name'] : '';
$imgurl = @$_GET['imgurl'] ? $_GET['imgurl'] : '';
$assem_date = @$_GET['assem_date'] ? $_GET['assem_date'] : '';
$d_pass = @$_GET['d_pass'] ? $_GET['d_pass'] : '';
$access_ctrl = @$_GET['access_ctrl'] ? $_GET['access_ctrl'] : '';

@require_once 'config.php';
$mysql_link = mysqli_connect($mysql_server, $mysql_user, $mysql_password, $mysql_db_name);

if($mysql_link) {
} else {
	//echo "Error: Unable to connect to MySQL." . PHP_EOL;
	//echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
	//echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
	exit;
}

$user_query = "INSERT INTO device(device_id, serial_num, net_address, connect_state, type, name,
						show_name, imgurl, assem_date, d_pass, access_ctrl) 
				values('$device_id', '$serial_num', '$net_address', '$connect_state', '$type', '$name', '$show_name', '$imgurl',
					'$assem_date', '$d_pass', '$access_ctrl');";
if($_GET == null) {
	exit;
}
$add_result = mysqli_query($mysql_link, $user_query);
if($add_result == FALSE) {
	echo "add device failed";
} else {
	echo "add device succeed!" . '<br/>';
}

