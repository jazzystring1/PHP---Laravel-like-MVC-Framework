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

	.action-list {
		float : right;
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
		$("#plate_number").keyup(function() {
			$.ajax({
				'type' : 'POST',
				'dataType' : 'json',
				'url' : 'http://localhost/MVC/manage/vehicle/check',
				'data' : { "plate_number" : $(this).val() },
				success : function(data) {
					if(data.plate_number_exist == "true") {
						$("#plate_number").removeClass("is-valid");
						$("#plate_number").addClass("is-invalid");
						$("#plate_number").next().removeClass('valid-feedback').addClass('invalid-feedback').html('Plate No. already exist!');
						$("#addVehicle").addClass("disabled");
					} else {
						$("#plate_number").removeClass("is-invalid");
						$("#plate_number").addClass("is-valid");
						$("#plate_number").next().removeClass('invalid-feedback').addClass('valid-feedback').html('Plate No. is available!');
						$("#addVehicle").removeClass("disabled");
					}
				}
			})
		})

		$("#cr_number").keyup(function() {
			$.ajax({
				'type' : 'POST',
				'dataType' : 'json',
				'url' : 'http://localhost/MVC/manage/vehicle/check',
				'data' : { "cr_number" : $(this).val() },
				success : function(data) {
					if(data.cr_number_exist == "true") {
						$("#cr_number").removeClass("is-valid");
						$("#cr_number").addClass("is-invalid");
						$("#cr_number").next().removeClass('valid-feedback').addClass('invalid-feedback').html('CR No. already exist!');
						$("#addVehicle").addClass("disabled");
					} else {
						$("#cr_number").removeClass("is-invalid");
						$("#cr_number").addClass("is-valid");
						$("#cr_number").next().removeClass('invalid-feedback').addClass('valid-feedback').html('CR No. is available!');
						$("#addVehicle").removeClass("disabled");
					}
				}
			})
		})
	})
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
			      <a class="nav-link" data-toggle="collapse" data-target="#managePackage"><b>Manage Packages</b></a>
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

		<div class="container-fluid">
			<div class="wrapper mx-auto">
				<div class="manage-employee card mx-auto">
					<div class="card-header">
						<h2>New Vehicle</h2>
					</div>
					<div class="card-body">
						    @if($success)
			   				<div class="alert alert-info">
			   					Vehicle got successfully added!
			   				</div>
			   				@endif
			   				<div class="action-list">
							<button class="btn btn-outline-secondary" onclick="window.location = 'http://localhost/MVC/manage/vehicle'">View Vehicle</button>
							</div>
		   					<form class="form p-5" method="POST" id="test" action="http://localhost/MVC/manage/vehicle/create">
		                      	@if($status)
		                      		<div class="alert alert-info">
		                      			Vehicle got successfully added!
		                      		</div>
		                      	@endif

		                      	@if($plate_number_error)
		                      		<div class="alert alert-danger">
		                      			Plate No. shouldn't contain special character.
		                      		</div>
		                      	@endif

		                      	@if($cr_number_error)
		                      		<div class="alert alert-danger">
		                      			Certificate of Registration No. shouldn't contain alphabet.
		                      		</div>
		                      	@endif

		                      	@if($model_name_error)
		                      		<div class="alert alert-danger">
		                      			Model name shouldn't contain special character.
		                      		</div>
		                      	@endif

		                      		@if($plate_number_exists)
		                      		<div class="alert alert-danger">
		                      			Plate No. already exist.
		                      		</div>
		                      	@endif

		                     	<div class="form-group">
		                            <label for="customer_name" class="control-label">Customer Name</label>

		                            	@if($customers)
			                                <select class="form-control" name="customer_name">
			                             		@foreach($customers as $customer)
			                                		<option data="{{$customer->id}}">
			                                			{{$customer->first_name}}
			                                			{{$customer->middle_name[0]}}.
			                                			{{$customer->last_name}}</option>
			                                	@endforeach	                                		
			                                </select>
		                                @else
			                                <select class="form-control text-muted" name="customer_name">
			                             		 <option hidden>No customer were added yet.</option>                       		
			                                </select>
		                                @endif
		                        </div>		                   

		                        <div class="form-group">
		                            <label for="model_name" class="control-label">Model Name</label>

		                            <input id="model_name" type="text" class="form-control" name="model_name" required>
		                        </div>

		                        <!--<div class="form-group">
		                            <label for="package_type" class="control-label">Package Type</label>

		                            @if($packages)
			                            <select class="form-control" name="package_type">
			                             	@foreach($packages as $package)
			                                	<option data="{{$package->id}}">
			                                		{{$package->package_type}}
			    								</option>
			                                @endforeach	                                		
			                            </select>
		                            @else
			                            <select class="form-control text-muted" name="package_type">
			                             	<option hidden>No packages/services were added yet.</option>                       		
			                            </select>
		                            @endif
		                        </div>-->

		                        <div class="form-row">
			                        <div class="form-group col-md-6">
			                            <label for="vehicle_type" class="control-label">Type</label>
			                             <select class="form-control" id="vehicle_type" name="vehicle_type">
			                                		@if($types)
			                                			@foreach($types as $type)
			                                				<option data="{{$type->id}}">
			                                						{{$type->type}}
			                                			@endforeach
			                                		@else
			                                			<option>No Vehicle Types Were Added Yet</option>
			                                		@endif
			                            </select>
			                        </div>

			                       <div class="form-group col-md-6">
			                            <label for="vehicle_size" class="control-label">Size</label>
			                             <select class="form-control" id="vehicle_size" name="vehicle_size">
			                                		<option>Small</option>
			                                		<option>Medium</option>
			                                		<option>Large</option>
			                                		<option>X-large</option>
			                            </select>
			                        </div>
							</div>

								<div class="form-row">
			                        <div class="form-group col-md-6">
			                            <label for="plate_number" class="control-label">Plate No.</label>

			                            <input id="plate_number" type="text" class="form-control" name="plate_number" required>
			                            <div class="valid-feedback">
			                            	
			                            </div>
			                        </div>

			                        <div class="form-group col-md-6">
			                            <label for="cr_number" class="control-label">Certificate of Registration No.</label>

			                            <input id="cr_number" type="text" class="form-control" name="cr_number" required>
			                            <div class="valid-feedback">
			                            	
			                            </div>
			                        </div>
			                    </div>
		                   
		                      
		                        <div class="form-group">
		                            	<center>
			                                <button type="submit" class="btn btn-outline-primary" id="addVehicle">
			                                    Add Vehicle
			                                </button>
		                                </center>
		                        </div>
		                   </form>
					</div>
					<div class="card-footer">
						
					</div>
				</div>
			</div>
		</div>
</body>
</html>

