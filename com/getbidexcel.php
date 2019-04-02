<?php
header("Content-Type: text/html; charset=UTF-8");

require '../PHPExcel/PHPExcel.php';
require 'db2.php';


$userid = $_GET['userid'];	
$orderid = $_GET['orderid'];

$db = new db();
$sql = 'select * from tree_order_index where id = ?';
$result = $db->prepare_query($sql , array($orderid))[0];

$ordername = $result['ordername'];

$sql = 'select * from tree_order where orderid = ?';
$datas = $db->prepare_query($sql , array($orderid));

for($i=0; $i < count($datas); $i++){
$data = $datas[$i];
$attribute = '';
if($data['trunk_diameter']){
$trunk_diameter = explode(',', $data['trunk_diameter']);
if(count($trunk_diameter) > 1){
$attribute .= '胸径'.$trunk_diameter[0].'-'.$trunk_diameter[1].'cm，';
}else if($trunk_diameter[0]){
$attribute .= '胸径'.$trunk_diameter[0].'cm，';
}
}
if($data['ground_diameter']){
$ground_diameter = explode(',', $data['ground_diameter']);
if(count($ground_diameter) > 1){
$attribute .= '地径'.$ground_diameter[0].'-'.$ground_diameter[1].'cm，';
}else if($ground_diameter[0]){
$attribute .= '地径'.$ground_diameter[0].'cm，';
}
}
if($data['pot_diameter']){
$pot_diameter = explode(',', $data['pot_diameter']);
if(count($pot_diameter) > 1){
$attribute .= '盆径'.$pot_diameter[0].'-'.$pot_diameter[1].'cm，';
}else if($pot_diameter[0]){
$attribute .= '盆径'.$pot_diameter[0].'cm，';
}
}
if($data['age']){
$attribute .= '苗龄'.$data['age'][0].'年，';
}
if($data['plant_height']){
$plant_height = explode(',', $data['plant_height']);
if(count($plant_height) > 1){
$attribute .= '株高'.((float)$plant_height[0]*100).'-'.((float)$plant_height[1]*100).'cm，';
}else if($plant_height[0]){
$attribute .= '株高'.((float)$plant_height[0]*100).'cm，';
}
}
if($data['plant_height_cm']){
$plant_height_cm = explode(',', $data['plant_height_cm']);
if(count($plant_height_cm) > 1){
$attribute .= '株高'.((float)$plant_height_cm[0]).'-'.((float)$plant_height_cm[1]).'cm，';
}else if($plant_height_cm[0]){
$attribute .= '株高'.((float)$plant_height_cm[0]).'cm，';
}
}
if($data['crown']){
$crown = explode(',', $data['crown']);
if(count($crown) > 1){
$attribute .= '冠幅'.((float)$crown[0]*100).'-'.((float)$crown[1]*100).'cm，';
}else if($crown[0]){
$attribute .= '冠幅'.((float)$crown[0]*100).'cm，';
}
}
if($data['crown_cm']){
$crown_cm = explode(',', $data['crown_cm']);
if(count($crown_cm) > 1){
$attribute .= '冠幅'.((float)$crown_cm[0]).'-'.((float)$crown_cm[1]).'cm，';
}else if($crown_cm[0]){
$attribute .= '冠幅'.((float)$crown_cm[0]).'cm，';
}
}
if($data['branch_length']){
$branch_length = explode(',', $data['branch_length']);
if(count($branch_length) > 1){
$attribute .= '分枝长'.((float)$branch_length[0]*100).'-'.((float)$branch_length[1]*100).'cm，';
}else if($branch_length[0]){
$attribute .= '分枝长'.((float)$branch_length[0]*100).'cm，';
}
}
if($data['bough_length']){
$bough_length = explode(',', $data['bough_length']);
if(count($bough_length) > 1){
$attribute .= '主枝长'.((float)$bough_length[0]*100).'-'.((float)$bough_length[1]*100).'cm，';
}else if($bough_length[0]){
$attribute .= '主枝长'.((float)$bough_length[0]*100).'cm，';
}
}
if($data['branch_point_height']){
$branch_point_height = explode(',', $data['branch_point_height']);
if(count($branch_point_height) > 1){
$attribute .= '分枝点高'.((float)$branch_point_height[0]*100).'-'.((float)$branch_point_height[1]*100).'cm，';
}else if($branch_point_height[0]){
$attribute .= '分枝点高'.((float)$branch_point_height[0]*100).'cm，';
}
}
if($data['branch_number']){
$branch_number = explode(',', $data['branch_number']);
if(count($branch_number) > 1){
$attribute .= '分枝数'.$branch_number[0].'-'.$branch_number[1].'个，';
}else if($branch_number[0]){
$attribute .= '分枝数'.$branch_number[0].'个，';
}
}
if($data['bough_number']){
$bough_number = explode(',', $data['bough_number']);
if(count($bough_number) > 1){
$attribute .= '主枝数'.$bough_number[0].'-'.$bough_number[1].'个，';
}else if($bough_number[0]){
$attribute .= '主枝数'.$bough_number[0].'个，';
}
}
if($data['substrate']){
$attribute .= '基质:'.$data['substrate'].'，';
}
if($data['mark']){
$attribute .= '备注:'.$data['mark'].'，';
}
$attribute = rtrim($attribute, "，");
$datas[$i]['attribute'] = $attribute;
}

