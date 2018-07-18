<?php
namespace Barbet;

require_once dirname(__FILE__) . "\..\AdapterFactory.php";
require_once dirname(__FILE__) . "\..\Exceptions.php";
require_once dirname(__FILE__) . "\..\Writer\SyntaxWriter.php";
require_once dirname(__FILE__) . "\..\Writer\BaseWriter.php";
require_once dirname(__FILE__) . "\ArguementInterface.php";
require_once dirname(__FILE__) . "\..\Statements\Join.php";
require_once dirname(__FILE__) . "\..\Statements\Order.php";

use Barbet\ArguementInterface;
use Barbet\AdapterFactory;
use Barbet\BarbetExceptions;
use Barbet\BaseWriter;
use Barbet\SyntaxWriter;

class QueryWriter extends AdapterFactory implements ArguementInterface
{
	use Join;
	use Order;

	protected $query;

	protected $action = "SELECT";

	protected $forBind = [];

	protected $helper;

	protected $writer = [];

	protected $limit;

	public function __construct(Writer\FieldWriter $fieldWriter, Writer\ConditionWriter $conditionWriter)
	{
		$this->writer = ["field" => $fieldWriter, "condition" => $conditionWriter];
		$this->helper = $this->createAdapter(new SyntaxWriter());
	}

	public function setTable($table)
	{
		$this->table = $table;
	}

	public function createBind($parts)
	{
		//If the action is update, it is expected that the current forbind value is for conditions and it should be in the last portion of array
		if ($this->action == "UPDATE") {
			$this->forBind = array_merge($parts, $this->forBind);
			return true;
		}

		//Storing "Value" for Binding Values and it's key will serve as our bind paramater or placeholder. This is responsible for compiling all the bind values and return it to Querybuilderhandler
		foreach ($parts as $k => $v) {
			$this->forBind[] = $v;
		}

	}

	public function writeSelectQuery($fields)
	{
		//Since simple select query is just imploding fields
		$this->writer['field']->createField($fields);
	} 

	public function writeDeleteQuery($fields)
	{
		$this->action = "DELETE";
	}

	public function writeInsertQuery($fields)
	{
		try {
			if (!is_array($fields)) {
				throw new BarbetExceptions("Parameters must be an array");
			}
			$this->action = "INSERT";
		    $this->writer['field']->createWrappedField($fields);
		    $this->writer['field']->placeholdValues($fields);
		    $this->createBind($this->writer['field']->getBindings());
		} catch (BarbetExceptions $e) {
			echo $e->getMessage();
		}
	}

	public function writeUpdateQuery($fields)
	{
		try {
			if (!is_array($fields)) {
				throw new BarbetExceptions("Parameters must be an array");
			}
			$this->action = "UPDATE";
		    $this->writer['field']->createSetValues($fields);
			$this->createBind($this->writer['field']->getBindings());
		} catch (BarbetExceptions $e) {
			echo $e->getMessage();
		}
	}

	public function writeWhereCondition($parts)
	{
		try 
		{
			if ($this->checkArguement($parts)) {
				throw new BarbetExceptions("Invalid number of parameters");
			} else {
				$this->writer['condition']->createCondition($parts);
				$this->createBind($this->writer['condition']->getBindings());
			}

		} catch (BarbetExceptions $e) {
			echo $e->getMessage();
		}

	}

	public function writeAndWhere($parts)
	{
	    $this->writer['condition']->setConjunction(' AND ');
	    $this->writer['condition']->createCondition($parts);
		$this->createBind($this->writer['condition']->getBindings());
		return $parts;
	}

	public function writeOrWhere($parts)
	{
	    $this->writer['condition']->setConjunction(' OR ');
	    $this->writer['condition']->createCondition($parts);
		$this->createBind($this->writer['condition']->getBindings());
		return $parts;
	}

	public function writeCount()
	{
		$this->writer['field']->buildCount();
	}

	public function writeJoin($table, $left, $operator, $right)
	{
		$this->buildJoin($table, $left, $operator, $right);
	}

	public function writeLeftJoin($table, $left, $operator, $right)
	{
		$this->buildLeftJoin($table, $left, $operator, $right);
	}

	public function writeRightJoin($table, $left, $operator, $right)
	{
		$this->buildRightJoin($table, $left, $operator, $right);
	}

	public function writeLimit($limit)
	{
		$this->limit = $limit;
	}

	public function writeOrderBy($column)
	{
		$this->buildOrder($column);
	}

	public function writeAsc()
	{
		$this->buildAsc();
	}

	public function writeDesc()
	{
		$this->buildDesc();
	}

	public function getBindings()
	{
		return $this->forBind;
	}

	public function finalizeQuery($action = null)
	{
		switch(trim($action)) {
			case 'SELECT': 
				return "{$this->action} {$this->writer['field']->getField()} FROM `{$this->table}` {$this->getJoins()} {$this->writer['condition']->getSeperator()} {$this->writer['condition']->getConditions()} {$this->getOrder()} {$this->getDirection()}";
				break;
			case 'INSERT':
				return "{$this->action} INTO {$this->table} {$this->writer['field']->getField()} VALUES {$this->writer['field']->getValues()}";
				break;
			case 'UPDATE':
				return "{$this->action} `{$this->table}` SET {$this->writer['field']->getSet()} {$this->writer['condition']->getSeperator()} {$this->writer['condition']->getConditions()}";
				break;
			case 'DELETE':
				return "{$this->action} FROM {$this->table} {$this->writer['condition']->getSeperator()} {$this->writer['condition']->getConditions()}";
				break;
			default:
				echo "{$action} is not a valid action";
				break;
		}
	}

	public function getFinalQuery()
	{
		return $this->finalizeQuery($this->action);
	}

	public function checkArguement($parts)
	{
		$length = sizeof($parts);

		if ($length > 3 | $length < 2) {
			return true;
		}

		return false;
	}
	
}

?>