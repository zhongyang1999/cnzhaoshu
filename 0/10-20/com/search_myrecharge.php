<?php 
require 'checkhost.php';
require 'db2.php';
	
	$userid = $_GET['userid'];
	$db = new db();
	$sql = 'select virtual_money,real_money from user where userid=?';
	$result = $db->prepare_query($sql,array($userid))[0];

	$money = $result['virtual_money'] + $result['real_money'];

	echo $money;
	unset($db);