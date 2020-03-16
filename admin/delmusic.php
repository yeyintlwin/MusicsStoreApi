<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/android_api_v2.0/include/DBFunctions.php';
$df=new DBFunctions();
$df->delmusic($_GET['id']);
//header("location: musicslist.php");
?>