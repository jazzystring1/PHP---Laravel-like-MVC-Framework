<?php
namespace App\Template;
session_start();

require "Framework/Jazzy/Helper/String.php";
require "Framework/Jazzy/Helper/Purefunctions.php";

use Exception;
use App\Helper\Str;

class DavyCompiler
{
	protected $firstCase = true;

	protected $datum = [];

	protected $path = null;

	protected $cacheDirectory = 'Storage/shows/';

	protected $by_route = false;

	/**
	 * Get file contents
	 * 
	 *@param string $file
	 *
	 *@return string
	 *
	**/
	public function getContent($file)
	{		
		return file_get_contents($file);
	}

	public function isExpired($file)
	{
		if(!$this->exists($this->compileDirectory($file)))
		{
			return true;
		}

		return $this->lastModified($file) >= $this->lastModified($this->compileDirectory($file));
	}

	public function exists($file)
	{
		return file_exists($file);
	}

	public function lastModified($file)
	{
		return filemtime($file);
	}

	public function hashTemplate($file)
	{
		return sha1($file);
	}

	/**
	 * Hash the include file that is responsible for holding variables for the template 
	 * 
	 *@param string $file
	 *
	 *@return string
	 *
	**/
	public function hashTemplateIncludeFile($file)
	{
		return sha1($file) . sha1("inc");
	}

	/**
	 * Check if template's cache file exist
	 * 
	 *@param string $file
	 *
	 *@return string|bool
	 *
	**/
	public function checkCacheFile($path)
	{

		$cache_path = $this->compileDirectory($path);					
		
		if(file_exists($cache_path)) {
			return $cache_path;
		}
			return false;
	}

	/**
	 * Convert show path to cache path
	 * 
	 *@param string $file
	 *
	 *@return string
	 *
	**/
	public function compileDirectory($path)	
	{
		return $this->cacheDirectory . $this->hashTemplate($path). ".php";
	}

	public function compileDirectoryforInclude($path)
	{
		return $this->cacheDirectory . $this->hashTemplateIncludeFile($path). ".php";
	}

	/**
	 * Get the script directory
	 * 
	 *@return string
	 *
	**/
	public function getScriptDirectory()
	{
		$parent = explode('/', $_SERVER['SCRIPT_NAME']);
		return sizeof($parent) > 2 ? $parent[1] . "/"  : false;
	}

	/**
	 * Get the current server name
	 * Ex : http://SERVER_NAME/
	 *@return string
	 *
	**/
	public function getServerName()
	{
		return $server_name = "http://" . $_SERVER['SERVER_NAME'] . "/";	
	}

	/**
	 * Get the server name and the directory if it exist for redirect route
	 * Ex : http://SERVER_NAME/DIRECTORY/
	 *@return string
	 *
	**/
	public function getFullPath()
	{
		return $this->getServerName() . $this->getScriptDirectory();
	}

	/**
	 * Compile the current template
	 * 
	 *@return string
	 *
	**/
	public function compile()
	{
		if ($this->by_route == true) {
			header("Location: " . $this->getFullPath() . $this->path);
		}
		
		//Get the content of the file
		$content = $this->getContent($this->getPath());

		//This serves for two purposes. One is for compiling statements into valid php code for writing 
		//and one is for rendering expressions to add functionality for rending/reading cache file
		$content = $this->compileStatements($content);
		
		//Initialize Data(w/value or null) for rendering cache file
		foreach($this->datum as $k => $v) {
			${$k} = $v;
		}

		echo "<br>";
		print_r($this->datum);

		//Check if cache file exist				
		if (!$this->isExpired($this->getPath())) {
			include ($this->compileDirectory($this->getPath()));
		} else {
			$content = $this->renderEchoStatements($content);
			if(file_put_contents($file = $this->compileDirectory($this->getPath()), $content, LOCK_EX))
			{
				include ($file);
			} 
				return false;
		}
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
	   	    return $this->addSession($k, $v);
		}

