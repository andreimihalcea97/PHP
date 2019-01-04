<?php
//namespace Framework;

//use PDO;
//use PDOException;

abstract class Model
{
	protected $table;

	public function newDbCon($resultAsArray = false)
	{
		
    	$dsn = Config::DB['driver'];
    	$dsn .= ":host=".Config::DB['host'];
    	$dsn .= ";dbname=".Config::DB['dbname'];
    	$dsn .= ";port=".Config::DB['port'];
    	$dsn .= ";charset=".Config::DB['charset'];

    	$options = [
        	PDO::ATTR_ERRMODE        	=> PDO::ERRMODE_EXCEPTION,
        	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        	PDO::ATTR_EMULATE_PREPARES   => false,
    	];
    	//by default the result from database will be an object but if specified it can be changed to	an associative array / matrix
    	if ($resultAsArray) {
        	$options[PDO::ATTR_DEFAULT_FETCH_MODE] = PDO::FETCH_ASSOC;
    	}

    	try {
        	return new PDO($dsn, Config::DB['user'], Config::DB['pass'], $options);
    	} catch (PDOException $e) {
        	throw new PDOException($e->getMessage(), (int)$e->getCode());
    	}
	}

	/**
 	* Return all data from table
 	*/
	public function getAll(): array
	{
		//updated
    	$db = $this->newDbCon();
    	$stmt = $db->query("SELECT * from $this->table");

    	return $stmt->fetchAll();
	}

	/**
 	* Get data with specified id/index
 	* return false or an object
 	*/
	public function get($id)
	{
		//updated
    	$db = $this->newDbCon();
    	$stmt = $db->prepare("SELECT * from $this->table where ID = :id");
    	$stmt->execute(array(':id' => $id));

    	return $stmt->fetch();
	}

	/**
 	* this function will prepare data to be used in sql statement
 	* 1. Will extract values from $data and $table_names
 	* 2. Will create the prepared sql string with columns from $data
 	*/
	public function prepareDataSearchForStmt(array $table_names, array $data): string
	{
		//updated
    	$query = '';
    	$values = [];
    	$i = 0;
		
		// delete from table names ID
		unset($table_names[0]);

		// load from data the searched user abut it's reversed.
    	foreach($data as $key => $value) {
			
        	$values[]= $value;
		}
		
		// bring it to normal
		$values = array_reverse($values);

		// create sql query
		foreach($table_names as $key => $value) {

			//echo $value;
			$query .= $value . " = '". "$values[$i]" . "'";
			if($i < (count($data) - 1)) {
					$query .= " AND ";
				}
			$i++;
		}

		// return query
    	return $query;
	}

	/**
 	*Find data with values
 	* if $like is not set it will search using the = sql operator
 	* if $like is set it will search using the LIKE sql operator
 	*
 	* return false or an object
 	*/
	public function find(array $data, bool $like = true)
	{
		//updated
		// get column names from table
		$db = $this->newDbCon();
		$q = $db->prepare("DESCRIBE $this->table");
		$q->execute();
		$table_fields = $q->fetchAll(PDO::FETCH_COLUMN);

		$query = $this->prepareDataSearchForStmt($table_fields, $data, $like);
    	$stmt = $db->prepare("SELECT * from $this->table WHERE $query");
		$stmt->execute();
		
    	return $stmt->fetch();
	}

	private function prepareStmtForAdding(array $table_names, array $data): array
	{
		//updated
    	$columns = ' (';
    	$values = [];
    	$i = 0;
		
		// delete from table names ID
		unset($table_names[0]);

		// load from data the searched user abut it's reversed.
    	foreach($data as $key => $value) {
			
			//echo $value;
        	$values[]= $value;
		}

		// bring it to normal
		$values = array_reverse($values);
		$values_string = '(';

		foreach($values as $key => $value) {
			
			$values_string .= "'" . $value . "'";
			if($i < (count($data) - 1)) {
				$values_string .= ", ";
			}
			$i++;
		}
		$values_string .= ")";

		$i = 0;

		// create sql query
		foreach($table_names as $key => $value) {
			//. "$values[$i]" . "'";
			$columns .= $value;
			if($i < (count($data) - 1)) {
					$columns .= ", ";
				}
			$i++;
		}
		$columns .= ")";
		
		// return query items
    	return [$columns, $values_string];
	}

	/**
 	* Insert new data in table
 	*/
	public function new(array $data): int
	{
		//updated
		$db = $this->newDbCon();
		$q = $db->prepare("DESCRIBE $this->table");
		$q->execute();
		$table_fields = $q->fetchAll(PDO::FETCH_COLUMN);
		
    	list($columns, $values) = $this->prepareStmtForAdding($table_fields, $data);

    	$stmt = $db->prepare("INSERT INTO $this->table $columns VALUES $values");
    	$stmt->execute();

    	return $db->lastInsertId();
	}

	private function prepareStmtForUpdating(array $table_names, array $data): array
	{
		//non functional
    	$columns = ' (';
    	$values = [];
    	$i = 0;
		
		// delete from table names ID
		unset($table_names[0]);

		// load from data the searched user abut it's reversed.
    	foreach($data as $key => $value) {
			
			//echo $value;
        	$values[]= $value;
		}

		// bring it to normal
		$values = array_reverse($values);
		$values_string = '(';

		foreach($values as $key => $value) {
			
			$values_string .= "'" . $value . "'";
			if($i < (count($data) - 1)) {
				$values_string .= ", ";
			}
			$i++;
		}
		$values_string .= ")";

		$i = 0;

		// create sql query
		foreach($table_names as $key => $value) {
			//. "$values[$i]" . "'";
			$columns .= $value;
			if($i < (count($data) - 1)) {
					$columns .= ", ";
				}
			$i++;
		}
		$columns .= ")";
		
		// return query items
    	return [$columns, $values_string];
	}

	/**
	 * Update data in table
	 * $condition = string (ex: EMail = email AND Username = username)
 	*/
	public function update(array $condition, array $data): bool
	{
		//non functional
    	$db = $this->newDbCon();
		$q = $db->prepare("DESCRIBE $this->table");
		$q->execute();
		$table_fields = $q->fetchAll(PDO::FETCH_COLUMN);
		
    	list($columns, $condition) = $this->prepareStmtForUpdating($table_fields, $data);
    	//add the value of $where array to the list of $values that will be used in the prepared statement
    	//reset($where) it's a trick to extract the value of an associative array with a single element
		//$values[] = reset($where);
		
    	$stmt = $db->prepare("UPDATE $this->table SET $columns WHERE $condition");

    	return $stmt->execute($values);
	}

	/**
 	* Delete data from table
 	*/
	public function delete(int $id)
	{
		//updated
    	$db = $this->newDbCon();
		$stmt = $db->prepare("DELETE FROM $this->table WHERE ID = :id");
		$stmt->execute(array(':id' => $id));

    	return $stmt->fetch();
	}
}