<?php 
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);
include '../../com/db2.php';
include '../../com/create_trading.php';
require_once "../lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
require_once 'log.php';

if(!$_COOKIE['user2']) exit;
$user2 = $_COOKIE['user2'];

$user2 = json_decode($user2,true);
$openId = $user2['wechatid'];
$tree_order_id = $_GET['tree_order_id'];
$money = $_GET['money']*100;
$tender_userid = $_GET['tender_userid'];
$bid_userid = $_GET['bid_userid'];



$serial_number = create_trading($tree_order_id,$tender_userid,$bid_userid);

setcookie("serial_number",$serial_number, time()+3600); 

//初始化日志
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

//打印输出数组信息
function printf_info($data)
{
    foreach($data as $key=>$value){
        echo "<font color='#ddd;'>$key</font> : $value <br/>";
    }
}

//①、获取用户openid
$tools = new JsApiPay();

//②、统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody("test");
$input->SetAttach("test");
$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
$input->SetTotal_fee($money);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("test");
$input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);

$jsApiParameters = $tools->GetJsApiParameters($order);

//获取共享收货地址js函数参数
$editAddress = $tools->GetEditAddressParameters();

//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
/**
 * 注意：
 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
 */
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/> 
    <title>微信支付-找树网</title>
    
	<script type="text/javascript">

		window.onload = function(){
			if (typeof WeixinJSBridge == "undefined"){
			    if( document.addEventListener ){
			        document.addEventListener('WeixinJSBridgeReady', editAddress, false);
			    }else if (document.attachEvent){
			        document.attachEvent('WeixinJSBridgeReady', editAddress); 
			        document.attachEvent('onWeixinJSBridgeReady', editAddress);
			    }
			}else{
				editAddress();
			}
		};

		//获取共享地址
		function editAddress(){
			WeixinJSBridge.invoke(
				'editAddress',
				<?php echo $editAddress; ?>,
				function(res){
					var value1 = res.proviceFirstStageName;
					var value2 = res.addressCitySecondStageName;
					var value3 = res.addressCountiesThirdStageName;
					var value4 = res.addressDetailInfo;
					var tel = res.telNumber;
				}
			);
		}
	
	</script>
</head>
<body>
    
    <font color="#9ACD32" style="padding: 30px 20px"><b>该笔订单支付金额为<span style="color:#f00;font-size:25px" id="money"></span>元</b></font>
	<div align="center">
		<button style="width:210px; height:40px; border-radius: 6px;background-color:#1AAD19; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:17px;" type="button" onclick="callpay()" >立即支付</button>
	</div>
    <script type="text/javascript">
    	var user = getcookie('user2');
    	user = JSON.parse(user);
    	function getcookie(name){//获取指定名称的cookie的值
    	    var arrStr = document.cookie.split("; ");
    	    for(var i = 0;i < arrStr.length;i ++){
    	        var temp = arrStr[i].split("=");
    	        if(temp[0] == name) return unescape(temp[1]);
    	    } 
    	}

    	var serial_number = getcookie('serial_number');
		function urlRequest(name) {
	        var match = RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);  
	        return match && decodeURIComponent(match[1].replace(/\+/g, ' ')); 
	    }	    
	    var money = urlRequest("money");
	    document.getElementById("money").innerHTML=money;

		//调用微信JS api 支付
		function jsApiCall(){
			WeixinJSBridge.invoke(
				'getBrandWCPayRequest',
				<?php echo $jsApiParameters; ?>,
				function(res){
					WeixinJSBridge.log(res.err_msg);
					//网页内支付接口err_msg返回结果值说明(ok,cancel,fail)
					if(res.err_msg == 'get_brand_wcpay_request:ok'){				
						window.location='http://cnzhaoshu.com/com/update_orderstate.php?serial_number='+serial_number+'&money='+money*100;	
					}else if(res.err_msg == 'get_brand_wcpay_request:cancel'){
						history.go(-1);
					}else{
						alert('支付失败');
						history.go(-1);
						 
					}

				}
			);
		}

		function callpay(){	
			if (typeof WeixinJSBridge == "undefined"){
			    if( document.addEventListener ){
			        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
			    }else if (document.attachEvent){
			        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
			        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
			    }
			}else{
			    jsApiCall();
			}
		}
	</script>
</body>
</html>