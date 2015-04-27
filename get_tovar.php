<? php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '28890929');
define('DB_NAME', 'CatTreeExampleDB');

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
		
		$ret_val = array();
		$result = $this->connection->query($query);
		while($row = $result->fetch_assoc())
			array_push($ret_val, $row);
		
		$result->close();
		return $ret_val;
	}
}

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

?>
