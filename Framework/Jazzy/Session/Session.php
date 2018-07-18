<?php
namespace App;

class Session
{
	/**
	 * Add session data
	 * 
	 *@param string $session_key
	 *@param string $data
	 *
	 *@return string
	 *
	**/
	public function set($session_key, $value, $key = null)
	{
		if ($key != null ) {
			return $_SESSION[$session_key] = array($key => $value);
		}

		$_SESSION[$session_key] = $value;
	}

	/**
	 * Get session data 
	 *
	 *@return string
	 *
	**/
	public function get($data)
	{
		return isset($_SESSION[$data]) ? $_SESSION[$data] : false;
	}

	/**
	 * Check if session data exists 
	 *
	 *@return string
	 *
	**/
	public function has($data)
	{
		return isset($_SESSION[$data]) ? true : false;
	}

	/**
	 * Unset session data
	 * 
	 *@return string
	 *
	**/
	public function flush($data)
	{
		 unset($_SESSION[$data]);
	}

	public function destroy()
	{
		return session_destroy();
	}

	public function regenerate()
	{
		return session_regenerate_id();
	}

}

?>