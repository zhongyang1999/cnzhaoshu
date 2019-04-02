<?php
/**
 * Created by PhpStorm.
 * User: Mrwang
 * Date: 2018/9/11
 * Time: 9:58
 * 合同列表接口
 */
//
include "../com/db.php";

$tel=isset($_POST['tel'])?$_POST['tel']:"";

$page=isset($_POST['page'])?$_POST['page']:"1";

$status=isset($_POST['status'])?$_POST['status']:"2";

$project_name=isset($_POST['project_name'])?$_POST['project_name']:"";

$partya_company_name=isset($_POST['partya_company_name'])?$_POST['partya_company_name']:"";

$audit_status=isset($_POST['audit_status'])?$_POST['audit_status']:"";

$lei=new Calculation();
//$lei->select_all_tree(1);
$info=[]; 

$info[]=$lei->tender_tree($page,$partya_company_name,$tel,$status,$project_name,$audit_status);

$info=json_encode($info);

class Calculation

{

    public $db;

    public function __construct(){

       $this->db=new db();

    }
    //当前项目下每株树木所占百分比
    public function select_all_tree($project_id=""){

        $project_id_sql="select * from order_project where project_id='".$project_id."'";
		
        $project_data=$this->db->query($project_id_sql)[0];

        return $project_data;
    }

//            计算所有项目的百分比
    public function tender_tree($page="",$partya_company_name="",$tel="",$status="",$project_name="",$audit_status=""){

        $page_num=20;

        $start=($page-1)*$page_num;

        $join="";

        $where="status <>1 ";

        if(!empty($status) && $status==4){
            $stop_time=time();
            $where .=" and Up_time < '".$stop_time."'";
        }else if(!empty($status)){

            $where.=" and status='".$status."'";

        }else{

            $where.=" and status=2 ";

        }

        if(!empty($partya_company_name) || !empty($tel) || !empty($project_name)){

            $join.=" left join contract_info on contract_info.company_name=order_project.partya_company_name";

        }

        if(!empty($partya_company_name)){

            $where.=" and contract_info.company_name like '%".$partya_company_name."%'";

        }

        if(!empty($tel)){

            $where.=" and contract_info.tel='".$tel."'";

        }

        if(!empty($project_name)){

            $where.=" and order_project.project_name like '%".$project_name."%'";

        }

        if(!empty($audit_status)){

            if($audit_status!=1){

                $join.=" left join tender_order on tender_order.project=order_project.project_id";
				
                    $where.=" and tender_status=1 and  order_project.`audits_num` <> 0 ";

            }else{
				
                $join.=" left join tender_order on tender_order.project=order_project.project_id";
				
                $where.=" and  order_project.`audits_num` = 0 ";
            }

        }

        $sql="select * from order_project $join where $where group by project_id  order by order_project.Up_time desc limit $start,$page_num  ";
//        var_dump($sql);die;
        $project=$this->db->query($sql);

        $project_count=count($project);

        for($i=0;$i<$project_count;$i++){

            $d = [];

            $d['project_info']=$this->select_all_tree($project[$i]['project_id']);


            $sqli="select * from contract_info where company_name='".$project[$i]['partya_company_name']."' ";

            $d['company_info']=$this->db->query($sqli)[0];

            $dat[] = $d;

        }

        if(!empty($dat)){

            $content=[

                'status'=>'0',

                'data'=>$dat,

                'totol_num'=>$project_count

            ];

        }else{

            $content=[

                'status'=>1,

                'data'=>""

            ];

        }

        echo json_encode($content);

    }

}
