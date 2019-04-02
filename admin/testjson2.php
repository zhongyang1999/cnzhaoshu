<?php

include "testjson.php";
include "../com/db.php";
include "testjson3.php";
$project_id=$_GET['project_id'];
//var_dump($project_id);die;
Createimage::create($project_id);

class Createimage
{

    public static function create($project_id = "")
    {
//        var_dump(1);

        $db = new db();

        $sql="select * from new_tree_order where project='".$project_id."'";
//        var_dump($sql);die;

        $datas = $db->query($sql);
//        var_dump($datas);die;
        $showdata = array();
//        var_dump(count($datas));die;
        for ($i = 0; $i < count($datas); $i++) {

            $data = $datas[$i];

            $attribute = '';

            $k = count($showdata);

            $showdata[$k] = array();

            $showdata[$k]['tree_name'] = $data['tree_name'];

            $showdata[$k]['tree_num'] = $data['tree_num'];

            $showdata[$k]['company'] = $data['company'];

            if ($data['dbh']) {

                $trunk_diameter = explode(',', $data['dbh']);

                if (count($trunk_diameter) > 1) {

                    $attribute .= '胸径' . $trunk_diameter[0] . '-' . $trunk_diameter[1] . 'cm，';

                } else if ($trunk_diameter[0]) {

                    $attribute .= '胸径' . $trunk_diameter[0] . 'cm，';

                }

            }

            if ($data['ground_diameter']) {

                $ground_diameter = explode(',', $data['ground_diameter']);

                if (count($ground_diameter) > 1) {

                    $attribute .= '地径' . $ground_diameter[0] . '-' . $ground_diameter[1] . 'cm，';

                } else if ($ground_diameter[0]) {

                    $attribute .= '地径' . $ground_diameter[0] . 'cm，';

                }

            }

            if ($data['basin_diameter']) {

                $pot_diameter = explode(',', $data['basin_diameter']);

                if (count($pot_diameter) > 1) {

                    $attribute .= '盆径' . $pot_diameter[0] . '-' . $pot_diameter[1] . 'cm，';

                } else if ($pot_diameter[0]) {

                    $attribute .= '盆径' . $pot_diameter[0] . 'cm，';

                }

            }

            if ($data['tree_age']) {

                $attribute .= '苗龄' . $data['tree_age'][0] . '年，';

            }

            if ($data['plant_height']) {

                $plant_height = explode(',', $data['plant_height']);

                if (count($plant_height) > 1) {

                    $attribute .= '株高' . ((float)$plant_height[0] * 100) . '-' . ((float)$plant_height[1] * 100) . 'cm，';

                } else if ($plant_height[0]) {

                    $attribute .= '株高' . ((float)$plant_height[0] * 100) . 'cm，';

                }

            }

            if ($data['crown']) {

                $crown = explode(',', $data['crown']);

                if (count($crown) > 1) {

                    $attribute .= '冠幅' . ((float)$crown[0] * 100) . '-' . ((float)$crown[1] * 100) . 'cm，';

                } else if ($crown[0]) {

                    $attribute .= '冠幅' . ((float)$crown[0] * 100) . 'cm，';

                }

            }

            if ($data['long_branch_point']) {

                $branch_length = explode(',', $data['long_branch_point']);

                if (count($branch_length) > 1) {

                    $attribute .= '分枝长' . ((float)$branch_length[0] * 100) . '-' . ((float)$branch_length[1] * 100) . 'cm，';

                } else if ($branch_length[0]) {

                    $attribute .= '分枝长' . ((float)$branch_length[0] * 100) . 'cm，';

                }

            }

            if ($data['main_tendril_length']) {

                $bough_length = explode(',', $data['main_tendril_length']);

                if (count($bough_length) > 1) {

                    $attribute .= '主枝长' . ((float)$bough_length[0] * 100) . '-' . ((float)$bough_length[1] * 100) . 'cm，';

                } else if ($bough_length[0]) {

                    $attribute .= '主枝长' . ((float)$bough_length[0] * 100) . 'cm，';

                }

            }

            if ($data['high_branch_point']) {

                $branch_point_height = explode(',', $data['high_branch_point']);

                if (count($branch_point_height) > 1) {

                    $attribute .= '分枝点高' . ((float)$branch_point_height[0] * 100) . '-' . ((float)$branch_point_height[1] * 100) . 'cm，';

                } else if ($branch_point_height[0]) {

                    $attribute .= '分枝点高' . ((float)$branch_point_height[0] * 100) . 'cm，';

                }

            }

            if ($data['branch_number']) {

                $branch_number = explode(',', $data['branch_number']);

                if (count($branch_number) > 1) {

                    $attribute .= '分枝数' . $branch_number[0] . '-' . $branch_number[1] . '个，';

                } else if ($branch_number[0]) {

                    $attribute .= '分枝数' . $branch_number[0] . '个，';

                }

            }

            if ($data['main_branch_number']) {

                $bough_number = explode(',', $data['main_branch_number']);

                if (count($bough_number) > 1) {

                    $attribute .= '主枝数' . $bough_number[0] . '-' . $bough_number[1] . '个，';

                } else if ($bough_number[0]) {

                    $attribute .= '主枝数' . $bough_number[0] . '个，';

                }

            }

            if ($data['stroma']) {

                $attribute .= '基质:' . $data['stroma'] . '，';

            }

            if ($data['remarks']) {

                $attribute .= '备注' .':'. $data['remarks'];

            }

            $attribute = rtrim($attribute, "，");

            $showdata[$k]['attribute'] = $attribute;

        }

        $qrcode = scerweima1($project_id);
        $sqli = "select * from order_project where project_id=" . $project_id . "";

        $expiration_date = $db->query($sqli)[0]['Up_time'];
        $data=$db->query($sqli)[0];
        $userid = $data['partya_company_name'];
        $city=$data['hcity'].','.$data['hproper'].','.$data['harea'];

        $useraddress = trim($city,',');

        //1.招标截至日期  2, 用户名称 3 . 用苗地 4.采购信息
        $image = Createtenderimage::create($expiration_date, $userid, $useraddress, $showdata, $qrcode);

        unset($db);

        $c_filename = "../tenderimage/'.$project_id.time().'.jpg";

        imagejpeg($image, $c_filename);

        imagedestroy($image);

        echo "<img src=" . $c_filename . ">";
//        return $c_filename;

    }
}




