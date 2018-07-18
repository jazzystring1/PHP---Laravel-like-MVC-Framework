<?php
namespace App;

class Template
{
	protected $var = array();

	protected $parent_dir = null;

	//protected $path = '/resources/';

	protected $by_route = false;

	protected $filename;

	const TAG_NAME = 'davy';

	public function __construct($filename, $parent_dir = null, $by_route = false)
	{
		$this->by_route = $by_route;
		$this->parent_dir = $parent_dir . "/";
		$this->filename = $filename;
	}

	public function set($session_key = null, $name, $data)
	{
		$this->var[$name] = $data;

		if($this->by_route == true) {
			$this->add_session($session_key, $name, $data);
		}
	}

	public function add_session($session_key, $name, $data)
	{
		$_SESSION[$session_key] = array($name => $data);
	}

	public function set_var($name, $data, $content)
	{
		return html_entity_decode(str_replace("{{$name}}", $data, $content));
	}

	public function get_tags($contents, $data)
	{
		$result = array();

		if(preg_match("/@if\($data\)+/", $contents)) {
			$result['if'] = true;
		}

		if(preg_match("/@isset\($data\)+/", $contents)) {
			$result['isset'] = true;
		}

		return $result;
	}

	public function unparse_tags($output)
	{
		$output = preg_replace("/@if\((.*)\)+/", "", $output);
		$output = preg_replace("/@endif+/", "", $output);
		$output = preg_replace("/@isset\((.*)\)+/", "", $output);
		$output = preg_replace("/@endisset+/", "", $output);
		$output = preg_replace("/{(.*)}/", "", $output);
		return $output;
	}

	public function parse_template($data, $output)
	{
		foreach ($data as $k => $v) {
			$result = $this->get_tags($output, $k);

			if (isset($result['if'])) {
				$output = $this->set_var($k, $v, $output);
			}

			if (isset($result['isset'])) {
				$output = $this->set_var($k, $v, $output);
			} 
		}
		return $output;
	}

	public function output()
	{
		$server_name = "http://" . $_SERVER['SERVER_NAME'] . "/";	

		$output = htmlentities(file_get_contents($server_name . $this->parent_dir . $this->filename));

		if($this->by_route == false) {
			if(isset($_SESSION[$_SESSION['CURRENT_ROUTE']])) {
				$output = $this->parse_template($_SESSION[$_SESSION['CURRENT_ROUTE']], $output);
				unset($_SESSION[$_SESSION['CURRENT_ROUTE']]);
			} else {
				$output = $this->parse_template($this->var, $output);
			}

		}

		if($this->by_route == true) {
			header("Location: " . $server_name . $this->parent_dir . $this->filename);
		}

		return html_entity_decode($this->unparse_tags($output));
	}




}
