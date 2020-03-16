<?php
   class MyDB extends SQLite3
   {
     function __construct()
     {
      	$this->open($_SERVER['DOCUMENT_ROOT'].'/android_api_v2.0/include/database/musics_store');
     }
   }
?>