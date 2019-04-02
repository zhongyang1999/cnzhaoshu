<?php 

	require 'checkhost.php';
	require 'db2.php';

	$base64_image_content = $_POST['image'];
	$id = $_POST['id'];
	$userid = $_POST['userid'];
	$orderid = $_POST['orderid'];
	
	//匹配出图片的格式
	if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
		$type = $result[2];
		$new_file = "../bidimage/";

		$name = date("Ymdhis",time());

		$new_file = $new_file.$name.".jpg";

		$db = new db();
		$sql = 'select * from bid_order where id=? and userid=? and orderid=?';
		$result1 = $db->prepare_query($sql,array($id,$userid,$orderid));

		if($result1){
			if($result1[0]['image']){
				$images = $result1[0]['image'].','.$name;
			}else{
				$images = $name;
			}
			if(($result1[0]['state'] == 0) || ($result1[0]['state'] == 1)){
				if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
					$sql = 'update bid_order set image=? where id=? and userid=? and orderid=?';
					$result3 = $db->prepare_exec($sql,array($images,$id,$userid,$orderid));
					if($result3){
						echo $name;
					}
				}
			}
		}else{
			if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
				$sql = 'insert into bid_order(userid,orderid,id,image) values(?,?,?,?)';
				$result2 = $db->prepare_exec($sql,array($userid,$orderid,$id,$name));
				if($result2){
					echo $name;
				}
			}
		}
		unset($db);
	}


 ?>