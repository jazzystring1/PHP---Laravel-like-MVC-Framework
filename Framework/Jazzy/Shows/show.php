<?php
session_start();

use App\Template\DavyCompiler;

class RedirectResponse
{
	protected $redirectshow;

	protected $compiler;

	public function __construct($route, $by_route = false)
	{		
		$this->redirectshow = $route;
		$this->compiler = new DavyCompiler();
		$this->compiler->setRouteMode($by_route);
		$this->compiler->setPath($route);
	}

	public function with($args)
	{
		foreach ($args as $k => $v) {
			$this->compiler->appendData($k, $v); 
		}
	} 

	public function __destruct()
	{
	 	 $this->compiler->compile();
	}
}

function show($filename)
{
	$directory = 'Resources/Show';

	$path = implode('/', explode('.', $filename));

	if(file_exists($file = 'Resources/Show/' . $path . ".php")) {
		return new RedirectResponse($file, false);
	} else {
		echo "<b>Show Error</b> : {$filename}.php don't exist."; 
	}
}


function redirectshow($route)
{
	return new RedirectResponse($route, true);
}


?>