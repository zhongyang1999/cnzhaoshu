<?php
/**
 * Created by PhpStorm.
 * User: Mrwang
 * Date: 2018/10/15
 * Time: 10:57
 * 发送图片连接
 */

include "../com/db.php";

require '../wechat/message.audit1.php';

require '../wechat/wechat.class.php';
//echo 1;die;
$project_id=isset($_POST['project_id'])?$_POST['project_id']:"8";

$contract_imgs_id=isset($_POST['contract_imgs_id'])?$_POST['contract_imgs_id']:"";

$weObj=new Wechat();

$db= new db();
//sendimgUrl("oM-qJjgKTkq2SYpRL7ct_Ics3YoU","大大大大","笑笑笑","啦啦啦",$weObj);die;
$img_info_sql="select * from contract_imgs where contract_imgs_id='".$contract_imgs_id."'";

$img_info=$db->query($img_info_sql)[0];

$project_user_id="select partya_company_name ,partyb_company_name ,project_name from order_project where project_id='".$project_id."'";

$project_users=$db->query($project_user_id)[0];

$user_id_sql="select userid from contract_info where company_name='".$project_users['partya_company_name']."'";

$user_ids[]=$db->query($user_id_sql)[0]['userid'];

$user_id_sql2="select userid from contract_info where company_name='".$project_users['partyb_company_name']."'";

$user_ids[]=$db->query($user_id_sql2)[0]['userid'];

$count_user_ids=count($user_ids);

for($i=0;$i<$count_user_ids;$i++){
	
    $openids_sql="select wechatid from user where userid='".$user_ids[$i]."'";
	
    $openids[]=$db->query($openids_sql)[0];
	
    sendimgUrl($openids[$i]['wechatid'],$img_info['img_kind'],$project_users['project_name'],$img_info['imgs'],$weObj);
	
}