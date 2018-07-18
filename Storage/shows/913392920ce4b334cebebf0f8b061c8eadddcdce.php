<!DOCTYPE html>
<html>
<head>
<title>RX Grand Wash</title>
</head>
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
		width : 100%;
		padding : 0;
		margin : 0;
		overflow : none;
	}

	.card {
		position : relative;
		top : 20%;
		width : 100%;
	}

	.card-body {
		padding : 2rem;
	}

</style>
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
                </div>
        </nav>
        <div class="container">
		    <div class="row">
		        <div class="col-md-6 mx-auto">
		            <div class="card">
		                <div class="card-header" style="background-color : #3097D1; text-align:center; color:white;font-size:30px;">Login</div>
		               
		                <div class="card-body">
		                    <form class="form-horizontal" method="POST" action="auth/login">
		                    <?php if($autherror) : ?>
		                    	<div class="alert alert-danger">
		                    		<?php echo(esc($autherror)); ?>
		                    	</div>
		                    <?php endif; ?>
		                        <div class="form-group">
		                            
		                            <label for="email" class="col-md-6 control-label">Username/Email Address</label>

		                            <div class="col-md-12">
		                                <input id="email" type="text" class="form-control" name="email" value="" required autofocus>
		                            </div>
		                        </div>

		                        <div class="form-group">
		                            <label for="password" class="col-md-4 control-label">Password</label>

		                            <div class="col-md-12">
		                                <input id="password" type="password" class="form-control" name="password" required>
		                            </div>
		                        </div>

		                        <div class="form-group">
		                            <div class="col-md-8 col-md-offset-4">
		                                <div class="checkbox">
		                                    <label>
		                                        <input type="checkbox" name="remember"> Remember Me
		                                    </label>
		                                </div>
		                            </div>
		                        </div>

		                        <div class="form-group">
		                            <div class="col-md-8 col-md-offset-4">
		                                <button type="submit" class="btn btn-primary" name="submit">
		                                    Login
		                                </button>
		                            </div>
		                        </div>
		                    </form>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
</body>
</html>