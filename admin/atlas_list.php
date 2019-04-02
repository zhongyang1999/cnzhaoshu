<?php
/**
 * Created by PhpStorm.
 * User: Mrwang
 * Date: 2018/10/12
 * Time: 16:59
 *  图集接口
 */

include "../com/db.php";

$project_id=isset($_POST['project_id'])?$_POST['project_id']:"";

$db= new db();

$contract_imgs_sql="select * from contract_imgs where project_id='".$project_id."' order by contract_imgs_id desc ";

$contract_imgs=$db->query($contract_imgs_sql);

$count_contract_imgs=count($contract_imgs);

for($i=0;$i<$count_contract_imgs;$i++){
	
    if(strpos($contract_imgs[$i]['imgs'],',') !== false){
		
            $contract_imgs[$i]['imgs']=implode(",",$contract_imgs[$i]['imgs']);
			
    }else{
		
        $contract_imgs[$i]['imgs']=$contract_imgs[$i]['imgs'];
		
    }
	
}

$content=[

    "status"=>0,
	
    "data"=>$contract_imgs
	
];

echo json_encode($content);

?>