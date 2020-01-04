## 说明
为了更加方便地向数据库添加设备，所以做出了简易网页用来向数据库添加设备，地址为[https://swv.wuwz.net/ad](https://swv.wuwz.net/ad) ，页面中表格已有默认值，为了快速新增设备，一般情况下你只需要更改一下 device_id 值，使数据库中没有重复的 device_id 值即可，否则会添加失败。当然如有需要，你也可以修改其他选项值。

## 接口使用样例
**注：所有返回数据均为 json 格式**
> 目录

> [TOC]

### URL/UserRegister
此接口在用户第一次授权登录小程序时调用，将用户信息写入到数据库
#### 使用样例
>
> https://swv.wuwz.net/UserRegister?openID=12485d8f45d&phone=18014711325&imgUrl=https://zkk.me/favicon.png

#### 参数
> - openID:用户微信账户openid
- phone:用户电话
- imgUrl:用户头像地址

#### 返回结果
> - result: 0(失败) / 1(成功)

------------

### URL/UserLogin
此接口可用于获取用户电话，头像地址信息
#### 使用样例
>
> https://swv.wuwz.net/UserLogin?openID=12485d8f45d

#### 参数
> - openID:用户账户ID

#### 返回结果
> 查询到此用户（登录成功）
- phone:用户电话号
- openID:用户账户ID
- imgUrl:用户头像地址

> 未查询到此用户（登录失败）
- phone:null
- openID:null
- imgUrl:null

------------

### URL/addDevice
这个接口是用户向自己的账户添加设备时调用，如果其中`source`值为2，表示此设备是其他人授权而添加到用户账户中，则需在下面样例基础上再添加`a_openID`字段和`a_access`字段，其值为授权者账户ID和授权权限类型
#### 使用样例
> https://swv.wuwz.net/addDevice?device_id=100001&openID=12485d8f45d&source=0

#### 参数
> - device_id:添加的设备ID
- openID:添加设备的用户ID
- source:添加设备的来源（0：扫码 1：输入序列号 2：其他用户授权）
- a_openID(可选):授权者账户ID
- a_access(可选):授权权限类型（0：仅访问 1：访问加控制），客户端在进行授权操作前需检查授权账户是否为设备的管理员，否则不能授权控制权限给他人

注：当source值为2时需要添加`a_openID`和`a_access`字段，其值为授权者的账户ID和授权权限类型

#### 返回结果
> - result:0（失败）/1(成功)

------------

### URL/delDevice
此接口在用户删除自己拥有的某个设备时调用
#### 使用样例
> https://swv.wuwz.net/delDevice?openID=100000000152&device_id=100002

#### 参数
> - openID:用户账户ID
- device_id:设备ID

#### 返回结果
> - result:0(失败) / 1(成功)

------------

### URL/startAccessCtrl
此接口在用户试图获取某个设备管理员身份时调用。

根据界面设计图片描述可知，一个设备的管理员只有一个，那就是开启设备的访问控制选项的用户。

在一个设备开启访问控制之前，任何用户都可以访问和控制此设备，但是当某个用户通过输入此设备正确的访问控制密码开启了此设备的访问控制选项，其他人需要经过管理员授权才能控制此设备，当然其他用户也可以通过输入正确的密码来获得此设备的控制权限，但是一旦管理员更改了此设备的访问控制密码，其他通过密码获得控制权限的用户都将失去权限，而被管理员授权的不会。

只有管理员可以将控制权限授权给其他用户，除管理员之外的用户只能将访问权限授权给其他人。

一个设备的默认的访问控制密码为123456。
#### 使用样例
> https://swv.wuwz.net/startAccessCtrl?apassword=123456&device_id=100001&openID=12485d8f45

#### 参数
> - apassword:用户输入的访问控制密码
- device_id:设备ID
- openID:用户账户ID

#### 返回结果
> - result:0(密码错误)/1（操作成功，即获得此设备的管理员身份）/2（操作失败，此设备已有管理员）/3(未找到设备或用户)

------------

### URL/getAccessCtrl
此接口在用户尝试获取设备的控制权限时调用（一个设备同时只能由一个管理员，但是可以有很多用户拥有其控制权限）
#### 使用样例
> https://swv.wuwz.net/getAccessCtrl?cpassword=123456&device_id=100001&openID=100000000151

#### 参数
> - cpassword:用户输入的访问控制密码
- device_id:设备ID
- openID:用户账户ID

#### 返回结果
> - result:0（失败）/1（成功）

------------

### URL/setDeviceTeam
此接口在用户设置设备的分组时调用
#### 使用样例
> https://swv.wuwz.net/setDeviceTeam?openID=100000000151&device_id=100001&team=1&team_name=highTeam

#### 参数
> - openID:用户ID
- device_id:设备ID
- team:分组号
- team_name:分组名称

#### 返回结果
> - result:0（失败）/1（成功）

------------

### URL/UserDevices
此接口用于获取某用户的所有设备的信息
#### 使用样例
> https://swv.wuwz.net/UserDevices?openID=100000000151

#### 参数
> - openID:用户ID

#### 返回结果
返回结果是一个数组，数组的每一个元素都是一个设备的信息。如下是有3个设备的数组，表示此用户有三个设备。
```json
[{
	"device_id": "100001",
	"serial_num": "100001",
	"connect_state": "1",
	"type": "1",
	"name": "another device",
	"show_name": "\u4e8c\u697c\u6c34\u9600 #1",
	"remark": "\u597d\u6c34\u9600",
	"imgurl": "",
	"access_ctrl": "1",
	"adm_openid": "12485d8f45d",
	"team": "1",
	"team_name": "highTeam",
	"state": "0",
	"error": "0",
	"position": "96",
	"open_ctrl": "2",
	"ctrl_type": "0",
	"accuracy": "12"
}, {
	"device_id": "100003",
	"serial_num": "100001",
	"connect_state": "1",
	"type": "1",
	"name": "another device",
	"show_name": "\u4e8c\u697c\u6c34\u9600 #3",
	"remark": "\u5907\u6ce8\u4fe1\u606f",
	"imgurl": "",
	"access_ctrl": "1",
	"adm_openid": "100000000152",
	"team": "1",
	"team_name": "highTeam",
	"state": "0",
	"error": "0",
	"position": "0",
	"open_ctrl": "0",
	"ctrl_type": "0",
	"accuracy": "45"
}, {
	"device_id": "100004",
	"serial_num": "100001",
	"connect_state": "1",
	"type": "1",
	"name": "another device",
	"show_name": "\u4e8c\u697c\u6c34\u9600 #4",
	"remark": "\u4e00\u53f0\u597d\u8bbe\u5907",
	"imgurl": "",
	"access_ctrl": "0",
	"adm_openid": null,
	"team": "1",
	"team_name": "highTeam",
	"state": "0",
	"error": "0",
	"position": "0",
	"open_ctrl": "0",
	"ctrl_type": "0",
	"accuracy": "32"
}]
```
如果此用户没有设备，则数组中只会有一个元素，并且其各个选项值均为 `null`

------------
### URL/UserDevice
此接口在获取用户某一个设备信息时调用，返回的结果是指定设备的信息
#### 使用样例
> https://swv.wuwz.net/UserDevice?openID=100000000152&device_id=100002

#### 参数
> - openID:用户账户ID
- device_id:要获取的设备ID

#### 返回结果
返回结果如下
```json
{
	"device_id": "100002",
	"serial_num": "100001",
	"connect_state": "1",
	"type": "1",
	"name": "another device",
	"show_name": "Great device",
	"remark": null,
	"imgurl": "",
	"access_ctrl": "1",
	"adm_openid": "100000000152",
	"team": "1",
	"team_name": "GoodTeam",
	"state": "0",
	"error": "0",
	"position": "0",
	"open_ctrl": "0",
	"ctrl_type": "0",
	"accuracy": "12"
}
```
如果未找到设备，则上述所有选项值均为 `null`

------------

### URL/OpenDeviceCtrl
此接口在用户使用执行器开关控制命令时调用
#### 使用样例
> https://swv.wuwz.net/OpenDeviceCtrl?openID=100000000152&device_id=100001&type=2

#### 参数
> - openID:操作者ID
- device_id:设备ID
- type:控制类型（0：停止/1：关闭/2：打开/3：ESD）

#### 返回结果
> - result:0（失败）/1（成功）/2（无操作权限）

------------

### URL/OpenDegreeCtrl
此接口在用户调节阀门开度时调用
#### 使用样例
> https://swv.wuwz.net/OpenDegreeCtrl?degree=236&openID=100000000152&device_id=100001

#### 参数
> - openID:操作者ID
- device_id:设备ID
- degree:阀门开度位置，为23.6%

#### 返回结果
> - result:0（失败）/1（成功）/2（无操作权限）

------------

### URL/setCtrlType
此接口在用户设置水阀开关控制方式时调用
#### 使用样例
> https://swv.wuwz.net/setCtrlType?type=1&openID=100000000152&device_id=100001

#### 参数
> - type:开关控制方式（1：高字节/2：低字节）
- openID：操作者ID
- device_id：设备ID

#### 返回结果
> - result:0（失败）/1（成功）/2（无操作权限）

------------

### URL/setAccuracy
此接口在用户设置设备精度时调用
#### 使用样例
> https://swv.wuwz.net/setAccuracy?accuracy=145&device_id=100001&openID=100000000152

#### 参数
> - accuracy:精度（5-100，前端加以判断）
- device_id:设备ID
- openID:操作者ID

#### 返回结果
> - result:0（失败）/1（成功）/2（无操作权限）

------------

### URL/UpdateDeviceName
此接口在用户修改设备名称或备注时调用
#### 使用样例
> https://swv.wuwz.net/UpdateDeviceName?remark=Good Valve&show_name=Valve&device_id=100001&openID=100000000152

#### 参数
> - show_name:设备名称（Value）
- remark:设备备注(Good Valve)
- device_id:设备ID
- openID:操作者ID

#### 返回结果
> - result:0（失败）/1（成功）/2（无操作权限）

------------

### URL/updateAccessCtrlPassword
此接口在管理员修改设备控制密码时调用
#### 使用样例
> https://swv.wuwz.net/updateAccessCtrlPassword?cPassword=123456789&device_id=100003&openID=100000000152

#### 参数
> - cPassword:新控制密码
- device_id:设备ID
- openID:操作者ID

#### 返回结果
> - result:0（失败）/1（成功）/2（无操作权限）

------------

### URL/DeviceHistoryInfo
此接口在用户获取某设备运行日志的时候调用
#### 使用样例
> https://swv.wuwz.net/DeviceHistoryInfo?device_id=100001

#### 参数
> - device_id:设备ID

#### 返回结果
返回结果是个数组，其中每个元素的字段含义如下
> - device_id:设备ID
- type:事件种类(阀门访问(0)，阀门控制(1)，阀门参数设置(2)，用户授权(3)，控制密码修改(4)，开启管理员权限（5），修改阀门名称（6))
- log:事件内容（返回结果为Unicode编码，需要解码以正常显示）
- time:事件时间

