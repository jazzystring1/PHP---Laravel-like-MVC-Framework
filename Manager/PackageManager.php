<?php
namespace App\Manager;

require_once "Framework/Jazzy/Manager/Manager.php";

use App\Session;
use App\Input;
use App\Manager;
use Barbet\Query\QueryBuilderHandler as Query;

class PackageManager extends Manager
{
	public function __construct()
	{
		if(!Session::has('username')) {
			return redirectshow('');
		}
	}
	public function index()
	{
		return show('package.manage-package');
	}

	public function show()
	{
		return show('package.create-package');
	}

	public function create(Input $input)
	{
		foreach($input->get('prize') as $size => $prize) {
			Query::table('package')
				 ->insert(['package_type' => $input->get('package_name'),
				 		   'prize' => $prize,
				 		   'size' => $size]);
		}
		return redirectshow('manage/package/new')->with(['success' => true]);
	}

	public function delete(Input $input)
	{
	
	}

	public function update(Input $input)
	{
		
	}

	public function get(Input $input)
	{
	
	}

	public function statsByTimeline(Input $input)
	{
		$id = $input->get('id');
		$start = date('Y-n-d', strtotime($input->get('start')));
		$end = date('Y-n-d', strtotime($input->get('end')));
	
		$db = new Query();
		$db->query("SELECT package.id, package.package_type, SUM(package.prize) as income, DATE_FORMAT(transaction.date_time, '%e %b %Y') as date FROM package INNER JOIN transaction on package.id = transaction.package_id WHERE DATE_FORMAT(transaction.date_time, '%Y-%c-%d') BETWEEN :first and :second GROUP BY package.package_type, date");
		$db->bindValue(':first', $start);
		$db->bindValue(':second', $end);
		$db->execute();
		print_r($db->fetch());
	}



}

?>