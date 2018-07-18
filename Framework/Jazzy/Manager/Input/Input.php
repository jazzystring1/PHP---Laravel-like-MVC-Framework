<?php
namespace App;

class Input
{
	protected $values = [];

	public function __construct()
	{
		$this->collectData();
	}

	public function collectData()
	{
		foreach ($_POST as $k => $v) {
			if(is_array($v)) {
				$this->values[$k] = $v;
				continue;
			}
			$this->values[$k] = htmlspecialchars($v);
		}
	}

	public function get($key)
	{
		return isset($this->values[$key]) ? $this->values[$key] : false;
	}

	public function all()
	{
		return $this->values;
	}

	public function except(...$keys)
	{
		$keys = $this->values;

		foreach ($keys as $v) {
			unset($keys[$v]);
		}
		
		return $keys;
	}

	public function trimall($data)
	{
		return preg_replace('/[^A-Za-z0-9\-\ñ]/', '', $data);
	}

	public function trim($data)
	{
		return trim($data, ' ');
	}

	public function spaceToHyphen($data)
	{
		return preg_replace('/ /', '-', $data);
	}

	public function spaceToUnderscore($data)
	{
		return preg_replace('/ /', '_', $data);
	}
	
	
}

?>