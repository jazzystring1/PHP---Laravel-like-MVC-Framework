<?php
namespace App\Manager;

require_once "Framework/Jazzy/Manager/Manager.php";
require_once "Framework/Jazzy/Session/Session.php";

use App\Session;
use App\Input;
use App\Manager;
use Barbet\Query\QueryBuilderHandler as Query;

class HomeManager extends Manager
{

	public function index()
	{
		if(Session::has('username')) {
			return show('home.index');
		}
		return redirectshow('');
	}

	public function logout()
	{
		Session::destroy();
		return redirectshow('');
	}

}

?>