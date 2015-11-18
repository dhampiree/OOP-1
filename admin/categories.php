<?php
	
	include('../autoloader.php');
	include('../core/config.php');

	use \core\storage\CategoryTree;
	use \core\storage\CategoryTreeNode;

	function newCategory($category_name, $parent_cat_id=0) {

		if (!is_integer($parent_cat_id)) {
			error_log('ERROR! Wrong parent parameter.');
			return false;
		}

		/*if () {
			error_log('ERROR! Wrong name parameter.');
			return false;
		}*/
		$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		// стандартна перевірка підключення до бази даних
		if ($connection->connect_error) {
		    die('Connect Error ('.$connection->connect_errno.') '.$connection->connect_error);
		}

		$query = '
		INSERT INTO Categories (title, parent_cat_id)
		VALUES ("'.$category_name.'", '.$parent_cat_id.')
		';

		$connection->query($query);
	}

	function removeCategory($category_id) {
		if (!is_integer($category_id)) {
			error_log('ERROR! Wrong category parameter.');
			return false;
		}

		$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		// стандартна перевірка підключення до бази даних
		if ($connection->connect_error) {
		    die('Connect Error ('.$connection->connect_errno.') '.$connection->connect_error);
		}

		$query = '
		DELETE FROM Categories
		WHERE id = '.$category_id;

		$connection->query($query);
	}

	function updateCategory($id, $name=false, $parent=false) {
		$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		// стандартна перевірка підключення до бази даних
		if ($connection->connect_error) {
		    die('Connect Error ('.$connection->connect_errno.') '.$connection->connect_error);
		}

		$namestring = ($name === false) ? '' : 'title = "'.$name.'"';
		$parentstring = ($parent === false) ? '' : 'parent_cat_id = "'.$parent.'"';
		$separator = ($namestring == '' or $parentstring == '') ? '' : ', ';

		$query = '
			UPDATE Categories
			SET '.$namestring.$separator.$parentstring.'
			WHERE id = '.$id.'
		';
		error_log($query);
		$connection->query($query);
	}

	if (isset($_POST['delete'])) {
		if (isset($_POST['id'])) {
			$id = intval($_POST['id']);
			removeCategory($id);
		}
	} else if (isset($_POST['add'])) {
		if (isset($_POST['name'])) {
			$parent = (isset($_POST['parent'])) ? intval($_POST['parent']) : 0;
			$name = $_POST['name'];
			newCategory($name, $parent);
		}
	} else if (isset($_POST['edit'])) {
		if (isset($_POST['id'])) {
			$id = intval($_POST['id']);
			$parent = (isset($_POST['parent']) && $_POST['parent']!='noch') ? intval($_POST['parent']) : false;
			$name = (isset($_POST['name']) && $_POST['name']!='') ? $_POST['name'] : false;
			updateCategory($id, $name, $parent);
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
?>

<html>
<head>
	<meta charset="utf8">
	<title>Редагування дерева категорій</title>
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
		$a = new CategoryTree();
		$a->dumpToHtml();
	?>
	</div>
	<div class="half" style="position: fixed; right:0">
		<hr>
		<h1>Додавання нової категорії</h1>
		<form method="POST">
			<p>Назва: <input name="name" type="text"></p>
			<p>Батьківська категорія: <select name="parent">
				<?php echoCategoriesOptions(); ?>
			</select></p>
			<input name="add" type="submit" value="Додати">
		</form>
		<hr>
		<h1>Видалення категорії</h1>
		<form method="POST">
			<p>Категорія для видалення: <select name="id">
				<?php echoCategoriesOptions(); ?>
			</select></p>
			<input name="delete" type="submit" value="Видалити">
		</form>
		<hr>
		<h1>Редагування категорії</h1>
		<form method="POST">
			<p>Категорія для редагування: <select name="id">
				<?php echoCategoriesOptions(); ?>
			</select></p>
			<p>Нова назва: <input name="name" type="text"></p>
			<p>Нова батьківська категорія: <select name="parent">
				<option value="noch" selected="">БЕЗ ЗМІН</option>
				<option value="0">КОРЕНЕВА</option>
				<?php echoCategoriesOptions(); ?>
			</select></p>
			<input name="edit" type="submit" value="Редагувати">
		</form>
		<hr>
	</div>
</body>
</html>