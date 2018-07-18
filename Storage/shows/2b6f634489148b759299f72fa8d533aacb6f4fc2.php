<!DOCTYPE html>
<html>
<head>
<title>RS Grand Wash</title>
<link href="<?php echo(esc( asset("css/bootstrap.min.css"))); ?>" rel="stylesheet">
<script src="<?php echo(esc( asset("js/jquery.js"))); ?>"></script>
<script src="<?php echo(esc( asset("js/popper.min.js"))); ?>"></script>
<script src="<?php echo(esc( asset("js/bootstrap.min.js"))); ?>"></script>
<script src="<?php echo(esc( asset("js/canvasjs.min.js"))); ?>"></script>
<script src="<?php echo(esc( asset("js/chart.js") )); ?>"></script>
<style type="text/css">
	@font-face {
	font-family: 'Raleway';
	src:
	    local('Raleway'),
	    local('Raleway-Regular'),
	    url('<?php echo(esc(asset("fonts/Raleway-Regular.ttf") )); ?> ');
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

		$(document).on('click', '#updateEmployee', function() {
			var id = $(this).closest('tr').attr('data');
			var first_name = $(this).closest('tr').children('#first_name').html();
			var middle_name = $(this).closest('tr').children('#middle_name').html();
			var last_name = $(this).closest('tr').children('#last_name').html();
			var address = $(this).closest('tr').children('#address').html();
			var birthday = $(this).closest('tr').children('#birthday').html();
			var contact_number = $(this).closest('tr').children('#contact_number').html();

			$(".verification").hide();
			$('.form.modal-content > .card > .card-header').html('<h2>Edit Form</h2>')
			$('.form.modal-content > .card > .card-body').html('<form method="POST" action="http://localhost/MVC/manage/employee/update"><input type="hidden" class="form-control" name="id" value="'+ id + '"><div class="form-group"><label for="first_name">Firstname</label><input type="text" class="form-control" name="first_name" value="'+ first_name + '"> </div><div class="form-group"><label for="middle_name">Middlename</label><input type="text" class="form-control" name="middle_name" value="'+ middle_name + '"> </div><div class="form-group"><label for="last_name">Lastname</label><input type="text" class="form-control" name="last_name" value="'+ last_name + '"> </div><div class="form-group"><label for="address">Address</label><input type="text" class="form-control" name="address" value="'+ address + '"> </div><div class="form-group"><label for="birthday">Birthday</label><input type="date" class="form-control" name="birthday" value="'+ birthday + '"> </div><div class="form-group"><label for="contact_number">Contact Number</label><input type="text" class="form-control" name="contact_number" value="'+ contact_number + '"> </div><center><input type="submit" class="mx-auto btn btn-outline-primary" value="Save"></center></form>');
			$('#verificationForm').modal('show');
		})

		$(document).on('click', '#deleteEmployee', function() {
			var id = $(this).closest('tr').attr('data');	

			$(".verification").hide();
			$('.form.modal-content > .card > .card-header').html('<h2>Confirmation</h2>')
			$('.form.modal-content > .card > .card-body').html('<form method="POST" action="http://localhost/MVC/manage/employee/delete"><input type="hidden" class="form-control" name="id" value="'+ id + '"><div class="form-group">Are you sure you want to delete this record?</div><center><input type="submit" class="mx-auto btn btn-outline-primary" value="Yes"></center></form>');
			$('#verificationForm').modal('show');
		})

		$(document).on('click', '#showGraph', function() {
			var year = '2018';
			var id = $(this).closest('tr').attr('data');

			if($("tbody tr td #chartContainer-" + id).length != 0) {
				$("tbody tr td #chartContainer-" + id).closest('tr').remove();
			} else {
				$.ajax({
					'type' : 'POST',
					'dataType' : 'json',
					'url' : 'http://localhost/MVC/manage/employee/stats/template',
					'data' : { "id" : id },
					success : function(data) {
						$("tbody tr").each(function() {
							if ($(this).attr('data') == id) {
								$(this).after(data.graphTemplate);
								renderChartbyYear(id, year);
							}
						})
					}
				});
			}
		});

		$(document).on('submit', '#search', function() {
			window.location.href = "http://localhost/MVC/manage/employee/search/" + $('#pattern').val();
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
			      <a class="nav-link" href="http://localhost/MVC/manage/transaction/"><b>Transactions</b></a>
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
		   				<div class="card-body" id="test">
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
						<?php if($search) : ?>
						<h2>Search Result</h2>
						<?php else: ?>
						<h2>Manage Employees</h2>
						<?php endif; ?>
					</div>
					<div class="card-body">
						<div class="action-list">
							<a class="btn btn-outline-secondary" href="http://localhost/MVC/manage/employee/new" id="newEmployee">New Employee</a>
						</div>		
						<form class="form-inline my-2 my-lg-0 p-3 clear-float" id="search">
							   <input class="form-control mr-sm-2" placeholder="Search Record" type="text" id="pattern">				   
							   <button class="btn btn-primary my-2 my-sm-0" type="submit">Search</button>
						</form>
						<?php if($employees) : ?>							
							<?php if($message) : ?>
								<div class="alert alert-info clear-float m-3">
									<?php echo(esc($message)); ?>
								</div>
							<?php endif; ?>
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
												Address
											</th>
											<th>
												Birthday
											</th>
											<th>
												Contact Number
											</th>									
											<th class="text-center">
												Action
											</th>
										</tr>
							<?php foreach($employees as $employee) : ?>
										<tr data="<?php echo(esc($employee->id)); ?>">
											<td id="first_name"><?php echo(esc($employee->first_name)); ?></td>
											<td id="middle_name"><?php echo(esc($employee->middle_name)); ?></td>
											<td id="last_name"><?php echo(esc($employee->last_name)); ?></td>
											<td id="address"><?php echo(esc($employee->address)); ?></td>
											<td id="birthday"><?php echo(esc($employee->birthday)); ?></td>
											<td id="contact_number"><?php echo(esc($employee->contact_number)); ?></td>
											<td class="text-center" style="width : 200px;">										
												<button class="btn btn-outline-warning" id="updateEmployee">Update</button>
												<button class="btn btn-outline-danger" id="deleteEmployee">Delete</button>
											</td>
									   </tr>
							<?php endforeach;  ?>
									</table>
								</div>
							<?php if($links) : ?>
							<?php echo( $links ); ?>
							<?php endif; ?>
						<?php else: ?>
							<?php if(isset($notfound)) : ?> 

								<div class="clear-float">
									<div class="alert alert-light">
										Record not found.
									</div>					
								</div>
							<?php else: ?>
								<div class="clear-float">
									<div class="alert alert-light">
										No record's available. Click "New Employee" to add.
									</div>					
								</div>
							<?php endif; ?> 

						<?php endif; ?>

						
					</div>
					<div class="card-footer">
						
					</div>
				</div>
			</div>
		</div>
</body>
</html>