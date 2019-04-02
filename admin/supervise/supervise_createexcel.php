    <?php
	// 生成EXCEL表格
header('Content-type: text/html;charset=utf-8');
include('../../com/db.php');
session_start();
/**
 * 数据导出
 * @param array $title   标题行名称
 * @param array $data   导出数据
 * @param string $fileName 文件名
 * @param string $savePath 保存路径
 * @param $type   是否下载  false--保存   true--下载
 * @return string   返回文件全路径
 * @throws PHPExcel_Exception
 * @throws PHPExcel_Reader_Exception
 */

$data=$_POST;

$count=count($_POST['tree_name']);

$keys=array_keys($data);

$_SESSION['order_project']=$data;

$project=$_POST['project'];

$field=implode(',',$keys);

$project_time=date("Y-m-d H:i:s",time());

$db= new db();

$sqll="insert into order_project(project_name,project_time) values ('".$project."','".$project_time."')";

$project_id=(int)$db->insert($sqll);

    $status=2;

for ($i=0; $i < $count; $i++)
{
    $sql = 'insert into new_tree_order(project_time,'.$field.') values ';

    unset($data['project']);
	
    for ($i=0; $i < $count; $i++){
		
        $sql .= '(';

        $value = '"'.$project_time.'","'.$project_id.'"'.',';

        foreach ($data as $k => $v)
		
        {
			
            if (isset($data[$k][$i]))
				
            {
				
                $value .= '"'.$v[$i].'"'.',';
				
            }else{
				
                $value .= ",";
				
            }
			
        }
		
        $sql .= trim($value,',').'),';

    }
	
    $sql = trim($sql,',');

    $re=$db->exec($sql);

}

if($re){
	
    header("Location:http://cnzhaoshu.com/admin/confirm_first_supervise.html?project_id=".$project_id);

}else{
	
    $content=[
	
        'status'=>3
		
    ];
	
    echo json_encode($content);
}


?>

