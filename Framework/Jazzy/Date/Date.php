<?php
namespace App;

require_once "Framework/Jazzy/Helper/String.php";

use App\Helper\Str;

class Date
{
	protected $key = 'date';

	protected $format;

	protected $flag = ['mode' => 0, 'count' => 0];

	//Fill missing gaps between date range
	public function fillMissingDates($start, $end, $results)
	{
		$newResults = [];
		
		for($x = $this->stringToTime($start); $x <= $this->stringToTime($end); $x += 86400) {
			foreach($results as $result) {
				if ($result->{$this->key} == date($this->format, $x)) {
					$this->flag['mode'] = 1;
					$this->flag['count'] = $result->count;
				} 
			}
			
			if($this->flag['mode'] == 1) {
				$newResults[] = (object) ['count' => $this->flag['count'], 'date' => date($this->format, $x)];
			} else {
				$newResults[] = (object) ['count' => 0, 'date' => date($this->format, $x)];
			}
			$this->revertCounter();
		}

		return $newResults;
	}

	//Set date format for comparison
	public function setDateFormat($format)
	{
		$this->format = $format;
		return $this;
	}

	public function setFlag($key, $value)
	{
		$this->flag[$key] = $value;
		return $this;
	}

	//Convert string to time
	public function stringToTime($date)
	{
		return strtotime($date);
	}

	public function getStartDateWeek($year, $week)
	{
		if(Str::startsWith('W', $week)) {
			$week = Str::removeFirstChar($week);
			$week -= 1;
			return date($this->format, strtotime("Jan {$year} + {$week} Week"));
		} 
		$week -= 1;
		return date($this->format, strtotime("Jan {$year} + {$week} Week"));
	}

	public function getEndDateWeek($year, $week)
	{
		if(Str::startsWith('W', $week)) {
			$week = Str::removeFirstChar($week);
			return date($this->format, strtotime("Jan {$year} + {$week} Week") - 86400);
		} 
		return date($this->format, strtotime("Jan {$year} + {$week} Week") - 86400);
	}

	public function getWeekNumber($date)
	{
	    //Get the first day of the month.
	    $firstOfMonth = strtotime(date("Y-m-01", strtotime($date)));
	    //Apply above formula.
	    return $this->ordinal(intval(date("W", strtotime($date))) - intval(date("W", $firstOfMonth)));
	}

	public function ordinal($number) {
	    $ends = array('th','st','nd','rd','th', 'th', 'th', 'th', 'th', 'th', 'th', 'th', 'th', 'th', 'th');
	    if ((($number % 100) >= 11) && (($number%100) <= 13)) {
	        return $number. 'th';
	    } else {
	        return $number. $ends[$number % 10];
	    }
	}

	public function revertCounter()
	{
		$this->flag['mode'] = 0;
		$this->flag['count'] = 0;
	}
}


?>