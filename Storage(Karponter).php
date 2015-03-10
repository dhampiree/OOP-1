<?
/**
* MySql storage class
*/
class Storage
{
	private $connection;
	private $host;
	private $db;

	function __construct() {
		$this->connection = false;
		$this->host = '';
		$this->db = false;
	}

	function select_db($dbname) {
		if (mysql_select_db($dbname, $this->connection)) {
			$this->db = $dbname;
			return true;
		} else {
			return false;
		}
	}

	function connect_to_db($host, $user, $password, $dbname=false) {
		if (($this->connection) and $dbname)
			if ($this->host == $host)
				return $this->select_db($dbname);

		$this->connection = mysql_connect($host, $user, $password);
		if ($this->connection) {
			$this->host = $host;
			return ($dbname) ? $this->select_db($dbname) : true;
		} else return false;
	}

	function select_query($selection_fields, $table, $conditions=false, $limit=false) {
		$limits = ($limit) ? 'LIMIT '.$limit.' ' : '';
		$fields = '';
		$table_name = '`'.$table.'` ';
		$conditions_string = ($conditions) ? 'WHERE '.$conditions : '';
		if(is_array($selection_fields)) {
			foreach ($selection_fields as $value) {
				$fields .= ($value == '*') ? $value : '`'.$value.'`,';
			}
		} else {
			$fields = ($value == '*') ? $value : '`'.$value.'`';
		}
		$query = 'SELECT '.$fields.' FROM '.$table_name.$conditions_string.' '.$limits;
		$result = mysql_query($query);
		$retVal = array();
		while($row = mysql_fetch_array($result, MYSQL_ASSOC))
			$retVal[] = $row;
		return $retVal;
	}

	function insert_query($table, $fields, $values) {
		$table_name = '`'.$table.'`';

		$fields_string = '(';
		foreach ($fields as $value)
			$fields_string.='`'.$value.'`, ';
		$fields_string.=') ';

		$values_string = '(';
		foreach ($values as $value)
			$values_string.='`'.$value.'`, ';
		$values_string.=') ';

		$query = 'INSERT INTO '.$table_name.$fields_string.'VALUES '.$values_string;
		return mysql_query($query);
	}

	function update_query($table, $fields, $values, $conditions=false) {
		$table_name = '`'.$table.'`';
		$set_string = '';
		$conditions_string = ($conditions) ? 'WHERE '.$conditions : '';

		foreach ($fields as $key => $value)
			$set_string.='`'.$value.'` = '.$values[$key].', ';
		$set_string = substr($set_string, 0, -2);
		$query = 'UPDATE '.$table_name.' SET '.$set_string.$conditions_string;
		return mysql_query($query);
	}

	function delete_query($table, $conditions) {
		$table_name = '`'.$table.'`';
		$query = 'DELETE FROM '.$table_name.' WHERE '.$conditions;
		return mysql_query($query);
	}
}
?>
