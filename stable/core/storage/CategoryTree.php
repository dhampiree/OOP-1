<?php 

namespace core\storage;

/**
* category tree prototype
*/
class CategoryTree {
	
	private $connection;
	private $tree_nodes;
	private $root_node_acsess;

	function __construct($root_category_id = false)	{

		$this->connection = new \mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		$this->tree_nodes = array();
		$this->root_node_acsess = array();

		if ($this->connection->connect_error) {
		    die('Connect Error ('.$this->connection->connect_errno.') '.$this->connection->connect_error);
		}

		if ($root_category_id === false) {
			if ($result = $this->connection->query('
				SELECT cat_id
				FROM Categories
				WHERE parent_cat_id = 0'
			)) {
				$result->data_seek(0);
				while ($row = $result->fetch_assoc()) {
					$category = intval($row['cat_id']);
					$this->parseCategory($category);
				}
				$result->close();
			}
		} else if (is_array($root_category_id)) {
			foreach ($root_category_id as $category) {
				$this->parseCategory($category);
			}
		} else {
			$this->parseCategory($root_category_id);
		}
	}

	function parseCategory($category_id) {
		if (is_integer($category_id) and $category_id != 0) {
			if ($result = $this->connection->query('
				SELECT *
				FROM Categories
				WHERE cat_id = '.$category_id
			)) {
				$result->data_seek(0);
				$row = $result->fetch_assoc();
				$result->close();

				$this->pushNode($row, true);
				$this->parseChildren($row['cat_id']);
			}
		} else {
			echo 'Wrong category identifier';
			echo '<br>';
			echo 'category_id: ';
			var_dump($category_id);
			exit();
		}
	}

	function parseChildren($category_id) {
		if ($result = $this->connection->query('
			SELECT *
			FROM Categories
			WHERE parent_cat_id = '.$category_id
		)) {
			$result->data_seek(0);
			while ($row = $result->fetch_assoc()) {
				$this->pushNode($row);
				$this->parseChildren($row['cat_id']);
			}
			$result->close();
		}
	}

	function pushNode(array $fetched_row, $root_node = false) {

		$node = new CategoryTreeNode($fetched_row);
		if (!$node->valid()) {
			echo 'Created node is not valid.';
			echo '<br>';
			echo 'ROW parameter: ';
			echo '<br>';
			var_dump($fetched_row);
			exit();
		}
		$cat_id = intval($fetched_row['cat_id']);
		$this->tree_nodes[$cat_id] = $node;

		if (!$root_node) {
			$parent_cat_id = intval($fetched_row['parent_cat_id']);
			if (!isset($this->tree_nodes[$parent_cat_id])) {
				echo 'Tree structure is broken.';
				echo '<br>';
				echo 'Trying to find missing parent: '.$fetched_row['parent_cat_id'];
				exit();
			}
			$this->tree_nodes[$parent_cat_id]->addChildNode($node);
		} else {
			array_push($this->root_node_acsess, $node);
		}
	}

	function acsessRootNode() {
		return $this->root_node_acsess;
	}
}
?>
