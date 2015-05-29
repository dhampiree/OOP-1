<?php
	define('DB_HOST', 'mysql.hostinger.com.ua');
	define('DB_USER', 'u661215920_root');
	define('DB_PASS', '1qa2w3');
	define('DB_NAME', 'u661215920_bd');

	Header("Content-Type: text/html; charset=utf-8");

	function simple_select($query, $connection) {
		$ret_val = array();
		$result = $connection->query($query);
		while($row = $result->fetch_assoc())
		array_push($ret_val, $row);
		$result->close();
		return $ret_val;
	}

	$connection  = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if ($connection->connect_error) {
		die('Connect Error ('.$connection->connect_errno.') '.$connection->connect_error);
	}

	if (isset($_GET['type'])) {
		$typetitle = $_GET['type'];
	} else {
		die('no type defined...');
	}

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
	$result = simple_select($query, $connection);
	var_dump($result);
?>
