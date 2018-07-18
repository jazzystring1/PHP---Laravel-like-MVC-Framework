<?php
namespace App\Template;

require_once "Compiler.php";
require_once "Statements/CompileCondition.php";
require_once "Statements/CompileLoop.php";
require_once "Statements/compileExpression.php";
require_once "Statements/CompileInclude.php";
require_once "Statements/CompileEcho.php";
require_once "Statements/CompilePhp.php";
require_once "/../../Helper/String.php";
require_once "/../../Helper/Purefunctions.php";

use Exception;
use App\Helper\Str;
use Compile;

class DavyCompiler extends Compiler
{
	use Compile\CompileCondition;
	use Compile\CompileLoop;
	use Compile\CompileExpression;
	use Compile\CompileInclude;
	use Compile\CompileEcho;
	use Compile\CompilePhp;

	protected $datum = [];

	protected $unescapedTags = ['{{', '}}'];

	protected $escapedTags = ['{!', '!}'];

	protected $path = null;

	protected $by_route = false;


	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Compile the current template
	 * 
	 *@return bool
	 *
	**/
	public function compile()
	{
		//If the path is route, then redirect.
		if ($this->by_route == true) {
			return header("Location: " . $this->directory->getFullPath() . $this->path);
		}

		//Get the content of the file
		$content = $this->file->get($this->getPath());

		//This serves for two purposes. One is for compiling statements into valid php code for writing 
		//and one is for rendering expressions to add functionality for rending/reading cache file
		$content = $this->compileStatements($content);
		
		//Initialize Data(w/value or null) for rendering cache file
		foreach($this->datum as $k => $v) {
			${$k} = $v;
		}

		if($session = $this->getSessionData()) {
			foreach($session as $k => $v) {
				${$k} = $v;
			}
		}

		//Check if cache file exist				
		if (!$this->isExpired($this->getPath())) {
			include ($this->compileDirectory($this->getPath()));
		} else {
			$content = $this->renderEchoStatements($content);
			$content = $this->compilePhpblocks($content);
			if(file_put_contents($file = $this->compileDirectory($this->getPath()), $content, LOCK_EX))
			{
				include ($file);
			} 
		}
		$this->flushSessionData();
	}

	/**
	 * Set the route mode. If true, it is referencing to route else referencing to file
	 * 
	 *@param bool $by_route
	 *
	 *@return string
	 *
	**/
	public function setRouteMode($by_route)
	{
		$this->by_route = $by_route;
	}

	/**
	 * Set file path
	 * 
	 *@param string $path
	 *
	 *@return string
	 *
	**/
	public function setPath($path)
	{
		$this->path = $path;
	}

	/**
	 * Get file path
	 *
	 *@return string
	 *
	**/
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * Append data to datum variable if the route mode is false else append to the session
	 * 
	 *@param string $k
	 *@param string $v
	 *
	 *@return string
	 *
	**/
	public function appendData($k, $v)
	{
		if($this->by_route == true) {
	   	    return $this->session->set($this->path, $v, $k);
		}

		$this->datum[$k] = $v;
	}

	/**
	 * Get flashed session data by the user
	 *
	 *@return string
	 *
	**/
	public function getSessionData()
	{
		return $this->session->get($_SESSION['CURRENT_ROUTE']);
	}

	/**
	 * Unset flashed session data
	 * 
	 *@return bool
	 *
	**/
	public function flushSessionData()
	{
		return $this->session->flush($_SESSION['CURRENT_ROUTE']);
	}
	
	/**
	 * Rendering {{WOOPS}} statements to echo statements escapedly
	 * 
	 *@param string $content
	 *
	 *@return string
	 *
	**/
	public function renderEchoStatements($content)
	{
		return preg_replace_callback("/{{(.*)}}/", function ($matches) {		
				if (!Str::startsWith('!', $matches[1]) & !Str::endsWith('!', $matches[1])) {
					return $this->compileEcho($matches[1]);
				} elseif (Str::firstStringPortion('!!', $matches[1]) & Str::lastStringPortion('!!', $matches[1])){
					return $this->compileUnescapedEcho(Str::removeBothSides($matches[1], 2));
				} 
				return $this->compileUntouchedEcho(Str::removeBothSides($matches[1], 1));
			}, $content);
	}

	/**
	 * Compile '@' statements
	 * 
	 *@param string $content
	 *
	 *@return string
	 *
	**/
	public function compileStatements($content)
	{
	    return preg_replace_callback('/\B@(@?\w+(?:::\w+)?)([ \t]*)(\( ( (?>[^()]+) | (?3) )* \))?/x',
			 function ($match) {
                return $this->compileStatement($match);
             }, $content);
	}

	/**
	 * Compile a single @ statement
	 * 
	 *@param string $content
	 *
	 *@return string
	 *
	**/
	public function compileStatement($match)
	{
		if(method_exists($this, $compiler = 'compile'.ucfirst($match[1]))) {
			//If the given statement is non param reserved word or the first portion 
			//of the string is 'end', directly call the appropriate compiler function
			if(Str::isNonParamReserve($match[1]) || Str::firstStringPortion('end', $match[1])) {
				return $this->{$compiler}();
			}
			//Compile the presence of data(null or not null)
			$this->compileDataPresence($this->removeSymbol($match[4]));

			return $this->{$compiler}($match[4]);
		}
		//Just return the same @ statement if it is an invalid statement
		return $match[0];
	}

	/**
	 * Initializing the integrity of data presence 
	 * 
	 *@param string $content
	 *
	 *@return string
	 *
	**/
	public function compileDataPresence($match)
	{
		//If the given expression contains 'as', it is expected that it is a foreach statement and the 
		//data to be rendered should be casted to array;
		if($key = $this->stripKeyValuePair($match)) {
			if(!isset($this->datum[$key])) {
				$this->datum[$key] = [];
			}
		} elseif(!isset($this->datum[$match])) {
		//If the parameters is not listed on datum, set it to null
			$this->datum[$match] = null;
		} 
	}

	public function compilePHPblocks($content)
	{
		$content = preg_replace_callback('/@php(.*)@endphp/', function ($matches) {
				return $this->compilePhp($matches[1]);
		}, $content);
		return preg_replace('/@endphp/', $this->compileEndphp(), $content);
	}

	/**
	 * Strip Key/Value pair leaving the original variable
	 * 
	 *@param string $content
	 *
	 *@return string|bool
	 *
	**/
	public function stripKeyValuePair($content)
	{
		return Str::Has(' as ', $content) ? Str::removeStringPortion(' as', $content) : false;
	}

	/**
	 * Strip the dollar sign
	 * 
	 *@param string $content
	 *
	 *@return string
	 *
	**/
	public function removeSymbol($str)
	{
		return Str::startsWith('$', $str) ?  Str::removeFirstChar($str) : false;
	}

}

?>