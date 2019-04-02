<?php 
require 'checkhost.php';
require 'db2.php';

$id = $_POST['id'];
$userid = $_POST['userid'];
$orderid = $_POST['orderid'];
$imageid = $_POST['imageid'];

$db = new db();

$sql = 'select image from bid_order where userid=? and orderid=? and id=?';
$result = $db->prepare_query($sql,array($userid,$orderid,$id))[0]['image'];

$image = explode(',', $result);

$str = '';
for ($i=0; $i < count($image); $i++) { 
	if($image[$i] != $imageid){
		$str .= $image[$i].',';
	}
}
$str = rtrim($str, ',');

$sql = 'update bid_order set image=? where userid=? and orderid=? and id=?';
$result = $db->prepare_exec($sql,array($str,$userid,$orderid,$id));

echo $result;

unset($db);
