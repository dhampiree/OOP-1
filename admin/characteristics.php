<?php
	
	include('../autoloader.php');
	include('../core/config.php');

	function newChar($char_name) {

		$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		// стандартна перевірка підключення до бази даних
		if ($connection->connect_error) {
		    die('Connect Error ('.$connection->connect_errno.') '.$connection->connect_error);
		}

		$query = '
		INSERT INTO Characteristics (title)
		VALUES ("'.$char_name.'")
		';

		$connection->query($query);
	}

	function removeChar($char_id) {

		$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		// стандартна перевірка підключення до бази даних
		if ($connection->connect_error) {
		    die('Connect Error ('.$connection->connect_errno.') '.$connection->connect_error);
		}

		$query = '
		DELETE FROM Characteristics
		WHERE id = '.intval($char_id);

		$connection->query($query);
	}

	function updateChar($id, $name) {

		$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		// стандартна перевірка підключення до бази даних
		if ($connection->connect_error) {
		    die('Connect Error ('.$connection->connect_errno.') '.$connection->connect_error);
		}

		$namestring = '';

		$query = '
			UPDATE Characteristics
			SET title = "'.$name.'"
			WHERE id = '.$id.'
		';
		$connection->query($query);
	}

	function echoCharacteristics() {

		$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		// стандартна перевірка підключення до бази даних
		if ($connection->connect_error) {
		    die('Connect Error ('.$connection->connect_errno.') '.$connection->connect_error);
		}

		$query = 'SELECT * FROM Characteristics';
		$characteristicList = array();
		$result = $connection->query($query);
		if ($result !== false) {
			while ($row = $result->fetch_assoc())
				echo '<div class="node">#'.$row['id'].' '.$row['title'].'</div>';
		}
	}

	function echoCharacteristicsOptions() {
		$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		// стандартна перевірка підключення до бази даних
		if ($connection->connect_error) {
		    die('Connect Error ('.$connection->connect_errno.') '.$connection->connect_error);
		}
		
		$result = $connection->query('SELECT * FROM Characteristics');
		if ($result !== false) {
			while ($row = $result->fetch_assoc()) {
				echo '<option value="'.$row['id'].'">('.$row['id'].') '.$row['title'].'</option>';
			}
		}
	}

	if (isset($_POST['delete'])) {
		if (isset($_POST['id'])) {
			$id = intval($_POST['id']);
			removeChar($id);
		}
	} else if (isset($_POST['add'])) {
		if (isset($_POST['name'])) {
			$name = $_POST['name'];
			newChar($name);
		}
	} else if (isset($_POST['edit'])) {
		if (isset($_POST['id'])) {
			$id = intval($_POST['id']);
			$name = (isset($_POST['name']) && $_POST['name']!='') ? $_POST['name'] : false;
			updateChar($id, $name);
		}
	}

?>

<html>
<head>
	<meta charset="utf8">
	<title>Редагування cписку характеристик</title>
	<style>
		.node {
			margin-left: 3em;
			margin-top: 1em;
			padding: 0.25em 1em;
			border: 1px solid black;
		}
		.half {
			float: 		left; 
			padding: 	2%; 
			width: 		46%;
		}
	</style>
</head>
<body>
	<div class="half">
	<?
		echoCharacteristics();
	?>
	</div>
	<div class="half" style="position: fixed; right:0">
		<hr>
		<h1>Додавання нової характеристики</h1>
		<form method="POST">
			<p>Назва: <input name="name" type="text"></p>
			<input name="add" type="submit" value="Додати">
		</form>
		<hr>
		<h1>Видалення характеристики</h1>
		<form method="POST">
			<p>Категорія для видалення: <select name="id">
				<?php echoCharacteristicsOptions(); ?>
			</select></p>
			<input name="delete" type="submit" value="Видалити">
		</form>
		<hr>
		<h1>Редагування характеристик</h1>
		<form method="POST">
			<p>Характеристика для редагування: <select name="id">
				<?php echoCharacteristicsOptions(); ?>
			</select></p>
			<p>Нова назва: <input name="name" type="text"></p>
			<input name="edit" type="submit" value="Редагувати">
		</form>
		<hr>
	</div>
</body>
</html>