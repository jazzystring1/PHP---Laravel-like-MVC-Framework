<?php
namespace App\Html;

class Html
{
	public static function isHtml($str)
	{
		return ($str != htmlentities($str)) ? true : false;
	}
}

?>