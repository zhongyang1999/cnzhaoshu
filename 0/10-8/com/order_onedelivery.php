<?php 

require 'db2.php';
include 'user2.php';
require 'message_attribute.php';
require '../wechat/message.audit1.php';
require '../wechat/wechat.class.php';

$id = $_GET['id'];
$db = new db();

$time = date('Y-m-d H:i:s',time());
$sql = 'update order_one set delivery_time = ?,state=8 where id=?';
$result1 = $db->prepare_exec($sql , array($time,$id));

$sql = 'select a.treeuserid,a.userid,a.serial_number,b.* from (select * from order_one where id=?) a left join (select * from tree) b on a.treeuserid=b.userid and a.treeid=b.treeid';
$result = $db->prepare_query($sql,array($id))[0];


if($result1 && $result){
	$weObj = new Wechat();
	$Messageattrbute = new Messageattrbute();
	$user = user::getUserByUserId($result['userid']);

	$treeuser = user::getUserByUserId($result['treeuserid']);

	$title = $Messageattrbute->Order_oneattribute($result);
	$title = $title.' '.$treeuser['name'].$treeuser['phone'];
	$remark = '找树网';
	$url = './yusuanphone.php?mbway=2&mbid='.$result['serial_number'].'#managerbuy';
	$keyword1 = $result['serial_number'];
	$keyword2 = '您的订单已经在路上了';
	sendbidMessage($user['wechatid'], $title, $keyword1, $keyword2, $remark, $url,$weObj);
	echo $result1;
}

unset($db);