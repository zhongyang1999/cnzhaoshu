<?php 


require 'db2.php';

$db = new db();

$limit = $_GET['limit'];
$limit = explode(',', $limit);

$sql = 'select c.*,d.name tender_name,d.phone tender_phone from (select a.*,b.name bid_name,b.phone bid_phone from (select * from order_one) a left join (select userid,name,phone from user) b on a.treeuserid=b.userid) c left join (select userid,name,phone from user) d on c.userid=d.userid order by c.state desc limit ?,?';

$result = $db->prepare_query($sql,array($limit[0],$limit[1]));

echo json_encode($result);

unset($db);
