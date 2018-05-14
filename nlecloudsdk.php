<?php
 /**
 * 新大陆物联网云平台 PHP API SDK
 * Created by cs
 * @time  2017/12/8
 * Time: 9:33
 */

class NLECloudSDK
{
    protected $token = NULL;

    protected $_base_url = 'http://api.nlecloud.com/';

    protected $_raw_response = ''; // 服务端返回的原始数据

    protected $_http_code = 200;

    protected $_error_no = 0;

    protected $_error = '';

    protected static $_ALLOW_METHODS = array(
        'GET',
        'PUT',
        'POST',
        'DELETE'
    );

    
    public function __construct($base_url = NULL)
    {
		if (! empty($base_url)) {
            $this->_base_url = $base_url;
        }
    }



    /* ============================== 帐号API ============================== */

	/*
    * 用户登录（同时返回AccessToken）
	$account  Account
	{
        Account  账号
        Password	用户密码
	    IsRememberMe  记住我
	} Required
    */
    public function user_login($account)
    {
       // var_dump($account);

        if(empty($account)||empty($account->Account)||empty($account->Password)) {
            return false;
        }

        $api = "/Users/Login";

        return $this->_call($api, 'POST', $account);
    }

    /* ============================== 项目API ============================== */

	/*
    * 查询单个项目
	* $projectId	string	项目ID	Required
	* $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function get_project_info($projectId, $token = NULL)
    {
        if(empty($projectId)) {
            return false;
        }

        $api = "/Projects/". $projectId;

        return $this->_call($api, 'GET', null, $token);
    }

    /*
    *  模糊查询项目
    * $query	ProjectQuery
     {
            Keyword	string	关键字（可选，从id或name字段模糊匹配查询）
            ProjectTag	string	项目标识码（可选，一个32位字符串）
            NetWorkKind	byte	联网方案 （可选，1：WIFI 2：以太网 3:蜂窝网络 4:蓝牙）
            PageSize	integer	指定每页要显示的数据个数，默认20，最多100
            StartDate	string	起始时间（可选，包括当天，格式YYYY-MM-DD）
            EndDate	string	结束时间（可选，包括当天，格式YYYY-MM-DD）
            PageIndex	integer	指定页码
    }	Required
    * $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function get_projects($query, $token = NULL)
    {
        if(empty($query)||!is_a($query,'ProjectQuery')) {
            return false;
        }

        $api = "/Projects".
            "?Keyword=".
            $query->Keyword.
            "&ProjectTag=".
            $query->ProjectTag.
            "&NetWorkKind=".
            $query->NetWorkKind.
            "&PageSize=".
            $query->PageSize.
            "&StartDate=".
            urlencode($query->StartDate).
            "&EndDate=".
            urlencode($query->EndDate).
            "&PageIndex=".
            $query->PageIndex;

        return $this->_call($api, 'GET', $query, $token);
    }

    /*
    * 查询项目所有设备的传感器
    * $projectid	string	项目ID	Required
    * $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function get_project_sensors($projectid, $token = NULL)
    {
        if(empty($projectid)) {
            return false;
        }

        $api = "/Projects/". $projectid."/Sensors";

        return $this->_call($api, 'GET', null, $token);
    }

    /* ============================== 设备API ============================== */

