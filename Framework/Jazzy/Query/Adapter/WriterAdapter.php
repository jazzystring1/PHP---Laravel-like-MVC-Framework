<?php
namespace Barbet;

class WriterAdapter
{
	protected $writer = null;

	protected $fieldWriter = null;

	/**
	 *@param class $writer An Instance of QueryWriter
	 */

	public function __construct(QueryWriter $writer)
	{
		$this->writer = $writer;
	}

	public function setTable($table)
	{
		return $this->writer->setTable($table);
	}

	public function addBind($parts)
	{
		return $this->writer->addBind($parts);
	}

	public function Select($fields)
	{
		return $this->writer->writeSelectQuery($fields);
	}

	public function Insert($values)
	{
		return $this->writer->writeInsertQuery($values);
	}

	public function Update($fields)
	{
		return $this->writer->writeUpdateQuery($fields);
	}

	public function Delete($values)
	{
		return $this->writer->writeDeleteQuery($values);
	}

	public function Where($parts)
	{
		return $this->writer->writeWhereCondition($parts);
	}

	public function AndWhere($parts)
	{
		return $this->writer->writeAndWhere($parts);
	}

	public function OrWhere($parts)
	{
		return $this->writer->writeOrWhere($parts);
	}

	public function Join($table, $left, $operator, $right)
	{
		return $this->writer->writeJoin($table, $left, $operator, $right);
	}

	public function RightJoin($table, $left, $operator, $right)
	{
		return $this->writer->writeRightJoin($table, $left, $operator, $right);
	}

	public function LeftJoin($table, $left, $operator, $right)
	{
		return $this->writer->writeLeftJoin($table, $left, $operator, $right);
	}

	public function Limit($start_from, $limit)
	{
		return $this->writer->writeLimit($start_from, $limit);
	}

	public function Count()
	{
		return $this->writer->writeCount();
	}

	public function Order($column)
	{
		return $this->writer->writeOrderBy($column);
	}

	public function Asc()
	{
		return $this->writer->writeAsc();
	}

	public function Desc()
	{
		return $this->writer->writeDesc();
	}

	public function getBindings()
	{
		return $this->writer->getBindings();
	}

	public function getFinalQuery()
	{
		return $this->writer->getFinalQuery();
	}

	public function erase()
	{
		return $this->writer->erase();
	}

}

?>