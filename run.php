<?php
class Test {
	public function display() {
		echo "hello";
	}
}

function route($route)
{
	if($route == "home") {
		echo "hello";
	}
}

$obj = new Test();

include "test.php";



?>