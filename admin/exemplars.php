<?php
	
	include('../autoloader.php');
	include('../core/config.php');

	use \core\view\ViewGateway;

	function newExemplar($g_id, $arrt_string) {

		$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		// стандартна перевірка підключення до бази даних
		if ($connection->connect_error) {
		    die('Connect Error ('.$connection->connect_errno.') '.$connection->connect_error);
		}

		$query = '
		INSERT INTO Goods_exemplars (goods_id, attribute_value_list)
		VALUES ("'.intval($g_id).'", "'.$attr_string.'")
		';

		$connection->query($query);
	}

	function removeExemplar($ex_id) {

		$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		// стандартна перевірка підключення до бази даних
		if ($connection->connect_error) {
		    die('Connect Error ('.$connection->connect_errno.') '.$connection->connect_error);
		}

		$query = '
		DELETE FROM Goods_exemplars
		WHERE id = '.intval($ex_id);

		$connection->query($query);
	}

	function getDefaultCategory() {
		$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		// стандартна перевірка підключення до бази даних
		if ($connection->connect_error) {
		    die('Connect Error ('.$connection->connect_errno.') '.$connection->connect_error);
		}

		$query = '
			SELECT id
			FROM Categories
			ORDER BY id DESC
			LIMIT 1
		';

		$result = $connection->query($query);
		if ($result !== false) {
			$row = $result->fetch_assoc();
			return $row['id'];
		} else return 1;
	}

	function echoExemplarOptions() {
		$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		// стандартна перевірка підключення до бази даних
		if ($connection->connect_error) {
		    die('Connect Error ('.$connection->connect_errno.') '.$connection->connect_error);
		}

		$result = $connection->query('SELECT * FROM Goods_exemplars');
		if ($result !== false) {
			while ($row = $result->fetch_assoc())
				echo '<option value="'.$row['id'].'">Exemplar #'.$row['id'].'</option>';
		}
	}

	function getCategotyName($cat_id) {
		$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		// стандартна перевірка підключення до бази даних
		if ($connection->connect_error) {
		    die('Connect Error ('.$connection->connect_errno.') '.$connection->connect_error);
		}
		
		$result = $connection->query('SELECT title FROM Categories WHERE id="'.$cat_id.'" LIMIT 1');
		if ($result !== false) {
			$row = $result->fetch_assoc();
			return $row['title'];
		} else return 1;
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
			removeExemplar($id);
		}
	} else if (isset($_POST['add'])) {
		if (isset($_POST['id']) && isset($_POST['attr_list'])) {
			$id = intval($_POST['id']);
			$attr_list = intval($_POST['attr_list']);
			newExemplar($id, $attr_list);
		}
	}
?>

<html>
<head>
	<meta charset="utf8">
	<title>Редагування cписку екземплярів</title>
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
		<hr>
		<h1>Активна категорія товарів</h1>
		<form class="topside" method="POST">
			<select name="category" onchange="document.location='?c='+this.options[this.selectedIndex].value">
				<option selected disabled>Оберіть категорію</option>
				<?php echoCategoriesOptions(); ?>
			</select>
		</form>
		<hr>
		<?php $cat_id = (isset($_GET['c'])) ? $_GET['c'] : getDefaultCategory(); ?>
		<h1>Категорія: <?php echo getCategotyName($cat_id); ?></h1>
		<?php
			$a = new ViewGateway();
			$a->loadTovarsOfCategory($cat_id);
			$a->simpleHTML();
		?>
	</div>
	<div class="half" style="position: fixed; right:0">
		<hr>
		<h1>Додавання нового екземпляру</h1>
		<form method="POST">
			<p>Товар: <select name="id">
				<?php echoGoodsOptions(); ?>
			</select></p>	
			<p>Атрибути: <input name="attr_list" type="text"></p>
			<input name="add" type="submit" value="Додати">
		</form>
		<hr>
		<h1>Видалення екземпляру</h1>
		<form method="POST">
			<p>Екземпляр для видалення: <select name="id">
				<?php echoExemplarOptions(); ?>
			</select></p>
			<input name="delete" type="submit" value="Видалити">
		</form>
		<hr>
	</div>
</body>
</html>