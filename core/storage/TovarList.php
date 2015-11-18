<?php
	namespace core\storage;
	
	class TovarList {
	private $connection;
	private $list;
	
	function __construct($cat_id) {
		$this->connection = new \mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if ($this->connection->connect_error) {
			die('Connect Error ('.$this->connection->connect_errno.') '.$this->connection->connect_error);
		}
		
		$this->list = $this->get_exemplars($cat_id);
		$goods = $this->g_idxArr($this->list);
		$exemplars = $this->idxArr($this->list);
		
		$prices = $this->get_prices($exemplars);
		$characteristics = $this->get_characteristics($goods);

		foreach ($this->list as $key =>$row) {
			$chars_list = array();
			foreach ($characteristics as $char)
				if ($char['g_idx'] == $row['g_idx'])
					array_push($chars_list, $char);

			$price_list = array();
			foreach ($prices as $price)
				if ($price['ex_idx'] == $row['idx'])
					array_push($price_list, $price);

			$this->list[$key]['characteristics'] = $chars_list;
			$this->list[$key]['attributes'] = $this->get_attributes($row['attribute_value_list']);
			$this->list[$key]['prices'] = $price_list;
		}
	}

	private function get_exemplars($cat_id) {
		$cat_id = intval($cat_id);
		if ($cat_id == 0) {
			error_log('ERROR: get_exemplars: category id can not be');
			exit();
		}
		
		$query = '
		SELECT Goods_exemplars.id AS idx, Goods_exemplars.attribute_value_list AS attribute_value_list,
			Goods.id AS g_idx, Goods.title AS title, Storage.amount as amount
		FROM Goods_exemplars
		
		LEFT JOIN Storage
		    ON Storage.exemplar_id = Goods_exemplars.id
		    
		JOIN Goods
		    ON Goods.id = Goods_exemplars.goods_id
		
		WHERE Goods_exemplars.goods_id
		IN (
		    SELECT G_CAT_connection.goods_id
		    FROM G_CAT_connection
		    WHERE G_CAT_connection.categ_id = '.$cat_id.'
		)
		';
		
		return $this->simple_select($query);
	}
	
	private function get_characteristics($goods_list_string) { 
		$query = '
		SELECT Goods.id as g_idx, Characteristics.title AS title ,GC_connection.value AS value
		FROM Goods
		JOIN GC_connection
		    ON GC_connection.goods_id = Goods.id
		JOIN Characteristics
		    ON GC_connection.char_id = Characteristics.id
		WHERE Goods.id IN ('.$goods_list_string.')
		';
		
		return $this->simple_select($query);
	}
	
	private function get_prices($idx_array_string) {
		
		$query = '
			SELECT Prices.id AS idx, Goods_exemplars.id AS ex_idx, Prices.type AS type, Prices.value AS value
			FROM Goods_exemplars
			LEFT JOIN Prices
			    ON Goods_exemplars.id = Prices.exemplar_id
			WHERE Goods_exemplars.id IN ('.$idx_array_string.')
		';
		
		return $this->simple_select($query);
	}
	
	private function get_attributes($attr_list_string) {
		
		$query = '
		SELECT Attribute.title AS title, Attribute_value.value AS value
		FROM Attribute
		LEFT JOIN Attribute_value
		    ON Attribute_value.attribute_id = Attribute.id
		WHERE Attribute_value.id IN ('.$attr_list_string.')
		';
		
		return $this->simple_select($query);
	}
	
	private function simple_select($query) {
		# var_dump($query);
		$ret_val = array();
		$result = $this->connection->query($query);
		
		if ($result) {
			while($row = $result->fetch_assoc())
				array_push($ret_val, $row);
			$result->close();
		}
		
		return $ret_val;
	}
	
	private function idxArr($list) {
		$retVal = '';

		foreach ($list as $row)
			$retVal .= $row['idx'].', ';
		
		return substr($retVal, 0, -2);
	}

	private function g_idxArr($list) {
		$g_idx_array = array();
		$retVal = '';

		foreach ($list as $row) {
			if (!array_search($row['g_idx'], $g_idx_array)) 
				array_push($g_idx_array, $row['g_idx']);
		}

		foreach ($g_idx_array as $row)
			$retVal .= $row.', ';
		
		return substr($retVal, 0, -2);
	}

	function getTovars() {
		return $this->list;
	}
}
?>
