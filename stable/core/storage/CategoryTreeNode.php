<?php 

namespace core\storage;
/**
* tree node prototype
*/
class CategoryTreeNode {

	private $category_id;
	private $category_name;
	private $parent_category_id;
	private $children;
	
	function __construct(array $fetched_row) {
		$this->category_id = (isset($fetched_row['cat_id'])) ? intval($fetched_row['cat_id']) : false;
		$this->category_name = (isset($fetched_row['cat_name'])) ? $fetched_row['cat_name'] : false;
		$this->parent_category_id = (isset($fetched_row['parent_cat_id'])) ? intval($fetched_row['parent_cat_id']) : false;
		$this->children = array();
	}

	function valid() {
		if ($this->category_id === false) return false;
		if ($this->category_name === false) return false;
		if ($this->parent_category_id === false) return false;
		return true;
	}

	function addChildNode(CategoryTreeNode $childNode) {
		array_push($this->children, $childNode);
	}

	function have_children() { 
		# if array empty $this->children return false else return true
	}
	
	function toHTML() {
		echo '<li><a href="index.php?cid='.$this->category_id.'">'.$this->category_name.'</a></li>';
	}
}
?>
