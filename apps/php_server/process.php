<?php
header("Content-Type:text/html; charset=UTF-8");
require_once "./common/libdb.php";

require_once "./test.php";

@session_start();//加在php代码的最头部,前面不能有html输出 
//界面向SQ传递数据时使用
$data=$GLOBALS['HTTP_RAW_POST_DATA'];//接收POST数据
$data=json_decode($data);//是否需要json_decode需要调试 //设置格式为object格式


//函数对应数组
//Left:JS中通过Ajax传递的要调用的接口函数
//Right：PHP中实际要执行的接口函数
$dataArray = array(
    "test"=> test,
    "insertIntoPerson" =>insertIntoPerson,
    "selectPerson" =>selectPerson,
    "selectPersonById" =>selectPersonById
);

$dataResponse = array(
	"requestTypeError" => 1
);
if(array_key_exists($data->requestType, $dataArray)) 
{
	$dataResponse = call_user_func_array($dataArray[$data->requestType],array($data->para));
}

echo json_encode($dataResponse);


?> 