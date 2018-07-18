<?php
namespace Barbet;

use PDO;

class DB 
{
	private $setcon;

	private $error;

	public function __construct() 
	{
		$db = require('config/database.php');

		$config = 'mysql:host=' . $db['configuration']['host'] . ';dbname=' . $db['configuration']['database'] . ';charset=utf8';

		try 
		{
			$this->setcon = new PDO($config, $db['configuration']['username'], $db['configuration']['password'], $db['options']);
		} 
		catch (PDOException $e) 
		{
			$this->error = $e->getMessage();
		}

	}

	public function getPDO() 
	{
		return $this->setcon;
	}

}

?>