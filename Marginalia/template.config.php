<?php
//Define Database Connection Information
	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'enter database user here');
	define('DB_PASSWORD','enter database password here');
	define('DB_NAME', 'enter database name here');
	
	$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

//Misc Information
	define('SITENAME','Marginalia');
	
?>