    /*
    *  批量查询设备最新数据
    * $devIds	string	设备ID用逗号隔开, 限制100个设备	Required
    * $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function get_devices_datas($devIds, $token = NULL)
    {
        if(empty($devIds)) {
            return false;
        }

        $api = "/Devices/Datas?devIds=". $devIds;

        return $this->_call($api, 'GET', null, $token);
    }

    /*
    *   批量查询设备的在线状态
    * $devIds	string	设备ID用逗号隔开, 限制100个设备	Required
    * $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function get_devices_status($devIds, $token = NULL)
    {
        if(empty($devIds)) {
            return false;
        }

        $api = "/Devices/Status?devIds=". $devIds;

        return $this->_call($api, 'GET', null, $token);
    }

    /*
    *   查询单个设备
    * $deviceId	string	设备ID		Required
    * $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function get_device_info($deviceId, $token = NULL)
    {
        if(empty($deviceId)) {
            return false;
        }

        $api = "/Devices/". $deviceId;

        return $this->_call($api, 'GET', null, $token);
    }

    /*
    *   模糊查询设备
    *  $query DeviceQuery
        {
                Keyword	string	关键字（可选，从id或name字段左匹配）
                DeviceIds	string	指定设备ID（可选，如“124,34423,2345”，多个用逗号分隔，最多100个）
                Tag	string	设备标识（可选）
                IsOnline	string	在线状态（可选，true|false）
                IsShare	string	数据保密性（可选，true|false）
                ProjectKeyWord	string	项目ID或纯32位字符的项目标识码（可选）
                PageSize	integer	指定每页要显示的数据个数，默认20，最多100
                StartDate	string	起始时间（可选，包括当天，格式YYYY-MM-DD）
                EndDate	string	结束时间（可选，包括当天，格式YYYY-MM-DD）
                PageIndex	integer	指定页码 Required
        }
    * $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function get_devices($query, $token = NULL)
    {
        if(empty($query)||!is_a($query,'DeviceQuery')) {
            return false;
        }

        $api = "/Devices".
            "?Keyword=".
            $query->Keyword.
            "&DeviceIds=".
            $query->DeviceIds.
            "&Tag=".
            $query->Tag.
            "&IsOnline=".
            $query->IsOnline.
            "&IsShare=".
            $query->IsShare.
            "&ProjectKeyWord=".
            $query->ProjectKeyWord.
            "&PageSize=".
            $query->PageSize.
            "&StartDate=".
            urlencode($query->StartDate).
            "&EndDate=".
            urlencode($query->EndDate).
            "&PageIndex=".
            $query->PageIndex;
            //var_dump($api);
        return $this->_call($api, 'GET', $query, $token);
    }

    /*
    *   添加个新设备
    * $device	DeviceAddUpdate
     {
            Protocol	byte	通讯协议（1:TCP 2:MQTT 3:HTTP）	RequiredRange: inclusive between 1 and 3
            IsTrans	boolean	数据上报状态，true | false（可选，默认为ture）
            ProjectIdOrTag	string	项目ID（一个数字）或标识码（一个32位字符串）	String length: inclusive between 1 and 32
            Name	string	设备名称（中英文、数字的6到15个字）	RequiredString length: inclusive between 1 and 15
            Tag	string	设备标识（英文、数字或其组合6到30个字符）	RequiredMatching regular expression pattern: ^[a-zA-Z0-9_]{6,30}$
            Coordinate	string	设备座标（可选，格式为经度值, 纬度值）
            DeviceImg	string	设备头像（可选）
            IsShare	boolean	数据保密性，true | false（可选，默认为ture）
    }Required
    * $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function add_device($device, $token = NULL)
    {
        //var_dump($device);
        if (empty($device) || !is_a($device, 'DeviceAddUpdate')) {
            return false;
        }

        $api = "/Devices";
        $result = $this->_call($api, 'POST', $device, $token);
        if ($result) {
            $device->DeviceId = $result->ResultObj;
        }
        return $result;
    }

    /*
    *    更新某个设备
    * $device DeviceAddUpdate
     {
            DeviceId  int  更新的设备ID
            Protocol	byte	通讯协议（1:TCP 2:MQTT 3:HTTP）	RequiredRange: inclusive between 1 and 3
            IsTrans	boolean	数据上报状态，true | false（可选，默认为ture）
            ProjectIdOrTag	string	项目ID（一个数字）或标识码（一个32位字符串）	String length: inclusive between 1 and 32
            Name	string	设备名称（中英文、数字的6到15个字）	RequiredString length: inclusive between 1 and 15
            Tag	string	设备标识（英文、数字或其组合6到30个字符）	RequiredMatching regular expression pattern: ^[a-zA-Z0-9_]{6,30}$
            Coordinate	string	设备座标（可选，格式为经度值, 纬度值）
            DeviceImg	string	设备头像（可选）
            IsShare	boolean	数据保密性，true | false（可选，默认为ture）
    }Required
    * $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function update_device($device,$token = NULL)
    {
        if(empty($device)||empty($device->DeviceId)||!is_a($device,'DeviceAddUpdate')) {
            return false;
        }

        $api = "/Devices/" .$device->DeviceId;
        //var_dump($api);
        return $this->_call($api, 'PUT', $device, $token);
    }

    /*
    *   删除某个设备
    * $deviceId string 设备id Required
    * $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function delete_device($deviceId, $token = NULL)
    {
        if(empty($deviceId)) {
            return false;
        }
        $api = "/Devices/" . $deviceId;

        return $this->_call($api, 'DELETE', null, $token);
    }

    /* ============================== 设备传感器API ============================== */

