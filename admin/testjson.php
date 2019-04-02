<?php
//2. 在生成的二维码中加上logo(生成图片文件)
//生成招标二维码
//传入项目ID

function scerweima1($project_id = "")
{

    require_once './phpqrcode/phpqrcode.php';
    //需要修改的地方
    $value = "http://www.cnzhaoshu.com/admin/mobile/zhaoshu/index.html#/bid?project_id=" . $project_id;//二维码内容

    $errorCorrectionLevel = 'H';    //容错级别

    $matrixPointSize = 9;            //生成图片大小

    $filename = 'qrcode/' . microtime() . '.png';   //生成二维码图片

    QRcode::png($value, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

    $logo = '../img/qrlogo.png';    //准备好的logo图片

    $QR = $filename;            //已经生成的原始二维码图

    if (file_exists($logo)) {

        $QR = imagecreatefromstring(file_get_contents($QR));        //目标图象连接资源。

        $logo = imagecreatefromstring(file_get_contents($logo));    //源图象连接资源。

        $QR_width = imagesx($QR);            //二维码图片宽度

//        $QR_height = imagesy($QR);			//二维码图片高度
        $logo_width = imagesx($logo);        //logo图片宽度

        $logo_height = imagesy($logo);        //logo图片高度

        $logo_qr_width = $QR_width / 4;    //组合之后logo的宽度(占二维码的1/5)

        $scale = $logo_width / $logo_qr_width;    //logo的宽度缩放比(本身宽度/组合后的宽度)

        $logo_qr_height = $logo_height / $scale;  //组合之后logo的高度

        $from_width = ($QR_width - $logo_qr_width) / 2;   //组合之后logo左上角所在坐标点

        //重新组合图片并调整大小
        /*
         *	imagecopyresampled() 将一幅图像(源图象)中的一块正方形区域拷贝到另一个图像中
         */
        imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
    }

    //输出图片
    $c_file = './upload/'.$project_id . time() . 'qrcode.png';

    imagepng($QR, $c_file);

    imagedestroy($QR);

    imagedestroy($logo);

    //根据照片格式转成资源
    $imc = imagecreatefrompng($c_file);

    return $imc;

}

//调用查看结果

?>