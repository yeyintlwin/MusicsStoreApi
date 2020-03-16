
<?php
$versioncode=$_POST ['versionCode'];
$lastversioncode='1';
$lastversion="1.0 ";
$url="http://localhost:8080/update/MyanmarMusics_1.0(mtda).apk";
$filesize="2.65 MB";
$description=
"- At 12/May/2019 this current app will not work because the server ip will change to private. please update to lasted version.
- Drawer Bug: Fix
- Design: change
- Add Ta Yar Section";

if($versioncode!=$lastversioncode){
echo "{\"avalable\":true,\"version\":\"$lastversion\",\"filesize\":\"$filesize\",\"url\":\"$url\",\"description\":\"$description\"}";
}else {
echo "{\"avalable\":false}";
}
?>