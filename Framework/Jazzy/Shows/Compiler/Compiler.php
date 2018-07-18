<?php
namespace App\Template;

require_once "CompilerInterface.php";
require_once "\..\..\Files\Filesystem.php";
require_once "\..\..\Files\Directorysystem.php";
require_once "\..\..\Session\Session.php";
require_once "\..\..\Encryption\Hash.php";

use App\File\Filesystem;
use App\Directory\Directorysystem;
use App\Session;
use App\Hash;
use App\CompilerInterface;

abstract class Compiler implements CompilerInterface
{
	protected $file;

	protected $directory;

	protected $session;

	protected $encryption;

	public function __construct() 
	{
		$this->file = new Filesystem();
		$this->directory = new Directorysystem();
		$this->session = new Session();
		$this->encryption = new Hash();
	}

	/**
	 * Check if the current template exists in the compiled directory. If not, then we will consider
	 * it as expired. If the current template modification time is greater than the compiled template, 
	 * the current template should be recompile.
	 *
	 *@param string $file
	 *
	 *@return string
	 *
	**/
	public function isExpired($file)
	{
		if(!$this->file->exists($this->compileDirectory($file)))
		{
			return true;
		}

		return $this->file->lastModified($file) >= $this->file->lastModified($this->compileDirectory($file));
	}

	/**
	 * Convert show directory to compile directory
	 * 
	 *@param string $path
	 *
	 *@return string
	 *
	**/
	public function compileDirectory($path)	
	{
		return $this->directory->getCompileDirectory() . $this->hashTemplate($path). ".php";
	}

	/**
	 * Convert show directory to compile directory FOR INCLUDE files
	 * 
	 *@param string $file
	 *
	 *@return string
	 *
	**/
	public function compileDirectoryforInclude($file)
	{
		return $this->directory->getCompileDirectory() . $this->hashTemplateIncludeFile($file). ".php";
	}

	/**
	 * Hash template filename
	 * 
	 *@param string $file
	 *
	 *@return string
	 *
	**/
	public function hashTemplate($file)
	{
		return $this->encryption->sha1_hash($file);
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
		return $this->encryption->sha1_hash($file) . $this->encryption->sha1_hash("inc");
	}
}

?>