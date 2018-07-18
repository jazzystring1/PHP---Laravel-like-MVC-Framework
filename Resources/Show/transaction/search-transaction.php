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

	.hide {
		display : none;
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
		$("#newVehicle").click(function() {
			$(".verification").hide();		//The Verification error Message
			$("#verificationForm").modal('show');
		})

		$(document).on('click', '#updateVehicle', function() {
			var p = $(this).parent().parent().children().children('p');
			if(p.attr('contenteditable', 'false')) {
				p.css('border', 'solid 1px gray').css('border-radius', '5px').attr('contenteditable', 'true').fadeIn();
				$(this).attr('id', 'onupdateVehicle');
				$(this).html('Save');	
			}
		})

		$(document).on('click', '#onupdateVehicle', function() {
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
				$(this).attr('id', 'updateVehicle');
				$(this).html('Update');
			}
		})

		$(document).on('click', '#deleteVehicle', function() {
			var id = $(this).parent().parent().attr('data');
			
			$.ajax({
				'type' : 'POST',
				'dataType' : 'json',
				'url' : 'http://localhost/MVC/manage/vehicle/delete',
				'data' :  { "id" : id },
				success : function(data) {
					if(data.status == "true") {
						var ctr = 0;
						$("tbody tr").each(function() {
							if($(this).attr('data') == id) {
								$(this).fadeOut();
							}
							ctr++;
						})

						if (ctr == 2) {
							location.reload();
						}
					} 
				}
			});
			
		});

		$("#verify").submit(function(evt) {
			$.ajax({
				'type' : 'POST',
				'dataType' : 'json',
				'url' : 'http://localhost/MVC/manage/vehicle/verify',
				'data' :  $(this).serialize(),
				success : function(data) {
					if(data.status == "true") {
						window.location = 'http://localhost/MVC/manage/vehicle/new';
					} else {
						$(".verification").show();
					}
				}
			});
			evt.preventDefault();
		});

		$(document).on('click', '#markTransaction', function() {
			$(this).closest('tr').children('#status').html('Done');
			var id = $(this).closest('tr').attr('datatransaction');
			if($(this).hasClass('disabled')) {
				return false;
			}
			$.ajax({
				'type' : 'POST',
				'url' : 'http://localhost/MVC/manage/transaction/done',
				'data' : { "id" : id },
				success : function() {
					$('.message').removeClass('hide');
					$('#markTransaction').addClass('disabled');		
				}
			});
		});

		$(document).on('click', '#showVehicleDetails', function() {
			$(this).closest('tr').addClass('show');
			var id = $(this).closest('tr').attr('datavehicle');

			if($(this).closest('tr').next().attr('data') == 'vehicleDetails') {
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
			window.location.href = "http://localhost/MVC/manage/transaction/search/" + $('#pattern').val();
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
							<a class="btn btn-outline-secondary" href="http://localhost/MVC/manage/transaction/new" id="newTransaction">New Transaction</a>
						</div>
						<form class="form-inline my-2 my-lg-0 p-3 clear-float" id="search">
							   <input class="form-control mr-sm-2" placeholder="Search Record" type="text" id="pattern">				   
							   <button class="btn btn-primary my-2 my-sm-0" type="submit">Search</button>
						</form>
						@if($transactions)			
							<div class="alert alert-success message hide">
								The transaction got marked as done successfully.
							</div>
								<div class="table-responsive p-3">
									<table class="table">
										<tr>
											<th>
												Carwasher Name
											</th>
											<th>
												Status
											</th>
											<th>
												Date and Time
											</th>
											<th class="text-center">
											    Details
											</th>
											<th class="text-center">
												Action
											</th>
										</tr>
							@foreach($transactions as $transaction)
								    	<tr dataVehicle="{{$transaction->vehicle_id}}" 
								    		dataTransaction="{{$transaction->id}}">
											<td>
											{{$transaction->first_name}}
											{{$transaction->middle_name[0]}}.
											{{$transaction->last_name}}
											</td>
											<td id="status">
											{{$transaction->status}}
											</td>
											<td>
											{{$transaction->date_time}}
											</td>
											<td class="text-center">
											<button class="btn btn-outline-info" id="showVehicleDetails">Show</button>
											</td>
											<?php if($transaction->status == 'Ongoing') : ?>
												<td class="text-center" style="width : 220px;">
													<button class="btn btn-outline-success" id="markTransaction">Mark as done</button>
												</td>
											<?php else: ?>
												<td class="text-center" style="width : 220px;">
													<button class="btn btn-outline-success disabled" id="markTransaction">Mark as done</button>
												</td>
											<?php endif; ?>
										</tr>	
							@endforeach
									</table>
								</div>
						@else
							@isset($notfound)
							<div class="clear-float">
								<div class="alert alert-light">
									<div>
									 Sorry, record not found.
									</div>
								</div>						
							</div>
							@else
								<div class="clear-float">
									<div class="alert alert-light">
										<div>
										What you're looking for don't exist. You may click "New Transaction" to add.
										</div>
									</div>						
								</div>
							@endisset
						@endif

						@if($links)
							{{!! $links !!}}
						@endif
					</div>
					<div class="card-footer">
						
					</div>
				</div>
			</div>
		</div>
</body>
</html>