    /*
    *  查询单个传感器
    * $deviceId string 设备id Required
    * $apiTag string 传感标识名	 Required
    * $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function get_sensor_info($deviceId, $apiTag, $token = NULL)
    {
        if(empty($deviceId)||empty($apiTag)) {
            return false;
        }

        $api = "/devices/" . $deviceId . "/Sensors/" . $apiTag;

        return $this->_call($api, 'GET', null, $token);
    }

    /*
    *  模糊查询传感器
    * $deviceId string 设备id Required
    * $apiTags string 传感标识名，多个标识名之间用逗号分开（参数缺省时为查询所有）		 Required
    * $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function get_sensors($deviceId, $apiTags=NULL, $token = NULL)
    {
        if(empty($deviceId)) {
            return false;
        }

        $api = "/devices/" . $deviceId . "/Sensors?apiTags=" . $apiTags;

        return $this->_call($api, 'GET', null, $token);
    }

    /*
    *  添加个新传感器
    *  $sensor	SensorAddUpdate
         {
                DeviceId 设备ID
                ApiTag string 标识名
                Name string 传感名称
                SerialNumber	int 序列号（可选，同一类型的多个以此区别，默认0）
                Unit	string	单位（可选，定义传感器的单位）
                Precision	string	精度（可选，默认保留两位小数）
                Groups int 传感组别（1：传感器 2：执行器 3：摄像头  4：LED）
                Protocol int 通信协议（1：modbus 2：zigbee 3：tcp  4：udp)
                TransType byte 传输类型（可选，0：只上报1：上报和下发2：报警3：故障，默认0）
                DataType  byte 数据类型（可选，0：整数型1：浮点型2：布尔型3：字符型4：枚举型5：二进制型，默认0）
                TypeAttrs string  传输类型与数据类型的属性（可选，如枚举型值以半角逗号分隔：可爱，有在，装备，蜗牛）
        } Required
    * $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function add_sensor($sensor, $token = NULL)
    {
        if(empty($sensor)||!is_a($sensor,'SensorAddUpdateBase')) {
            return false;
        }

        $api = "/devices/".$sensor->DeviceId."/Sensors";

        return $this->_call($api, 'POST', $sensor, $token);
    }

    /*
    *  更新某个传感器
    *  $sensor	SensorAddUpdate
         {
                DeviceId 设备ID
                ApiTag string 标识名
                Name string 传感名称
                SerialNumber	int 序列号（可选，同一类型的多个以此区别，默认0）
                Unit	string	单位（可选，定义传感器的单位）
                Precision	string	精度（可选，默认保留两位小数）
                Groups int 传感组别（1：传感器 2：执行器 3：摄像头  4：LED）
                Protocol int 通信协议（1：modbus 2：zigbee 3：tcp  4：udp)
                TransType byte 传输类型（可选，0：只上报1：上报和下发2：报警3：故障，默认0）
                DataType  byte 数据类型（可选，0：整数型1：浮点型2：布尔型3：字符型4：枚举型5：二进制型，默认0）
                TypeAttrs string  传输类型与数据类型的属性（可选，如枚举型值以半角逗号分隔：可爱，有在，装备，蜗牛）
        } Required
    * $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function update_sensor($sensor, $token = NULL)
    {
        if(empty($sensor)||!is_a($sensor,'SensorAddUpdateBase')) {
            return false;
        }

        $api = "/devices/".$sensor->DeviceId."/Sensors/".$sensor->ApiTag;

        return $this->_call($api, 'PUT', $sensor, $token);
    }

    /*
    *   删除某个传感器
    * $deviceId string 设备id Required
    * $apiTag string 传感器标识 Required
    * $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function delete_sensor($deviceId, $apiTag, $token = NULL)
    {
        if(empty($deviceId)||empty($apiTag)) {
            return false;
        }

        $api = "/devices/" . $deviceId . "/Sensors/" . $apiTag;

        return $this->_call($api, 'DELETE', null, $token);
    }

    /* ============================== 传感器数据API ============================== */

