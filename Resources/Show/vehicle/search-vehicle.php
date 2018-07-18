<!DOCTYPE html>
<html>
<head>
<title>RS Grand Wash</title>
<link href="{{ asset("css/bootstrap.min.css")}}" rel="stylesheet">
<script src="{{ asset("js/jquery.js")}}"></script>
<script src="{{ asset("js/popper.min.js")}}"></script>
<script src="{{ asset("js/bootstrap.min.js")}}"></script>
<style type="text/css">
	@font-face {
	font-family: 'Raleway';
	src:
	    local('Raleway'),
	    local('Raleway-Regular'),
	    url('{{asset("fonts/Raleway-Regular.ttf") }} ');
	}

    body {
		font-family: 'Raleway', sans-serif;
		height : 100vh;
		width : 100%;
		padding : 0;
		margin : 0;
		overflow-y : none;
	}

	nav.navbar.navbar-expand-sm {
		border-bottom : solid 3px #E9EBEE;
	}

	.vertical.navbar.bg-light {
      background-color : #E9EBEE;
      position : absolute;
      top : 0px;
      padding : 0;
      border-right : solid 2px #E9EBEE;
      width : 15rem;
      height : 100vh;
    }

    .nav-holder {
      position: sticky;
      position: -webkit-sticky;
      top: 1px;
      z-index : 20;
      float : left;
    }

    .vertical.navbar.bg-light .navbar-nav {
      width : 100%;
    }

    .vertical.navbar.bg-light .navbar-nav > li {
      padding : 1rem 0rem 1rem 0rem;
      border-top : solid 1px #c9c7c7;
      text-align : center;
    }

     .vertical.navbar.bg-light .navbar-nav > li:last-of-type {
      border-bottom : solid 1px #c9c7c7;
    }
 
    .vertical.navbar.bg-light .navbar-nav > li > a {
      cursor : pointer;
      color : black;
      margin : 0 auto;
    }

    #manageVehicle > ul > li > a {
    	color : black;
    	text-decoration : none;
    }

    #manageVehicle > ul > li {
    	text-align : center;
    }

    #manageReports > ul > li > a {
    	color : black;
    	text-decoration : none;
    }

    #manageReports > ul > li {
    	text-align : center;
    }

	#center-content {
		position: relative;
		width : 100%;
		align-items: center;
        justify-content: center;
	}

	.wrapper {
    position : relative;
    background-color : white;
    height : 100vh;
    padding-left : 14rem;
   }

	.clear-float {
		clear : both;
	}

	.container {
		margin : 0rem 0rem 0rem 13rem;
		padding : 0;
	}

	.action-list {
		float : right;
	}

	.verification {
		margin : 2rem 2rem 0rem 2rem;
	}

	.notification {
		margin-top : 2rem;
	}

	.m-b-1 {
		margin-bottom : 1rem;
	}

	.p-3 {
		padding-top : 3rem;
	}

	.manage-employee.card.row {
		position : absolute;
		width : 100%;
	}

	.manage-employee.card {
		position : relative;
		width : 90%;
		top : 2rem;
	}

