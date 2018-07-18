<!DOCTYPE html>
<html>
<head>
<title>Bio Data</title>
<link href="Resources/Assets/css/bootstrap.min.css" rel="stylesheet">
<script src="Resources/Assets/js/jquery.js"></script>
<script src="Resources/Assets/js/popper.min.js"></script>
<script src="Resources/Assets/js/bootstrap.min.js"></script>
<style type="text/css">
	@import url(https://fonts.googleapis.com/css?family=Raleway:300,400,600);

    body {
		font-family: 'Raleway', sans-serif;
		width : 100%;
		padding : 0;
		margin : 0;
		overflow : none;
	}

	.card {
		position : relative;
		width : 100%;
		top : 20%;
	}

</style>
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
                        RS Grand Chuchu
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <ul class="nav navbar-nav ml-auto">
                             <li class="nav-item"><a class="nav-link" href="/MVC"></i>Login</a></li>
                             <li class="nav-item"><a class="nav-link" href="/register"></i>Sign Up</a></li>

                    </ul>
                </div>
        </nav>
	<div class="container">
		    <div class="row">
		        <div class="col-md-6 mx-auto">
		            <div class="card">
		                <div class="card-header" style="background-color : #3097D1; text-align:center; color:white;font-size:30px;">Register</div>

		                <div class="card-body">
		              
		                    <form class="form-horizontal" method="POST" action="register/new">

		                        <div class="form-group">		                            
		                            <label for="username" class="col-md-6 control-label">Username</label>

		                            <div class="col-md-12">
		                                <input id="username" type="text" class="form-control" name="username" value="" required autofocus>
		                            </div>
		                        </div>

		                        <div class="form-group">
		                            <label for="password" class="col-md-4 control-label">Password</label>

		                            <div class="col-md-12">
		                                <input id="password" type="password" class="form-control" name="password" required>
		                            </div>
		                        </div>

		                        <div class="form-group">
		                            <label for="rep-password" class="col-md-6 control-label">Repeat Password</label>

		                            <div class="col-md-12">
		                                <input id="repassword" type="password" class="form-control" name="repassword" required>
		                            </div>
		                        </div>
		                      
		                        <div class="form-group">
		                            <div class="col-md-8 col-md-offset-4">
		                                <button type="submit" class="btn btn-primary" name="submit">
		                                    Create Account
		                                </button>
		                            </div>
		                        </div>
		                    </form>
		                </div>
		            </div>
		        </div>
		    </div>
	    </div>
	  </div>
</body>
</html>