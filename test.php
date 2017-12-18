
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
	<title>新大陆物联网云平台 PHP API SDK</title>
</head><body>


<?php
require 'nlecloudsdk.php';

$apiurl = 'http://api0.nlecloud.com';	//测试地址，请修改为服务器正式地址，或直接为空
$token = '';


$Account = '13800000011';				//测试帐号
$Password = '123456';					//测试密码
$ProjectTag = "p97e1000479";			//测试的项目标识
$gatewayTag = "p97e1000479";			//测试的设备标识
$sensorApiTag = "76941BDCB657";			//测试的传感器ApiTag
$actuatorApiTag = "597A6D9232F8";		//测试的执行器ApiTag
$cametaApiTag = "040D26673AAF";			//测试的摄像头ApiTag

//创建api对象
$nleApi = new NLECloudSDK($apiurl);

echo "<b>用户登录（同时返回AccessToken）:</br ></b>";
$response = $nleApi->user_login($Account, $Password);
if ($response) 
{
    $token = json_decode($response)->ResultObj->AccessToken;
} 
else 
{
	//处理错误信息
    $error_code = $nleApi->error_no();
    $error = $nleApi->error();
}
var_dump($response);

if (empty($token)) {
	echo "<span style='color:red;'>TOKEN为空,以下接口不进行请求。</br ></span>";
	return;
}


echo "</br ></br ><b>获取某个设备的信息:</br ></b>";
$response = $nleApi->get_gateway_info($gatewayTag, $token);
if (!$response) 
{
	//处理错误信息
	$error_code = $nleApi->error_no();
	$error = $nleApi->error();
}
var_dump($response);


echo "</br ></br ><b>获取某个设备的传感器列表:</br ></b>";
$response = $nleApi->get_sensor_list($gatewayTag, $token);
if (!$response) 
{
	//处理错误信息
	$error_code = $nleApi->error_no();
	$error = $nleApi->error();
}
var_dump($response);

echo "</br ></br ><b>获取某个传感器的信息:</br ></b>";
$response = $nleApi->get_sensor_info($gatewayTag, $sensorApiTag, $token);
if (!$response) 
{
	//处理错误信息
	$error_code = $nleApi->error_no();
	$error = $nleApi->error();
}
var_dump($response);

echo "</br ></br ><b>获取某个设备的执行器列表:</br ></b>";
$response = $nleApi->get_actuator_list($gatewayTag, $token);
if (!$response) 
{
	//处理错误信息
	$error_code = $nleApi->error_no();
	$error = $nleApi->error();
}
var_dump($response);

echo "</br ></br ><b>获取某个执行器的信息:</br ></b>";
$response = $nleApi->get_actuator_info($gatewayTag, $actuatorApiTag, $token);
if (!$response) 
{
	//处理错误信息
	$error_code = $nleApi->error_no();
	$error = $nleApi->error();
}
var_dump($response);

echo "</br ></br ><b>获取某个设备的摄像头列表:</br ></b>";
$response = $nleApi->get_camera_list($gatewayTag, $token);
if (!$response) 
{
	//处理错误信息
	$error_code = $nleApi->error_no();
	$error = $nleApi->error();
}
var_dump($response);

echo "</br ></br ><b>获取某个摄像头的信息:</br ></b>";
$response = $nleApi->get_camera_info($gatewayTag, $cametaApiTag, $token);
if (!$response) 
{
	//处理错误信息
	$error_code = $nleApi->error_no();
	$error = $nleApi->error();
}
var_dump($response);


echo "</br ></br ><b>获取某个设备的当前在/离线状态:</br ></b>";
$response = $nleApi->get_gateway_onoffline($gatewayTag, $token);
if (!$response) 
{
	//处理错误信息
	$error_code = $nleApi->error_no();
	$error = $nleApi->error();
}
var_dump($response);

echo "</br ></br ><b>获取某个设备的历史分页在/离线状态:</br ></b>";
$response = $nleApi->get_gateway_historypager_onoffline($gatewayTag,'2017-01-01','2017-12-01',1,20, $token);
if (!$response) 
{
	//处理错误信息
	$error_code = $nleApi->error_no();
	$error = $nleApi->error();
}
var_dump($response);

echo "</br ></br ><b>获取某个设备的当前启/禁状态:</br ></b>";
$response = $nleApi->get_gateway_status($gatewayTag, $token);
if (!$response) 
{
	//处理错误信息
	$error_code = $nleApi->error_no();
	$error = $nleApi->error();
}
var_dump($response);

echo "</br ></br ><b>获取某个设备的所有传感器、执行器最新值:</br ></b>";
$response = $nleApi->get_gateway_newest_data($gatewayTag, $token);
if (!$response) 
{
	//处理错误信息
	$error_code = $nleApi->error_no();
	$error = $nleApi->error();
}
var_dump($response);

echo "</br ></br ><b>获取某个传感器的最新值:</br ></b>";
$response = $nleApi->get_sensor_newest_data($gatewayTag,$sensorApiTag, $token);
if (!$response) 
{
	//处理错误信息
	$error_code = $nleApi->error_no();
	$error = $nleApi->error();
}
var_dump($response);

echo "</br ></br ><b>获取某个传感器的历史数据:</br ></b>";
$response = $nleApi->get_sensor_history_data($gatewayTag,$sensorApiTag, 2, 7, $token);
if (!$response) 
{
	//处理错误信息
	$error_code = $nleApi->error_no();
	$error = $nleApi->error();
}
var_dump($response);

echo "</br ></br ><b>获取某个传感器的历史分页数据:</br ></b>";
$response = $nleApi->get_sensor_history_pagerdata($gatewayTag,$sensorApiTag,'2017-01-01','2017-12-01',1,20, $token);
if (!$response) 
{
	//处理错误信息
	$error_code = $nleApi->error_no();
	$error = $nleApi->error();
}
var_dump($response);


echo "</br ></br ><b>获取某个执行器的最新值:</br ></b>";
$response = $nleApi->get_actuator_newest_data($gatewayTag,$actuatorApiTag, $token);
if (!$response) 
{
	//处理错误信息
	$error_code = $nleApi->error_no();
	$error = $nleApi->error();
}
var_dump($response);

echo "</br ></br ><b>获取某个执行器的历史数据:</br ></b>";
$response = $nleApi->get_actuator_history_data($gatewayTag,$actuatorApiTag, 2, 7, $token);
if (!$response) 
{
	//处理错误信息
	$error_code = $nleApi->error_no();
	$error = $nleApi->error();
}
var_dump($response);

echo "</br ></br ><b>获取某个执行器的历史分页数据:</br ></b>";
$response = $nleApi->get_actuator_history_pagerdata($gatewayTag,$actuatorApiTag,'2017-01-01','2017-12-01',1,20, $token);
if (!$response) 
{
	//处理错误信息
	$error_code = $nleApi->error_no();
	$error = $nleApi->error();
}
var_dump($response);

echo "</br ></br ><b>获取某个项目的信息:</br ></b>";
$response = $nleApi->get_project_info($ProjectTag,  $token);
if (!$response) 
{
	//处理错误信息
	$error_code = $nleApi->error_no();
	$error = $nleApi->error();
}
var_dump($response);

echo "</br ></br ><b>控制某个执行器:</br ></b>";
$response = $nleApi->control_actuator($gatewayTag,$actuatorApiTag, 1 , $token);
if (!$response) 
{
	//处理错误信息
	$error_code = $nleApi->error_no();
	$error = $nleApi->error();
}
var_dump($response);


?>

</body>