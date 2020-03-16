<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/android_api_v2.0/include/DBFunctions.php';
	$df=new DBFunctions();
	echo $df->getsmusics(
		$_POST['from'],
		$_POST['limit'],
		$_POST['title'],
		$_POST['type'],
		$_POST['id']
		);
?>