示例：
```json
{
	"2020-01-04": [{
		"device_id": "100005",
		"type": "7",
		"log": "18014711358删除了此设备",
		"time": "19:25:11"
	}, {
		"device_id": "100005",
		"type": "7",
		"log": "删除了此设备",
		"time": "22:30:17"
	}, {
		"device_id": "100005",
		"type": "7",
		"log": "删除了此设备",
		"time": "22:30:22"
	}, {
		"device_id": "100005",
		"type": "7",
		"log": "删除了此设备",
		"time": "22:30:38"
	}, {
		"device_id": "100005",
		"type": "7",
		"log": "13970501975删除了此设备",
		"time": "22:36:42"
	}, {
		"device_id": "100005",
		"type": "6",
		"log": "13970501975修改了此设备的名称和备注",
		"time": "23:54:58"
	}, {
		"device_id": "100005",
		"type": "2",
		"log": "13970501975已将此设备开度设置到了25",
		"time": "23:55:36"
	}, {
		"device_id": "100005",
		"type": "1",
		"log": "13970501975已将此设备打开",
		"time": "23:55:38"
	}, {
		"device_id": "100005",
		"type": "2",
		"log": "13970501975已将此设备开度设置到了30",
		"time": "23:55:44"
	}, {
		"device_id": "100005",
		"type": "1",
		"log": "13970501975已将此设备精度设置到了60",
		"time": "23:55:51"
	}, {
		"device_id": "100005",
		"type": "1",
		"log": "13970501975已将此设备开关控制方式设置为高字节",
		"time": "23:55:53"
	}, {
		"device_id": "100005",
		"type": "5",
		"log": "13970501975已成为此设备的管理员;",
		"time": "23:56:10"
	}, {
		"device_id": "100005",
		"type": "3",
		"log": "13970501975已经删除了13255671258对于此设备的所有权限",
		"time": "23:58:46"
	}, {
		"device_id": "100005",
		"type": "3",
		"log": "13970501975已经删除了13258741565对于此设备的所有权限",
		"time": "23:58:49"
	}],
	"2020-01-05": [{
		"device_id": "100005",
		"type": "3",
		"log": "13970501975已将设备100005访问控制权限授权给了",
		"time": "00:05:33"
	}, {
		"device_id": "100005",
		"type": "1",
		"log": "15301027593已将此设备停止",
		"time": "00:11:50"
	}, {
		"device_id": "100005",
		"type": "1",
		"log": "15301027593已将此设备打开",
		"time": "00:13:29"
	}, {
		"device_id": "100005",
		"type": "7",
		"log": "15301027593删除了此设备",
		"time": "00:33:45"
	}, {
		"device_id": "100005",
		"type": "3",
		"log": "13970501975已将此设备访问控制权限授权给了",
		"time": "00:37:04"
	}, {
		"device_id": "100005",
		"type": "7",
		"log": "15301027593删除了此设备",
		"time": "00:38:15"
	}, {
		"device_id": "100005",
		"type": "3",
		"log": "13970501975已将此设备访问控制权限授权给了",
		"time": "00:38:37"
	}, {
		"device_id": "100005",
		"type": "7",
		"log": "15301027593删除了此设备",
		"time": "00:39:09"
	}, {
		"device_id": "100005",
		"type": "3",
		"log": "13970501975已将此设备访问控制权限授权给了",
		"time": "00:39:21"
	}, {
		"device_id": "100005",
		"type": "7",
		"log": "15301027593删除了此设备",
		"time": "00:40:27"
	}, {
		"device_id": "100005",
		"type": "3",
		"log": "13970501975已将此设备访问控制权限授权给了15301027593",
		"time": "00:40:31"
	}]
}
```

