<?php
namespace App\Manager;

require_once "Manager.php";

use Input;
use App\Manager;
use Barbet\Query\QueryBuilderHandler as Query;

class PostManager extends Manager
{
	public function store(Input $input)
	{
		$name = $input->name;
		$age = $input->age;
		$address = $input->address;

		$db = new Query();
		$db->table('students')
		   ->insert(['name' => $name,
					 'age' => $age,
					 'address' => $address
			     ]);

	}

	public function delete(Input $input)
	{
		$id = $input->id;

		$db = new Query();
		$db->table('students')
		   ->where('id', $id)
		   ->delete();
		
	}

}

?>