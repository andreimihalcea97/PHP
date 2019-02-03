<?php
namespace Framework;

use PDO;
use PDOException;
use App\Config;

abstract class GameModel
{
    protected $table;
    protected $userGamesTable;

    public function newDbCon($resultAsArray = false)
    {

        $dsn = Config::DB['driver'];
        $dsn .= ":host=".Config::DB['host'];
        $dsn .= ";dbname=".Config::DB['dbname'];
        $dsn .= ";port=".Config::DB['port'];
        $dsn .= ";charset=".Config::DB['charset'];

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        //by default the result from database will be an object but if specified it can be changed to    an associative array / matrix
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
     *Return all data from table
     */
    public function getAllGames(): array
    {
        $db = $this->newDbCon();
        $stmt = $db->query("SELECT * from $this->table");

        return $stmt->fetchAll();
    }

    /**
     *Buy game
     */
    public function buyGame(array $data): int
	{
        //updated
		$db = $this->newDbCon();
		$q = $db->prepare("DESCRIBE $this->userGamesTable");
		$q->execute();
        $table_fields = $q->fetchAll(PDO::FETCH_COLUMN);
		
        list($columns, $values) = $this->prepareStmtForAdding($table_fields, $data);
        $stmt = $db->query("INSERT INTO $this->userGamesTable $columns VALUES $values");

    	return $stmt->fetch();
    }
    
    /**
     *Return data with specified id/index
     */
    public function get($id)
    {
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT * from $this->table where id=?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // find if the user already bouht the game
    public function findIfAlreadyBought(array $data)
    {
        //updated
		$db = $this->newDbCon();
		$q = $db->prepare("DESCRIBE $this->userGamesTable");
		$q->execute();
        $table_fields = $q->fetchAll(PDO::FETCH_COLUMN);
		
        list($columns, $values) = $this->prepareStmtForAdding($table_fields, $data);
        $stmt = $db->query("SELECT * FROM $this->userGamesTable WHERE $columns = $values");
        
    	return $stmt->fetch();
    }

    // prepare database for adding a game
    private function prepareStmtForAdding(array $table_names, array $data): array
	{
		//updated
    	$columns = ' (';
    	$values = [];
    	$i = 0;

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
}
