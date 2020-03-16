<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/android_api_v2.0/include/DBFunctions.php';
$fun=new DBFunctions();
if($_POST['deviceId']){
	echo $fun->ucounter($_POST['deviceId']);
}
?>