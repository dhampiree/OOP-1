Skip to content
 This repository
Explore
Gist
Blog
Help
@Fraideron Fraideron
 
 Watch 1
  Star 0
 Fork 0LabbyForKspu/OOP
 branch: master  OOP/get_tovar.php
@KarponterKarponter 6 days ago Update get_tovar.php
2 contributors @Karponter @Fraideron
RawBlameHistory    200 lines (155 sloc)  4.705 kb

https://www.datatables.net/
<?php
define('DB_HOST', 'mysql.hostinger.com.ua');
define('DB_USER', 'u372374362_user');
define('DB_PASS', '28890929');
define('DB_NAME', 'u372374362_db');
class TovarList {
	private $connection;
	private $list;
	
	function __construct($cat_id) {
		$this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if ($this->connection->connect_error) {
			die('Connect Error ('.$this->connection->connect_errno.') '.$this->connection->connect_error);
		}
		
		$this->list = $this->get_exemplars($cat_id);
		$goods = $this->g_idxArr($this->list);
		$exemplars = $this->idxArr($this->list);
		
		foreach ($this->list as $row) {
			$row['characteristics'] = $this->get_characteristics($goods);
			$row['attributes'] = $this->get_attributes($row['attribute_list']);
			$row['prices'] = $this->prices($exemplars);
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
	
	function get_characteristics($goods_list_string) { 
		$query = '
		SELECT Goods.id AS idx, Goods.title AS title, Characteristic.title AS cat_title ,GC_connection.value AS value
		FROM Goods
		JOIN GC_connection
		    ON GC_connection.goods_id = Goods.id
		JOIN Characteristic
		    ON GC_connection.categ_id = Characteristic.id
		WHERE Goods.id IN ('.$goods_list_string.')
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
	
	function simple_select($query) {
		$ret_val = array();
		$result = $this->connection->query($query);
		
		while($row = $result->fetch_assoc())
			array_push($ret_val, $row);
		
		$result->close();
		return $ret_val;
	}
	
	function idxArr($list) {
		$idx_array= array();
		
		foreach ($list as $value) {
			$idx_array .= $value.", ";
		}
		
		return substr($idx_array, 0, -2);
	}
	function g_idxArr($list) {
		$g_idx_array =  array();
		foreach ($list as $val) {
			$g_idx_array .= $g_idx_array.", ";
		}
		

		$val_g_idx  = array();
		$unicalArray = array_unique($g_idx_array);
		$unicalArrayValues = array_values($unicalArray);
		
		
		foreach ($unicalArrayValues as $we) {
		 	$val_g_idx .= $we." ";
		 } 

		return substr($val_g_idx,0,-3);
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
$a = new TovarList(1);
?>
Status API Training Shop Blog About
© 2015 GitHub, Inc. Terms Privacy Security Contact
