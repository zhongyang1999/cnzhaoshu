<?php
	require 'checkhost.php';
	require 'db2.php';

	$db = new db();

	$id = $_GET['id'];
	$name = $_GET['name'];
	$attribute = $_GET['attribute'];

	$sql = 'update map_supervision set tree_name=?,tree_attribute=? where id=?';

	$result = $db->prepare_exec($sql,array($name,$attribute,$id));

	unset($db);
	
	echo $result;
