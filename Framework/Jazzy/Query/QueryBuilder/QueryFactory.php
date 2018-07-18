<?php
namespace Barbet\Query;

require_once "\..\Connection\db.php";

use PDO;
use Barbet\DB;

class QueryFactory 
{
	private $statement;

	private $conn;

	private $query;

	public function __construct()
	{
		$this->conn = new DB();
		$this->conn = $this->conn->getPDO();   //Get PDO Object/Connection	
	}

	public function query($query) 
	{
		$this->query = $query;
		$this->statement = $this->conn->prepare($this->query);
	}

	public function bindValue($param ,$val) 
	{
		$this->statement->bindParam($param, $val);
	}

	public function execute() 
	{
	    $this->statement->execute();	
	}

	public function executeCurrentQuery()
	{
		$this->query($this->query);
		$this->execute();
		return $this->fetch();
	}

	public function fetch($type = null) 
	{
		if ($type == null) {
			return $this->toJSON($this->statement->fetchAll(PDO::FETCH_ASSOC));
		} else if ($type == 'single') {
			return $this->toJSON($this->statement->fetch(PDO::FETCH_ASSOC));
		}
	}

	public function toJSON($data)
	{
		$data = json_encode($data);
		return json_decode($data);
	}


}

?>