    /*
    *    新增传感数据
    * $deviceId string  设备ID	Required
    * $data	array of SensorDataAdd	Required
    * $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function add_sensor_data($deviceId, $data, $token = NULL)
    {
        if(empty($deviceId)||empty($data)) {
            return false;
        }
        $api = "/devices/" . $deviceId . "/Datas";

        return $this->_call($api, 'POST',  array("DatasDTO"=>$data), $token);
    }

    /*
    *   查询传感数据
    *  $query SensorDataQuery
         {
                DeviceId integer	设备ID	Required
                ApiTags	string	传感标识名（可选，多个用逗号分隔，最多50个）
                Method	integer	查询方式（1：XX分钟内 2：XX小时内 3：XX天内 4：XX周内 5：XX月内 6：按startDate与endDate指定日期查询）
                TimeAgo	decimal number	与Method配对使用表示"多少TimeAgo Method内"的数据，例：(Method=2,TimeAgo=30)表示30小时内的历史数据
                StartDate	string	起始时间（可选，格式YYYY-MM-DD HH:mm:ss）
                EndDate	string	结束时间（可选，格式YYYY-MM-DD HH:mm:ss）
                Sort	string	时间排序方式，DESC:倒序，ASC升序
                PageSize	integer	指定每次要请求的数据条数，默认1000，最多3000
                PageIndex	integer	指定页码	Required
        }
    * $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function get_sensor_data($query, $token = NULL)
    {
        if(empty($query)|| is_a($query,"SensorDataQuery"||empty($query->DeviceId))) {
            return false;
        }
        $api =  "/devices/" .$query->DeviceId . "/Datas".
            "?deviceId=".
            $query->DeviceId.
            "&ApiTags=".
            $query->ApiTags.
            "&Method=".
            $query->Method.
            "&TimeAgo=".
            $query->TimeAgo.
            "&StartDate=".
            urlencode($query->StartDate).
            "&EndDate=".
            urlencode($query->EndDate).
            "&Sort=".
            $query->Sort.
            "&PageSize=".
            $query->PageSize.
            "&PageIndex=".
            $query->PageIndex;
        //var_dump($api);

        return $this->_call($api, 'GET', $query, $token);
    }

    /* ============================== 命令API  ============================== */

