<?php
/**
 * Created by PhpStorm.
 * User: Mrwang
 * Date: 2018/8/28
 * Time: 11:22
 */
//$user=json_decode($_COOKIE['user2'],true);
$user=isset($user['userid'])?$user['userid']:"124610";
//前台请求的页码
//var_dump(1);
$page=isset($_POST['p'])?$_POST['p']:1;
//树木id 返回一条数据
$tree_order_id=isset($_POST['tree_order_id'])?$_POST['tree_order_id']:"";
//传过来的项目id 返回多条
$project=isset($_POST['project'])?$_POST['project']:"";
//搜索条件返回 多条   条件
$address=isset($_POST['address'])?$_POST['address']:"";
//根据名称 返回多条   条件  条件可以拼写  原生分页
$tree_name=isset($_POST['name'])?$_POST['name']:"";

bidding_search($page,$tree_order_id,$project,$address,$tree_name,$user);

function bidding_search($page="",$tree_order_id="",$project="",$address="",$tree_name="",$user=""){

    include "../com/db.php";

    $db=new db();
    //每页显示条数
    $page_num=7;
    //计算当前总条数
        $where='';

        if(!empty($tree_name)){

            if(is_array($tree_name)){

                    for($i=0;$i<count($tree_name);$i++){

                        $where .="or tree_name='".$tree_name[$i]."'";

                    }

            }else{

                $where.= " and tree_name='".$tree_name."'";

            }

        }

        if(!empty($tree_order_id)){

            $where.= " and tree_order_id='".$tree_order_id."'";

        }

        if(!empty($project)){

            $where.= " and project='".$project."'";

        }

        if(!empty($address[0])){
//            echo  1;die;
            if(is_array($address)){

                for($i=0;$i<count($address);$i++){

                    $address[$i]=$address[$i].'%';

                    if(count($address)>1){
//                        echo 1; die;
                        $where .=" or hcity like'".$address[$i]."'";

                    }else{
//                        echo 2;die;
                        $where .=" and hcity like'".$address[$i]."'";

                    }

                }

            }else{

                $address=$address.'%';

                $where.= " and hcity like'".$address."'";

            }

        }
        //截掉第一个

//
        if(strpos($where,"and")){

            $where=substr($where,4);

        }else{

            $where=substr($where,3);

        }
        if($where!=""){

            $where= "where contract_status = 0".$where;
//            $where="where"."  ".$where;

        }else{
            $where= "where contract_status = 0";
        }

        $sql="select count(*) as num  from new_tree_order  $where";
//        var_dump($sql);die;
        $total=$db->prepare_query($sql,array($tree_name));

        $total_page=ceil($total['num']/$page_num);

        $start=($page-1)*$page_num;

        $sqli="select * from new_tree_order $where order by Up_time desc  limit $start,$page_num  ";
//        var_dump($sqli);
        $result=$db->query($sqli);
//        var_dump($result);die;
        $project_id=$result['0']['project'];

        $sqll="select * from order_project where project_id='".$project_id."'";

        $sqls="select partya_company_name from order_project where project_id='". $project_id."' ";

        for($i=0;$i<count($result);$i++){

            $result[$i]['contract']=$db->query($sqls)[0]['partya_company_name'];
            $sqle="select  tel,contacts from contract_info where company_name='".$result[$i]['contract']."'";
            $sqle_data=$db->query($sqle)[0];
            $result[$i]['tel']=$sqle_data['tel'];
            $result[$i]['contacts']=$sqle_data['contacts'];
            $result[$i]['project_info']=$db->query($sqll)[0];

        }
//        echo json_encode($result);die;
        $subscribe_sql="select get_msg from user where userid='".$user."'";
//        var_dump($subscribe_sql);die;
        $subscribe=$db->query($subscribe_sql)[0]['get_msg'];
        if(empty($result)){
            $result=[];
        }
        $content=[

            'status'=>0,

            'data'=>$result,

            'subscribe'=>$subscribe

        ];

        echo  json_encode($content);

}


