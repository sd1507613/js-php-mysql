<?php
header("Content-Type:text/html; charset=UTF-8");

class Common_Libdb
{
	var $db = null;//数据库对象
	var $isLog = false;//是否打出log
	
	 //链接数据库
	function connect()
	{
		//设定访问的数据库、登录用户名、密码、主机
		$db_type_name = "mysql"; //数据库类型oracle用odi，对于开发者来说，使用不同的数据库，只要改这个，不用记住那么多的函数了
		$db_host_name = "localhost";//数据库主机名
		$db_username = "root";//数据库连接用户名
		$db_password = "root";//对应的密码
		$db_database_name = "tianshui";//数据库名
		
		$dsn = "$db_type_name:host=$db_host_name;dbname=$db_database_name";
		$this->displayLog(__LINE__.$dsn."</br>");
		
		//面向过程的DB连接
		//$db = new PDO($dsn, $db_username, $db_password);
		
		//面向对象的DB连接
		try {
			$this->db = new PDO($dsn, $db_username, $db_password);
			//设置使用关联索引获取数据集的时候，关联索引是大写还是小写
			$this->db->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL );//列名按照原始的方式
			
			$this->displayLog(__LINE__." "."database open OK.");
		} catch (PDOException $e) {
			$this->db = null;
			//print "Error: " . $e->getMessage() . "<br/>";
			$this->displayLog(__LINE__." "."database open error.");
			die(__LINE__." ".$e->getMessage()."</br>");
		}
	}
	
	//构造函数
	function __construct() 
	{
		$this->displayLog(__LINE__." "."database __construct.");
		$this->connect();
	}
	//析构函数
	function __destruct() 
	{
		$this->displayLog(__LINE__." "."database close.");
		$this->db = null;
		//pdo使用完后，需要手动释放,php资源有点像java的垃圾回收机制
	}
	
	
	//Log输出
	function displayLog($logMsg)
	{
		if($this->isLog)
		{
			echo $logMsg."</br>";
		}
	}
	
	
	//数据库语句执行
	function getQuerryArrayMap($strsql)
	{
		$this->displayLog(__LINE__." "."getQuerryArrayMap() Begin.");
		
		if(!$this->db)
		{
			$this->displayLog(__LINE__." "."$this->db"."</br>");
			return null;
		}

		$resultArray = array();
		$this->displayLog(__LINE__);
		//PDO::query()主要是用于有记录结果返回的操作，特别是SELECT操作
		if(stripos($strsql, "select") === 0)
		{	
			$this->displayLog(__LINE__);
			try{
					$this->db->query('set names utf8;');//解决中文字符乱码的问题
					$result = $this->db->query($strsql);
					
					$this->displayLog(__LINE__);
					
					//设置获取结果集的返回值的类型
					$result->setFetchMode(PDO::FETCH_ASSOC);//关联数组形式
				}
			catch(Exception $e)
				{					
					$this->displayLog(__LINE__." ".$e->getMessage());
					die(__LINE__." "."Failed: ".$e->getMessage());
					
					return null;
				}
				
				//数据库操作信息判断是否异常
				if ($this->db->errorCode() !='00000'){
					//print_r($db->errorInfo());
					$this->displayLog(__LINE__." ".$this->db->errorInfo());
					
					die (__LINE__." "."Failed: ".$result->getMessage());
					//exit;
					return null;
				}
				
				//获取所有记录集到一个数组中
				$resultArray = $result->fetchAll();
		}
		//PDO::exec()主要是针对没有结果集合返回的操作，比如INSERT、UPDATE、DELETE等操作，它返回的结果是当前操作影响的列数。
		else if((stripos($strsql, "insert") === 0) || 
			(stripos($strsql, "update") === 0) ||
			(stripos($strsql, "delete") === 0))
		{
			//事务的 4 个特征：原子性（Atomicity）、一致性（Consistency）、独立性（Isolation）和持久性（Durability），即 ACID
			//通过将这两个更新包括在beginTransaction() 和 commit() 调用中，就可以保证在更改完成之前，其他人无法看到更改。
			//如果发生了错误，catch 块可以回滚事务开始以来发生的所有更改，并打印出一条错误消息。
			try{
					$this->db->beginTransaction();
					
					$this->db->exec('set names utf8;');//解决中文字符乱码的问题
					$db_result = $this->db->exec($strsql);

					$this->db->commit();
				}
			catch(Exception $e)
				{
					$this->db->rollBack();//回滚事务开始以来发生的所有更改
					
					$this->displayLog(__LINE__." ".$e->getMessage());
					die(__LINE__." "."Failed: ".$e->getMessage());
					return null;
				}

			//数据库操作信息判断是否异常
			if ($this->db->errorCode() !='00000'){
				//print_r($db->errorInfo());
				$this->displayLog(__LINE__." ".$this->db->errorInfo());
				return null;
			}

			return $db_result;
		}
		
		$this->displayLog(__LINE__." "."getQuerryArrayMap() End."."</br>");
		//print_r(__LINE__.$resultArray[0]."</br>");
		return $resultArray;
	}	 
}

?>