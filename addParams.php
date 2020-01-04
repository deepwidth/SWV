<?php
$add_state = "INSERT INTO `device_state`(`device_id`, `state`, `error1`, `error2`, `position`, `tor`, `vol`, `cur`, `pow`, `ecu_vol`, `elec`, `temper`, `inertia`, `in_state`, `out_state`, `analog_in`, `updatetime`, `time`)
VALUES ('$device_id',1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);";
mysqli_query($mysql_link, $add_state);
$add_info = "INSERT INTO `device_info`(`device_id`, `position`, `state`, `warning`, `net`, `error`, `block_time`, `accuracy`, `break`, `posneg`, `updatetime`)
VALUES ('$device_id',0,0,0,0,0,0,5,0,0,0);";
mysqli_query($mysql_link, $add_info);

$add_ctrl = "INSERT INTO `device_ctrl`(`device_id`, `open_ctrl`, `position_ctrl`, `ctrl_type`, `accuracy`) VALUES ('$device_id',0,0,1,5);";
mysqli_query($mysql_link, $add_ctrl);

?>
