<?php
namespace App\Manager;

require_once "Framework/Jazzy/Manager/Manager.php";
require_once "Framework/Jazzy/Helper/Purefunctions.php";

use App\Session;
use App\Input;
use stdClass;
use App\Manager;
use Barbet\Query\QueryBuilderHandler as Query;

class EmployeeManager extends Manager
{
	public function __construct()
	{
		if(!Session::has('username')) {
			return redirectshow('');
		}
	}
	public function index()
	{
		return redirectshow('manage/employee/page/1');
	}

	public function show()
	{
		return show('employee.create-employee');
	}

	public function page($id)
	{
		$db = new Query();
		$start = ($id-1) * 10;
		$db->query("SELECT * FROM employee LIMIT {$start}, 10");
		$db->execute();
		$result = $db->fetch();
	    $links = $db->links('employee', 10);

		return show('employee.manage-employee')->with(['employees' => $result, 'links' => $links]);
	}

	public function profile($id)
	{
		$result = Query::table('employee')
				   ->where('id', $id)
				   ->grab();

		return show('employee.manage-employee')->with(['employees' => $result, 'notfound' => true]);
	}

	public function search($pattern)
	{
		$db = new Query();
		$db->query("SELECT * FROM employee WHERE first_name LIKE :pattern or middle_name LIKE :pattern or last_name LIKE :pattern or address LIKE :pattern or contact_number LIKE :pattern");
		$db->bindValue(":pattern", $pattern ."%");
		$db->execute();
		$result = $db->fetch();
		
		return show('employee.search-employee')->with(['employees' => $result]);	
	}


	public function create(Input $input)
	{
		Query::table('employee')
		   ->insert($input->all());

		return redirectshow('manage/employee/new')->with(['success' => 'true']);
	}

	public function update(Input $input)
	{
	    $input->except('id');
		Query::table('employee')
			 ->where('id', $input->get('id'))
			 ->update($input->except('id'));

		return redirectshow('manage/employee')->with(['message' => 'Data got successfully updated']);
	}

	public function delete(Input $input)
	{
		$id = $input->get('id');

		Query::table('employee')
		   ->where('id', $id)
		   ->delete();

		return redirectshow('manage/employee')->with(['message' => 'Data got successfully deleted']);
	}


	public function stats(Input $input)
	{
		$id = $input->get('id');

		echo json_encode(['graphTemplate' => '<tr><td colspan="8"><div id="chartContainer-'.$id.'"style="height : 400px;"></div></td></tr>']);
	}

	public function statsByYear(Input $input)
	{
		$id = $input->get('id');
		$year = $input->get('year');

		$db = new Query();
		$db->query("SELECT count(id) as y, DATE_FORMAT(date_time, '%b') as month FROM transaction WHERE YEAR(date_time) = :year and carwasher_id = :id and status = 'Done' GROUP BY month;");
		$db->bindValue(':year', $year);
		$db->bindValue(':id', $id);
		$db->execute();
		echo json_encode($db->fetch());
	}

	public function statsByTimeline(Input $input)
	{
		$id = $input->get('id');
		$start = date('Y-n-d', strtotime($input->get('start')));
		$end = date('Y-n-d', strtotime($input->get('end')));

		$db = new Query();
		$db->query("SELECT employee.id, employee.first_name, employee.middle_name, employee.last_name, COUNT(transaction.status) as count, DATE_FORMAT(transaction.date_time, '%e %b %Y') as date FROM employee INNER JOIN transaction on employee.id = transaction.carwasher_id WHERE transaction.status = 'Done' and DATE_FORMAT(transaction.date_time, '%Y-%c-%d') BETWEEN :start and :end and transaction.carwasher_id = :id GROUP BY date");
		$db->bindValue(':start', $start);
		$db->bindValue(':end', $end);
		$db->bindValue(':id', $id);
		$db->execute();
		$results = dates()->setDateFormat('j M Y')->fillMissingDates($start, $end, $db->fetch());
		echo json_encode($results);
	}


}

?>