$sql = 'select a.id,a.number,a.price,b.name,b.phone,a.mark from (select * from bid_order where orderid = ? and state=1) a left join (select name,phone,userid from user) b on a.userid = b.userid';
$biddata = $db->prepare_query($sql , array($orderid));

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");
$objPHPExcel->setActiveSheetIndex(0);

$activeSheet = $objPHPExcel->getActiveSheet();
$activeSheet->getDefaultStyle()->getFont()->setSize(11);
//设置默认行高
$activeSheet->getDefaultRowDimension()->setRowHeight(20);
$activeSheet->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

//合并单元格
$activeSheet
			->mergeCells('A1:F1')
            ->setCellValue('A1', $ordername)
			->getStyle('A1')->getFont()->setSize(24);

$activeSheet->getStyle('A1')->getAlignment()
			->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$activeSheet->getRowDimension('1')->setRowHeight(50);
$activeSheet->getColumnDimension('A')->setWidth(10);
$activeSheet->getColumnDimension('B')->setWidth(40);
$activeSheet->getColumnDimension('C')->setWidth(20);
$activeSheet->getColumnDimension('D')->setWidth(15);
$activeSheet->getColumnDimension('E')->setWidth(15);
$activeSheet->getColumnDimension('F')->setWidth(100);

$activeSheet->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$activeSheet->getStyle('B')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
$activeSheet->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$activeSheet->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$activeSheet->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$activeSheet->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$activeSheet->setCellValue('A2', '序号')
			->setCellValue('B2', '姓名')
			->setCellValue('C2', '联系方式')
			->setCellValue('D2', '')
			->setCellValue('E2', '上车价格')
			->setCellValue('F2', '备注');

$datacount = count($datas);
$biddatacount = count($biddata);

$row = 2;
for($i=0; $i < $datacount; $i++){
		$row++;
		$data = $datas[$i];
		$activeSheet->setCellValue('A'.$row, $i+1);
		$activeSheet->setCellValue('B'.$row, " ".$data['id']);
		$activeSheet->setCellValue('C'.$row, $data['name']);
		$activeSheet->setCellValue('D'.$row, $data['unit']);
		$activeSheet->setCellValue('E'.$row, $data['count']);
		$activeSheet->setCellValue('F'.$row, $data['attribute']);


		for ($j=0; $j < $biddatacount; $j++) { 
			if($data['id'] == $biddata[$j]['id']){
				$row++;
				$activeSheet->setCellValue('B'.$row, $biddata[$j]['name']);
				$activeSheet->setCellValue('C'.$row, $biddata[$j]['phone']);
				$activeSheet->setCellValue('D'.$row, '');
				$activeSheet->setCellValue('E'.$row, $biddata[$j]['price']/100);
				$activeSheet->setCellValue('F'.$row, $biddata[$j]['mark']);
			}		
		}

}



//设置边框
$activeSheet->getStyle('A2:F'.$row)
        ->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$activeSheet->getStyle('A2:F'.$row)
        ->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$activeSheet->getStyle('A2:F'.$row)
        ->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$activeSheet->getStyle('A2:F'.$row)
        ->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$activeSheet->getStyle('A2:F'.$row)
        ->getBorders()->getVertical()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$activeSheet->getStyle('A2:F'.$row)
        ->getBorders()->getHorizontal()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);



// 设置页面文字的方向和页面大小    锚：bbb  
$activeSheet->getPageSetup()
		// ->setOrientation(PHPExcel_Worksheet_PageSetup:: ORIENTATION_LANDSCAPE)
		->setPaperSize(PHPExcel_Worksheet_PageSetup:: PAPERSIZE_A4);     //A4纸大小  


// Redirect output to a client’s web browser (Excel5)
$filename = $ordername.date('Ymd',time());;
if(strpos($_SERVER["HTTP_USER_AGENT"],"Windows")) $filename = urlencode($filename);
// $filename = iconv("UTF-8","UTF-8",$filename);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;