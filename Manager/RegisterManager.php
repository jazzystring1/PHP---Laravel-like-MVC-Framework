<?php
namespace App\Manager;

require_once "Framework/Jazzy/Manager/Manager.php";

use App\Input;
use App\Manager;
use Barbet\Query\QueryBuilderHandler as Query;

class RegisterManager extends Manager
{
	public function index()
	{
		return show('auth.register');
	}

	public function register(Input $input)
	{
		$username = $input->get('username');
		$password = $input->get('password');
		$repassword = $input->get('repassword');

		if($password == $repassword) {
			Query::table('users')
				 ->insert(['username' => $username, 'password' => $password]);
				 
			session_start();
			$session = new Session();
			$session->set('username', $username);
			return redirectshow('dashboard');
		}

		return redirectshow('register');
		
	}

}

?>