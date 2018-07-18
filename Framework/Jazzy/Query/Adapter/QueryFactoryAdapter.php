<?php
namespace Barbet\Query;

class QueryFactoryAdapter
{
	protected $factory = null;

	public function __construct(QueryFactory $factory)
	{
		$this->factory = $factory;
	}

	public function query($query)
	{
		$this->factory->query($query);
	}

	public function bindValue($param, $val)
	{
		$this->factory->bindValue($param, $val);
	}

	public function execute() 
	{
	    $this->factory->execute();	
	}

	public function executeCurrentQuery()
	{
		return $this->factory->executeCurrentQuery();
	}

	public function fetch($type = null) 
	{
		return $this->factory->fetch($type);
	}

	
}


?>