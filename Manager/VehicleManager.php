<?php
namespace App\Manager;

require_once "Framework/Jazzy/Manager/Manager.php";

use App\Session;
use App\Input;
use App\Manager;
use Barbet\Query\QueryBuilderHandler as Query;

class VehicleManager extends Manager
{
	public function __construct()
	{
		if(!Session::has('username')) {
			return redirectshow('');
		}
	}
	public function index()
	{
		return redirectshow('manage/vehicle/page/1');
	}

	public function page($id)
	{
		$db = new Query();
		$start = ($id-1) * 10;
		$db->query("SELECT vehicle.id, vehicle.customer_id, vehicle.model_name, vehicle.size, vehicle.plate_number, vehicle.cr_number, vehicle_info.type FROM vehicle INNER JOIN vehicle_info ON vehicle.vehicle_type_id = vehicle_info.id LIMIT {$start}, 10");
		$db->execute();
		$result = $db->fetch();
	    $links = $db->links('vehicle', 10);

		return show('vehicle.manage-vehicle')->with(['vehicles' => $result, 'links' => $links]);
	}

	public function profile($id)
	{
		$db = new Query();
		$start = ($id-1) * 10;
		$db->query("SELECT vehicle.id, vehicle.customer_id, vehicle.model_name, vehicle.size, vehicle.plate_number, vehicle.cr_number, vehicle_info.type FROM vehicle INNER JOIN vehicle_info ON vehicle.vehicle_type_id = vehicle_info.id WHERE vehicle.id = :id");
	    $db->bindValue(':id', $id);
		$db->execute();
		$result = $db->fetch();
	    $links = $db->links('vehicle', 10);

		return show('vehicle.manage-vehicle')->with(['vehicles' => $result, 'links' => $links, 'notfound' => $result]);
	}

	public function search($pattern)
	{
		$db = new Query();
		$db->query("SELECT vehicle.id, vehicle.customer_id, vehicle.model_name, vehicle.size, vehicle.plate_number, vehicle.cr_number, vehicle_info.type FROM vehicle INNER JOIN vehicle_info ON vehicle.vehicle_type_id = vehicle_info.id  WHERE vehicle.customer_id LIKE :pattern or vehicle.vehicle_type_id LIKE :pattern  or vehicle.model_name LIKE :pattern or vehicle.size LIKE :pattern or vehicle.plate_number LIKE :pattern or vehicle.cr_number LIKE :pattern");
		$db->bindValue(":pattern", $pattern ."%");
		$db->execute();
		$result = $db->fetch();
		
		return show('vehicle.search-vehicle')->with(['vehicles' => $result]);
	}

	public function check(Input $input)
	{
		$plate_number = $input->get('plate_number');
		$cr_number = $input->get('cr_number');

		if($plate_number) {
			$result = Query::table('vehicle')->where('plate_number', strtoupper($input->spaceToHyphen($input->trim($plate_number))))->single();

			if($result) {
				echo json_encode(['plate_number_exist' => "true"]);
				return true;
			} else {
				echo json_encode(['plate_number_exist' => "false"]);
				return true;
			}
		}

		if($cr_number) {
			$result = Query::table('vehicle')->where('cr_number', strtoupper($input->trim($cr_number)))->single();
			
			if($result) {
				echo json_encode(['cr_number_exist' => "true"]);
				return true;
			} else {
				echo json_encode(['cr_number_exist' => "false"]);
				return true;
			}
		}
	}

	public function show()
	{
		$customers = Query::table('customer')->grab();
		$vehicle_type = Query::table('vehicle_info')->grab();

		return show('vehicle.create-vehicle')->with(['types' => $vehicle_type,
													 'customers' => $customers, 
													]);
		}

