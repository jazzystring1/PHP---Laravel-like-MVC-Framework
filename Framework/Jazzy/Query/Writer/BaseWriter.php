<?php
namespace Barbet;

class BaseWriter
{
	public function renderDefaultAction()
	{
		return 'SELECT ';
	}

	public function renderInsert() 
	{
		return 'INSERT ';
	}

	public function renderDelete() 
	{
		return 'DELETE ';
	}

	public function renderUpdate() 
	{
		return 'UPDATE ';
	}

	public function renderWhere()
	{
		return 'WHERE ';
	}
}
?>