<?php
namespace App\Manager;

require_once "Framework/Jazzy/Manager/Manager.php";

use Input;
use App\Manager;
use Barbet\Query\QueryBuilderHandler as Query;

class CarManager extends Manager
{
	public function index()
	{
		return show('car.index')->with(['welcome' => "Welcome to carwash", 'developers' => ['Daniel', 'David']]);
	}

	public function store(Input $input)
	{
		$name = $input->name;
		$color = $input->color;

		$db = new Query();
		$db->table('car')
		   ->insert(['name' => $name,
					 'color' => $color
				    ]);
		   
		return redirectshow('car')->with(['message' => "{$input->name} successfully added"]);
	}

	public function delete(Input $input)
	{
		$id = $input->id;

		$db = new Query();
		$db->table('car')
		   ->where('id', $id)
		   ->delete();

		return redirectshow('car')->with(['message' => '{$input->name} successfully deleted']);
	}

	public function update(Input $input)
	{
		$id = $input->id;
		$name = $input->name;

		$db = new Query();
		$db->table('car')
		   ->where('id', $id)
		   ->update(['name' => $name]);

		return redirectshow('car')->with(['message' => '{$input->name} successfully updated']);
	}
}

?>