<?php
namespace App;

require_once "\..\Query\Connection\db.php";
require_once "\..\Query\QueryBuilder\QueryBuilderHandler.php";
require_once "\..\Shows\show.php";
require_once "ManagerValidatorInterface.php";

use App\ManagerValidatorInterface;
use ReflectionClass;
use Exception;

class ManagerValidator implements ManagerValidatorInterface
{
	protected $managerPath;

	protected $namespaceTemplate;

	protected $namespaceMethod;

	protected $routeParam = [];

	protected $dependencies = [];

	public function __construct($manager_path = null)
	{
		if ($manager_path != null) {
			return $this->managerPath = $manager_path;
		}
		return $this->managerPath = 'Manager/';
	}

	public function setNamespace($namespace)
	{
		$this->namespaceTemplate = $namespace;
	}

	public function validateManager($name, $action)
	{
		if (file_exists($file = "{$this->managerPath}{$name}.php")) {
			require_once ($file);
			$this->setFullNamespace($name);
			$this->validateMethod($action); //validate if method exists

			if ($this->dependencies) {
				call_user_func_array($this->getFullNamespacewithMethod($action), $this->dependencies);
			} else {
				call_user_func($this->getFullNamespacewithMethod($action));
			}

		} else {
			echo "<b>Manager Error</b> : {$name} class don't exist.";
		}

		
	}

	public function validateMethod($action)
	{
		if (method_exists($this->createNamespaceInstance(), $action)) {	
			//Create a reflection class object 
			$reflect = new ReflectionClass($this->getFullNamespace());	

			//Get all the methods via the getMethods()
			foreach ($reflect->getMethods() as $k => $method) {	
				//We will examine the {$action} class name if it exists
				if($method->name == $action) {
					//We will loop all through the parameters found in the method named {$action}
					foreach($method->getParameters() as $num => $parameter) {
						//Check if the current parameter index has type-hinted class then continue to the next loop
						if(isset($parameter->getClass()->name)) {
							$dependency = $parameter->getClass()->name;
							array_push($this->dependencies, new $dependency);
							continue;	
						}
						//Check if the parameter exists in routeParam property
						if(isset($this->routeParam[$parameter->name])) {
						    array_push($this->dependencies, $this->routeParam[$parameter->name]);
						    continue;
						}
						//If no type hinted class found or parameter found in routeParam, throw an exception
						throw new Exception("Parameter '{$parameter->name}' don't exist in {$this->getFullNamespace()}\\$action() method");
						
					}
				}
			}
	    } else {
			echo "<b>Manager Error</b> : {$action}() function don't exist in class {$this->getFullNamespace()}.";
		}
	}

	public function setRouteParam($placeholder, $param)
	{
		$this->routeParam[$placeholder] = $param;
	}

	public function setFullNamespace($name)
	{
		return $this->namespaceTemplate = $this->getDefaultNamespace() . $name;
	}

	public function getFullNamespace()
	{
		return $this->namespaceTemplate;
	}

	public function getFullNamespacewithMethod($method)
	{
	    return  $this->namespaceTemplate."::{$method}";
	}

	public function getDefaultNamespace()
	{
		return 'App\\Manager\\';
	}

	public function createNamespaceInstance()
	{
		$obj = $this->getFullNamespace();
		return new $obj;
	}


}



?>