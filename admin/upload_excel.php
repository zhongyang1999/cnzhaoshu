<?php
require_once("./PHPExcel/Classes/PHPExcel.php");
//上传excel
header("Content-type:text/html;charset=utf-8");

session_start();

$_SESSION['info']=$_POST;

$name=$_FILES['userfile']['name'];//名字

$suffixName=explode('.', $name)[1];//后缀名

$filePath='upload/'.$_FILES["userfile"]["name"];//文件存储路径

upload_excel($suffixName,$filePath,$info);

function upload_excel($suffixName = '', $filePath = '')
{
    if($suffixName=="xls" || $suffixName=="xlsx")
    {
        if (is_uploaded_file($_FILES["userfile"]["tmp_name"]))
        {
            //        echo "<script> alert('sucess');location.href='http://localhost/admin/handle.php?filepath='</script>";
            header('http://localhost/admin/handle.php?filepath='.$filePath);
            //        echo "已经上传到临时文件夹"."<hr>";
            if (!move_uploaded_file(iconv("UTF-8", "gbk",$_FILES["userfile"]["tmp_name"]),$filePath))
            {
                echo "移动失败";
            }
            else
            {
                //            echo "上传到".$filePath."成功";echo "<hr>";
                //调用解析方法 返回数组
                header('Location:handle.php?filepath='.$filePath);
            }
        }
        else
        {
            echo "上传临时文件失败";
        }
    }
    else
    {
        echo "<a href='http://localhost/admin/upload_excel.html'>文件上传格式不正确，请重新上传</a>";
    }
}


function analysisExcel($filePath){
	
    $file_type=explode('.',$filePath)[1];
	
    $objReader='';
    //根据上传类型做不同处理
    if ($file_type == 'xls') {
		
        $objReader = \PHPExcel_IOFactory::createReader('Excel5');//创建读取实例
		
    }
	
    if ($file_type == 'xlsx') {
		
        $objReader = new \PHPExcel_Reader_Excel2007();
		
    }
	
    $objPHPExcel = $objReader->load($filePath,$encode='utf-8');//加载文件
	
    $sheet = $objPHPExcel->getSheet(0);//取得sheet(0)表
	
    $highestRow = $sheet->getHighestRow(); // 取得总行数
	
    $highestColumn = $sheet->getHighestColumn(); // 取得总列数
	
    $data=[];
	
    for($i=1;$i<=$highestRow;$i++){
		
        array_push($data, $objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue());
		
    }
	
    return $data;
	
}
?>