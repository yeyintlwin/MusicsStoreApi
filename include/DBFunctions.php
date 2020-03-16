<?php
class DBFunctions {
/*
* Ye Yint Lwin - 2018/7/3
* Admin Pannel
*/
	private $db;
	function __construct(){
		require_once 'database/MyDB.php';
		$this->db = new MyDB();
		if(!$this->db) echo $this->db->lastErrorMsg();
	}
	private function runsql($sql){
		return $this->db->query($sql);
	}
	
	private function getrow($sql){
		return $this->runsql($sql)->fetchArray(SQLITE3_ASSOC);
	}
	
	private function getnrows($sql){
		$ret= $this->runsql($sql);
		$n = 0;
		while($row =$ret->fetchArray(SQLITE3_ASSOC)):
			$n++;
		endwhile;
		return $n;
	}
	#@@@@@@@@@@@@@#USER#@@@@@@@@@@@@@#
	public function getcats($tt, $n){
		$t=array("artists","genres", "albums","countries");
		$sql="SELECT * FROM $t[$tt] WHERE name LIKE '%$n%' ORDER BY name";
		$ret = $this->runsql($sql);
		$arr = array();
		while($row = $ret->fetchArray(SQLITE3_ASSOC)):
			array_push($arr,array(
				"id"=>$row['id'],
				"name"=>$row['name'])
				);
		endwhile;
		$out = array("category"=> $arr);
		return json_encode($out, JSON_UNESCAPED_SLASHES);
	}

	private function getcat($tt, $id){
		$t=array("artists","genres", "albums","countries");
		$row =$this->getrow("SELECT * FROM $t[$tt] WHERE id='$id'");
		return $row['name'];
	}
		
	private function fmt($sql){
		$ret=$this->runsql($sql);
		$arr=array();
		while($row=$ret->fetchArray(SQLITE3_ASSOC)):
			array_push($arr, array(
				"id"     =>$row['id'],
				"title"  =>$row['title'],
				"artist" =>$this->getcat(0, $row['artist']),
				"genre"  =>$this->getcat(1, $row['genre']),
				"album"  =>$this->getcat(2, $row['album']),
				"country"=>$this->getcat(3, $row['country']),
				"cover"  =>$row['cover'],
				"link"   =>$row['link'],
				"counter"=>$row['counter']
				));
		endwhile;
		return $arr;
	}
	
	public function tmusics(){
		$sql="SELECT * FROM musics";
		$nrows=$this->getnrows($sql);
		$arr = array("num_rows"=>$nrows,"musics"=>$this->fmt($sql));
		return json_encode($arr, JSON_UNESCAPED_SLASHES);
	}
	
	public function getmusics($f, $l, $t){
		$sql ="SELECT * FROM 'musics' WHERE title LIKE '%$t%' 
		ORDER BY id DESC LIMIT '$f','$l'";
		$nrows=$this->getnrows("SELECT * FROM 'musics' WHERE title LIKE '%$t%'");
		$arr = array("num_rows"=>$nrows,"musics"=>$this->fmt($sql));
		return json_encode($arr, JSON_UNESCAPED_SLASHES);
	}
	
	public function getsmusics($f, $l, $t, $tt, $id){
		$csel = array('artist','genre','album','country');
		$nrows =$this->getnrows(
		"SELECT * FROM musics WHERE title LIKE '%$t%' AND $csel[$tt] LIKE '$id'");
		$sql = 
		"SELECT * FROM musics WHERE title LIKE '%$t%' AND $csel[$tt] LIKE '$id' 
		ORDER BY id DESC LIMIT '$f','$l'";
		$arr = array("num_rows"=>$nrows,"musics"=>$this->fmt($sql));
		return json_encode($arr, JSON_UNESCAPED_SLASHES);
	}
	
	public function getfmusics($l){
		$sql = "SELECT * FROM musics ORDER BY counter DESC LIMIT 0,'$l'";
		$arr = array("musics"=>$this->fmt($sql));
		return json_encode($arr, JSON_UNESCAPED_SLASHES);
	}
	
	public function dcounter($id){
		$row = $this->getrow("SELECT * FROM musics WHERE id = '$id'");
		$add = $row['counter']+1;
		$this->runsql("UPDATE musics SET counter='$add' WHERE id = '$id'");
		return 'ok';
	}
	
