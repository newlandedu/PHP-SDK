
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
	<title>新大陆物联网云平台 PHP API SDK</title>
</head>
<body>


<?php
require 'nlecloudsdk.php';

$token = '';						//存储登录成功返回临时Token,用于请求其它API的凭证

$userName = '18965562233';			//测试帐号
$password = '123456';				//测试密码
$projectID = "282";					//测试的项目ID
$deviceID = "282";					//测试的设备ID
$Tag = 'P97E1000479';				//测试重复添加设备的标识
$sensorApiTag = "m_temperature";	//测试的传感器ApiTag
$actuatorApiTag = "nl_fan";			//测试的执行器ApiTag
$cametaApiTag = "newCamera";		//测试的摄像头ApiTag

//创建api对象
$nleApi = new NLECloudSDK();

echo "<b>用户登录（同时返回AccessToken）:</br ></b>";

$loginInfo = new Account();
$loginInfo->Account=$userName;
$loginInfo->Password=$password;
$loginInfo->IsRememberMe=false;

$response = $nleApi->user_login($loginInfo);
if ($response)
{
    $token = $response->ResultObj->AccessToken;
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


echo "</br ></br ><b>查询单个项目:</br ></b>";
$response = $nleApi->get_project_info($projectID, $token);
if (!$response)
{
    //处理错误信息
    $error_code = $nleApi->error_no();
    $error = $nleApi->error();
}
var_dump($response);

echo "</br ></br ><b>模糊查询项目:</br ></b>";

$projectQuery=new ProjectQuery();
$projectQuery->StartDate="2018-01-01 00:00:00";
$projectQuery->EndDate="2018-12-31 00:00:00";
$projectQuery->PageSize=3;
$response = $nleApi->get_projects($projectQuery, $token);
if (!$response)
{
    //处理错误信息
    $error_code = $nleApi->error_no();
    $error = $nleApi->error();
}
var_dump($response);

echo "</br ></br ><b>查询项目所有设备的传感器:</br ></b>";
$response = $nleApi->get_project_sensors($projectID, $token);
if (!$response)
{
    //处理错误信息
    $error_code = $nleApi->error_no();
    $error = $nleApi->error();
}
var_dump($response);


echo "</br ></br ><b>批量查询设备最新数据:</br ></b>";
$deviceIds=$deviceID;
$response = $nleApi->get_devices_datas($deviceIds, $token);
if (!$response)
{
    //处理错误信息
    $error_code = $nleApi->error_no();
    $error = $nleApi->error();
}
var_dump($response);


echo "</br ></br ><b>批量查询设备的在线状态:</br ></b>";
$deviceIds=$deviceID;
$response = $nleApi->get_devices_status($deviceIds, $token);
if (!$response)
{
    //处理错误信息
    $error_code = $nleApi->error_no();
    $error = $nleApi->error();
}
var_dump($response);


echo "</br ></br ><b>查询单个设备:</br ></b>";
$response = $nleApi->get_device_info($deviceID, $token);
if (!$response)
{
    //处理错误信息
    $error_code = $nleApi->error_no();
    $error = $nleApi->error();
}
var_dump($response);


echo "</br ></br ><b>模糊查询设备:</br ></b>";

$deviceQuery=new DeviceQuery();
$deviceQuery->StartDate="2018-01-01 00:00:00";
$deviceQuery->EndDate="2018-12-31 00:00:00";
$deviceQuery->PageSize=3;
$response = $nleApi->get_devices($deviceQuery, $token);
if (!$response)
{
    //处理错误信息
    $error_code = $nleApi->error_no();
    $error = $nleApi->error();
}
var_dump($response);


echo "</br ></br ><b>添加个新设备（重复添加示例）:</br ></b>";

$deviceAdd=new DeviceAddUpdate();
$deviceAdd->Protocol=1;
$deviceAdd->Name=time();
$deviceAdd->Tag=$gatewayTag;
$deviceAdd->ProjectIdOrTag=$projectID;
$response = $nleApi->add_device($deviceAdd, $token);
if (!$response)
{
    //处理错误信息
    $error_code = $nleApi->error_no();
    $error = $nleApi->error();
}else{
    $deviceId = $response->ResultObj;
}
var_dump($response);

echo "</br ></br ><b>添加个新设备:</br ></b>";

$deviceAdd=new DeviceAddUpdate();
$deviceAdd->Protocol=1;
$deviceAdd->Tag= "d".time();
$deviceAdd->Name=time();
$deviceAdd->ProjectIdOrTag=$projectID;
$deviceAdd->IsShare=true;
$response = $nleApi->add_device($deviceAdd, $token);
if (!$response)
{
    //处理错误信息
    $error_code = $nleApi->error_no();
    $error = $nleApi->error();
}

var_dump($response);
var_dump($nleApi->get_device_info($deviceAdd->DeviceId, $token));

echo "</br ></br ><b>更新某个设备:</br ></b>";

$deviceUpdate=new DeviceAddUpdate();
$deviceUpdate->DeviceId=$deviceAdd->DeviceId;
$deviceUpdate->Tag ="new_tag";
$deviceUpdate->Name= "new_name";
$deviceUpdate->Protocol=2;
$deviceUpdate->IsShare=false;
$deviceUpdate->ProjectIdOrTag=$projectID;
$response = $nleApi->update_device($deviceUpdate, $token);
if (!$response)
{
    //处理错误信息
    $error_code = $nleApi->error_no();
    $error = $nleApi->error();
}
var_dump($response);
var_dump($nleApi->get_device_info($deviceAdd->DeviceId, $token));

echo "</br ></br ><b>删除某个设备:</br ></b>";

$response = $nleApi->delete_device($deviceAdd->DeviceId, $token);
if (!$response)
{
    //处理错误信息
    $error_code = $nleApi->error_no();
    $error = $nleApi->error();
}
var_dump($response);
var_dump($nleApi->get_device_info($deviceAdd->DeviceId, $token));

echo "</br ></br ><b>查询单个传感器:</br ></b>";
$response = $nleApi->get_sensor_info($deviceID, $sensorApiTag, $token);
if (!$response)
{
    //处理错误信息
    $error_code = $nleApi->error_no();
    $error = $nleApi->error();
}
var_dump($response);

echo "</br ></br ><b>模糊查询传感器:</br ></b>";
$response = $nleApi->get_sensors($deviceID, $sensorApiTag, $token);
if (!$response)
{
    //处理错误信息
    $error_code = $nleApi->error_no();
    $error = $nleApi->error();
}
var_dump($response);

echo "</br ></br ><b>添加个新传感器:</br ></b>";

$sensorAdd = new SensorAddUpdate();
$sensorAdd->DeviceId = $deviceID;
$sensorAdd->ApiTag = "s".time();
$sensorAdd->Name =time();
$sensorAdd->TransType=1;
$sensorAdd->DataType=1;
$sensorAdd->Unit= "Low";
$response = $nleApi->add_sensor($sensorAdd, $token);
if (!$response)
{
    //处理错误信息
    $error_code = $nleApi->error_no();
    $error = $nleApi->error();
}
var_dump($response);
var_dump($nleApi->get_sensor_info($deviceID, $sensorAdd->ApiTag, $token));

echo "</br ></br ><b>添加个新执行器:</br ></b>";

$actorAdd = new ActorAddUpdate();
$actorAdd->DeviceId = $deviceID;
$actorAdd->ApiTag = "a".time();
$actorAdd->Name =time();
$actorAdd->TransType=1;
$actorAdd->DataType=1;
$actorAdd->OperType=1;
$actorAdd->OperTypeAttrs='{"MaxRange" : 100 ,"MinRange" : 0, "Step" : 10}';
$response = $nleApi->add_sensor($actorAdd, $token);
if (!$response)
{
    //处理错误信息
    $error_code = $nleApi->error_no();
    $error = $nleApi->error();
}
var_dump($response);
var_dump($nleApi->get_sensor_info($deviceID, $actorAdd->ApiTag, $token));

echo "</br ></br ><b>添加个新摄像头:</br ></b>";

$cameraAdd = new CameraAddUpdate();
$cameraAdd->DeviceId = $deviceID;
$cameraAdd->ApiTag = "c".time();
$cameraAdd->Name =time();
$cameraAdd->TransType=1;
$cameraAdd->DataType=1;
$cameraAdd->HttpIp='127.0.0.1';
$cameraAdd->HttpPort=8080;
$cameraAdd->UserName='user';
$cameraAdd->Password='123';
$response = $nleApi->add_sensor($cameraAdd, $token);
if (!$response)
{
    //处理错误信息
    $error_code = $nleApi->error_no();
    $error = $nleApi->error();
}
var_dump($response);
var_dump($nleApi->get_sensor_info($deviceID, $cameraAdd->ApiTag, $token));

echo "</br ></br ><b>更新某个传感器:</br ></b>";

$sensorUpdate = new SensorAddUpdate();
$sensorUpdate->DeviceId = $deviceID;
$sensorUpdate->ApiTag = $sensorAdd->ApiTag;
$sensorUpdate->Name =time();
$sensorUpdate->TransType=0;
$sensorUpdate->DataType=0;
$sensorUpdate->Unit= "Height";
$response = $nleApi->update_sensor($sensorUpdate, $token);
if (!$response)
{
    //处理错误信息
    $error_code = $nleApi->error_no();
    $error = $nleApi->error();
}
var_dump($response);
var_dump($nleApi->get_sensor_info($deviceID, $sensorAdd->ApiTag, $token));

echo "</br ></br ><b>更新某个执行器:</br ></b>";

$actorUpdate = new ActorAddUpdate();
$actorUpdate->DeviceId = $deviceID;
$actorUpdate->ApiTag = $actorAdd->ApiTag;
$actorUpdate->Name =time();
$actorUpdate->TransType=0;
$actorUpdate->DataType=0;
$actorUpdate->OperType=2;
$actorUpdate->OperTypeAttrs='{"MaxRange" : 200 ,"MinRange" : 0, "Step" : 10}';
$response = $nleApi->update_sensor($actorUpdate, $token);
if (!$response)
{
    //处理错误信息
    $error_code = $nleApi->error_no();
    $error = $nleApi->error();
}
var_dump($response);
var_dump($nleApi->get_sensor_info($deviceID, $actorAdd->ApiTag, $token));

echo "</br ></br ><b>更新某个摄像头:</br ></b>";

$cameraUpdate = new CameraAddUpdate();
$cameraUpdate->DeviceId = $deviceID;
$cameraUpdate->ApiTag = $cameraAdd->ApiTag;
$cameraUpdate->Name =time();
$cameraUpdate->TransType=0;
$cameraUpdate->DataType=0;
$cameraUpdate->HttpIp='127.0.0.2';
$cameraUpdate->HttpPort=8888;
$cameraUpdate->UserName='user1';
$cameraUpdate->Password='123456';
$response = $nleApi->update_sensor($cameraUpdate, $token);
if (!$response)
{
    //处理错误信息
    $error_code = $nleApi->error_no();
    $error = $nleApi->error();
}
var_dump($response);
var_dump($nleApi->get_sensor_info($deviceID, $cameraAdd->ApiTag, $token));

echo "</br ></br ><b>删除某个传感器:</br ></b>";

$response = $nleApi->delete_sensor($deviceID, $sensorAdd->ApiTag, $token);
if (!$response)
{
    //处理错误信息
    $error_code = $nleApi->error_no();
    $error = $nleApi->error();
}
var_dump($response);
var_dump($nleApi->get_sensor_info($deviceID, $sensorAdd->ApiTag, $token));

echo "</br ></br ><b>删除某个执行器:</br ></b>";

$response = $nleApi->delete_sensor($deviceID, $actorAdd->ApiTag, $token);
if (!$response)
{
    //处理错误信息
    $error_code = $nleApi->error_no();
    $error = $nleApi->error();
}
var_dump($response);
var_dump($nleApi->get_sensor_info($deviceID, $actorAdd->ApiTag, $token));

echo "</br ></br ><b>删除某个摄像头:</br ></b>";

$response = $nleApi->delete_sensor($deviceID, $cameraAdd->ApiTag, $token);
if (!$response)
{
    //处理错误信息
    $error_code = $nleApi->error_no();
    $error = $nleApi->error();
}
var_dump($response);
var_dump($nleApi->get_sensor_info($deviceID, $cameraAdd->ApiTag, $token));

echo "</br ></br ><b>新增传感数据:</br ></b>";

$sensorDataArray=  array(new SensorDataAdd(), new SensorDataAdd());
$sensorDataArray[0]->ApiTag = $sensorApiTag;
$sensorDataArray[1]->ApiTag = $sensorApiTag;
$sensorDataArray[0]->PointDTO = array(new SensorDataAddPoint());
$sensorDataArray[1]->PointDTO = array(new SensorDataAddPoint());
$sensorDataArray[0]->PointDTO[0]->Value=time();
$sensorDataArray[0]->PointDTO[0]->RecordTime="2018-01-01 00:00:00";
$sensorDataArray[1]->PointDTO[0]->Value=time();
$sensorDataArray[1]->PointDTO[0]->RecordTime="2018-02-01 00:00:00";

$response = $nleApi->add_sensor_data($deviceID,$sensorDataArray, $token);
if (!$response)
{
    //处理错误信息
    $error_code = $nleApi->error_no();
    $error = $nleApi->error();
}
var_dump($response);


echo "</br ></br ><b>查询传感数据:</br ></b>";
$sensorDataQuery=new SensorDataQuery();
$sensorDataQuery->DeviceId=$deviceID;
$sensorDataQuery->ApiTags=$sensorApiTag;
$sensorDataQuery->Method=6;
$sensorDataQuery->StartDate="2018-01-01 00:00:00";
$sensorDataQuery->EndDate="2018-12-31 00:00:00";
$sensorDataQuery->PageSize=3;
$response = $nleApi->get_sensor_data($sensorDataQuery, $token);
if (!$response)
{
    //处理错误信息
    $error_code = $nleApi->error_no();
    $error = $nleApi->error();
}
var_dump($response);


echo "</br ></br ><b>发送命令/控制设备:</br ></b>";

$response = $nleApi->Cmds($deviceID, $actuatorApiTag, 0, $token);
if (!$response)
{
    //处理错误信息
    $error_code = $nleApi->error_no();
    $error = $nleApi->error();
}
var_dump($response);




?>

</body>