<?php
namespace Barbet\Writer;

trait Aggregate
{
	protected $statement = [];

	public function buildCount()
	{
		$this->statement[] = "COUNT(ID) as total";
	}

	public function getCount()
	{
		return implode(" ", $this->statement);
	}

}
?>