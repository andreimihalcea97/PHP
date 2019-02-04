<?php
namespace Framework;

use PDO;
use PDOException;
use App\Config;

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
    public function getAll(): array
    {
        $db = $this->newDbCon();
        $stmt = $db->query("SELECT * from $this->table");

        return $stmt->fetchAll();
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

    public function checkWallet($userID, $gameValue)
    {
        $user = $this->get($userID);
        $availableBalance = $user->Wallet;
        if($availableBalance >= $gameValue){
            return true;
        }
        else return false;
    }

    public function addFunds($userID, $value)
    {
        $db = $this->newDbCon();
        
        $stmt = $db->prepare("UPDATE $this->table SET wallet=? WHERE id=?");
        $stmt->execute([$value, $userID]);

    	return $stmt->fetch();
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
    public function findIfAlreadyExists(array $data)
    {
        //updated
		$db = $this->newDbCon();
        $values = $this->prepareValuesForQuery($data);
        
        $stmt = "SELECT * FROM $this->table WHERE (EMail, Username, Password) = $values";
        $result = $db->query($stmt);
        
    	return $result->fetch();
    }

    public function logIn(array $data)
    {
        //updated
		$db = $this->newDbCon();
        $values = $this->prepareValuesForQuery($data);
        
        $stmt = "SELECT * FROM $this->table WHERE (EMail, Password) = $values";
        $stmt = $db->prepare($stmt);
        $stmt->execute();
        
    	return $stmt->fetch();
    }
    
    public function new(array $data): int
	{
		//updated
        $db = $this->newDbCon();
        $values = $this->prepareValuesForQuery($data);
		$stmt = "INSERT INTO $this->table (EMail, Username, Password) VALUES $values";

    	$stmt = $db->query($stmt);

    	return $stmt->fetch();
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
