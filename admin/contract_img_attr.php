<?php
/**
 * Created by PhpStorm.
 * User: Mrwang
 * Date: 2018/10/12
 * Time: 10:36
 * 合同图片属性
 */

include "./define_attr/Define_contract_imgs.php";

$define=new Define_contract_imgs();

$data=$define->contract_imgs;

//var_dump($data);

echo json_encode($data);