<?php
namespace Barbet;

trait Order
{
	protected $statement;

	protected $direction;

	public function buildOrder($column)
	{
		$this->statement = "{$this->orderKeyword()} {$column}";
	}

	public function buildAsc()
	{
		$this->direction = $this->ascKeyword();
	}

	public function buildDesc()
	{
		$this->direction = $this->descKeyword();
	}

	public function getOrder()
	{
		return $this->statement;
	}

	public function getDirection()
	{
		return $this->direction;
	}

	public function orderKeyword()
	{
		return 'ORDER BY';
	}

	public function ascKeyword()
	{
		return 'ASC';
	}

	public function descKeyword()
	{
		return 'DESC';
	}
}

?>