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

    public function get($id)
    {
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT * from $this->table where id=?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     *Return data with specified id/index
     */
    public function getGamesForUser($userID)
    {
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT * from $this->userGamesTable WHERE IDUser=?");
        $stmt->execute([$userID]);
        $result = $stmt->fetchAll();
        $gameIDs = array();
        foreach ($result as $key => $value) {
            array_push($gameIDs, $value->IDGame);
        }

        $gameIDs = $this->prepareValuesForQuery($gameIDs);
        $stmt = $db->prepare("SELECT * from $this->table WHERE id IN $gameIDs");
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }
    
    protected function prepareValuesForQuery(array $values)
    {
        $string = '(';
        $i = 1;
        foreach ($values as $key => $value) {
            $string = $string . "'" . $value . "'";

            if($i < (count($values))) {
                $string .= ", ";
            }

            $i++;
        }
        $string = $string . ')';
        return $string;
    }

    /**
     *Buy game
     */
    public function buyGame(array $data): int
	{
        //updated
		$db = $this->newDbCon();
        $values = $this->prepareValuesForQuery($data);
		$stmt = "INSERT INTO $this->userGamesTable (IDUser, IDGame) VALUES $values";
        
        $result = $db->query($stmt);

    	return $result->fetch();
    }
    
    
    // find if the user already bouht the game
    public function findIfAlreadyBought(array $data)
    {
        //updated
        $db = $this->newDbCon();
        $values = $this->prepareValuesForQuery($data);
        $stmt = "SELECT * FROM $this->userGamesTable WHERE (IDUser, IDGame) = $values";
        
        $result = $db->query($stmt);
        
    	return $result->fetch();
    }
}
