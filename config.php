<?php
	define('DB_SERVER', 'localhost:3306');
	define('DB_USERNAME', 'basicuser');
	define('DB_PASSWORD', 'basicuser@password');
	define('DB_NAME', 'timey');

	$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

	if($link === false)
	{
		die("ERROR: Could not connect. " . mysqli_connect_error());
	}
?>
