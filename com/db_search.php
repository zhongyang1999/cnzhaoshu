<?php
/**
* 数据库搜索
**/

class db 
{
	private static $dsn = 'mysql:host=localhost;dbname=zhaoshu';
	private static $username = 'zhaoshu_select';
	private static $password = 'p7o6yh8a9';
	private $pdo = null; 
	/**
	* 构造函数
	*/
	public function __construct($type='utf8mb4') 
	{
		$this->pdo = new PDO(self::$dsn, self::$username, self::$password);
		$this->pdo->query("SET NAMES '$type'");  // 设置数据库编码 utf8mb4 utf8 存微信名称特殊字符
		$this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	}

	/**
	* 析构函数
	*/
	public function __destruct() 
	{
		$this->pdo = null;
	}

	/**
	* 查找条件汇总
	*/
	private static function getWhere($where, $isFuzzy)
	{
		$fields = array();
		$values = array();

		$statistics = array();	// 统计

		if ($where['key']) {
			$where['key'] = str_replace('蜡','腊',$where['key']);

			if (is_null($isFuzzy)) {
				if (empty($where['isfuzzy']) || (!empty($where['isfuzzy']) && $where['isfuzzy']=="true")) {
					$isFuzzy = true;
				}
			}

			if ($isFuzzy) {
				array_push($fields,'name like ?');
				array_push($values,'%'.$where['key'].'%');
			} else {//if ($isFuzzy || empty($where[isfuzzy]) || (!empty($where[isfuzzy]) && $where[isfuzzy]=="true")) {
				array_push($fields,"(name like ? or name like '".$where['key']."_')");
				array_push($values,$where['key']);
			}
			array_push($statistics,"word='".$where['key']."'");
			array_push($statistics,"ip='".$_SERVER["REMOTE_ADDR"]."'");
		}
		if ($where['dbh']) {
			array_push($fields,"dbh=?");
			array_push($values,$where['dbh']);
			array_push($statistics,"dbh=".$where['dbh']);
		}else if ($where['dbh1'] && $where['dbh2']) {
			array_push($fields,"dbh>=? and dbh<=?");
			array_push($values,$where['dbh1'],$where['dbh2']);
		}
		if ($where['crownwidth']) {
			array_push($fields,"crownwidth=?");
			array_push($values,$where['crownwidth']);
			array_push($statistics,"crownwidth=".$where['crownwidth']);
		}else if ($where['crownwidth1'] && $where['crownwidth2']) {
			array_push($fields,"crownwidth>=? and crownwidth<=?");
			array_push($values,$where['crownwidth1'],$where['crownwidth2']);
		}
		if ($where['height']) {
			array_push($fields,"height=?");
			array_push($values,$where['height']);
			array_push($statistics,"height=".$where['height']);
		}else if ($where['height1'] && $where['height2']) {
			array_push($fields,"height>=? and height<=?");
			array_push($values,$where['height1'],$where['height2']);
		}
		if ($where['province']) {
			array_push($fields,"province=?");
			array_push($values,$where['province']);
			array_push($statistics,"province='".$where['province']."'");
		}
		if ($where['city']) {
			array_push($fields,"city=?");
			array_push($values,$where['city']);
			array_push($statistics,"city='".$where['city']."'");
		}
		if ($where['district']) {
			array_push($fields,"district=?");
			array_push($values,$where['district']);
			array_push($statistics,"district='".$where['district']."'");
		}
		if ($where['phone']) {
			include_once("basic.php");
			$where['phone'] = basic::encode($where['phone']);

			// array_push($fields,"userid in (select userid from user where phone=?)");
			array_push($fields,"userphone=?");
			array_push($values,$where['phone']);
		}
		if ($where['name']) {
			// array_push($fields,"userid in (select userid from user where name like ?)");
			array_push($fields,"username like ?");
			array_push($values,'%'.$where['name'].'%');
		}
		
		if ($where['price1']) {
			array_push($fields,"price>=?");
			array_push($values,$where['price1']);
			array_push($statistics,"price>=".$where['price1']);
		}else if ($where['price']) {
			array_push($fields,"price>0");
		}
		if ($where['price2']) {
			array_push($fields,"price<=?");
			array_push($values,$where['price2']);
			array_push($statistics,"price<=".$where['price2']);
		}			
		if ($where['renzheng']) {
			array_push($fields,"userisrenzheng=1");
		}
		if ($where['yuantu']) {
			array_push($fields,"phototime is not null");
		}
		if ($where['shipin']) {
			array_push($fields,"video is not null");
		}
		if ($where['platform']) {
			// array_push($fields,"userstate=".$where[platform]);
			array_push($fields,"userstate=?");
			array_push($values,$where['platform']);
		}
		if ($where['shopid']) {
			array_push($fields,"userid=?");
			array_push($values,$where['shopid']);
		}

		return array('fields' => $fields, 'values' => $values, 'statistics' => $statistics);
	}	

	/**
	* 符合条件的省份
	*/
	public function countProvince($where, $isFuzzy, $fromWechat)
	{
		if (empty($where)){
			$sql = "select province, count(*) as items, sum(count) as count,imgpath from v_tree where state>0 group by province order by count desc";			
			$result = $this->pdo->query($sql);
		} else {
			$where = json_decode($where,true);

			if ((count($where)==1 && $where['group']) || (count($where)==2 && $where['group'] && $where['price'])) {
				$sql = "select province, count(*) as items, sum(count) as count,imgpath from v_tree where state>0 "
				      ." group by province order by count desc";			
				$result = $this->pdo->query($sql);				
			}else{
				if ($where['city']) $area = 'district';
				else if ($where['province']) $area = 'city';
					 else $area = 'province';

				$where = self::getWhere($where, $isFuzzy);

				// 写入统计
				if ($fromWechat) {
					$sql = "insert into keyword2 set type=2,".join(",",$where['statistics']);
					$this->pdo->exec($sql);
				}


				$sql = "select province,city,district, count(*) as items, sum(count) as count,imgpath from v_tree where state>0 and ".join(" and ",$where['fields'])." group by $area order by count desc";	

				$result = $this->pdo->prepare($sql);
				$result->execute($where['values']);
			}
		}

		$result->setFetchMode(PDO::FETCH_ASSOC);

		if ($result->rowCount()==0) {
			return null;
		}else {
			return $result->fetchAll();
		}
	}

