<?php
	define('DB_HOST', 'mysql.hostinger.com.ua');
	define('DB_USER', 'u372374362_user');
	define('DB_PASS', '28890929');
	define('DB_NAME', 'u372374362_db');

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

	if (isset($_GET['type']) {
		$typetitle = $_GET['type'];
	} else {
		die('no type defined...');
	}

	$query = '
		SELECT car_values.charact_value, characteristics.title
		FROM car_values

		JOIN characteristics
		ON characteristics.id_charact = car_values.charact_id

		JOIN avto
		ON avto.avto_id = car_values.avto_id

		WHERE avto.type_id IN (
			SELECT type.id_type
			FROM type
			WHERE type.title = '.$typetitle.' 
		)
	';

	$result = simple_select($query, $connection);
	var_dump($result);
?>
