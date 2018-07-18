<?php
namespace Barbet\Writer;

require_once dirname(__FILE__) . "\..\AdapterFactory.php";
require_once dirname(__FILE__) . "\..\Writer\SyntaxWriter.php";
require_once dirname(__FILE__) . "\..\Statements\Aggregate.php";

use Barbet\AdapterFactory;
use Barbet\SyntaxWriter;

class FieldWriter
{
    use Aggregate;

	protected $set = [];

	protected $placeholdedValues = null;

	protected $field = " * ";

	protected $seperator = ", ";

	protected $bindings = [];
	
	public function __construct()
	{
		$this->provider = new AdapterFactory();
		$this->formatter = $this->provider->createAdapter(new SyntaxWriter());
	}

	public function createField($fields)
	{
		$this->field = $this->formatter->seperateChar($this->seperator, $fields);
	}

	public function placeholdValues($fields)
	{
		//Original Array
		$this->values = $fields;

		if (is_array($fields)) {
			//Get all Keys
			$fields = array_keys($fields);
			//Set array keys a placeholder and returns string
			$this->placeholdedValues =  $this->formatter->writeplaceHolder($fields);
			//$this->values = $this->formatter->seperateChar($this->seperator, array_values($fields));
			foreach ($this->values as $k => $v) {
				//Create array for bindings
				array_push($this->bindings, $v);
			}
			return $this->wrap($this->placeholdedValues);
		}
	}

	public function createSetValues($fields)
	{		
		if (is_array($fields)) {
			foreach ($fields as $k => $v) {
				array_push($this->set, "{$k} = {$this->formatter->writeplaceHolder()}");
				array_push($this->bindings, $v);
			}
		}
	}

	public function getSet()
	{
		return $this->formatter->seperateChar($this->seperator, $this->set);
	}

	public function getField()
	{
		return $this->field;
	}

	public function getValues()
	{
		return $this->wrap($this->placeholdedValues);
	}

	public function getBindings()
	{
		return $this->bindings;
	}

	public function wrap($str)
	{
		return '(' . $str . ')';
	}

	public function createWrappedField($fields)
	{
		if (is_array($fields)) {
			$this->field = $this->wrap($this->formatter->seperateChar($this->seperator, array_keys($fields)));
		}
	}


}

?>