	/**
	* 搜索
	*/
	public function search($where, $limit, $fromWhere)
	{
   		!empty($limit) && $limit = "limit ".$limit;
		
		if (empty($where)){
			$sql = 'select * from v_tree where state>0 order by time desc '.$limit;	
			$result = $this->pdo->query($sql);
		} else {
			$where = json_decode($where,true);

			$priceorder = $where['price'] ? 
							($where['price']=='up' ? 'price,' : 'price desc,') : '';
			$grouporder = $where['platform'] && $where['platform']==1001 ? ' grouporder,' : '';
			$shoporder = $where['shopid'] ? 
							($where['price'] ? 
								' top desc,CONVERT(name USING gbk),' :
								' top desc,CONVERT(name USING gbk),price desc,')
							: '';
			

			// 模糊查找
			$where = self::getWhere($where);

			// 写入统计
			$sql = 'insert into keyword2 set type='.$fromWhere.','.join(',',$where['statistics']);
			$this->pdo->exec($sql);

			// 排序：认证用户state、二维码100、原图99、视频98、点米、时间
			$sql = 'select * from v_tree where state>0 and '
				.join(' and ',$where['fields']).' order by '.$priceorder.$grouporder.$shoporder.' state desc,isqrcode desc,showorder desc,dianmi desc,time desc '.$limit;

			$result = $this->pdo->prepare($sql);
			$result->execute($where['values']);
		}

		$result->setFetchMode(PDO::FETCH_ASSOC);

		if ($result->rowCount()==0) {
			return null;
		}else {
			return $result->fetchAll();
		}
	}


	/**
	* 求评价价格
	*/
	public function averageprice($where)
	{
		if (empty($where)){
			$sql = "select avg(price) as avg,max(price) as max,min(price) as min from tree where state>0";	
			$result = $this->pdo->query($sql);
		}else{
			$where = json_decode($where,true);
			$where = self::getWhere($where);

			$sql = "select avg(price) as avg,max(price) as max,min(price) as min from v_tree where state>0 and ".join(" and ",$where['fields']);

			$result = $this->pdo->prepare($sql);
			$result->execute($where['values']);
		}

		$result->setFetchMode(PDO::FETCH_ASSOC);
		return $result->fetch();
	}

	/**
	* 按价格分段40查询数量
	*/
	public function histogram($where,$min,$max)
	{

		$p = ($max-$min)/40;

		$sum = '';
		for ($i=0; $i < 39; $i++) { 
			$begin = $min + $i*$p;
			$end = $begin + $p;
			$sum .= "count(case when price>=$begin and price<$end then '$i' end) as '$i',";
		}
		$begin = $max - $p;
		$sum .= "count(case when price>=$begin and price<=$max then '39' end) as '39'";

		if (empty($where)){
			$sql = "select $sum from tree where state>0";	
			$result = $this->pdo->query($sql);
		}else{
			$where = json_decode($where,true);
			$where = self::getWhere($where);
			$sql = "select $sum from v_tree where state>0 and ".join(" and ",$where['fields']);

			$result = $this->pdo->prepare($sql);
			$result->execute($where['values']);
		}

		$result->setFetchMode(PDO::FETCH_ASSOC);
		return $result->fetch();
	}

	/**
	* 按周查询 最低价 最高价 平均价 数量select DATE_FORMAT(create_time,'%Y%m') months,count(caseid) count from tc_case group by months; 
	*/
	public function count_weeks($where)
	{		
		if (empty($where)){
			// $sql = 'select DATE_FORMAT(time,\'%Y%u\') week,DATE_FORMAT(time,\'%Y.%m.%d\') day,sum(count) total,min(price) min,max(price) max,avg(price) avg from tree where state>0 and price>0 group by week';	
			$sql = 'select DATE_FORMAT(time,\'%Y%m\') month,DATE_FORMAT(time,\'%Y.%m.%d\') day,sum(count) total,min(price) min,max(price) max,avg(price) avg from tree where state>0 and price>0 group by month';	
			$result = $this->pdo->query($sql);
		} else {
			$where = json_decode($where,true);			

			// 模糊查找
			$where = self::getWhere($where);


			// 排序：认证用户state、二维码100、原图99、视频98、点米、时间
			// $sql = 'select DATE_FORMAT(time,\'%Y%u\') week,DATE_FORMAT(time,\'%Y.%m.%d\') day,sum(count) total,min(price) min,max(price) max,avg(price) avg from v_tree where state>0 and price>0 and '
			$sql = 'select DATE_FORMAT(time,\'%Y%m\') month,DATE_FORMAT(time,\'%Y.%m.%d\') day,sum(count) total,min(price) min,max(price) max,avg(price) avg from v_tree where state>0 and price>0 and '
				.join(' and ',$where['fields']).' group by month';

			$result = $this->pdo->prepare($sql);
			$result->execute($where['values']);
		}

		$result->setFetchMode(PDO::FETCH_ASSOC);

		if ($result->rowCount()==0) {
			return null;
		}else {
			return $result->fetchAll();
		}
	}
}


?>