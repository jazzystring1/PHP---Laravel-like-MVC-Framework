<!DOCTYPE html>
<html>
<head>
<title>RS Grand Wash</title>
<link href="{{ asset("css/bootstrap.min.css")}}" rel="stylesheet">
<script src="{{ asset("js/jquery.js")}}"></script>
<script src="{{ asset("js/popper.min.js")}}"></script>
<script src="{{ asset("js/bootstrap.min.js")}}"></script>
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
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
      top: 0; /* required */
      z-index : 20;
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

	.verification {
		margin : 2rem 2rem 0rem 2rem;
	}

	.notification {
		margin-top : 2rem;
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
		/*$("#newCustomer").click(function() {
			$(".verification").hide();		//The Verification error Message
			$("#verificationForm").modal('show');
		})*/

		$(document).on('click', '#updateCustomer', function() {
			var id = $(this).closest('tr').attr('data');
			var first_name = $(this).closest('tr').children('#first_name').html();
			var middle_name = $(this).closest('tr').children('#middle_name').html();
			var last_name = $(this).closest('tr').children('#last_name').html();
			var contact_number = $(this).closest('tr').children('#contact_number').html();

			$(".verification").hide();
			$('.form.modal-content > .card > .card-header').html('<h2>Edit Form</h2>')
			$('.form.modal-content > .card > .card-body').html('<form method="POST" action="http://localhost/MVC/manage/customer/update"><input type="hidden" class="form-control" name="id" value="'+ id + '"><div class="form-group"><label for="first_name">Firstname</label><input type="text" class="form-control" name="first_name" value="'+ first_name + '"> </div><div class="form-group"><label for="middle_name">Middlename</label><input type="text" class="form-control" name="middle_name" value="'+ middle_name + '"> </div><div class="form-group"><label for="last_name">Lastname</label><input type="text" class="form-control" name="last_name" value="'+ last_name + '"> </div><div class="form-group"><label for="contact_number">Contact Number</label><input type="text" class="form-control" name="contact_number" value="'+ contact_number + '"> </div><center><input type="submit" class="mx-auto btn btn-outline-primary" value="Save"></center></form>');
			$('#verificationForm').modal('show');
		})

		$(document).on('click', '#onupdateCustomer', function() {
			var p = $(this).parent().parent().children().children('p');
			if(p.attr('contenteditable', 'true')) {
				$.ajax({
					'type' : 'POST',
					'dataType' : 'json',
					'url' : 'http://localhost/MVC/employee/update',
					'data' : {},
					success : function(data) {
						if(data.status == "true") {

						}
					}
				});
				p.css('border', 'none').attr('contenteditable', 'false');
				$(this).attr('id', 'updateCustomer');
				$(this).html('Update');
			}
		})

		$(document).on('click', '#deleteCustomer', function() {
			var id = $(this).closest('tr').attr('data');	

			$(".verification").hide();
			$('.form.modal-content > .card > .card-header').html('<h2>Delete Form</h2>')
			$('.form.modal-content > .card > .card-body').html('<form method="POST" action="http://localhost/MVC/manage/customer/delete"><input type="hidden" class="form-control" name="id" value="'+ id + '"><div class="form-group">Are you sure you want to delete this record?</div><center><input type="submit" class="mx-auto btn btn-outline-primary" value="Yes"></center></form>');
			$('#verificationForm').modal('show');
		})

		$(document).on('click', '#showCustomerProperty', function() {
			$(this).closest('tr').addClass('show');
			var id = $(this).closest('tr').attr('data');

			if($(this).closest('tr').next().attr('data') == 'customerProperty') {
				$(this).closest('tr').next().remove();
				$(this).closest('tr').removeClass('show');
			} else {
				$.ajax({
					'type' : 'POST',
					'dataType' : 'json',
					'url' : 'http://localhost/MVC/manage/vehicle/get',
					'data' : { "id" : id },
					success : function(data) {
						$("tbody tr").each(function() {
							if ($(this).attr('class') == "show") {
								$(this).after(data.vehicle);
							}
						})
					}
				});
			}
		});

		$(document).on('submit', '#search', function() {
			window.location.href = "http://localhost/MVC/manage/customer/search/" + $('#pattern').val();
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
			    <div id="manageVehicle" class="collapse">  
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

		<div class="container-fluid">
			<div class="wrapper mx-auto">
				<div class="manage-employee card mx-auto">
					<div class="card-header">
						<h2>Search Result</h2>
					</div>
					<div class="card-body">

						<div class="action-list">
							<a class="btn btn-outline-secondary" href="http://localhost/MVC/manage/customer/new" id="newCustomer">New Customer</a>
						</div>
						<form class="form-inline my-2 my-lg-0 p-3 clear-float" id="search">
							   <input class="form-control mr-sm-2" placeholder="Search Record" type="text" id="pattern">				   
							   <button class="btn btn-primary my-2 my-sm-0" type="submit">Search</button>
						</form>
						@if($customers)					
							@if($message)
								<div class="alert alert-info">
									{{$message}}
								</div>
							@endif
								<div class="table-responsive p-3">
									<table class="table">
										<tr>
											<th>
												First Name
											</th>
											<th>
												Middle Name
											</th>
											<th>
												Last Name
											</th>
											<th>
												Contact Number
											</th>
											<th>
												Property
											</th>
											<th class="text-center">
												Action
											</th>						
										</tr>
							@foreach($customers as $customer)
										<tr data="{{$customer->id}}">
											<td id="first_name">{{$customer->first_name}}</td>
											<td id="middle_name">{{$customer->middle_name}}</td>
											<td id="last_name">{{$customer->last_name}}</td>
											<td id="contact_number">{{$customer->contact_number}}</td>
											<td>
												<button class="btn btn-outline-info" id="showCustomerProperty">Show</button>
											</td>									
											<td class="text-center" style="width : 200px;">											
												<button class="btn btn-outline-warning" id="updateCustomer">Update</button>
												<button class="btn btn-outline-danger" id="deleteCustomer">Delete</button>
											</td>
										</tr>
										
							@endforeach
									</table>
								</div>
							@if($links)
								{{!!$links!!}}
							@endif
						@else
							<div class="clear-float">
								<div class="alert alert-light">
									What you're looking for don't exist. You may click "New Customer" to add.
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