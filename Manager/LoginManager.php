<?php
namespace App\Manager;

require_once "Framework/Jazzy/Manager/Manager.php";

use App\Session;
use App\Input;
use App\Manager;
use Barbet\Query\QueryBuilderHandler as Query;

class LoginManager extends Manager
{
	public function index()
	{
		if(Session::has('username')) {
	   		 return redirectshow('dashboard');
	    }
	    return show('auth.login');
	}

	public function login(Input $input)
	{
		$username = $input->get('email');
		$password = $input->get('password');

		$result = Query::table('users')
				     ->select('username', 'password')
				     ->where('username', $username)
				     ->andWhere('password', $password)
				     ->grab();
				     
		if($result) {
			$_SESSION['username'] = $username;
			return redirectshow('dashboard');
		} 
		return redirectshow('/')->with(['autherror' => 'Invalid Username or Password']);
	}

	public function test($id, Input $input)
	{
		echo $id;
	}

}

?>