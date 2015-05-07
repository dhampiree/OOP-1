<?php
	namespace logic;
	
class JSON_handler {
	function pack($data) {
		return json_encode($data);
	}

	function unpack($data) {
		return json_decode($data);
	}
}
?>
