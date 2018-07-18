<?php
require_once "Framework/Jazzy/Html/Html.php";
require_once "Framework/Jazzy/Date/Date.php";

use App\Html\Html;
use App\Directory\DirectorySystem;
use App\Date;

function esc($str)
{
	if (Html::isHtml($str)) {
		return htmlspecialchars($str, ENT_QUOTES, 'UTF-8', true);
	}
	return $str;
}

function route($route)
{
	
}

function asset($filename)
{
	$directory = new DirectorySystem();
	$resources = require('config/assets.php');
	return $directory->getFullPath() . $resources['assets'] . $filename;
}

function dates()
{
	return new Date();
}

?>