    /*
    *  发送命令/控制设备
    * $deviceId string  设备ID	Required
    * $apiTag	string	传感标识名（可选）
     * data	Object	开关类：开=1，关=0，暂停=2
                    家居类：调光灯亮度=0~254，RGB灯色度=2~239，窗帘、卷闸门、幕布打开百分比=3%~100%，红外指令=1(on)2(off)
                    其它：integer/float/Json/String类型值
    * $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function Cmds($deviceId, $apiTag, $data, $token = NULL)
    {
        if(empty($deviceId)) {
            return false;
        }

        $api = "/Cmds?deviceId=" . $deviceId . "&apiTag=" . $apiTag;

        return $this->_call($api, 'POST', array("data"=>$data), $token);
    }


    public function error()
    {
        return $this->_error;
    }

    public function error_no()
    {
        return $this->_error_no;
    }


    protected function _paddingUrl($url)
    {
        if (empty($url)) {
            return $url;
        }
        
        if ($url[0] != '/') {
            $url = '/' . $url;
        }
        
        return $this->_base_url . $url;
    }

	// 返回直接的ret数据
    protected function _rawcall($url, $method = 'POST', $data = NULL, $token = NULL)
    {
        $url = $this->_paddingUrl($url);

        $this->_error_no = 0;
        $this->_error = NULL;
        if (empty($url)) {
            $this->_http_code = 500;
            return FALSE;
        }
        if (!in_array($method, self::$_ALLOW_METHODS)) {
            $this->_http_code = 500;
            return FALSE;
        }

		$headers = array(
             'Content-Type: application/json ; charset=utf-8'
        );
		if (! empty($token)) {
            $headers[] = 'AccessToken:' .$token;
        }
		elseif(! empty($this -> $token)) {
            $headers[] = 'AccessToken:' . $this -> $token;
        }

        // 如果data不是想要的，直接设置为NULL
        if (is_null($data) || $data === FALSE) {
            $data = NULL;
        } else {
            $data = json_encode($data);
        }
		

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
		if ($method != 'GET') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			$headers['Content-Length'] = strlen($data);
        }
		//var_dump( $headers);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

		//return ;


        $ret = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (!empty($http_code)) {
            $this->_http_code = $http_code;
        }
        curl_close($ch);
        $this->_raw_response = $ret;

        return $ret;
    }
	
	protected function _call($url, $method = 'POST', $data = array(), $token = NULL)
    {
        $ret = $this->_rawcall($url, $method, $data, $token);

        if (empty($ret)) {
            $ret = FALSE;
        } else {
            $ret = json_decode($ret);
            if ($ret->Status == 0) {
                return $ret;
            } else {
                $this->_error_no = $ret->Status;
                if (!empty($ret->Msg)) {
                    $this->_error = $ret->Msg;
                }
                var_dump("错误消息：".$ret->Msg);
                $ret = FALSE;
            }
        }
        return $ret;
    }
}


class Account
{
    public $Account=''; //账号
    public $Password='';//密码
    public $IsRememberMe=false;//记住我
}

class ProjectQuery
{
    public $Keyword = '';//关键字（可选，从id或name字段模糊匹配查询）
    public $ProjectTag = NULL;        //项目标识码（可选，一个32位字符串）
    public $NetWorkKind = NULL;        //联网方案 （可选，1：WIFI 2：以太网 3:蜂窝网络 4:蓝牙）
    public $PageSize = 20;        //指定每页要显示的数据个数，默认20，最多100
    public $StartDate = '';        //起始时间（可选，包括当天，格式YYYY-MM-DD）
    public $EndDate = '';        //结束时间（可选，包括当天，格式YYYY-MM-DD）
    public $PageIndex = 1;    //指定页码
}

class DeviceQuery
{
    public $Keyword = '';    //关键字（可选，从id或name字段左匹配）
    public $DeviceIds = '';    //指定设备ID（可选，如“124,34423,2345”，多个用逗号分隔，最多100个）
    public $Tag = NULL;    //设备标识（可选）
    public $IsOnline = NULL;    //在线状态（可选，true|false）
    public $IsShare = NULL;    //数据保密性（可选，true|false）
    public $ProjectKeyWord = '';    //项目ID或纯32位字符的项目标识码（可选）
    public $PageSize = 20;    //指定每页要显示的数据个数，默认20，最多100
    public $StartDate = '';    //起始时间（可选，包括当天，格式YYYY-MM-DD）
    public $EndDate = '';    //结束时间（可选，包括当天，格式YYYY-MM-DD）
    public $PageIndex = 1;    //指定页码 Required
}

class DeviceAddUpdate
{
    public $DeviceId = NULL; //设备ID
    public $Protocol = ''; // 	通讯协议（1:TCP 2:MQTT 3:HTTP）	RequiredRange: inclusive between 1 and 3
    public $IsTrans =true; //	数据上报状态，true | false（可选，默认为ture）
    public $ProjectIdOrTag = ''; //	项目ID（一个数字）或标识码（一个32位字符串）	String length: inclusive between 1 and 32
    public $Name = ''; //	设备名称（中英文、数字的6到15个字）	RequiredString length: inclusive between 1 and 15
    public $Tag = ''; //	设备标识（英文、数字或其组合6到30个字符）	RequiredMatching regular expression pattern: ^[a-zA-Z0-9_]{6,30}$
    public $Coordinate = ''; //	设备座标（可选，格式为经度值, 纬度值）
    public $DeviceImg = ''; //	设备头像（可选）
    public $IsShare = true; //	数据保密性，true | false（可选，默认为ture）
}

class SensorAddUpdateBase
{
    public $DeviceId = ''; //设备ID
    public $Name = ''; // 传感名称
    public $ApiTag = ''; // 标识名
    public $TransType = NULL; // 传输类型（可选，0：只上报1：上报和下发2：报警3：故障，默认0）
    public $DataType = NULL; // 数据类型（可选，0：整数型1：浮点型2：布尔型3：字符型4：枚举型5：二进制型，默认0）
    public $TypeAttrs = ''; //  传输类型与数据类型的属性（可选，如枚举型值以半角逗号分隔：可爱，有在，装备，蜗牛）
}

class SensorAddUpdate extends SensorAddUpdateBase
{
    public $Unit = ''; //	单位（可选，定义传感器的单位）
    public $Precision = 2; //	精度（可选，默认保留两位小数）
}

class ActorAddUpdate extends SensorAddUpdateBase
{
    public $OperType = NULL; //	操作类型（1：开关型 2：开关停型 3：按钮型 4：刻度型）
    public $OperTypeAttrs = ''; //	操作类型的附加属性（JSON格式，如刻度型时定义：{"MaxRange" : 180 ,"MinRange" : 0, "Step" : 10}）
    public $SerialNumber = 0; //	序列号（可选，同一类型的多个以此区别，默认0）
}

class CameraAddUpdate extends SensorAddUpdateBase
{
    public $HttpIp = ''; //	IP地址
    public $HttpPort = NULL; //	端口
    public $UserName = ''; //	登录用户名
    public $Password = ''; //	登录密码
}

class SensorDataQuery
{
    public $DeviceId = NULL; //设备ID	Required
    public $ApiTags = ''; //传感标识名（可选，多个用逗号分隔，最多50个）
    public $Method = NULL; //查询方式（1：XX分钟内 2：XX小时内 3：XX天内 4：XX周内 5：XX月内 6：按startDate与endDate指定日期查询）
    public $TimeAgo = NULL;  //与Method配对使用表示"多少TimeAgo Method内"的数据，例：(Method=2,TimeAgo=30)表示30小时内的历史数据
    public $StartDate = NULL; //起始时间（可选，格式YYYY-MM-DD HH:mm:ss）
    public $EndDate = NULL;//结束时间（可选，格式YYYY-MM-DD HH:mm:ss）
    public $Sort = 'ASC';//时间排序方式，DESC:倒序，ASC升序
    public $PageSize = 1000; //指定每次要请求的数据条数，默认1000，最多3000
    public $PageIndex = 1; //指定页码	Required
}


class SensorDataAdd{
    public  $ApiTag=''; //传感标识名（设备范围内唯一）
    public $PointDTO=NULL;  //传感数据列表（）
}

class SensorDataAddPoint{
    public $Value=NULL;//传感的最新值（有引号是字符串或枚举，无引号是整数型或浮点型，true|false是布尔值，其它为二进制型）
    public $RecordTime='';  //值最新上传时间（格式：YYYY-MM-DD HH:mm）
}


