<?php 
require 'db2.php';
include 'user2.php';
require 'message_attribute.php';
require '../wechat/message.audit1.php';
require '../wechat/wechat.class.php';

$weObj = new Wechat();
$Messageattrbute = new Messageattrbute();

	$id = $_GET['id'];
	$supplierid = $_GET['supplierid'];
	$orderid = $_GET['orderid'];
	$db = new db();

	$sql = 'select * from tree_order where orderid=? and id=?';
	$orderinfo = $db->prepare_query($sql,array($orderid,$id))[0];	

	$date = date('ymdHis',time());;
    $time = explode (" ", microtime () );   
    $time = 1000000 + (int)($time [0] * 1000000);
    $rand = rand(100001,999999);
	$serial_number = $date . $time . $rand;
	$sql = 'insert into orders(serial_number, tree_order_id, tender_userid, bid_userid) values (?,?,?,?)';
	$result = $db->prepare_insert($sql,array($serial_number,$id,$orderinfo['userid'],$supplierid));

	$sql = 'update bid_order set state=2 where id=? and userid=? and orderid=?';
	$result = $db->prepare_exec($sql,array($id,$supplierid,$orderid));

	$title = $Messageattrbute->Ordersattribute($orderinfo);

	$user = user::getUserByUserId($supplierid);

	$remark = '找树网';
	$url = 'yusuanphone.php?bhway=2&bhid='.$serial_number.'#bidhistory';
	$keyword1 = $serial_number;
	$keyword2 = '已中标!';
	sendbidMessage($user['wechatid'], $title, $keyword1, $keyword2, $remark, $url,$weObj);
	
	echo $result;
	unset($db);
