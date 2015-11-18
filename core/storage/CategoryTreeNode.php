<?php 

namespace core\storage;

/**
* Прототип окремого вузла дерева
* $fetched_row - вхідний параметр конструктора. Зчитаний з бази данних рядок.
* 	асоціативний масив з ключами {cat_id, cat_name, parent_cat_id}
*/
class CategoryTreeNode {

	private $category_id;				// ідентифіктор категорії
	private $category_name;				// назва категорії
	private $parent_category_id;		// ідентифікатор батьківської категорії
	private $children;					// массив дочірніх вузлів (категорій)
	
	// конструктор
	function __construct(array $fetched_row) {
		// ініціалізуємо об’єкт значеннями з вхідного параметру, якщо вони визначені.
		$this->category_id = (isset($fetched_row['cat_id'])) ? intval($fetched_row['cat_id']) : false;
		$this->category_name = (isset($fetched_row['cat_name'])) ? $fetched_row['cat_name'] : false;
		$this->parent_category_id = (isset($fetched_row['parent_cat_id'])) ? intval($fetched_row['parent_cat_id']) : false;
		$this->children = array();
	}

	// перевірка ініціалізації об’єкта.
	// повертає TRUE якщо ініціалізація всіх атрибутів пройшла вдало, інакше FALSE
	function valid() {
		if ($this->category_id === false) return false;
		if ($this->category_name === false) return false;
		if ($this->parent_category_id === false) return false;
		return true;
	}

	// додаємо вузол-нащадок
	function addChildNode(CategoryTreeNode $childNode) {
		array_push($this->children, $childNode);
	}

	// перевірка наявності вузлів-нащадків
	// повертає TRUE якщо існує хочаб один вузол, нащадок поточного
	function haveChildren() { 
		return (empty($this->children)) ? false : true;
	}

	// відображає на HTML сторінці предаставлення даного вузла
	function dumpToHtml() {
		echo '<div class="node">';
		echo '<p>'.$this->category_name.'</p>';
		// якщо у вузла є нашадки
		if ($this->haveChildren()) {
			// послідовно виводимо HTML представлення нащадків
			foreach ($this->children as $kidNode) {
				$kidNode->dumpToHtml();
			}
		}
		echo '</div>';
	}

	function toHypertextLinkList() {
		echo '<li><a href="index.php?cid='.$this->category_id.'">'.$this->category_name.'</a></li>';
		if (!empty($this->children)) {
			echo '<ul>';
			foreach ($this->children as $node)
				$node->toHypertextLinkList();
			echo '</ul>';
		}
	}
}

?>
