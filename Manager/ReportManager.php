<?php
namespace App\Manager;

require_once "Framework/Jazzy/Manager/Manager.php";
require_once "Framework/Jazzy/Helper/Purefunctions.php";
require_once "Framework/Jazzy/Helper/String.php";

use App\Session;
use App\Input;
use App\Manager;
use App\Helper\Str;
use Barbet\Query\QueryBuilderHandler as Query;

class ReportManager extends Manager
{
	public function __construct()
	{
		if(!Session::has('username')) {
			return redirectshow('');
		}
	}

	//Get today's employees statistics
	public function statisticsDaily($page)
	{
		$db = new Query();
		$start = ($page-1) * 10;
		$db->query("SELECT employee.id, employee.first_name, employee.middle_name, employee.last_name, COUNT(transaction.status) as count, WEEK(transaction.date_time, 1) as week_number, YEAR(CURDATE()) as year FROM employee INNER JOIN transaction on employee.id = transaction.carwasher_id WHERE transaction.status = 'Done' and DAY(transaction.date_time) = DAY(CURDATE()) GROUP BY (employee.first_name) ORDER BY employee.last_name  LIMIT {$start}, 10");
		$db->execute();
		$result = $db->fetch();
	    $links = $db->links('transaction', 10);

	    //Get the start date and end date of the given year and week number
	    $timeline = $result ? ['start' => dates()->setDateFormat('F d,Y')->getStartDateWeek($result[0]->year, $result[0]->week_number),
	    			 'end' => dates()->setDateFormat('F d,Y')->getEndDateWeek($result[0]->year, $result[0]->week_number)] : null;
	    

		return show('report.statistics-report')->with(['statistics' => $result, 'links' => $links, 'header' => 'Daily Statistics Report(Today)', 'weekTitle' => 'Current Week Statistics Report', 'byTimeline' => $timeline, 'graphType' => 'showWeekTimeline']);
	}

	public function statisticsYear($year, $page)
	{
		$db = new Query();
		$start = ($page-1) * 10;
		$db->query("SELECT employee.id, employee.first_name, employee.middle_name, employee.last_name, COUNT(transaction.status) as count FROM employee INNER JOIN transaction on employee.id = transaction.carwasher_id WHERE transaction.status = 'Done' and YEAR(transaction.date_time) = :year GROUP BY (employee.first_name) ORDER BY employee.last_name LIMIT {$start}, 10");
		$db->bindValue(':year', $year);
		$db->execute();
		$result = $db->fetch();
	    $links = $db->links('transaction', 10);

		return show('report.statistics-report')->with(['statistics' => $result, 'links' => $links, 'header' => "{$year} Statitistics Report", 'byYear' => $year, 'graphType' => 'showGraph']);
	}

	public function statisticsWeek($week, $page)
	{
		$month = date('F', strtotime($week));
		$year = date('Y', strtotime($week));
		$weekNumber = date('W', strtotime($week));

		$db = new Query();
		$start = ($page-1) * 10;
		$db->query("SELECT employee.id, employee.first_name, employee.middle_name, employee.last_name, COUNT(transaction.status) as count, WEEK(transaction.date_time, 1) as week_number, YEAR(CURDATE()) as year, transaction.date_time FROM employee INNER JOIN transaction on employee.id = transaction.carwasher_id WHERE transaction.status = 'Done' and WEEK(transaction.date_time, 1) = :week and YEAR(transaction.date_time) = :year GROUP BY (employee.first_name) ORDER BY employee.last_name  LIMIT {$start}, 10");
		$db->bindValue(':week', $weekNumber);
		$db->bindValue(':year', $year);
		$db->execute();
		$result = $db->fetch();
	    $links = $db->links('transaction', 10);

	    //Get the start date and end date of the given year and week number
	    $timeline = $result ? ['start' => dates()->setDateFormat('F d,Y')->getStartDateWeek($year, $weekNumber),
	    			 'end' => dates()->setDateFormat('F d,Y')->getEndDateWeek($year, $weekNumber)] : null;
	    $weekTitle = "{$month} " . dates()->getWeekNumber($week) . " Week Statistics Report";

		return show('report.statistics-report')->with(['statistics' => $result, 'links' => $links, 'header' => $weekTitle, 'weekTitle' => $weekTitle, 'byTimeline' => $timeline, 'graphType' => 'showWeekTimeline']);
	}

	public function statisticsDateRange($first, $second, $page)
	{
		$db = new Query();
		$start = ($page-1) * 10;
		$db->query("SELECT employee.id, employee.first_name, employee.middle_name, employee.last_name, COUNT(transaction.status) as count FROM employee INNER JOIN transaction on employee.id = transaction.carwasher_id WHERE transaction.status = 'Done' and DATE_FORMAT(date_time, '%Y-%m-%d') BETWEEN :first and :second or DATE_FORMAT(date_time, '%Y-%m-%d') BETWEEN :second and :first GROUP BY employee.first_name ORDER BY employee.last_name LIMIT {$start}, 10");
		$db->bindValue(':first', $first);
		$db->bindValue(':second', $second);
		$db->execute();
		$result = $db->fetch();
	    $links = $db->links('transaction', 10);

	    $timeline = ['start' => date("F d,Y", strtotime($first)),
	    			 'end' => date("F d,Y", strtotime($second))];

		return show('report.statistics-report')->with(['statistics' => $result, 
													   'links' => $links,
													   'header' => "{$timeline['start']} - {$timeline['end']} ",
													   'graphType' => 'showTimeline', 
													    'byTimeline' => $timeline]);
	}