	public function create(Input $input)
	{
		$data = array('customer_name' => $input->trimall($input->get('customer_name')), 'vehicle_type' => $input->get('vehicle_type'));

		if (preg_match('/[^a-zA-Z\s\-\d]/', $input->get('plate_number'))) {
			return redirectshow('manage/vehicle/new')->with(['plate_number_error' => true]);
		}

		if (preg_match('/[^a-zA-Z\s\d]/', $input->get('model_name'))) {
			return redirectshow('manage/vehicle/new')->with(['model_name_error' => true]);
		}

		if(ctype_alpha($input->get('cr_number'))) {
			return redirectshow('manage/vehicle/new')->with(['cr_number_error' => true]);
		}

		if(Query::table('vehicle')->select('plate_number')->where('plate_number', $input->spaceToHyphen($input->trim($input->get('plate_number'))))->single()) {
			return redirectshow('manage/vehicle/new')->with(['plate_number_exists' => true]);
		}
		echo $data['customer_name'];
		//Replace Middle Name to Initial and remove whitespaces.
		$customer = Query::table('customer')
						 ->select('id')
						 ->where("CONCAT(replace(first_name, ' ', ''),replace(LEFT(middle_name, 1), ' ', ''), replace(last_name, ' ', ''))", $data['customer_name'])
						 ->single();

		$vehicle_type = Query::table('vehicle_info')
					         ->select('id')
						     ->where('type', $data['vehicle_type'])
						     ->single();

		$plate_number = strtoupper($input->spaceToHyphen($input->trim($input->get('plate_number'))));

        Query::table('vehicle')
             ->insert(['customer_id' => $customer->id,
        			  'vehicle_type_id' => $vehicle_type->id,
        			  'model_name' => $input->get('model_name'),
        			  'size' => $input->get('vehicle_size'),
        			  'plate_number' => $plate_number,
        			  'cr_number' => $input->get('cr_number')]);

   		 return redirectshow('manage/vehicle/new')->with(['status' => true]);
	}

	public function delete(Input $input)
	{
		$id = $input->get('id');

		Query::table('vehicle')
			 ->where('id', $id)
			 ->delete();

		return redirectshow('manage/vehicle')->with(['status' => true]);
	}

	public function update(Input $input)
	{
		$vehicle_type = Query::table('vehicle_info')
							 ->select('id')
							 ->where('type', $input->get('vehicle_type'))
							 ->single();

		Query::table('vehicle')
		     ->where('id', $input->get('id'))
		     ->update(['model_name' =>  $input->get('model_name'),
		     		   'vehicle_type_id' => $vehicle_type->id,
		     		   'size' => $input->get('vehicle_size'),
		     		    'plate_number' => $input->get('plate_number'),
		     		    'cr_number' => $input->get('cr_number')]);

		return redirectshow('manage/vehicle')->with(['message' => 'Data got successfully updated']);
	}

	public function get(Input $input)
	{
		$id = $input->get('id');

		$result = Query::table('vehicle')
				       ->select('vehicle.id','vehicle.customer_id','vehicle.model_name','vehicle.size','vehicle.plate_number','vehicle.cr_number','vehicle_info.type','transaction.status','customer.first_name','customer.middle_name','customer.last_name','customer.contact_number')
		               ->join('vehicle_info','vehicle.vehicle_type_id','=','vehicle_info.id')
		               ->join('customer', 'vehicle.customer_id','=','customer.id')
		               ->join('transaction', 'vehicle.id','=','transaction.vehicle_id')
		               ->single();
	    if($result) {
		echo json_encode(['vehicle' => '<tr data="vehicleDetails"><td colspan="7"><div id="vehicleDetails-'.$result->id.'" class="mx-auto w-75"><div class="card"><div class="card-header"><h4>Transaction Details</h4></div><div class="card-body"><div class="form-group"><div class="col-md-8">Model Name : '.$result->model_name. '</div></div>	<div class="form-group"><div class="col-md-8">Type : '.$result->type.'</div></div><div class="form-group"><div class="col-md-8">Size : '.$result->size.'</div></div><div  class="form-group"><div class="col-md-8">Plate No. : '.$result->plate_number.'</div></div><div class="form-group"><div class="col-md-8">COR No. : '.$result->cr_number.'</div></div></div></div><div class="card" style="margin-top : 2rem;"><div class="card-header"><h4>Customer Details</h4></div><div class="card-body"><div class="form-group"><div class="col-md-8">Customer Name : '.$result->first_name. ' '. $result->middle_name[0]. '. '. $result->last_name . ' </div></div><div class="form-group"><div class="col-md-8">Contact Number : '.$result->contact_number.'</div></div></div></div></div></td></tr>']);
		}
	}

	public function statsByYear(Input $input)
	{
		$year = $input->get('year');

		$db = new Query();

		$db->query("SELECT count(id) as y, DATE_FORMAT(date_time, '%b') as month FROM transaction WHERE YEAR(date_time) = :year and status = 'Done' GROUP BY month;");
		$db->bindValue(':year', $year);
		$db->execute();
		echo json_encode($db->fetch());
	}



}

?>