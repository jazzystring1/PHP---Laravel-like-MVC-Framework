<?php
namespace App\Manager;

require_once "Framework/Jazzy/Manager/Manager.php";

use App\Session;
use App\Input;
use App\Manager;
use Barbet\Query\QueryBuilderHandler as Query;

class CustomerManager extends Manager
{
	public function __construct()
	{
		if(!Session::has('username')) {
			return redirectshow('');
		}
	}

	public function index()
	{
		return redirectshow('manage/customer/page/1');
	}

	public function show()
	{
		return show('customer.create-customer');
	}

	public function page($id)
	{
		$db = new Query();
		$start = ($id-1) * 10;
		$db->query("SELECT * FROM customer LIMIT {$start}, 10");
		$db->execute();
		$result = $db->fetch();
	    $links = $db->links('customer', 10);

		return show('customer.manage-customer')->with(['customers' => $result, 'links' => $links]);
	}

	public function profile($id)
	{
		$db = new Query();

		$result = $db->table('customer')
			    	 ->where('id', $id)
			    	 ->grab();

		return show('customer.manage-customer')->with(['customers' => $result, 'notfound' => $result]);
	}

	public function search($pattern)
	{
		$db = new Query();
		$db->query("SELECT * FROM employee WHERE first_name LIKE :pattern or middle_name LIKE :pattern or last_name LIKE :pattern or contact_number LIKE :pattern");
		$db->bindValue(":pattern", $pattern ."%");
		$db->execute();
		$result = $db->fetch();
		
		return show('customer.search-customer')->with(['customers' => $result]);
	}

	public function create(Input $input)
	{
		Query::table('customer')
			 ->insert($input->all());

		return redirectshow('manage/customer/new')->with(['success' => true]);
	}

	public function update(Input $input)
	{
		Query::table('customer')
			 ->where('id', $input->get('id'))
			 ->update($input->except('id'));
			 
		return redirectshow('manage/customer')->with(['message' => 'Data got successfully updated']);
	}

	public function delete(Input $input)
	{
		$id = $input->get('id');
		echo $id;
		Query::table('customer')
		   ->where('id', $id)
		   ->delete();
		return redirectshow('manage/customer')->with(['message' => 'Data got successfully deleted']);
	}

	public function get(Input $input)
	{
		$id = $input->get('id');

		$result = Query::table('customer')
				   ->where('id', $id)
				   ->single();

	    if($result) {
		echo json_encode(['customer' => '<tr data="customerDetails">
											<td colspan="8">
												<div id="customerDetails-'.$result->id.'" class="mx-auto w-75">
													<div class="card">
														<div class="card-header">
															<h4>Customer Details</h4>
														</div>

														<div class="card-body">
															<div class="form-group">
																<div class="col-md-8">
																	Name : '.$result->first_name. ' '.$result->middle_name[0]. '. '.$result->last_name.'
																</div>
															</div>
															
															<div class="form-group">
																<div class="col-md-8">
																	Contact Number : '.$result->contact_number.'
																</div>
															</div>
														</div>
													</div>
												</div>
											</td>
										</tr>']);
		}
	}

}

?>