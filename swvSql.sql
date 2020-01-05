#create database and user
create database SWV;
CREATE USER 'SWV'@'localhost' IDENTIFIED BY 'SWV';
GRANT ALL ON SWV.* TO 'SWV'@'localhost' WITH GRANT OPTION;

use SWV;
#create tabel user
CREATE TABLE user(
   u_openid VARCHAR(40),
   u_phone VARCHAR(20) NOT NULL UNIQUE,
   u_img VARCHAR(50),
   PRIMARY KEY (u_openid)
)DEFAULT CHARSET=utf8;

#create tabel device
CREATE TABLE device(
   device_id VARCHAR(40) NOT NULL,
   serial_num VARCHAR(40),
   net_address VARCHAR(40),
   connect_state int,
   type int,
   name VARCHAR(40),
   show_name VARCHAR(40),
   remark VARCHAR(50),
   imgurl VARCHAR(50),
   assem_date datetime,
   d_pass VARCHAR(40),
   access_ctrl int,
   c_pass VARCHAR(40) NOT NULL DEFAULT '123456',
   adm_openid VARCHAR(40),
   reserve1 VARCHAR(40),
   reserve2 VARCHAR(40),
   PRIMARY KEY (device_id),
   FOREIGN KEY (adm_openid) REFERENCES user(u_openid) on update cascade
)DEFAULT CHARSET=utf8;

#create tabel device_group
CREATE TABLE device_group(
   u_openid VARCHAR(40) NOT NULL,
   team int NOT NULL DEFAULT 1,
   team_name VARCHAR(20),
   PRIMARY KEY (u_openid, team),
   FOREIGN KEY (u_openid) REFERENCES user(u_openid) on delete cascade on update cascade
)DEFAULT CHARSET=utf8;

#create tabel user_device
CREATE TABLE user_device(
   u_openid VARCHAR(40) NOT NULL,
   device_id VARCHAR(40) NOT NULL,
   source int,
   a_access int NOT NULL DEFAULT 0,
   p_access int NOT NULL DEFAULT 0,
   a_openid VARCHAR(40),
   team int NOT NULL DEFAULT 1,
   PRIMARY KEY (u_openid, device_id),
   FOREIGN KEY (u_openid) REFERENCES user(u_openid) on delete cascade on update cascade,
   FOREIGN KEY (device_id) REFERENCES device(device_id) on delete cascade on update cascade
)DEFAULT CHARSET=utf8;

#create tabel user_log
CREATE TABLE user_log(
   device_id VARCHAR(40) NOT NULL,
   type int,
   log VARCHAR(100),
   time datetime,
   FOREIGN KEY (device_id) REFERENCES device(device_id) on delete cascade on update cascade
)DEFAULT CHARSET=utf8;

#create tabel device_state
CREATE TABLE device_state(
   device_id VARCHAR(40) NOT NULL,
   state INT,
   error1 INT,
   error2 INT,
   position INT,
   tor INT,
   vol INT,
   cur INT,
   pow INT,
   ecu_vol INT,
   elec INT,
   temper INT,
   inertia INT,
   in_state INT,
   out_state INT,
   analog_in INT,
   updatetime DATETIME,
   time datetime,
   PRIMARY KEY (device_id),
   FOREIGN KEY (device_id) REFERENCES device(device_id) on delete cascade on update cascade
)DEFAULT CHARSET=utf8;

#create tabel device_ctrl
CREATE TABLE device_ctrl(
   device_id VARCHAR(40) NOT NULL,
   open_ctrl INT,
   position_ctrl INT,
   ctrl_type INT,
   accuracy INT,
   PRIMARY KEY (device_id),
   FOREIGN KEY (device_id) REFERENCES device(device_id) on delete cascade on update cascade
)DEFAULT CHARSET=utf8;

#create table device_event
CREATE TABLE device_event(
   device_id VARCHAR(40) NOT NULL,
   time DATETIME,
   event VARCHAR(20),
   FOREIGN KEY (device_id) REFERENCES device(device_id) on delete cascade on update cascade
)DEFAULT CHARSET=utf8;

#create table device_error
CREATE TABLE device_error(
   device_id VARCHAR(40) NOT NULL,
   time DATETIME,
   event VARCHAR(20),
   FOREIGN KEY (device_id) REFERENCES device(device_id) on delete cascade on update cascade
)DEFAULT CHARSET=utf8;

#create table device_info
CREATE TABLE device_info(
   device_id VARCHAR(40) NOT NULL,
   position INT,
   state INT,
   warning INT,
   net INT,
   error INT,
   block_time INT,
   accuracy INT,
   break INT,
   posneg INT,
   updatetime DATETIME,
   PRIMARY KEY (device_id),
   FOREIGN KEY (device_id) REFERENCES device(device_id) on delete cascade on update cascade
)DEFAULT CHARSET=utf8;




