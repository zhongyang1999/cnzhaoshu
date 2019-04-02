<?php
/**
 * Created by PhpStorm.
 * User: Mrwang
 * Date: 2018/10/10
 * Time: 16:31
 */

include "../com/db.php";

$db=new db();

$partya_company_name=isset($_POST['partya_company_name'])?$_POST['partya_company_name']:"";

$project_name=isset($_POST['project_name'])?$_POST['project_name']:"";

$tel=isset($_POST['tel'])?$_POST['tel']:"";

$p=isset($_POST['p'])?$_POST['p']:"1";

$page_num=20;

$start=($p-1)*$page_num;

$where=" order_project.status = 1 ";

$join="";

if(!empty($partya_company_name)){

    $where.= " and partya_company_name like '%".$partya_company_name."%'";

}

if(!empty($project_name)){

    $where.=" and project_name like '%".$project_name."%'";

}

if(!empty($tel)){

    $join= " right join contract_info on order_project.partya_company_name= contract_info.company_name ";

    $where.=" and contract_info.tel='".$tel."'";

}

$count_contract_sql="select count(project_id) as cos_num from order_project $join where $where ";

$count_contract_num=$db->query($count_contract_sql)[0]['cos_num'];

$contract_info_sql="select * from order_project $join where  $where limit $start,$page_num ";

$contract_info=$db->query($contract_info_sql);

$count_contract_info=count($contract_info);

for($i=0;$i<$count_contract_info;$i++){

    $partya_info_sql="select * from contract_info where company_name='".$contract_info[$i]['partya_company_name']."'";

    $contract_info[$i]['partya_info']=$db->query($partya_info_sql)[0];


    $partyb_info_sql="select * from contract_info where company_name='".$contract_info[$i]['partyb_company_name']."'";

    $contract_info[$i]['partyb_info']=$db->query($partyb_info_sql)[0];

}

$content=[

    'status'=>0,

    'data'=>$contract_info,

    'totol_num'=>$count_contract_num

];

echo json_encode($content);