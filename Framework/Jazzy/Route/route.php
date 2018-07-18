<?php
namespace App\Routing;

require_once "Framework\Jazzy\Route\RouteException.php";
require_once "Framework\Jazzy\Manager\Input\Input.php";
require_once "Framework\Jazzy\Manager\ManagerValidator.php";
require_once "Framework\Jazzy\Helper\String.php";

use App\ManagerValidator;
use Test\RouteException;
use ReflectionClass;
use App\Helper\Str;

class Route
{
	protected static $url;

	protected $routes = [];

	protected static $namedRoute = [];

	const FILTER = '/\\';

	public static function set($path, $callback)
	{
		$url = self::get_url();

		$requestUrl = self::sanitizeRoute($path);

		self::$url[] = $requestUrl;
		
		if ($requestUrl == $url) {
			$_SESSION['CURRENT_ROUTE'] = $requestUrl;

			if (is_callable($callback)) {
				self::invokeMethod($callback);
			}
			
			if (is_string($callback)) {
				$parts = explode('@', $callback);
				$manager = array('name' => $parts[0], 'action' => $parts[1]);

				if (sizeof($parts) > 2) {
					throw new RouteException('Route Error : Syntax should be : "NameofManager@method"');			//Need to be revised
				} 

				$m = new ManagerValidator();
				$m->validateManager($manager['name'], $manager['action']);
				return true;
			}
		}
	}

	public static function get($path, $callback)
	{
		$urlParts =  array('nonParamUrl' => array(), 'param' => array(), 'placeholdedParam' => array());

		$url = explode('/', self::get_url());

		$requestUrl = explode('/', self::sanitizeRoute($path));

		if(sizeof($url) == sizeof($requestUrl)) {
			foreach ($requestUrl as $k => $v) {
				if(!preg_match('/^{(.*)}/', $v)) {
					array_push($urlParts['nonParamUrl'], $v);
				} else {

					array_push($urlParts['placeholdedParam'], $v); //The placeholded parameters such as {id}
					array_push($urlParts['param'], $url[$k]);	//The parameters 
					array_push($urlParts['nonParamUrl'], "");	//The base url without parameters;
				}
			}

			foreach (array_count_values($urlParts['placeholdedParam']) as $k => $v) {	//If there is duplicated parameter name, it will throw exception
				if ($v == 2) {
					throw new RouteException('Syntax Error : Parameters should not be duplicated');
				}
			}

			for ($x = 0; $x <= sizeof($url) - 1; $x++) {
				if ($urlParts['nonParamUrl'][$x] == null) {
					$url[$x] = null;
				}

				if($url[$x] == $urlParts['nonParamUrl'][$x]) {
					$url[$x] = $urlParts['nonParamUrl'][$x];
				} else {
					return false;
				}		
			}

			if ($url === $urlParts['nonParamUrl']) {

				if(is_callable($callback)) {
					return self::invokeMethod($callback, $urlParts['param']);
				}

				$parts = explode('@', $callback);
				$manager = array('name' => $parts[0], 'action' => $parts[1]);

				if (sizeof($parts) > 2) {
					throw new RouteException('Syntax should be : "NameofManager@method"');			//Need to be revised
				} 

				$m = new ManagerValidator();
				//For each placeholded paramters such as {id} and its corresponding value,
				//It should be placed into the manager validor routeParam property for reflection purposes
				foreach($urlParts['placeholdedParam'] as $k => $v) {
					$m->setRouteParam(Str::removeBothSides($v, 1), $urlParts['param'][$k]);
				}
				$m->validateManager($manager['name'], $manager['action']);

			}
		}

	}

	public static function post($path, $callback)
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$url = self::get_url();
			$requestUrl = self::sanitizeRoute($path);

			self::$url[] = $requestUrl;
			
			if ($requestUrl == $url) {
				if (!is_callable($callback)) {
					$parts = explode('@', $callback);
					$manager = array('name' => $parts[0], 'action' => $parts[1]);

					if (sizeof($parts) > 2) {
						throw new RouteException('Syntax should be : "NameofManager@method"');			//Need to be revised
					} 

					$m = new ManagerValidator();
					$m->validateManager($manager['name'], $manager['action']);
							
				}
			}
		}
	}

	public function get_url()
	{
		return isset($_REQUEST['url']) ? rtrim($_REQUEST['url'], self::FILTER) : '/';
	}

	public function sanitizeRoute($path)
	{
		if ($path != '/') {
			$requestUrl = filter_var(trim($path, self::FILTER), FILTER_SANITIZE_URL);
		} else {
			$requestUrl = '/';
		}

		return $requestUrl;
	}

	public static function invokeMethod($callback, $param = null)
	{
		if (is_array($param) & $param != null) {
			if (is_callable($callback)) {
				call_user_func_array($callback, $param);
			}
			return true;
		} 

		if (is_callable($callback)) {
			echo call_user_func($callback);
		}
			
	}


}


?>