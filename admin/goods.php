<?php
	
	include('../autoloader.php');
	include('../core/config.php');

	use \core\view\ViewGateway;

	function newTovar($goods_name) {

		$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		// стандартна перевірка підключення до бази даних
		if ($connection->connect_error) {
		    die('Connect Error ('.$connection->connect_errno.') '.$connection->connect_error);
		}

		$query = '
		INSERT INTO Goods (title)
		VALUES ("'.$goods_name.'")
		';

		$connection->query($query);
	}

	function removeTovar($goods_id) {

		$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		// стандартна перевірка підключення до бази даних
		if ($connection->connect_error) {
		    die('Connect Error ('.$connection->connect_errno.') '.$connection->connect_error);
		}

		$query = '
		DELETE FROM Goods
		WHERE id = '.intval($goods_id);

		$connection->query($query);
	}

	function updateTovar($id, $name=name) {

		$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		// стандартна перевірка підключення до бази даних
		if ($connection->connect_error) {
		    die('Connect Error ('.$connection->connect_errno.') '.$connection->connect_error);
		}

		$namestring = ($name === false) ? '' : 'title = "'.$name.'"';

		$query = '
			UPDATE Attribute
			SET '.$namestring.'
			WHERE id = '.$id.'
		';
		$connection->query($query);
	}

	function G_Cat_connect($g_id, $cat_id) {
		$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		// стандартна перевірка підключення до бази даних
		if ($connection->connect_error) {
		    die('Connect Error ('.$connection->connect_errno.') '.$connection->connect_error);
		}

		$query = '
			SELECT id
			FROM G_CAT_connection
			WHERE
				categ_id = "'.intval($cat_id).'" AND
				goods_id = "'.intval($g_id).'"
		';

		$result = $connection->query($query);
		if ($result !== false) {
			if ($result->num_rows == 0) {
				$query = '
					INSERT INTO G_CAT_connection (categ_id, goods_id)
					VALUES ("'.intval($cat_id).'", "'.intval($g_id).'")';
				$connection->query($query);
			}
		}
	}

	function G_Char_connect($g_id, $char_id, $value) {
		$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		// стандартна перевірка підключення до бази даних
		if ($connection->connect_error) {
		    die('Connect Error ('.$connection->connect_errno.') '.$connection->connect_error);
		}

		$query = '
			SELECT id
			FROM GC_connection
			WHERE
				char_id = "'.intval($char_id).'" AND
				goods_id = "'.intval($g_id).'"
		';

		$result = $connection->query($query);
		if ($result !== false) {
			if ($result->num_rows == 0) {
				$query = '
					INSERT INTO GC_connection (char_id, goods_id, value)
					VALUES ("'.intval($cat_id).'", "'.intval($g_id).'", "'.$value.'")';
				$connection->query($query);
			}
		}	
	}

	function echoGoodsList() {
		$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		// стандартна перевірка підключення до бази даних
		if ($connection->connect_error) {
		    die('Connect Error ('.$connection->connect_errno.') '.$connection->connect_error);
		}

		$query = '
			SELECT 
				Goods.id AS id,
				Goods.title AS goods
			FROM Goods
		';

		$result = $connection->query($query);
		if ($result !== false) {

			while ($row = $result->fetch_assoc()) {

				echo '<div class="node">#'.$row['id'].' '.$row['goods'].' (';
				$query = '
					SELECT Categories.title as cat
					FROM Categories, G_CAT_connection
					WHERE
						Categories.id = G_CAT_connection.categ_id AND
						G_CAT_connection.goods_id = '.intval($row['id']).'
				';
				$result_tmp = $connection->query($query);
				if ($result_tmp !== false) {
					while ($row_tmp = $result_tmp->fetch_assoc())
						echo $row_tmp['cat'].' ';
				}

				echo ')';

				$query = '
					SELECT Characteristics.title as char
					FROM Characteristics, GC_connection
					WHERE
						Characteristics.id = GC_connection.char_id AND
						GC_connection.goods_id = '.intval($row['id']).'
				';
				$result_tmp = $connection->query($query);
				if ($result_tmp !== false) {
					while ($row_tmp = $result_tmp->fetch_assoc())
						echo $row_tmp['char'].' ';
				}

				echo '</div>';
			}
		}
	}

	function echoCategoriesOptions() {
		$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		// стандартна перевірка підключення до бази даних
		if ($connection->connect_error) {
		    die('Connect Error ('.$connection->connect_errno.') '.$connection->connect_error);
		}
		
		$result = $connection->query('SELECT * FROM Categories');
		while ($row = $result->fetch_assoc()) {
			echo '<option value="'.$row['id'].'">('.$row['id'].') '.$row['title'].'</option>';
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

	function echoGoodsOptions() {
		$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		// стандартна перевірка підключення до бази даних
		if ($connection->connect_error) {
		    die('Connect Error ('.$connection->connect_errno.') '.$connection->connect_error);
		}
		
		$result = $connection->query('SELECT * FROM Goods');
		while ($row = $result->fetch_assoc()) {
			echo '<option value="'.$row['id'].'">('.$row['id'].') '.$row['title'].'</option>';
		}
	}

	if (isset($_POST['delete'])) {
		if (isset($_POST['id'])) {
			$id = intval($_POST['id']);
			removeTovar($id);
		}
	} else if (isset($_POST['add'])) {
		if (isset($_POST['name'])) {
			$name = $_POST['name'];
			newTovar($name);
		}
	} else if (isset($_POST['edit'])) {
		if (isset($_POST['id'])) {
			$id = intval($_POST['id']);
			$name = (isset($_POST['name']) && $_POST['name']!='') ? $_POST['name'] : false;
			updateTovar($id, $name);
		}
	} else if (isset($_POST['add_category'])) {
		if (isset($_POST['id']) && isset($_POST['cat_id'])) {
			$id = intval($_POST['id']);
			$cat_id = intval($_POST['cat_id']);
			G_Cat_connect($id, $cat_id);
		}
	} else if (isset($_POST['add_characteristics'])) {
		if (isset($_POST['id']) && isset($_POST['char_id'])) {
			$id = intval($_POST['id']);
			$cat_id = intval($_POST['char_id']);
			$value = $_POST['char_value'];
			GC_connect($id, $cat_id, $value);
		}
	}
?>

<html>
<head>
	<meta charset="utf8">
	<title>Редагування cписку атрибутів</title>
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
		.topside {
			height: 2em;
			display: block;
		}
	</style>
</head>
<body>
	<div class="half">
	<?
		echoGoodsList();
	?>
	</div>
	<div class="half" style="right:0">
		<hr>
		<h1>Додавання нового товару</h1>
		<form method="POST">
			<p>Назва: <input name="name" type="text"></p>
			<input name="add" type="submit" value="Додати">
		</form>
		<hr>
		<h1>Видалення товару</h1>
		<form method="POST">
			<p>Товар для видалення: <select name="id">
				<?php echoGoodsOptions(); ?>
			</select></p>
			<input name="delete" type="submit" value="Видалити">
		</form>
		<hr>
		<h1>Редагування товарів</h1>
		<form method="POST">
			<p>Товар для редагування: <select name="id">
				<?php echoGoodsOptions(); ?>
			</select></p>
			<p>Нова назва: <input name="name" type="text"></p>
			<input name="edit" type="submit" value="Редагувати">
		</form>
		<hr>
		<h1>Категоризація товарів</h1>
		<form method="POST">
			<p>Товар для редагування: <select name="id">
				<?php echoGoodsOptions(); ?>
			</select></p>
			<p>Категорія товару: <select name="cat_id">
				<?php echoCategoriesOptions(); ?>
			</select></p>
			<input name="add_category" type="submit" value="Додати категорію">
		</form>
		<hr>
		<h1>Характеристики товарів</h1>
		<form method="POST">
			<p>Товар для редагування: <select name="id">
				<?php echoGoodsOptions(); ?>
			</select></p>
			<p>Характеристика товару: <select name="char_id">
				<?php echoCharacteristicsOptions(); ?>
			</select></p>
			<p><textarea type="text" name="char_value"></textarea></p>
			<input name="add_characteristics" type="submit" value="Додати категорію">
		</form>
		<hr>
	</div>
</body>
</html>