</style>
<script type="text/javascript">
	$(function() {
		/*$("#newVehicle").click(function() {
			$(".verification").hide();		//The Verification error Message
			$("#verificationForm").modal('show');
		})*/

		$(document).on('click', '#updateVehicle', function() {
			var id = $(this).closest('tr').attr('datavehicle');
			var model_name = $(this).closest('tr').children('#model_name').html();
			var vehicle_type = $(this).closest('tr').children('#vehicle_type').html();
			var vehicle_size = $(this).closest('tr').children('#vehicle_size').html();
			var plate_number = $(this).closest('tr').children('#plate_number').html();
			var cr_number = $(this).closest('tr').children('#cr_number').html();

			$(".verification").hide();
			$('.form.modal-content > .card > .card-header').html('<h2>Edit Form</h2>')
			$('.form.modal-content > .card > .card-body').html('<form method="POST" action="http://localhost/MVC/manage/vehicle/update"><input type="hidden" class="form-control" name="id" value="'+ id + '"><div class="form-group"><label for="model_name">Model Name</label><input type="text" class="form-control" name="model_name" value="'+ model_name + '"> </div><div class="form-group"><label for="vehicle_type">Type</label><select class="form-control" id="vehicle_type" name="vehicle_type"><option>Motorcycle</option><option>SUV</option><option>Van</option><option>Pickup</option><option>Bus</option><option>Coaster</option><option>Bicycle</option><option>Tricycle</option><option>PUJ/Jeep</option></select></div><div class="form-group"><label for="size">Size</label><select class="form-control" name="vehicle_size"><option>Small</option><option>Medium</option><option>Large</option><option>X-Large</option></select></div><div class="form-group"><label for="plate_number">Plate Number</label><input type="text" class="form-control" name="plate_number" value="'+ plate_number + '"> </div><div class="form-group"><label for="cr_number">Certificate of Registration No</label><input type="text" class="form-control" name="cr_number" value="'+ cr_number + '"> </div><center><input type="submit" class="mx-auto btn btn-outline-primary" value="Save"></center></form>');
			$('#verificationForm').modal('show');
		})

		$(document).on('click', '#deleteVehicle', function() {
			var id = $(this).closest('tr').attr('datai');	

			$(".verification").hide();
			$('.form.modal-content > .card > .card-header').html('<h2>Confirmation</h2>')
			$('.form.modal-content > .card > .card-body').html('<form method="POST" action="http://localhost/MVC/manage/vehicle/delete"><input type="hidden" class="form-control" name="id" value="'+ id + '"><div class="form-group">Are you sure you want to delete this record?</div><center><input type="submit" class="mx-auto btn btn-outline-primary" value="Yes"></center></form>');
			$('#verificationForm').modal('show');
		})

		$(document).on('click', '#showCustomerDetails', function() {
			$(this).closest('tr').addClass('show');
			var id = $(this).closest('tr').attr('data');

			if($(this).closest('tr').next().attr('data') == 'customerDetails') {
				$(this).closest('tr').next().remove();
				$(this).closest('tr').removeClass('show');
			} else {
				$.ajax({
					'type' : 'POST',
					'dataType' : 'json',
					'url' : 'http://localhost/MVC/manage/customer/get',
					'data' : { "id" : id },
					success : function(data) {
						$("tbody tr").each(function() {
							if ($(this).attr('class') == "show") {
								$(this).after(data.customer);
							}
						})
					}
				});
			}
		});

		$(document).on('submit', '#search', function() {
			window.location.href = "http://localhost/MVC/manage/vehicle/search/" + $('#pattern').val();
			return false;
		});
	});
