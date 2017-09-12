<?php
header("Content-Type:text/html; charset=UTF-8");

//MYSQL加密码
define("AES_KEY_MYSQL", "1qaz!QAZ2wsx@WSX");

//加密
function ENCRYPT($input)
{
	return "AES_ENCRYPT('$input', '".AES_KEY_MYSQL."')";
}

//解密
function DECRYPT($input)
{
	return "AES_DECRYPT(".$input.", '".AES_KEY_MYSQL."') as ".$input." ";
}


//where语句解密
function DECRYPT_WHERE($input)
{
	return "AES_DECRYPT(".$input.", '".AES_KEY_MYSQL."')";
}

?>
