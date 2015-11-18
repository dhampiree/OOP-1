<?php

include('autoloader.php');
include('core/config.php');

use \core\storage\CategoryTree;
use \core\view\ViewGateway;

Header("Content-Type: text/html; charset=utf-8");
?>
<html>
<head>
	<style>
		ul {
			display:block;
			margin-left: 2em;
			border: 1px solid black;
		}
		td {
			border: 1px solid black;
			padding: 5px;
		}
	</style>	
</head>
<body>
<?php
if (isset($_GET['cid'])) {
	$a = new ViewGateway();
	$a->loadTovarsOfCategory(intval($_GET['cid']));
	$a->toHTML();
} else {
	$a = new CategoryTree();
	$a->toHypertextLinkList();
}
?>	
</body>
</html>
<script type="text/javascript">
function sort(el) {
	var col_sort = el.innerHTML;
	var tr = el.parentNode;
	var table = tr.parentNode;   
	var td, arrow, col_sort_num;
	for (var i=0; (td = tr.getElementsByTagName("td").item(i)); i++) {
		if (td.innerHTML == col_sort) {
			col_sort_num = i;
			if (td.prevsort == "y"){
			arrow = td.firstChild;
			el.up = Number(!el.up);
			}else{
			td.prevsort = "y";
			arrow = td.insertBefore(document.createElement("span"),td.firstChild);
			el.up = 0;
			}
			arrow.innerHTML = el.up?"↑ ":"↓ ";
		}else{
			if (td.prevsort == "y"){
				td.prevsort = "n";
			if (td.firstChild) td.removeChild(td.firstChild);
				}
			}
	}
	var a = new Array();
	for(i=1; i < table.rows.length; i++) {
		a[i-1] = new Array();
		a[i-1][0]=table.rows[i].getElementsByTagName("td").item(col_sort_num).innerHTML;
		a[i-1][1]=table.rows[i];
	}
	a.sort();
	if(el.up) a.reverse(); 
	for(i=0; i < a.length; i++) table.appendChild(a[i][1]);

	}
</script>
