<?php
/**
 * Created by PhpStorm.
 * User: Mrwang
 * Date: 2018/9/11
 * Time: 9:58
 */
header('Access-Control-Allow-Origin:*');
//
include "../com/db.php";

$tel=isset($_POST['tel'])?$_POST['tel']:"";

$page=isset($_POST['page'])?$_POST['page']:"1";

$status=isset($_POST['status'])?$_POST['status']:"2";

$project_name=isset($_POST['project_nme'])?$_POST['project_nme']:"";

$partya_company_name=isset($_POST['partya_company_name'])?$_POST['partya_company_name']:"";

$audit_status=isset($_POST['audit_status'])?$_POST['audit_status']:"";

$lei=new CalculationBack();
//$lei->select_all_tree(1);
$info=[]; 

$info[]=$lei->tender_tree($page,$partya_company_name,$tel,$status,$project_name,$audit_status);

$info=json_encode($info);

class CalculationBack

{

    public $db;

    public function __construct(){

       $this->db=new db();

    }
    //当前项目下每株树木所占百分比
    public function select_all_tree($project_id=""){
//        $project_id=5;
//        var_dump($project_id);die;
        $sql="select count(tree_name) from new_tree_order  where project ='".$project_id."'";
//        var_dump($sql);die;
        $count=$this->db->query($sql)[0]['count(tree_name)'];
        //当前每种树所占百分比
        $percentage=round(1/$count*100,2);
//        var_dump($percentage);die;
        //当前项目下所需要多少棵树
        $sqli="select tree_order_id ,tree_num from new_tree_order where project='".$project_id."'";

        $tree_order=$this->db->query($sqli);

        for($i=0;$i<count($tree_order);$i++){

            $sqll="select sum(tree_num) ,tender_tree_id from tender_order where tender_tree_id = '".$tree_order[$i]['tree_order_id']."'and tender_status= 2";
//        var_dump($sqll);die;
            $data[]=$this->db->query($sqll)[0];
            //            var_dump($data);
            if(empty($data[$i]['tender_tree_id'])){

                    $data[$i]['sum(tree_num)']=0;

            }
            //所投标的树木数量
            $data[$i]['recommed_num']=round($data[$i]['sum(tree_num)']/$tree_order[$i]['tree_num']*100,2);

            if($data[$i]['recommed_num']>=100){

                $data[$i]['recommed_num']=100;

            }

            $num= 0;
            //每株树木所占百分比
            $data[$i]['totle_recommed']=$data[$i]['recommed_num']*$percentage/100;

            if($data[$i]['totle_recommed']==$percentage){

                $num++;

            }
//            var_dump($data[$i]['totle_recommed']);die;
        }
        //当前项目下有多少未审核数据
        $sqlis="select count(tender_order_id) from tender_order where project='".$project_id."' and tender_status=1";
//        var_dump($sqlis);die;
        $data['unaudited']=$this->db->query($sqlis)[0]["count(tender_order_id)"];
//        var_dump($data['unaudited']);die;
        //当前项目有多少种
//        $data['audited']=$count-$num;
        //当前项目的投标进度
        if($num==count($tree_order)){

            $data['total_sum']=100;

        }else{

            $data['total_sum']= array_sum(array_map(create_function('$val', 'return $val["totle_recommed"];'), $data));
        }
        //返回招标总进度
//        var_dump(count($data));die;
        $data['project_id']=$project_id;

        $res=[

            'total_sum'=>$data['total_sum'],

            'project_id'=>$data['project_id'],

          'unaudited'=>$data['unaudited']

        ];

        return $res;

    }

//            计算所有项目的百分比

    public function tender_tree($page="",$partya_company_name="",$tel="",$status="",$project_name="",$audit_status=""){

        $page_num=20;

        $start=($page-1)*$page_num;

        $join="";

        $where="status <>1 ";

        if(!empty($status && $status==4)){
            $stop_time=time();
            $where .=" and Up_time < $stop_time";
        }else if(!empty($status)){

            $where.=" and status='".$status."'";

        }else{

            $where.=" and status=2 ";

        }

        if(!empty($partya_company_name) || !empty($tel) || !empty($project_name)){

            $join.=" left join contract_info on contract_info.company_name=order_project.partya_company_name";

        }

        if(!empty($partya_company_name)){

            $where.=" and contract_info.company_name='".$partya_company_name."'";

        }

        if(!empty($tel)){

            $where.=" and contract_info.tel='".$tel."'";

        }

        if(!empty($project_name)){

            $where.=" and order_project.project_name='".$project_name."'";

        }

        if(!empty($audit_status)){

//            $audit_status=2;
            if($audit_status!=1){

                $join.=" left join tender_order on tender_order.project=order_project.project_id";

                    $where.=" and tender_status=1";

            }else{
                $join.=" left join tender_order on tender_order.project=order_project.project_id";
                $where.=" and tender_status <>1";
            }

        }

        $sql="select * from order_project $join where $where group by project_id  order by order_project.Up_time desc limit $start,$page_num  ";
//        var_dump($sql);die;
        $project=$this->db->query($sql);
//        var_dump($project);die;
        $project_count=count($project);

        for($i=0;$i<$project_count;$i++){

            $d = [];

            $d['data_info']=$this->select_all_tree($project[$i]['project_id']);


            $sqli="select * from contract_info where company_name='".$project[$i]['partya_company_name']."' ";

            $d['company_info']=$this->db->query($sqli)[0];

            $sqll="select* from order_project join tender on tender.tender_project_id=order_project.project_id where project_id ='".$d['data_info']['project_id']."'";
//            var_dump($sqll);die;
            $d['project_info']=$this->db->query($sqll)[0];

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
