<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/android_api_v2.0/include/DBFunctions.php';
$df=new DBFunctions();
$df->updcat(
		$_POST ['type'],
		$_POST ['id'],
		$_POST ['name']
	);
//header("location: categorys-list.php?type=$type");
?>