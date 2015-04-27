<? php
	function get_tovars($cat_id) {
		
		if (!is_integer($cat_id) and $cat_id != 0) {
			console.log('ERROR: get_tovar: category id is not integer');
			exit();
		}
		
		'
		SELECT G_CAT_connection.goods_id
		FROM G_CAT_connection
		WHERE `categ_id` ='.$cat_id.'
		'
		
		'
		SELECT *
		FROM `Goods_exemplars`
		WHERE `goods_id` IN 
		'
	}
?>
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
