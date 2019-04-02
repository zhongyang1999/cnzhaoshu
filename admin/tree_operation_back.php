<?php
header('Access-Control-Allow-Origin:*');
include "../com/db.php";

$project_id=isset($_POST['project_id'])?$_POST['project_id']:"2";

tree_operation($project_id);

function tree_operation($project_id){

    $db=new db();

    $sql="select count(tree_name) from new_tree_order  where project ='".$project_id."'";
//        var_dump($sql);die;
    $count=$db->query($sql)[0]['count(tree_name)'];
    //当前每种树所占百分比
    $percentage=round(1/$count*100,2);
    //当前项目下所需要多少棵树
    $sqli="select tree_order_id ,tree_num from new_tree_order where project='".$project_id."'";

    $tree_order=$db->query($sqli);
//    var_dump($tree_order);die;
    for($i=0;$i<count($tree_order);$i++){

        $sqll="select sum(tree_num) ,tender_tree_id from tender_order where tender_tree_id = '".$tree_order[$i]['tree_order_id']."'and tender_status= 2";
//        var_dump($sqll);die;
        $data[]=$db->query($sqll)[0];

        if(empty($data[$i]['tender_tree_id'])){

            $data[$i]['sum(tree_num)']=0;

        }
        //投标总数/所需数目总数
        $data[$i]['recommed_num']=round($data[$i]['sum(tree_num)']/$tree_order[$i]['tree_num']*100,2);

        if($data[$i]['recommed_num']>=100){

            $data[$i]['recommed_num']=100;

        }


        //每株树木所占百分比
        $data[$i]['totle_recommed']=$data[$i]['recommed_num']*$percentage/100;

        if($data[$i]['totle_recommed']==$percentage){

            $data[$i]['totle_recommed']=100;

        }

    }

    $sqlis="select * from new_tree_order where project='".$project_id."'";

    $res=$db->query($sqlis);

    for($i=0;$i<count($res);$i++){

        $sql_tree_id="select count(tender_order_id) from tender_order where tender_tree_id='".$res[$i]['tree_order_id']."'";

        $data[$i]['totol_order']=$db->query($sql_tree_id)[0]["count(tender_order_id)"];

        $sql_exam="select count(tender_order_id) from tender_order where tender_tree_id='".$res[$i]['tree_order_id']."' and tender_status=1";

//        var_dump($sql_exam);die;
        $data[$i]['unexam_order']=$db->query($sql_exam)[0]["count(tender_order_id)"];

        $sql_tender_info="select * from tender_order where tender_tree_id='".$res[$i]['tree_order_id']."'";

        $data[$i]['tender_info']=$db->query($sql_tender_info);

        if(!empty($data[$i]['tender_info'])){

            $user_count=count($data[$i]['tender_info']);

            for($e=0;$e<$user_count;$e++){

                $user_sql="select name,phone from user where userid='".$data[$i]['tender_info'][$e]['tender_user_id']."'";

                $data[$i]['tender_info'][$e]['userinfo']=$db->query($user_sql)[0];

            }

        }

        else{

            $data[$i]['tender_info']=null;

        }

    }

    //当前树木所投多少单
    foreach ($data as $key => $value) {
# code...
        $data[$key] = array_merge($value,$res[$key]);
    }

    $content=[

        'status'=>0,

        'data'=>$data

    ];

    echo json_encode($content);

}
?>