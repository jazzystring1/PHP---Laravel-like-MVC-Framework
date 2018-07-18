<?php
namespace App\Manager;

require_once "Framework/Jazzy/Manager/Manager.php";

use App\Session;
use App\Input;
use App\Manager;
use Barbet\Query\QueryBuilderHandler as Query;

class TransactionManager extends Manager
{
	public function __construct()
	{
		if(!Session::has('username')) {
			return redirectshow('');
		}
	}

	public function index()
	{
		return redirectshow('manage/transaction/page/1');
	}

	public function redirectSearch()
	{
		return show('transaction.search-transaction')->with(['transactions' => []]);
	}

	public function show()
	{
		$packages = Query::table('package')->select('DISTINCT package_type')->grab();
		$carwashers = Query::table('employee')->grab();

		return show('transaction.create-transaction')->with(['packages' => $packages, 'carwashers' => $carwashers]);
	}

	public function page($id)
	{
		$db = new Query();
		$start = ($id-1) * 10;
		$db->query("SELECT employee.first_name, employee.middle_name, employee.last_name, transaction.id,  transaction.status, DATE_FORMAT(transaction.date_time, '%M, %d, %Y %l:%i %p') as date_time, transaction.vehicle_id FROM employee INNER JOIN transaction ON employee.id = transaction.carwasher_id ORDER BY date_time DESC LIMIT {$start}, 10");
		$db->execute();
		$result = $db->fetch();
	    $links = $db->links('transaction', 10);

		if($result) {
			return show('transaction.manage-transaction')->with(['transactions' => $result, 'links' => $links]);
		}
		return show('transaction.manage-transaction');
	}

	public function profile($id)
	{
		$db = new Query();

		$db->query('SELECT DISTINCT employee.first_name, employee.middle_name, employee.last_name, transaction.id, transaction.status, transaction.date_time, transaction.vehicle_id FROM employee INNER JOIN transaction ON employee.id = transaction.carwasher_id WHERE vehicle_id = :vehicle_id ORDER BY date_time DESC;');
		$db->bindValue(':vehicle_id', $id);
		$db->execute();
		$result = $db->fetch();

		return show('transaction.manage-transaction')->with(['transactions' => $result, 'notfound' => $result]);
	}

	public function search($pattern)
	{
		$db = new Query();
		$db->query("SELECT DISTINCT employee.first_name, employee.middle_name, employee.last_name, transaction.id, transaction.status, DATE_FORMAT(transaction.date_time, '%M, %d, %Y %l:%i %p') as date_time, transaction.vehicle_id FROM employee INNER JOIN transaction ON employee.id = transaction.carwasher_id WHERE employee.first_name LIKE :pattern or employee.middle_name LIKE :pattern or employee.last_name LIKE :pattern or transaction.status LIKE :pattern or DATE_FORMAT(transaction.date_time, '%M, %d, %Y %l:%i %p') LIKE :pattern ORDER BY date_time DESC");
		$db->bindValue(":pattern", $pattern ."%");
		$db->execute();
		$result = $db->fetch();
		
		return show('transaction.search-transaction')->with(['transactions' => $result]);
	}

	public function done(Input $input)
	{
		$id = $input->get('id');

		Query::table('transaction')
		   ->where('id', $id)
		   ->update(['status' => 'Done']);

		return redirectshow('manage/transaction')->with(['success' => true]);
	}

	public function create(Input $input)
	{
		//Replace Middle Name to Initial and remove whitespaces.
		echo $input->spaceToHyphen($input->trim($input->get('plate_number')));

		$vehicle = Query::table('vehicle')
						->select('id', 'size')
						->where('plate_number', $input->spaceToHyphen($input->trim($input->get('plate_number'))))
						->single();

		if(!$vehicle) {
			return redirectshow('manage/transaction/new')->with(['plate_number_exists' => true]);
		}

		$package = Query::table('package')
						 ->select('id')
						 ->where('package_type', $input->get('package_type'))
						 ->andWhere('size', $vehicle->size)
						 ->single();

        $employee = Query::table('employee')
        			   ->select('id')
        			   ->where("CONCAT(replace(first_name, ' ', ''),replace(LEFT(middle_name, 1), ' ', ''), replace(last_name, ' ', ''))", $input->trimall($input->get('carwasher_name')))
        			   ->single();

        Query::table('transaction')
           ->insert(['vehicle_id' => $vehicle->id,
           	  		  'package_id' => $package->id,
           	  		  'carwasher_id' => $employee->id,
           	  		  'status' => 'Ongoing']);
        
   		return redirectshow('manage/transaction/new')->with(['status' => true]);

	}


}

?>