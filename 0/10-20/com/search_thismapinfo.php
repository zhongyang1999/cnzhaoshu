<?php 
require 'checkhost.php';
require 'db2.php';

$mapid = $_GET['mapid'];

$db = new db();

$sql = 'select d.*,e.active,e.photo,e.time,e.x,e.y,e.id,e.userid from (select a.maporderid,a.mapid,a.tree_id,a.state,b.name tree_name from (select id maporderid,mapid,tree_id,state from map_order where mapid=?) a left join (select name,id from map_tree) b on a.tree_id = b.id) d left join (select id,userid,maporderid,active,photo,time,x,y from map_record order by time desc) e on d.maporderid = e.maporderid group by maporderid';
$data1 = $db->prepare_query($sql,array($mapid));


$sql = 'select e.*,f.name tree_name,f.attribute,f.unit from (select c.*,d.name username,d.phone userphone from (select b.id,b.treeuserid,b.tree_id,b.qxqrcode,b.number,b.active,b.state,b.time,a.userid,a.id mapid,a.qrcode,a.x,a.y,a.address,a.name projectname,a.switch,a.create_time from (select * from map where id=?) a left join (select q.*,w.time,w.active from (select * from map_order) q left join (select time,maporderid,active from map_record order by time desc) w on q.id=w.maporderid group by q.id) b on a.id=b.mapid) c left join (select name,phone,userid from user) d on c.treeuserid = d.userid) e left join (select * from map_tree) f on e.tree_id = f.id order by e.create_time desc';
$data2 = $db->prepare_query($sql,array($mapid));


$data = array();
$data['photo'] = $data1;
$data['project'] = $data2;

echo json_encode($data);
unset($db);

