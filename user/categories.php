<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/android_api_v2.0/include/DBFunctions.php';
	$df=new DBFunctions();
	echo $df->getcats($_POST['type'],$_POST['name']);
	//echo $df->getcats(0,'');
?>