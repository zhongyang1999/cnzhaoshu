<?php 
require 'checkhost.php';
require('db2.php');

	
	$userid = $_GET['userid'];
	$supplierid = $_GET['supplierid'];
	$state = $_GET['state'];
	$db = new db();
	$sql = 'update supplier set stateb=? where userid=? and supplier_id=?';
	$result = $db->prepare_exec($sql,array($state,$userid,$supplierid));
	echo $result;
	unset($db);
