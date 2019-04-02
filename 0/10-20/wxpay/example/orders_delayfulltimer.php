<?php  

ini_set('date.timezone','Asia/Shanghai');
require_once "../lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
require '../../com/checkhost.php';
require '../../com/db2.php';
include '../../com/user2.php';
require '../../wechat/message.audit1.php';
require '../../wechat/wechat.class.php';


ignore_user_abort();
$oldtime=60;
$url="http://cnzhaoshu.com/wxpay/example/orders_delayfulltimer.php";

$run = include '../../com/ordersfull_delaytimer.php';

if(!$run) die;

  	$db = new db();
	$time = date('Y-m-d H:i:s',strtotime('-15 day'));

	$sql = 'select serial_number,tree_order_id,bid_userid,tender_userid,fullamount from orders where state=2 and order_switch=1 and fullamount_time < \''.$time.'\'';
	$unfinshed = $db->query($sql);

	if(count($unfinshed)){
	  	for ($i=0; $i < count($unfinshed); $i++) { 	  	
			$tender_userid = $unfinshed[$i]['tender_userid'];
			$bid_userid = $unfinshed[$i]['bid_userid'];
			$tree_order_id = $unfinshed[$i]['tree_order_id'];
			$serial_number = $unfinshed[$i]['serial_number'];
			$fullamount = $unfinshed[$i]['fullamount'];
			$money = (int)$fullamount;
			$user = user::getUserByUserId($bid_userid);

			$desc = '苗木款(编号：'.$serial_number.')';
			$input = new WxPayCompanypay();
			$input->Setpartner_trade_no($data['serial_number']);
			$input->Setopenid($user['wechatid']);
			$input->Setcheck_name('NO_CHECK');
			$input->Setamount($money);
			$input->Setdesc($desc);
			$order = WxPayApi::companyPay($input);

			if($order['return_code'] == "SUCCESS" && $order['result_code'] == "SUCCESS"){
				$payment_no = $order['payment_no'];
				$payment_time = $order['payment_time'];
				$serial_number = $order['partner_trade_no'];
				$sql = 'update orders set payment_no=? , payment_time=? , payment_mount=? , state=4 where serial_number=?';
				$result = $db->prepare_exec( $sql, array($payment_no,$payment_time,$money,$serial_number) );
				$sql = 'update bid_order set state=8 where id=? and userid=?';
				$result = $db->prepare_exec( $sql, array($tree_order_id,$bid_userid) );

				$weObj = new Wechat();
				$title = '苗木款(编号：'.$serial_number.')';
				$name = $user['name'];
				$money = $money/100;
				$money = $money.'元';
				$way = '微信';
				$desc = '感谢您使用找树网';
				$url = './yusuanphone.php#bidhistory';
				takemoney($user['wechatid'], $title, $name, $money, $way, $desc, $url, $weObj);
			}else{
				$time = date('Y-m-d H:i:s',time());
				$myfile = fopen("./log.log", "a+");
				fwrite($myfile, '招标全款 时间：'.$time.',交易号'.$serial_number.',交易金额'.$money.',状态：失败'."\r\n");
				fclose($myfile);	
			}
	  	}		
	}

sleep($oldtime);
file_get_contents($url);




