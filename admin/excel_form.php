<?php
header("content-type:text/html;charset=utf-8");

require './PHPExcel/Classes/PHPExcel/IOFactory.php';
//引入配置文件
require './define_attr/Define_three_attr.php';
//echo 1;die;
ini_set("display_errors", "On");
session_start();

 $upFile = $_FILES['file'];
//var_dump($upFile);die;
/**
 * 创建文件夹函数,用于创建保存文件的文件夹
 * @param str $dirPath 文件夹名称
 * @return str $dirPath 文件夹名称
 *  表格渲染到EXCEL表格
 */

//var_dump($cc);die;
function creaDir($dirPath=""){
	
    $curPath = dirname(__FILE__);
	
    $path = $curPath.'\\'.$dirPath;
	
	
    if (is_dir($path) || mkdir($path,0777,true)) {
		
        return $dirPath;
		
    }
	
}

//获取配置文件中的树木属性
$tree_attr=new Define_three_attr();

//树木属性
$attr=$tree_attr->three_attr;

//判断文件是否为空或者出错

if ($upFile['error']==0 && !empty($upFile)) {
	
    $dirpath = creaDir('upload/xlsx');
	
    $filename = $_FILES['file']['name'];
	
    $round_time=time();
	
    $queryPath = './'.$dirpath.'/'.$round_time.'.xlsx';
    //move_uploaded_file将浏览器缓存file转移到服务器文件夹
    if(move_uploaded_file($_FILES['file']['tmp_name'],$queryPath)){
		
        date_default_timezone_set('PRC');
        // 读取excel文件
        try {
			
            $_SESSION['query_file_path']=$queryPath;
			
            $inputFileType = PHPExcel_IOFactory::identify($queryPath);
			
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
			
            $objPHPExcel = $objReader->load($queryPath);
			
        } catch(Exception $e) {
			
            die('加载文件发生错误："'.pathinfo($queryPath,PATHINFO_BASENAME).'": '.$e->getMessage());
			
        }

// 确定要读取的sheet，什么是sheet，看excel的右下角，真的不懂去百度吧
        $sheet = $objPHPExcel->getSheet(0);
		
        $highestRow = $sheet->getHighestRow();
		
        $highestColumn = $sheet->getHighestColumn();

// 获取一行的数据
        for ($row = 1; $row <= $highestRow; $row++){

            $rowData[]= $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
//这里得到的rowData都是一行的数据，得到数据后自行处理，我们这里只打出来看看效果

        }

//获取标题
        $rowData['title'] = $rowData[0][0];

        foreach ($rowData['title'] as $k=>$v){

            $title[]=$v;
		
        }
//获取字段名称
        $title=$title[0];

        $rowData['field']=$rowData[1];
		
        foreach ($rowData['field'] as $key=>$val){
			
            $field=$val;
			
        }

        for($i=2;$i<count($rowData)-2;$i++){
			
            foreach ($rowData[$i] as $ke=>$va){
				
                $arr[]=$va;
				
            }
			
        }
	
$fieldd="";

foreach ($field as $key => $value) {

	$fieldd[$value] = isset($attr[$value]) ? $attr[$value] : null;
	
};

        $content=[
		
            'field'=>$fieldd,
			
            'arr'=>$arr,
			
            'title'=>$title,
			
        ];

        echo  json_encode($content);
    }
}

?>