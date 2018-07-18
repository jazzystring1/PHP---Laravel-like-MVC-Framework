<?php
namespace App;

require_once "\..\Query\Connection\db.php";
require_once "\..\Query\QueryBuilder\QueryBuilderHandler.php";

use ReflectionClass;
use Barbet\Query\QueryBuilderHandler as Query;

class Manager 
{

	public function middleware()
	{
		echo "Middleware!!";
	}

}

?>