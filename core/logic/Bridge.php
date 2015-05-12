<?php
	namespace core/logic;

class Bridge {
	private $JSON_handler;

	function __construct() {
		$this->JSON_handler = new JSON_handler();
	}

	function getTovars($category) {
		$list = new TovarList($category);
		return $this->JSON_handler->pack(
			$list->getTovars()
		);
	}
}
?>