	public function statisticsMonth($month, $year, $page)
	{
		$db = new Query();
		$start = ($page-1) * 10;
		$db->query("SELECT employee.id, employee.first_name, employee.middle_name, employee.last_name, COUNT(transaction.status) as count FROM employee INNER JOIN transaction on employee.id = transaction.carwasher_id WHERE transaction.status = 'Done' and DATE_FORMAT(transaction.date_time, '%M') = :month and YEAR(transaction.date_time) = :year GROUP BY (employee.first_name) ORDER BY employee.last_name LIMIT {$start}, 10");
		$db->bindValue(':month', $month);
		$db->bindValue(':year', $year);
		$db->execute();
		$result = $db->fetch();
	    $links = $db->links('transaction', 10);

	    $timeline = ['start' => "{$month} 01, {$year}",
	    			 'end' => "{$month} ".date('t', strtotime("{$month} {$year}")).", {$year}"];

		return show('report.statistics-report')->with(['statistics' => $result, 
													   'links' => $links,
													   'header' => "{$month} {$year} Statistics Report",
													   'graphType' => 'showMonthTimeline', 
													    'byTimeline' => $timeline
													 ]);

	}


	public function salaryPage($page)
	{
		$db = new Query();
		$start = ($page-1) * 10;
		$db->query("SELECT employee.first_name, employ4ee.middle_name, employee.last_name, transaction.id,  transaction.status, DATE_FORMAT(transaction.date_time, '%M, %d, %Y %l:%i %p') as date_time, transaction.vehicle_id FROM employee INNER JOIN transaction ON employee.id = transaction.carwasher_id ORDER BY date_time ASC LIMIT {$start}, 10");
		$db->execute();
		$result = $db->fetch();
	    $links = $db->links('transaction', 10);

		return show('report.salary-report');
	}

	public function salaryDateRange($first, $second, $page)
	{

	}

	public function salaryMonth($month, $year)
	{

	}

	public function salaryYear($year)
	{
		
	}

	public function salesPage($page)
	{
		$db = new Query();
		$start = ($page-1) * 10;
		$db->query("SELECT package.id, package.package_type, SUM(package.prize) as income FROM package INNER JOIN transaction on package.id = transaction.package_id WHERE DAY(transaction.date_time) = DAY(CURDATE()) GROUP BY package.package_type LIMIT {$start}, 10");
		$db->execute();
		$result = $db->fetch();
		$db->query("SELECT id, package_type, prize = 0 as income FROM package WHERE id NOT IN (SELECT package_id FROM transaction WHERE DAY(date_time) = CURDATE()) GROUP BY package_type  ");
		$db->execute();
		$secondResults = $db->fetch();
		for($x = 0; $x <= sizeof($result) - 1; $x++) {
			foreach($secondResults as $k => $secondResult) {
				if($secondResult->package_type == $result[$x]->package_type) {
					unset($secondResults[$k]);
				} 
			}
		}

		foreach($secondResults as $secondResult) {
			$result[] = $secondResult;
		}

	    $links = $db->links('transaction', 10);

		return show('report.sales-report')->with(['sales' => $result, 'links' => $links, 'graphType' => 'showWeekTimeline']);
	}

	public function salesDateRange($first, $second, $page)
	{
		$db = new Query();
		$start = ($page-1) * 10;
		$db->query("SELECT package.id, package.package_type, SUM(package.prize) as income FROM package INNER JOIN transaction on package.id = transaction.package_id WHERE DATE_FORMAT(transaction.date_time, '%Y-%m-%d') BETWEEN :first and :second GROUP BY package.package_type LIMIT {$start}, 10");
		$db->bindValue(':first', $first);
		$db->bindValue(':second', $second);
		$db->execute();
		$result = $db->fetch();
		$db->query("SELECT id, package_type, prize = 0 as income FROM package WHERE id NOT IN (SELECT package_id FROM transaction WHERE DATE_FORMAT(transaction.date_time, '%Y-%m-%d') BETWEEN :first and :second) GROUP BY package_type  ");
		$db->bindValue(':first', $first);
		$db->bindValue(':second', $second);
		$db->execute();
		$secondResults = $db->fetch();
		for($x = 0; $x <= sizeof($result) - 1; $x++) {
			foreach($secondResults as $k => $secondResult) {
				if($secondResult->package_type == $result[$x]->package_type) {
					unset($secondResults[$k]);
				} 
			}
		}

		foreach($secondResults as $secondResult) {
			$result[] = $secondResult;
		}
	    $links = $db->links('transaction', 10);

	    $timeline = ['start' => date("F d,Y", strtotime($first)),
	    			 'end' => date("F d,Y", strtotime($second))];

		return show('report.sales-report')->with(['sales' => $result, 'links' => $links, 'header' => "{$timeline['start']} - {$timeline['end']} Sales Report", 'graphType' => 'showTimeline', 'byTimeline' => $timeline]);
	}

	public function salesMonth($month, $year)
	{

	}

	public function salesYear($year)
	{

	}

}

?>