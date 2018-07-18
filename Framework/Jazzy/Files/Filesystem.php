<?php
namespace App\File;

class Filesystem
{
	public function get($file)
	{		
		return file_get_contents($file);
	}

	public function exists($file)
	{
		return file_exists($file);
	}

	public function lastModified($file)
	{
		return filemtime($file);
	}

	public function create($file, $content)
	{
		return file_put_contents($file, $content, LOCK_EX);
	}

	public function rewrite($file, $content)
	{

		if($this->exists("MVC/Storage/shows/{$file}")) {
			$file = fopen($file, 'W');
			fwrite($file, $content);
			return fclose($file);
		}
		return $this->create($file, $content);
	}
	
	public function inherit($file)
	{
		return include($file);
	}
}

?>