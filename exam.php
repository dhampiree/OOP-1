<?php
	define('DB_HOST', 'mysql.hostinger.com.ua');
	define('DB_USER', 'u661215920_root');
	define('DB_PASS', '1qa2w3');
	define('DB_NAME', 'u661215920_bd');
	
	Header("Content-Type: text/html; charset=utf-8");
	
	class Avto {
		ptivate $connection;
		
		function simple_select($query) {
			$ret_val = array();
			$result = $this->connection->query($query);
			while($row = $result->fetch_assoc())
			array_push($ret_val, $row);
			$result->close();
			return $ret_val;
		}
		
		function __construct() {
			$this->connection  = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			if ($this->connection->connect_error) {
				die('Connect Error ('.$this->connection->connect_errno.') '.$this->connection->connect_error);
			}
		}
		
		function getCarsOfCategory($typetitle) {
			$query = '
				SELECT Avto.model_title, characteristics.titile, car_values.charact_value
				FROM car_values
		
				JOIN characteristics
				ON characteristics.id_charact = car_values.charact_id
		
				JOIN Avto
				ON Avto.avto_id = car_values.avto_id
		
				WHERE Avto.type_id IN (
					SELECT type.id_type
					FROM type
					WHERE type.title = "'.$typetitle.'" 
				)
			';
			return simple_select($query, $connection);
		}
	}
	
	if (isset($_GET['type'])) {
		$tmp = new Avto();
		$tmp->getCarsOfCategory($_GET['type']);
	} else {
		die('no type defined...');
	}
	
?>