------------

### URL/addAccessCtrlUsers
此接口在用户尝试将某设备向他人授权时调用
#### 使用样例
> https://swv.wuwz.net/addAccessCtrlUsers?a_openID=100000000152&phone=13825489635&device_id=100002&type=1

#### 参数
> - a_openID:授权者ID
> - phone:被授权用户电话号
> - device_id:设备ID
> - type:授权类型（0：仅访问/1：访问加控制）
#### 返回结果
> - result: 0:失败/1：成功/2：无权限/3:无此用户

------------

### URL/getAccessCtrlUsers
此接口在用户获取某个设备的所有有权限（访问或控制）的用户的列表及详情是调用(任何用户都可以获取此列表)
#### 使用样例
> https://swv.wuwz.net/getAccessCtrlUsers?device_id=100002

#### 参数
> - device_id:设备ID

#### 返回结果
返回结果是一个数组，每一个元素表示了一个拥有此设备的账户及所拥有的权限详情。（`p_access`为1表示有密码权限，`a_access`为0表示被授权访问权限，为1表示被授权控制权限，`a_openid`表示授权者账户，`u_phone`表示被授权者电话，`a_phone`是授权者电话）
```json
[{
	"u_openid": "100000000151",
	"device_id": "100001",
	"source": "0",
	"a_access": "0",
	"p_access": "1",
	"a_openid": "",
	"u_phone": "18014711358",
	"a_phone": ""
}, {
	"u_openid": "100000000152",
	"device_id": "100001",
	"source": "2",
	"a_access": "1",
	"p_access": "0",
	"a_openid": "12485d8f45d",
	"u_phone": "15858741258",
	"a_phone": "18014711325"
}, {
	"u_openid": "100000000161",
	"device_id": "100001",
	"source": "0",
	"a_access": "0",
	"p_access": "1",
	"a_openid": "",
	"u_phone": "13255671258",
	"a_phone": ""
}, {
	"u_openid": "12485d8f45d",
	"device_id": "100001",
	"source": "1",
	"a_access": "0",
	"p_access": "1",
	"a_openid": "",
	"u_phone": "18014711325",
	"a_phone": ""
}, {
	"u_openid": "odmNL5A1CdfYqHJo0plN0GvmQVbM",
	"device_id": "100001",
	"source": "0",
	"a_access": "0",
	"p_access": "1",
	"a_openid": "",
	"u_phone": "15189802118",
	"a_phone": ""
}, {
	"u_openid": "odmNL5AMpOwsIqoJbhjj0F7FFnaU",
	"device_id": "100001",
	"source": "1",
	"a_access": "0",
	"p_access": "1",
	"a_openid": "",
	"u_phone": "13970501975",
	"a_phone": ""
}]
```

------------

### URL/delAccessCtrlUsers
此接口在管理员删除某用户对于某设备的权限时调用
#### 使用样例
> https://swv.wuwz.net/delAccessCtrlUsers?a_openID=100000000152&device_id=100002&openID=100000000151

#### 参数
> - a_openID:操作者ID
- device_id:设备ID
- openID:被删除权限的用户

#### 返回结果
> - result:0(失败)/1（成功）/2（无权限，不是管理员）