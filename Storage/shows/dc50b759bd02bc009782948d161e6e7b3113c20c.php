<!DOCTYPE html>
<html>
<head>
<title>RS Grand Wash</title>
<link href="<?php echo(esc( asset("css/bootstrap.min.css"))); ?>" rel="stylesheet">
<script src="<?php echo(esc( asset("js/jquery.js"))); ?>"></script>
<script src="<?php echo(esc( asset("js/popper.min.js"))); ?>"></script>
<script src="<?php echo(esc( asset("js/bootstrap.min.js"))); ?>"></script>
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
			     <div id="manageReports" class="collapse show">  
			    	<ul class="list-unstyled">
					    <li class="nav-item">
					      <a class="nav-link" href="http://localhost/MVC/manage/report/statistics/page/1">Statistics Report</a>
					    </li>
					    <li class="nav-item">
					      <a class="nav-link" href="http://localhost/MVC/manage/report/salary/page/1">Salary Report</a>
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
						<h2>New Transaction</h2>
					</div>
					<div class="card-body">
						    <?php if($success) : ?>
			   				<div class="alert alert-info">
			   					Vehicle got successfully added!
			   				</div>
			   				<?php endif; ?>
			   				<div class="action-list">
							<button class="btn btn-outline-secondary" onclick="window.location = 'http://localhost/MVC/manage/transaction'">View Transaction</button>
							</div>
		   					<form class="form p-5" method="POST" id="test" action="http://localhost/MVC/manage/transaction/create">
		                      	<?php if($status) : ?>
		                      		<div class="alert alert-info">
		                      			Transaction got successfully added!
		                      		</div>
		                      	<?php endif; ?>
		           
		                        <div class="form-group">
		                            <label for="plate_number" class="control-label">Plate Number</label>

		                            <input id="plate_number" type="text" class="form-control" name="plate_number" required>
		                        </div>

		                        <div class="form-group">
		                        	<label for="carwasher_name" class="control-label">Carwasher/Employee Name</label>

		                            	<?php if($carwashers) : ?>
			                                <select class="form-control" name="carwasher_name">
			                             		<?php foreach($carwashers as $carwasher) : ?>
			                                		<option data="<?php echo(esc($carwasher->id)); ?>">
			                                			<?php echo(esc($carwasher->first_name)); ?>
			                                			<?php echo(esc($carwasher->middle_name[0])); ?>.
			                                			<?php echo(esc($carwasher->last_name)); ?></option>
			                                	<?php endforeach;  ?>
			                                </select>
		                                <?php else: ?>
			                                <select class="form-control text-muted" name="carwasher_name">
			                             		 <option hidden>No carwashers/employees were added yet.</option>                       		
			                                </select>
		                                <?php endif; ?>
		                        </div>

		                        <div class="form-group">
		                            <label for="package_type" class="control-label">Package Type</label>

		                            <?php if($packages) : ?>
			                            <select class="form-control" name="package_type">
			                             	<?php foreach($packages as $package) : ?>
			                                	<option data="<?php echo(esc($package->id)); ?>">
			                                		<?php echo(esc($package->package_type)); ?>
			    								</option>
			                                <?php endforeach;  ?>
			                            </select>
		                            <?php else: ?>
			                            <select class="form-control text-muted" name="package_type">
			                             	<option hidden>No packages/services were added yet.</option>                       		
			                            </select>
		                            <?php endif; ?>
		                        </div>
                      					
		                      
		                        <div class="form-group">
		                            	<center>
			                                <button type="submit" class="btn btn-outline-primary">
			                                    Add Transaction
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

