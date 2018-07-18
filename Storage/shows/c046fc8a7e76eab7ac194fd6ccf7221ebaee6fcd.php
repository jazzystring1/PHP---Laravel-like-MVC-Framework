<!DOCTYPE html>
<html>
<head>
<title>RS Grand Wash</title>
<link href="<?php echo(esc( asset("css/bootstrap.min.css"))); ?>" rel="stylesheet">
<script src="<?php echo(esc( asset("js/jquery.js"))); ?>"></script>
<script src="<?php echo(esc( asset("js/popper.min.js"))); ?>"></script>
<script src="<?php echo(esc( asset("js/bootstrap.min.js"))); ?>"></script>
<script src="<?php echo(esc( asset("js/canvasjs.min.js"))); ?>"></script>
<script src="<?php echo(esc( asset("js/chart.js"))); ?>"></script>

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
		$(document).on('click', '#showTimeline', function() {
			var id = $(this).closest('tr').attr('data');
			var start = $('.table-responsive').attr('datastart');
			var end = $('.table-responsive').attr('dataend');

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
								renderSalesChartByTimeline(id, start, end);
							}
						})
					}
				});
			}
		});

		$(document).on('change', '#showByoption', function() {
		 	if($(this).val() == 'Year') {
		 		$("#year-header").removeClass('d-none');
		 		$("#yearOption").removeClass('d-none');
		 		$("#from-header").addClass('d-none');
		 		$("#fromOption").addClass('d-none');
		 		$("#to-header").addClass('d-none');
		 		$("#toOption").addClass('d-none');+
		 		$("#month-header").addClass('d-none');
		 		$("#monthOption").addClass('d-none');
		 		$("#weekOption").addClass('d-none'); 
		 		$(".generateReport").attr('id', 'generateYear');
		 	} 

		 	if ($(this).val() == 'Day') {
		 		$("#from-header").removeClass('d-none');
		 		$("#fromOption").removeClass('d-none');
		 		$("#to-header").removeClass('d-none');
		 		$("#toOption").removeClass('d-none');
		 		$("#year-header").addClass('d-none');
		 		$("#yearOption").addClass('d-none'); 
		 		$("#month-header").addClass('d-none');
		 		$("#monthOption").addClass('d-none');	
		 		$("#weekOption").addClass('d-none'); 
		 		$(".generateReport").attr('id', 'generate');
		 	}

		 	if ($(this).val() == 'Month') {
		 		$("#month-header").removeClass('d-none');
		 		$("#monthOption").removeClass('d-none');
		 		$("#to-header").addClass('d-none');
		 		$("#toOption").addClass('d-none');
		 		$("#from-header").addClass('d-none');
		 		$("#fromOption").addClass('d-none');
		 		$("#year-header").removeClass('d-none');
		 		$("#yearOption").removeClass('d-none'); 	
		 		$("#weekOption").addClass('d-none'); 
		 		$(".generateReport").attr('id', 'generateMonth');
		 	}

		 	if ($(this).val() == 'Week') {
		 		$("#from-header").addClass('d-none');
		 		$("#fromOption").addClass('d-none');
		 		$("#to-header").addClass('d-none');
		 		$("#toOption").addClass('d-none');
		 		$("#year-header").addClass('d-none');
		 		$("#yearOption").addClass('d-none'); 
		 		$("#month-header").addClass('d-none');
		 		$("#monthOption").addClass('d-none');
		 		$("#weekOption").removeClass('d-none'); 	
		 		$(".generateReport").attr('id', 'generateWeek');
		 	}

		})

		$(document).on('submit', '#search', function() {
			window.location.href = "http://localhost/MVC/manage/transaction/search/" + $('#pattern').val();
			return false;
		});

		$(document).on('submit', '#generate', function() {
			window.location.href = "http://localhost/MVC/manage/report/sales/from/" + $('#fromOption').val() + "/to/" + $('#toOption').val() + "/page/1";
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
			     <div id="manageReports" class="collapse show">  
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
						<h2>Sales Report</h2>
					</div>
					<div class="card-body">
						<form class="form-inline my-2 my-lg-0 p-3 clear-float" id="search">
							   <input class="form-control mr-sm-2" placeholder="Search Record" type="text" id="pattern">				   
							   <button class="btn btn-primary mr-sm-2 my-sm-0" type="submit">Search</button>
							
						</form>
						<form class="form-inline my-2 my-lg-0 p-3 generateReport" id="generate">
							<label class="mr-sm-2" for="from">Show by </label>
							<select class="form-control mr-sm-2" name="showBy" id="showByoption">
								<option>Day</option>
								<option>Week</option>
								<option>Month</option>
								<option>Year</option>
							</select>
							<label class="mr-sm-2 d-none" for="week">Week </label>
							<input type="week" class="form-control d-none mr-sm-2" name="weekOption" id="weekOption">
							<label class="mr-sm-2 d-none" for="from" id="month-header">Month </label>
							<select class="form-control mr-sm-2 d-none" name="month" id="monthOption">
								<option>January</option>
								<option>February</option>
								<option>March</option>
								<option>April</option>
								<option>May</option>
								<option>June</option>
								<option>July</option>
								<option>August</option>
								<option>September</option>
								<option>October</option>
								<option>November</option>
								<option>December</option>
							</select>
							<label class="mr-sm-2 d-none" for="from" id="year-header">Year </label>
							<select class="form-control mr-sm-2 d-none" name="year" id="yearOption">
							<?php for($x = 2018; $x <= 2099; $x++) : ?> 

								<option><?php echo(esc($x)); ?></option>
							<?php endfor; ?> 

							</select>
							<label class="mr-sm-2" for="from" id="from-header">From </label>
							<input type="date" class="form-control mr-sm-2" name="from" id="fromOption" max="2099-12-31"><br><br>
							<label class="mr-sm-2" for="to" id="to-header">To </label>
							<input type="date" class="form-control mr-sm-2" name="to" id="toOption" min="2016-01-02"><br><br>
							<button class="btn btn-primary my-2 my-sm-0" type="submit"> Generate Report </button>
						</form>
						<div class="text-center pt-3 pb-3">
						<?php if($header) : ?>
						  <?php echo(esc($header)); ?>
						<?php else: ?>
						<?php echo(esc("Daily Sales Report(Today)")); ?>	
						<?php endif; ?>
						</div>
						<?php if($sales) : ?>		
							<?php if($byTimeline) : ?>	
								<div class="table-responsive p-3" datastart="<?php echo(esc($byTimeline['start'])); ?>"
								dataend="<?php echo(esc($byTimeline['end'])); ?>">
							<?php else: ?>
								<div class="table-responsive p-3">
							<?php endif; ?>
									<table class="table">
										<tr>
											<th>
												Package Name
											</th>
											<th>
												Income
											</th>
											<th class="text-center">
												Action
											</th>
										</tr>
							<?php foreach($sales as $sale) : ?>
								    	<tr data="<?php echo(esc($sale->id)); ?>">
											<td>
											<?php echo(esc($sale->package_type)); ?>
											</td>
											<td>
											₱<?php echo(esc($sale->income)); ?>
											</td>
											<td class="text-center" style="width : 220px;">
												<button class="btn btn-outline-success" id="<?php echo(esc($graphType)); ?>">Show Graph/Timeline</button>
											</td>
										</tr>	
							<?php endforeach;  ?>
									</table>
								</div>
						<?php else: ?>
							<div class="clear-float">
								<div class="alert alert-light">
									<div>
									No reports were generated
									</div>
								</div>						
							</div>
						<?php endif; ?>

						<?php if($links) : ?>
							<?php echo( $links ); ?>
						<?php endif; ?>
					</div>
					<div class="card-footer">
						
					</div>
				</div>
			</div>
		</div>
</body>
</html>