</script>
</head>
<body>
		<nav class="navbar navbar-expand-sm bg-light navbar-light">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="">
                        RX Grand Wash
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <ul class="nav navbar-nav ml-auto">
                             <li class="nav-item"><a class="nav-link" href="http://localhost/MVC/dashboard"></i>Dashboard</a></li>
                             <li class="nav-item"><a class="nav-link" href="http://localhost/MVC/dashboard/logout"></i>Signout</a></li>

                    </ul>
                </div>
        </nav>
        <div class="nav-holder">
	        <nav class="vertical navbar bg-light">
			  <ul class="navbar-nav">
			    <li class="nav-item">
			      <a class="nav-link" href="http://localhost/MVC/manage/employee"><b>Manage Employee</b></a>
			    </li>
			    <li class="nav-item">
			      <a class="nav-link" data-toggle="collapse" data-target="#manageVehicle"><b>Manage Vehicles</b></a>
			    </li>
			    <div id="manageVehicle" class="collapse show">  
			    	<ul class="list-unstyled">
					    <li class="nav-item">
					      <a class="nav-link" href="http://localhost/MVC/manage/vehicle">Show Vehicles</a>
					    </li>
					    <li class="nav-item">
					      <a class="nav-link" href="http://localhost/MVC/manage/customer">Show Customers</a>
					    </li>
					</ul>
				</div>
			    <li class="nav-item">
			      <a class="nav-link" href="http://localhost/MVC/manage/package"><b>Manage Packages</b></a>
			    </li>		    
			    <li class="nav-item">
			      <a class="nav-link" href="http://localhost/MVC/manage/transaction"><b>Transactions</b></a>
			    </li>
			    <li class="nav-item">
			      <a class="nav-link" data-toggle="collapse" data-target="#manageReports"><b>Reports</b></a>
			    </li>
			     <div id="manageReports" class="collapse">  
			    	<ul class="list-unstyled">
					    <li class="nav-item">
					      <a class="nav-link" href="http://localhost/MVC/manage/report/statistics/page/1">Statistics Report</a>
					    </li>
					    <li class="nav-item">
					      <a class="nav-link" href="http://localhost/MVC/manage/report/sales/page/1">Sales Report</a>
					    </li>
					</ul>
				</div>
			  </ul>
			</nav>
		</div>

		<div class="modal fade" id="verificationForm">
			<div class="modal-dialog" style="">
			    <div class="form modal-content">
		   			<div class="card">
		   				<div class="card-header">
		   					<h3>
		   					Confirmation
		   					</h3>
		   				</div>
		   				<div class="alert alert-danger verification">
		   					<center>
		   							Invalid password
		   					</center>
		   				</div>
		   				<div class="card-body">
		   					<p>
		   					For security purposes, please enter your password to proceed
		   					</p>
		   					<form id="verify">
			   					<div class="form-group">
			                        <input type="password" class="form-control" name="password" required></input>
			                    </div>
			                    <center>
			                    <input type="submit" class="mx-auto btn btn-outline-primary" name="submit" value="Proceed">
			                    </center>
		                    </form>
		   				</div>
		   				<div class="card-footer">
		   					
		   				</div>
		   			</div>

			    </div>
			</div>
		</div>

		<div class="container-fluid">
			<div class="wrapper mx-auto">
				<div class="manage-employee card mx-auto">
					<div class="card-header">
						<h2>Search Result</h2>
					</div>
					<div class="card-body">				
						<div class="action-list">
							<a class="btn btn-outline-secondary" href="http://localhost/MVC/manage/vehicle/new" id="newVehicle">New Vehicle</a>
						</div>
						<form class="form-inline my-2 my-lg-0 p-3 clear-float" id="search">
							   <input class="form-control mr-sm-2" placeholder="Search Record" type="text" id="pattern">				   
							   <button class="btn btn-primary my-2 my-sm-0" type="submit">Search</button>
						</form>
						@if($vehicles)
							@if($message)
								<div class="alert alert-info clear-float">
									{{$message}}
								</div>
							@endif
								<div class="table-responsive p-3">
									<table class="table">
										<tr>
											<th>
												Model Name
											</th>
											<th>
												Type
											</th>
											<th>
												Size
											</th>
											<th>
												Plate No.
											</th>
											<th>
												CR No.
											</th>
											<th class="text-center">
												Customer Details
											</th>
											<th class="text-center">
												Action
											</th>
										</tr>
							@foreach($vehicles as $vehicle)
										<tr datai="{{$vehicle->id}}" 
										 data="{{$vehicle->customer_id}}" 
										 datavehicle = "{{$vehicle->id}}">
											<td id="model_name">{{$vehicle->model_name}}</td>
											<td id="vehicle_type">{{$vehicle->type}}</td>
											<td id="vehicle_size">{{$vehicle->size}}</td>
											<td id="plate_number">{{$vehicle->plate_number}}</td>
											<td id="cr_number">{{$vehicle->cr_number}}</td>
											<td class="text-center">
											<button class="btn btn-outline-info" id="showCustomerDetails">Show</button>
											</td>
											<td style="width : 220px;">
												<button class="btn btn-outline-warning w-50" id="updateVehicle">Update</button>
												<button class="btn btn-outline-danger w-50 float-right" id="deleteVehicle">Delete</button>
												<a class="btn btn-outline-success w-100" href="http://localhost/MVC/manage/transaction/id/{{$vehicle->id}}" id="viewTransaction">View Transaction</a>
											</td>
										</tr>							
							@endforeach
									</table>
								</div>
							@if($links)
							  {{!! $links !!}}
							@endif
						@else
							<div class="clear-float">
								<div class="alert alert-light">
									What you're looking for don't exist. You may click "New Vehicle" to add.
								</div>						
							</div>
						@endif
						
					</div>
					<div class="card-footer">
						
					</div>

				</div>
			</div>
		</div>
</body>
</html>