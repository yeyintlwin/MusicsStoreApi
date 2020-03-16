<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/android_api_v2.0/include/DBFunctions.php';
	$fun=new DBFunctions();
	echo $fun->getmusics($_POST['from'],$_POST['limit'],$_POST['title']);
	//echo $fun->getmusics(0, 20, '');
// hello
?>