<?php
namespace Barbet;

trait Join
{
	protected $statements = [];

	protected $seperator = 'ON';

	public function buildJoin($table, $left, $operator, $right)
	{
		$this->statements[] = "{$this->innerJoinKeyword()} {$table} {$this->seperator} {$left} {$operator} {$right}";
	}

	public function buildLeftJoin($table, $left, $operator, $right)
	{
		$this->statements[] = "{$this->leftJoinKeyword()} {$table} {$this->seperator} {$left} {$operator} {$right}";
	}

	public function buildRightJoin($table, $left, $operator, $right)
	{
		$this->statements[] = "{$this->rightJoinKeyword()} {$table} {$this->seperator} {$left} {$operator} {$right}";
	}

	public function innerJoinKeyword()
	{
		return 'INNER JOIN';
	}

	public function leftJoinKeyword()
	{
		return 'LEFT JOIN';
	}

	public function rightJoinKeyword()
	{
		return 'RIGHT JOIN';
	}

	public function getJoins()
	{
		return implode(' ', $this->statements);
	}
}
?>