		$this->datum[$k] = $v;
	}

	/**
	 * Append data passed by user to the session for temporary messages
	 * 
	 *@param string $name
	 *@param string $data
	 *
	 *@return string
	 *
	**/
	public function addSession($name, $data)
	{
		$_SESSION[$name] = $data;

	}

	/**
	 * Get flushed session data by the user
	 *
	 *@return string
	 *
	**/
	public function getflushedSessionData()
	{
		return isset($_SESSION[$_SESSION['CURRENT_ROUTE']]) ? $_SESSION[$_SESSION['CURRENT_ROUTE']] : false;
	}

	/**
	 * Unset flushed session data
	 * 
	 *@return string
	 *
	**/
	public function unsetflushedSessionData()
	{
		 unset($_SESSION[$_SESSION['CURRENT_ROUTE']]);
	}
	
	/**
	 * Rendering {{xxx}} statements to echo statements
	 * 
	 *@param string $content
	 *
	 *@return string
	 *
	**/
	public function renderEchoStatements($content)
	{
		return preg_replace_callback('/{{(.*)}}/', function ($matches) {
				return $this->compileEcho($matches[1]);
		}, $content);
	}
	
	public function compileStatements($content)
	{
	    return preg_replace_callback('/\B@(@?\w+(?:::\w+)?)([ \t]*)(\( ( (?>[^()]+) | (?3) )* \))?/x',
			 function ($match) {
                return $this->compileStatement($match);
             }, $content);
	}

	public function compileStatement($match)
	{
		print_r($match);
		if(method_exists($this, $compiler = 'compile'.ucfirst($match[1]))) {
			//If the given statement is non param reserved word or the first portion 
			//of the string is 'end', directly call the appropriate compiler function
			if(Str::isNonParamReserve($match[1]) || Str::firstStringPortion('end', $match[1])) {
				return $this->{$compiler}();
			}
			//Compile the presence of data(null or not null)
			$this->compileDataPresence($this->removeSymbol($match[4]));

			return $this->{$compiler}($match[4]);
		} else {
			throw new Exception("Invalid statement");
		} 
	}

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

	public function renderTemplateInclude($content)
	{
		$content = $this->compileInclude($this->hashTemplateIncludeFile($this->getPath())) . $content;
		return $content;
	}

	public function compileForeach($expression)
	{
		return "<?php foreach({$expression}) : ?>";
	}
	
	public function compileEndforeach()
	{
		return "<?php endforeach;  ?>";
	}

	public function compileSwitch($expression)
	{
		$this->firstCase = true;
		return "<?php switch({$expression}) : ";
	}

	public function compileCase($expression)
	{
		if($this->firstCase) {
			$this->firstCase = false;
			return "case {$expression} : ?>";
		}
		return "<?php case {$expression} : ?>";
	}

	public function compileEndswitch()
	{
		return "<?php endswitch;  ?>";
	}

	public function compileBreak()
	{
		return "<?php break;  ?>";
	}

	public function compileContinue()
	{
		return "<?php continue;  ?>";
	}

	public function compileDefault()
	{
		return "<?php default :   ?>";
	}
	
	public function compileEcho($expression)
	{
		return !empty($expression) ? "<?php echo(esc({$expression})); ?>" : "<?php echo(''); ?>";
	}

	public function compileInclude($filename)
	{
		return "<?php include({$filename}); ?> \n";
	}

	public function compilePhp($expression)
	{
		return "<?php {$expression} \n";
	}

	public function compileEndphp()
	{
		return "?> \n";
	}

	public function compileIsset($expression)
	{
		return "<?php if(isset({$expression})) : ?> \n";
	}

	public function compileEndisset()
	{
		return "<?php endif; ?> \n";
	}

	public function compileWhile($expression)
	{
		return "<?php while({$expression}) : ?>";
	}

	public function compileEndwhile()
	{
		return "<?php endwhile; ?>";
	}

	public function compileFor($expression)
	{
		return "<?php for({$expression}) : ?> \n";
	}

	public function compileEndfor()
	{
	    return "<?php endfor; ?> \n";
	}

	public function compileIf($expression)
	{
		return "<?php if ({$expression}) :  ?>";
	}

	public function compileElse()
	{
		return "<?php else: ?>";
	}

	public function compileDisplay()
	{
		return "<?php display(); ?>";
	}

	public function compileEndif()
	{
		return "<?php endif; ?>";
	}

}

$x = new DavyCompiler();
$x->setPath('test.php');
$x->setRouteMode(false);
$x->compile();



?>