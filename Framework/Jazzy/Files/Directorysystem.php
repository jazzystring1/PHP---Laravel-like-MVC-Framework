<?php
namespace App\Directory;

class DirectorySystem
{	
	
	/**
	 * Get the compile directory for templates
	 *
	 *@return string
	 *
	**/
	public function getCompileDirectory()
	{
		return 'Storage/shows/';
	}

	/**
	 * Get the url script directory
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
	 * Get the current url server name
	 * Ex : http://SERVER_NAME/
	 *
	 *@return string
	 *
	**/
	public function getServerName()
	{
		return $server_name = "http://" . $_SERVER['SERVER_NAME'] . "/";	
	}

	/**
	 * Get the url's server name and the directory if it exist for redirect route
	 * Ex : http://SERVER_NAME/DIRECTORY/
	 *
	 *@return string
	 *
	**/
	public function getFullPath()
	{
		return $this->getServerName() . $this->getScriptDirectory();
	}
}

?>