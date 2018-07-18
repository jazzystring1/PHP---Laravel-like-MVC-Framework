<!DOCTYPE html>
<html>
<head>
<title>RS Grand Wash</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link href= "<?php echo(esc( asset("css/bootstrap.min.css") )); ?>" rel="stylesheet">
<script src="<?php echo(esc( asset("js/jquery.js") )); ?>"></script>
<script src="<?php echo(esc( asset("js/popper.min.js") )); ?>"></script>
<script src="<?php echo(esc( asset("js/bootstrap.min.js"))); ?>"></script>
<script src="<?php echo(esc( asset("js/canvasjs.min.js") )); ?>"></script>
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

	#center-content {
		position: relative;
		width : 100%;
		align-items: center;
    justify-content: center;
	}

	.row {
		position : absolute;
		width : 100%;
	}

	.card {
		position : relative;
		width : 90%;
		top : 2rem;
	}

  .container {
    padding : 0;
  }

  .wrapper {
    position : relative;
    background-color : white;
    height : 100vh;
    padding-left : 14rem;
  }

</style>
<script type="text/javascript">
  $(function() {
    renderChartTotalVehicles("2018");
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
                             <li class="nav-item"><a class="nav-link" href="dashboard"></i>Dashboard</a></li>
                             <li class="nav-item"><a class="nav-link" href="dashboard/logout"></i>Signout</a></li>

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
			  </ul>
			</nav>
		</div>
    <div class="container-fluid">
        <div class="wrapper mx-auto">
           <div class="mt-3" style="margin-left : 4rem;">
             <h1>Dashboard</h1>
             
           </div>

           <div class="card" style="margin-left : 4rem;">
             <div class="card-header">
               <h2>
                 Statistics
               </h2>
             </div>
             <div class="card-body">
               <div id="chartTotalVehicles" style="height : 60vh;">

              </div>
             </div>
            
           </div>
            
        </div>   
    </div>
</body>
</html>