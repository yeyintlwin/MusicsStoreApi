<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/android_api_v2.0/include/DBFunctions.php';
$df=new DBFunctions();
$df->addmusic(
		$_POST['title'],
		$_POST['artist'],
		$_POST['genre'],
		$_POST['album'],
		$_POST['country'],
		$_POST['cover'],
		$_POST['link']
		);
?>
