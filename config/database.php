<?php
return [ 
	'configuration' => [
		'host' => 'localhost',
		'username' => 'root',
		'password' => '',
		'database' => 'carwash'
	],
	'options' => [
		PDO::ATTR_PERSISTENT => true,
		PDO::ATTR_ERRMODE => true,
	]
];

?>