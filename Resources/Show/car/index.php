<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	@if($message)
		<h3>{$message}!</h3>
	@endif

	<center>
		@if($welcome)
			<h3>{$welcome}</h3>
		@endif
	</center>
	<center>
		<h3>Developers :
			@foreach($developers as $developer)
				{$developer}
			@endforeach
		</h3>
	</center>
	<h2>Add Car</h2>
	<form method="POST" action="car/new">
		Name of Car : 
		<input type="text" name="name">
		<br>
		Color of Car :
		<input type="text" name="color">
		<br>
		<input type="submit" value="Submit">
	</form>

	<h2>Update Car</h2>
	<form method="POST" action="car/update">
		ID of Car : 
		<input type="text" name="id">
		<br>
		New Car Name : 
		<input type="text" name="name">
		<br>
		<input type="submit" value="Submit">
	</form>

	<h2>Delete Car</h2>
	<form method="POST" action="car/delete">
		ID of Car : 
		<input type="text" name="id">
		<br>
		<input type="submit" value="Submit">
	</form>
</body>
</html>