<?php

define('DB_HOST', 'mysql.hostinger.com.ua');
define('DB_USER', 'u372374362_user');
define('DB_PASS', '28890929');
define('DB_NAME', 'u372374362_db');

class TovarList {
	private $connection;
	
	function __construct() {
		$this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if ($this->connection->connect_error) {
			die('Connect Error ('.$this->connection->connect_errno.') '.$this->connection->connect_error);
		}
	}
	
	function get_exemplars($cat_id) {
		
		if (!is_integer($cat_id) and $cat_id != 0) {
			console.log('ERROR: get_tovar: category id is not integer');
			exit();
		}
		
		$query = '
		SELECT Goods_exemplars.id AS idx, Goods.id AS g_idx, Goods.title AS title, Storage.amount as amount
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
	
	function get_prices($idx_array_string) {
		
		$query = '
			SELECT Prices.id AS idx, Goods_exemplars.id AS ex_idx, Prices.type AS type, Prices.value AS value
			FROM Goods_exemplars
			LEFT JOIN Prices
			    ON Goods_exemplars.id = Prices.exemplar_id
			WHERE Goods_exemplars.id IN ('.$idx_array_string.')
		';
		
		return $this->simple_select($query);
	}
	
	function get_attributes($attr_list_string) {
		
		$query = '
		SELECT Attribute.title AS title, Attribute_value.value AS value
		FROM Attribute
		LEFT JOIN Attribute_value
		    ON Attribute_value.attribute_id = Attribute.id
		WHERE Attribute_value.id IN ('.$attr_list_string.')
		';
		
		return $this->simple_select($query);
	}
	
	function get_categories($goods_list_string) { 
		$query = '
		SELECT Goods.id AS idx, Goods.title AS title, Category.title AS cat_title ,GC_connection.value AS value
		FROM Goods
		JOIN GC_connection
		    ON GC_connection.goods_id = Goods.id
		JOIN Categories
		    ON GC_connection.categ_id = Categories.id
		WHERE Goods.id IN ('.$goods_list_string.')
		';
		
		return $this->simple_select($query);
	}
	
	function simple_select($query) {
		$ret_val = array();
		$result = $this->connection->query($query);
		
		while($row = $result->fetch_assoc())
			array_push($ret_val, $row);
		
		$result->close();
		return $ret_val;
	}
}

/*
SELECT Attribute.title AS title, Attribute_value.value AS value
FROM Attribute

LEFT JOIN Attribute_value
    ON Attribute_value.attribute_id = Attribute.id
    
WHERE Attribute_value.id IN (1,2)
*/

/* grabbing prices
SELECT Prices.id AS idx, Goods_exemplars.id AS ex_idx, Prices.type AS type, Prices.value AS value
FROM Goods_exemplars

LEFT JOIN Prices
    ON Goods_exemplars.id = Prices.exemplar_id
    
WHERE Goods_exemplars.id IN (1,2,3)
*/

/* grab exemplars
SELECT Goods_exemplars.id AS idx, Goods.id AS g_idx, Goods.title AS title, Storage.amount as amount
FROM Goods_exemplars

LEFT JOIN Storage
    ON Storage.exemplar_id = Goods_exemplars.id
    
JOIN Goods
    ON Goods.id = Goods_exemplars.goods_id

WHERE Goods_exemplars.goods_id
IN (
    SELECT G_CAT_connection.goods_id
    FROM G_CAT_connection
    WHERE G_CAT_connection.categ_id =1
)
*/


/*
SELECT *
FROM Goods_exemplars

LEFT JOIN Prices
    ON Prices.exemplar_id = Goods_exemplars.id
LEFT JOIN Storage
    ON Storage.exemplar_id = Goods_exemplars.id
JOIN Goods
    ON Goods.id = Goods_exemplars.id
JOIN GC_connection
    ON GC_connection.goods_id = Goods_exemplars.id

WHERE Goods_exemplars.goods_id
IN (
    SELECT G_CAT_connection.goods_id
    FROM G_CAT_connection
    WHERE G_CAT_connection.categ_id =1
)
*/
$a = new TovarList ();
$list = $a->get_exemplars(1);
var_dump($list);
?>
