<?php
namespace Barbet\Query;

require_once dirname(__FILE__) . "\..\AdapterFactory.php";
require_once dirname(__FILE__) . "\QueryFactory.php";
require_once dirname(__FILE__) . "\..\Writer\QueryWriter.php";
require_once dirname(__FILE__) . "\..\Writer\FieldWriter.php";
require_once dirname(__FILE__) . "\..\Writer\ConditionWriter.php";

use Barbet\Writer\ConditionWriter;
use Barbet\Writer\FieldWriter;
use Barbet\AdapterFactory;
use Barbet\QueryWriter;
use Barbet\Query\QueryFactory;

class QueryBuilderHandler extends AdapterFactory
{

	private static $factory;	//Instance of Factory Adapter

	private static $writer;

	private $bindings;	//Check for binding values

	private $fetch_mode = null; // Fetch all is the default mode

	public function __construct() 
	{
		$this->factory = $this->createAdapter(new QueryFactory());
	}

	public function __destruct()
	{
		unset($this->bindings);
	}

	public function checkBind()
	{
		if (isset($this->bindings)) 
		{
			foreach ($this->bindings as $k => $v)
			{
				$this->factory->bindValue($k + 1, $v);
			}
		}
	}

	public static function table($table) 
	{
		self::$writer = self::createAdapter(new QueryWriter(new FieldWriter(), new ConditionWriter));
		self::$writer->setTable($table);
		return new static;
	}

	public function select(...$fields) 
	{
		self::$writer->Select($fields);
		return $this;
	}

	public function insert($fields) 
	{
		self::$writer->Insert($fields);
		$this->save();
		return $this;
	}

	public function update($fields)
	{
		self::$writer->Update($fields);
		$this->save();
		return $this;
	}

	public function delete(...$fields)
	{
		self::$writer->Delete($fields);
		$this->save();
		return $this;
	}

	public function where(...$data) 
	{
	    self::$writer->Where($data);
		return $this;
		
	}

	public function andWhere(...$data)
	{
		self::$writer->AndWhere($data);
		return $this;
	}

	public function orWhere(...$data)
	{
		self::$writer->OrWhere($data);
		return $this;
	}

	public function join($table, $left, $operator, $right)
	{
		self::$writer->Join($table, $left, $operator, $right);
		return $this;
	}

	public function rightJoin($table, $left, $operator, $right)
	{
		self::$writer->RightJoin($table, $left, $operator, $right);
		return $this;
	}

	public function leftJoin($table, $left, $operator, $right)
	{
		self::$writer->LeftJoin($table, $left, $operator, $right);
		return $this;
	}

	public function paginate($start, $end) 
	{
		self::$writer->Limit($start, $end);
		return $this;
	}

	public function offset($offset) 
	{
		self::$writer->Offset($offset);
		return $this;
	}

	public function count() 
	{
		self::$writer->Count();
		return $this;
	}

	public function orderBy($column)
	{
		self::$writer->Order($column);
		return $this;
	}

	public function desc()
	{
		self::$writer->Desc();
		return $this;
	}

	public function asc()
	{
		self::$writer->Asc();
		return $this;
	}

	public function printQuery()
	{
		echo self::$writer->getFinalQuery();
	}

	public function grab() 
	{
	    return $this->executeQuery(self::$writer->getFinalQuery(), 'fetch');
	}

	public function save() 
	{
	    $this->executeQuery(self::$writer->getFinalQuery());
	    return $this;
	}

	public function single()
	{
		$this->fetch_mode = 'single';
		return $this->executeQuery(self::$writer->getFinalQuery(), 'fetch');
	}

	public function executeQuery($query, $type = 'unfetch')
	{
		$this->bindings = self::$writer->getBindings();
		$this->factory->query($query);
		$this->checkBind();
		$this->factory->execute();
		if ($type == 'fetch') {
			return $this->factory->fetch($this->fetch_mode);
		}
	}
	
	// For raw queries
	public function query($query)
	{
		return $this->factory->query($query);
	}

	public function bindValue($param, $val)
	{
		return $this->factory->bindValue($param, $val);
	}

	public function execute()
	{
	    return $this->factory->execute();
	}

	public function fetch($type = null)
	{
		return $this->factory->fetch($type);
	}

	public function links($table, $limit)
	{
		$this->query("SELECT COUNT(id) as count FROM $table");
		$this->execute();
		$row = $this->fetch($this->fetch_mode);
		$total_records = $row[0]->count;
		$total_pages = ceil($total_records / $limit);
		$link = '<ul class="pagination m-3"><li class="page-item"><a class="page-link" href="http://localhost/MVC/manage/'.$table.'/page/1">Previous</a></li>';
		for ($page = 1; $page <= $total_pages; $page++) {
			$link .= '<li class="page-item"><a class="page-link" href="http://localhost/MVC/manage/'.$table."/page/".$page.'">'.$page.'</a></li>';
		}
		$link .= '<li class="page-item"><a class="page-link" href="http://localhost/MVC/manage/'.$table."/page/2".'">Next</a></li></ul>';
		return $link;
	}

}
