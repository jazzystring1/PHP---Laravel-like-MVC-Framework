<?php
namespace Barbet\Writer;
require_once dirname(__FILE__) . "\..\AdapterFactory.php";
require_once dirname(__FILE__) . "\..\Writer\SyntaxWriter.php";
require_once dirname(__FILE__) . "\..\Exceptions.php";

use Barbet\AdapterFactory;
use Barbet\SyntaxWriter;
use Barbet\BarbetExceptions;

class ConditionWriter
{
	const OPERATOR = [
		'IS EQUAL TO' => '=',
		'GREATER THAN' => '>',
		'LESS THAN' => '<',
		'GREATER THAN OR EQUAL' => '>=',
		'LESS THAN OR EQUAL' => '<=',
		'NOT EQUAL' => '!='
	];

	const CONJUNCTION = [
		'AND',
		'OR'
	];

	protected $seperator = null;

	protected $conjunction  = null;

	protected $conditions = [];

	protected $helper = null;

	protected $bindings = [];

	public function __construct()
	{
		$this->provider = new AdapterFactory();
		$this->helper = $this->provider->createAdapter(new SyntaxWriter());
	}

	public function createCondition($parts)
	{
		$this->bindings = [];
		try {
			$this->seperator = "WHERE";
			if (sizeof($parts) == 3) {
				if (!in_array($parts[1], self::OPERATOR)) {
					throw new BarbetExceptions("BarbetQueryBuilder Error : Invalid Operator Used");
				} 
				array_push($this->conditions, "{$this->conjunction} {$parts[0]} {$parts[1]} {$this->helper->writeplaceHolder($parts[2])}");
				array_push($this->bindings, $parts[2]);
			} else {
				array_push($this->conditions, "{$this->conjunction} {$parts[0]} " . self::OPERATOR['IS EQUAL TO'] . " {$this->helper->writeplaceHolder($parts[1])}");
				array_push($this->bindings, $parts[1]);
			}

	    } catch (BarbetExceptions $e) {
				echo $e->getMessage();
	 	}
	}

	public function setConjunction($conjunction)
	{
		if ($this->checkConjunction($conjunction)) {
			$this->conjunction = $conjunction;
		}
	}

	public function checkConjunction($conjunction)
	{
		try {
			if(isset($conjunction)) {
				if (in_array(trim($conjunction), self::CONJUNCTION)) {
					return true;
				}
					throw new BarbetExceptions('BarbetQueryBuilder Error : Invalid Conjunction Used');
			} 
			return false;
		} catch (BarbetExceptions $e) {
			echo $e->getMessage();
		}
	}

	public function getConditions()
	{
		return $this->helper->ArrayToText($this->conditions);
	}

	public function getSeperator()
	{
		return $this->seperator;
	}

	public function getBindings()
	{
		return $this->bindings;
	}

	public function erase()
	{
	    return $this->conditions = [];
	}

}
?>