	public function ucounter($did){
		$find="SELECT * FROM users WHERE device_id = '$did'";
		if($this->getnrows($find)!=1){
			$insert="INSERT INTO users (device_id) VALUES ('$did')";
			$this->runsql($insert);
		}
		return "{\"regesting\":\"ok\"}";
	}
	#@@@@@@@@@@@@@#ADMIN#@@@@@@@@@@@@@#
	
	private function addcat($tt, $n){
		$t=array("artists","genres", "albums","countries");
		$this->runsql("INSERT INTO $t[$tt] (name) VALUES ('$n')");
	}
	
	public function updcat($tt, $id, $n){
		$t=array("artists","genres", "albums","countries");
		$this->runsql("UPDATE $t[$tt] SET name = '$n' WHERE id = '$id'");
	}
	
	private function delcat($tt, $id){
		$t=array("artists","genres", "albums","countries");
		$this->runsql("DELETE FROM $t[$tt] WHERE id = '$id'");
	}
	
	public function addmusic($ti, $ar, $ge, $al, $cu, $cv, $li){
		$ocats=array();
		$icats=array($ar, $ge, $al, $cu);
		$t=array("artists","genres","albums","countries");
		for($i=0; $i <4;$i++){
			$row = $this->getrow("SELECT * FROM $t[$i] WHERE name = '$icats[$i]'");
			if($row['name']!=$icats[$i]){
				$this->addcat($i, $icats[$i]);
			}
			$row = $this->getrow("SELECT * FROM $t[$i] WHERE name = '$icats[$i]'");
			array_push($ocats,$row['id']);
		}
		$sql="INSERT INTO musics (title, artist, genre, album, country, cover, link, created_date, modified_date) VALUES('$ti', '$ocats[0]', '$ocats[1]', '$ocats[2]', '$ocats[3]', '$cv', '$li', datetime('now', 'localtime'), datetime('now', 'localtime'))";
		$this->runsql($sql);
	}
	
	public function delmusic($id){
		$row = $this->getrow("SELECT * FROM musics WHERE id = $id");
		$ids = array($row['artist'], $row['genre'], $row['album'],$row['country']);
		$csel= array('artist','genre','album','country');
		for($i=0; $i <4;$i++){
			$nrows = $this->getnrows("SELECT * FROM musics WHERE $csel[$i] = '$ids[$i]'");
			if($nrows == 1) $this->delcat($i, $ids[$i]);
		}
		$this->runsql("DELETE FROM musics WHERE id = '$id'");
	}
	
	public function updmusic($id, $ti, $ar, $ge, $al, $cu, $cv, $li){
		$ocats=array();
		$icats=array($ar, $ge, $al, $cu);
		$csel = array('artist','genre','album','country');
		$t=array("artists","genres","albums","countries");
		$orow = $this->getrow("SELECT * FROM musics WHERE id = '$id'");
		$oid=array($orow['artist'],$orow['genre'],$orow['album'],$orow['country']);
		for($i=0; $i <4; $i++){
			$row = $this->getrow("SELECT * FROM $t[$i] WHERE id = '$oid[$i]'");
			$nrows=$this->getnrows("SELECT * FROM musics WHERE $csel[$i] = '$oid[$i]'");
			if($icats[$i]==$row['name']){
				array_push($ocats,$oid[$i]);
			}else{
				if(!($nrows > 1)){//==
					$this->delcat($i,$oid[$i]);
				}
				$chc_row=$this->getrow("SELECT * FROM $t[$i] WHERE name = '$icats[$i]'");
				if($icats[$i]!=$chc_row['name']){
					$this->addcat($i, $icats[$i]);
				}
				$crd_row=$this->getrow("SELECT * FROM $t[$i] WHERE name = '$icats[$i]'");
				array_push($ocats, $crd_row['id']);
			}
		}
		$otcl=array();
		$itcl_data=array($ti, $cv, $li);
		$otcl_data=array($orow['title'],$orow['cover'],$orow['link']);
		for($i=0; $i <3; $i++){
		array_push($otcl,
			$itcl_data[$i]==$otcl_data[$i] ? $otcl_data[$i]:$itcl_data[$i]);
		}
		$this->runsql("UPDATE musics SET title='$otcl[0]', artist='$ocats[0]', genre='$ocats[1]', album='$ocats[2]',country='$ocats[3]', cover='$otcl[1]', link='$otcl[2]', modified_date=datetime('now', 'localtime') WHERE id = '$id'");
	}
	
	public function  getnusers(){
		$sql="SELECT * FROM users";
		return $this->getnrows($sql);
	}
	
}
#@@@@@@@@@@@@@#END#@@@@@@@@@@@@@#
?>