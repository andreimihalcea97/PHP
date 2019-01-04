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
    	$db = $this->newDbCon();
    	$stmt = $db->prepare("SELECT * from $this->table where id=?");
    	$stmt->execute([$id]);

    	return $stmt->fetch();
	}

	/**
 	* this function will prepare data to be used in sql statement
 	* 1. Will extract values from $data
 	* 2. Will create the prepared sql string with columns from $data
 	*/
	protected function prepareDataSearchForStmt(array $data, bool $like): array
	{
    	$columns = '';
    	$values = [];
    	$i = 1;
    	$searchStr = "=";
    	if ($like) {
        	$searchStr = " LIKE ";
    	}

    	foreach($data as $key => $value) {

        	$values[]= $value;
        	$columns .= $key . $searchStr . "?";
        	//if we are not at the last element with the iteration
        	if($i < (count($data))) {
            	$columns .= "AND ";
        	}

        	$i++;
    	}

    	return [$columns, $values];
	}

	/**
 	*Find data with values
 	* if $like is not set it will search using the = sql operator
 	* if $like is set it will search using the LIKE sql operator
 	*
 	* return false or an object
 	*/
	public function find(array $data, bool $like = false)
	{
    	list($columns, $values) = $this->prepareDataForSearchStmt($data, $like);

    	$db = $this->newDbCon();
    	$stmt = $db->prepare("SELECT * from $this->table where $columns");
    	$stmt->execute($values);

    	return $stmt->fetch();
	}

	private function prepareStmt(array $data): array
	{
    	$i = 1;
    	$columns = '';
    	$values = [];

    	foreach ($data as $key => $value) {
        	$values[] = $value;
        	$columns .= $key .'=?';

        	if($i < (count($data))) {
            	$columns .= ", ";
        	}

        	$i++;
    	}

    	return [$columns, $values];
	}

	/**
 	* Insert new data in table
 	*/
	public function new(array $data): int
	{
    	list($columns, $values) = $this->prepareStmt($data);

    	$db = $this->newDbCon();
    	$stmt = $db->prepare('INSERT INTO ' . $this->table . ' SET ' . $columns);

    	$stmt->execute($values);

    	return $db->lastInsertId();
	}

	/**
 	* Update data in table
 	*/
	public function update(array $where, array $data): bool
	{
    	list($columns, $values) = $this->prepareStmt($data);
    	//add the value of $where array to the list of $values that will be used in the prepared statement
    	//reset($where) it's a trick to extract the value of an associative array with a single element
    	$values[] = reset($where);

    	$db = $this->newDbCon();
    	$stmt = $db->prepare('UPDATE ' . $this->table . ' SET ' . $columns . ' WHERE ' . key($where) . '=?');

    	return $stmt->execute($values);
	}

	/**
 	* Delete data from table
 	*/
	public function delete(int $id): bool
	{
    	$db = $this->newDbCon();
    	$stmt = $db->prepare('DELETE FROM ' . $this->table . ' WHERE id=?');

    	return $stmt->execute([$id]);
	}
}