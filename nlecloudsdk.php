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


    public function error()
    {
        return $this->_error;
    }

    public function error_no()
    {
        return $this->_error_no;
    }

	/*
    * 用户登录（同时返回AccessToken）
	* $Account	用户名	Required
	* $Password	用户密码	RequiredData type: PasswordString length: inclusive between 0 and 32
    */
    public function user_login($account, $password)
    {
        if (empty($account) || empty($password)) {
            return FALSE;
        }
        $api = "/v2/account/login";
        $data = array();
        $data['Account'] = $account;
        $data['Password'] = $password;
        return $this->_call($api, 'POST', $data);
    }

	/*
    * 获取某个设备的信息
	* $gatewag_tag	string	设备标识	Required
	* $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function get_gateway_info($gatewag_tag, $token = NULL)
    {
        $api = "/v2/Gateway/". $gatewag_tag;

        return $this->_call($api, 'GET', null, $token);
    }

	/*
    * 获取某个设备的传感器列表
	* $gatewag_tag	string	设备标识	Required
	* $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
	public function get_sensor_list($gatewag_tag, $token = NULL)
    {
        $api = "v2/Gateway/".$gatewag_tag."/SensorList";

        return $this->_call($api, 'GET', null, $token);
    }

	/*
    * 获取某个传感器的信息
	* $gatewag_tag	string	设备标识	Required
	* $api_tag		string	传感器API标识	Required
	* $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
	public function get_sensor_info($gatewag_tag, $api_tag, $token = NULL)
    {
        $api = "v2/Gateway/".$gatewag_tag."/Sensor/" . $api_tag;

        return $this->_call($api, 'GET', null, $token);
    }


	/*
    * 获取某个设备的执行器列表
	* $gatewag_tag	string	设备标识	Required
	* $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
	public function get_actuator_list($gatewag_tag, $token = NULL)
    {
        $api = "v2/Gateway/".$gatewag_tag."/ActuatorList";

        return $this->_call($api, 'GET', null, $token);
    }

	/*
    * 获取某个执行器的信息
	* $gatewag_tag	string	设备标识	Required
	* $api_tag		string	执行器API标识	Required
	* $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function get_actuator_Info($gatewag_tag, $api_tag, $token = NULL)
    {
        $api = "v2/Gateway/".$gatewag_tag."/Actuator/" . $api_tag;

        return $this->_call($api, 'GET', null, $token);
    }

    /*
    * 获取某个设备的摄像头列表
	* $gatewag_tag	string	设备标识	Required
	* $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
	public function get_camera_list($gatewag_tag, $token = NULL)
    {
        $api = "v2/Gateway/".$gatewag_tag."/CameraList";

        return $this->_call($api, 'GET', null, $token);
    }

	/*
    * 获取某个摄像头的信息
	* $gatewag_tag	string	设备标识	Required
	* $api_tag		string	执行器API标识	Required
	* $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function get_camera_Info($gatewag_tag, $api_tag, $token = NULL)
    {
        $api = "v2/Gateway/".$gatewag_tag."/Camera/" . $api_tag;

        return $this->_call($api, 'GET', null, $token);
    }


	/*
    * 获取某个设备的当前在/离线状态
	* $gatewag_tag	string	设备标识	Required
	* $api_tag		string	执行器API标识	Required
	* $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
	public function get_gateway_OnOffline($gatewag_tag, $token = NULL)
    {
        $api = "v2/Gateway/".$gatewag_tag."/OnOffline";

        return $this->_call($api, 'GET', null, $token);
    }

	
	/*
    * 获取某个设备的历史分页在/离线状态
	* $gatewag_tag	string	设备标识	Required
	* $start_date	integer	指定每页要显示的数据个数，默认20，最多100	
	* $end_date		string	起始时间，包括当天	
	* $page_index	string	结束时间，包括当天	
	* $page_size	integer	指定页码
	* $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function get_gateway_historypager_onoffline($gatewag_tag, $start_date, $end_date, $page_index, $page_size,  $token = NULL)
    {
        $api = "/v2/Gateway/".gatewag_tag."/HistoryPagerOnOffline";
        $api = $api . "?StartDate=" . $start_date;
		$api = $api . "&EndDate=" . $end_date;
		$api = $api . "&PageIndex=" . $page_index;
		$api = $api . "&PageSize=" . $page_size;

        return $this->_call($api, 'GET', $data, $token);
    }
	
	/*
    * 获取某个设备的当前启/禁状态
	* $gatewag_tag	string	设备标识	Required
	* $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function get_gateway_status($gatewag_tag, $token = NULL)
    {
        $api = "v2/Gateway/".$gatewag_tag."/Status";
        return $this->_call($api, 'GET', null, $token);
    }
	
	/*
    * 获取某个设备的所有传感器、执行器最新值
	* $gatewag_tag	string	设备标识	Required
	* $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function get_gateway_newest_data($gatewag_tag, $token = NULL)
    {
        $api = "v2/Gateway/".$gatewag_tag."/NewestDatas";
        return $this->_call($api, 'GET', null, $token);
    }

	/*
    * 获取某个传感器的最新值
	* $gatewag_tag	string	设备标识	Required
	* $api_tag		string	执行器API标识	Required
	* $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function get_sensor_newest_data($gatewag_tag, $api_tag, $token = NULL)
    {
        $api = "v2/Gateway/".$gatewag_tag."/Sensor/".$api_tag."/NewestData";
        return $this->_call($api, 'GET', null, $token);
    }

	/*
    * 获取某个传感器的历史数据
	* $gatewag_tag	string	设备标识	Required
	* $api_tag		string	执行器API标识	Required
	* $method		integer	查询方式（1：XX分钟内 2：XX小时内 3：XX天内 4：XX周内 5：XX月内 6：按startDate与endDate指定日期查询）	
    * $timeago		integer	与Method配对使用表示"多少TimeAgo Method内"的数据，例：(Method=2,TimeAgo=30)表示30小时内的历史数据
	* $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function get_sensor_history_data($gatewag_tag, $api_tag, $method, $timeago,  $token = NULL)
    {
        $api = "v2/Gateway/".$gatewag_tag."/Sensor/".$api_tag."/HistoryData";
		$api = $api . "?Method=". $method ."&timeago=" . $timeago;
        return $this->_call($api, 'GET', null, $token);
    }

	/*
    * 获取某个传感器的历史分页数据
	* $gatewag_tag	string	设备标识	Required
	* $api_tag		string	执行器API标识	Required
	* $start_date	integer	指定每页要显示的数据个数，默认20，最多100	
	* $end_date		string	起始时间，包括当天	
	* $page_index	string	结束时间，包括当天	
	* $page_size	integer	指定页码
	* $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function get_sensor_history_pagerdata($gatewag_tag, $api_tag, $start_date, $end_date, $page_index, $page_size, $token = NULL)
    {
        $api = "v2/Gateway/".$gatewag_tag."/Sensor/".$api_tag."/HistoryPagerData";
		$api = $api . "?StartDate=" . $start_date;
		$api = $api . "&EndDate=" . $end_date;
		$api = $api . "&PageIndex=" . $page_index;
		$api = $api . "&PageSize=" . $page_size;
        return $this->_call($api, 'GET', null, $token);
    }

	/*
    * 获取某个执行器的最新值
	* $gatewag_tag	string	设备标识	Required
	* $api_tag		string	执行器API标识	Required
	* $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function get_actuator_newest_data($gatewag_tag, $api_tag, $token = NULL)
    {
        $api = "v2/Gateway/".$gatewag_tag."/actuator/".$api_tag."/NewestData";
        return $this->_call($api, 'GET', null, $token);
    }

	/*
    * 获取某个传感器的历史数据
	* $gatewag_tag	string	设备标识	Required
	* $api_tag		string	执行器API标识	Required
	* $method		integer	查询方式（1：XX分钟内 2：XX小时内 3：XX天内 4：XX周内 5：XX月内 6：按startDate与endDate指定日期查询）	
    * $timeago		integer	与Method配对使用表示"多少TimeAgo Method内"的数据，例：(Method=2,TimeAgo=30)表示30小时内的历史数据
	* $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function get_actuator_history_data($gatewag_tag, $api_tag, $method, $timeago,  $token = NULL)
    {
        $api = "v2/Gateway/".$gatewag_tag."/actuator/".$api_tag."/HistoryData";
		$api = $api . "?Method=". $method ."&timeago=" . $timeago;
        return $this->_call($api, 'GET', null, $token);
    }

	/*
    * 获取某个执行器的历史分页数据
	* $gatewag_tag	string	设备标识	Required
	* $api_tag		string	执行器API标识	Required
	* $start_date	integer	指定每页要显示的数据个数，默认20，最多100	
	* $end_date		string	起始时间，包括当天	
	* $page_index	string	结束时间，包括当天	
	* $page_size	integer	指定页码
	* $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function get_actuator_history_pagerdata($gatewag_tag, $api_tag, $start_date, $end_date, $page_index, $page_size, $token = NULL)
    {
        $api = "v2/Gateway/".$gatewag_tag."/actuator/".$api_tag."/HistoryPagerData";
		$api = $api . "?StartDate=" . $start_date;
		$api = $api . "&EndDate=" . $end_date;
		$api = $api . "&PageIndex=" . $page_index;
		$api = $api . "&PageSize=" . $page_size;
        return $this->_call($api, 'GET', null, $token);
    }


	/*
    * 获取某个项目的信息
	* $project_tag	string	以英文组合的项目标识	Required
	* $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function get_project_info($project_tag, $token = NULL)
    {
        $api = "v2/Project/".$project_tag;
        return $this->_call($api, 'GET', null, $token);
    }

	/*
    * 控制某个执行器
	* $gatewag_tag	string	设备标识	Required
	* $api_tag		string	执行器的ApiTag	Required
	* $data			integer	开关类：开=1，关=0，暂停=2 家居类：调光灯亮度=0~254，RGB灯色度=2~239，窗帘、卷闸门、幕布打开百分比=3%~100%，红外指令=1(on)2(off)
	* $token		string	请求API地址时需要发送的HTTP头部token，可以从用户登录接口获得
    */
    public function control_actuator($gatewag_tag, $api_tag, $data, $token = NULL)
    {
        $api = "v2/Gateway/". $gatewag_tag ."/actuator/". $api_tag ."/Control?data=" .$data;
        return $this->_call($api, 'POST', null, $token);
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
    protected function _rawcall($url, $method = 'POST', $data = array(), $token = NULL)
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
        if (is_null($data) || (is_array($data) && count($data) == 0) || $data === FALSE) {
            $data = NULL;
        } else {
            if (is_array($data)) {
                $data = json_encode($data);
            }
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
        $ori_ret = $ret;

        if (empty($ret)) {
            $ret = FALSE;
        } else {
            
			if ($ret['Status'] == 0) {
                return $ret;
            } else {
                
                $this->_error_no = $ret['Status'];
                if (! empty($ret['Msg'])) {
                    $this->_error = $ret['Msg'];
                }
                
                $ret = FALSE;
            }

        }
        return $ret;
    }
}