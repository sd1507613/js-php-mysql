<?php
header("Content-Type: text/html;charset=utf-8"); 

//test
function test($para)
{
	$str1 = $para->str1;
	$str2 = $para->str2;
	error_log($str1."  |   ".$str2);
	$sqlstr = "select name from test;";
	
	$mysqldb = new Common_Libdb();
	$result = $mysqldb->getQuerryArrayMap($sqlstr);
	error_log($sqlstr);
	return $result;
}

function insertIntoMysql($para)
{
	$sqlstr = "insert INTO test (name) VALUES ('$para'); ";
	$mysqldb = new Common_Libdb();
	$result = $mysqldb->getQuerryArrayMap($sqlstr);
	error_log($sqlstr);
}

function selectPerson($para)
{
	$sqlstr = "select * from person where type = $para->type; ";
	$mysqldb = new Common_Libdb();
	$result = $mysqldb->getQuerryArrayMap($sqlstr);
	error_log($sqlstr);
	return $result;
}
function selectPersonById($para)
{
	$sqlstr = "select * from person where id = $para->id; ";
	$mysqldb = new Common_Libdb();
	$result = $mysqldb->getQuerryArrayMap($sqlstr);
	error_log($sqlstr);
	return $result;
}
function insertIntoPerson($para)
{
	$name = $para->name;
	$sex = $para->sex;
	$card_num = $para->card_num;
	$home = $para->home;
	$inhabit = $para->inhabit;
	$valueful = $para->valueful;
	$police_local = $para->police_local;
	$tel = $para->tel;
	$police_person = $para->police_person;
	$url = $para->url;
	$type = $para->type;

	$sqlstr = "insert INTO person (name,sex,card_num,home,inhabit,valueful,police_local,tel,police_person,url,type) VALUES ('$name','$sex','$card_num','$home','$inhabit','$valueful','$police_local','$tel','$police_person','$url','$type'); ";
	$mysqldb = new Common_Libdb();
	$result = $mysqldb->getQuerryArrayMap($sqlstr);
	error_log($sqlstr);
	return $result;
}
?>