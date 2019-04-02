<?php 

	require 'db2.php';

	$orderid = $_GET['orderid'];
	$id = $_GET['id'];
	
	$db = new db();
	$sql = 'delete from tree_order where orderid=? and id=?';
	$result = $db->prepare_exec($sql,array($orderid,$id));
	
	$sql = 'delete from bid_order where orderid=? and id=?';
	$result = $db->prepare_exec($sql,array($orderid,$id));

	$sql = 'select count(id) from tree_order where orderid=?';
	$count = $db->prepare_query($sql,array($orderid))[0]['count(id)'];

	if(!$count){
		$sql = 'delete from tree_order_index where id=?';
		$result = $db->prepare_exec($sql,array($orderid));
	}

	unset($db);
	echo $result;


 ?>