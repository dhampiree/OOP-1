<?php
	namespace core\logic;

<<<<<<< HEAD
	use core\storage\TovarList;

=======
>>>>>>> 78b4624dd05e214fe21943d70270e0e059b706cf
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
