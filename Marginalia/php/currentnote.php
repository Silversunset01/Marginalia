<?php 
require_once "../config.php";

$db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($db->connect_error) {die("Connection Failed: ".$db->connect_error);}

	$ct = $db->prepare("SELECT * FROM Notes WHERE owner = ? AND ID = ?");
	$ct->bind_param("si", $_GET['USR'], $_GET['ID']);
	$ct->execute();
	$ctresult = $ct->get_result();
	//if($ctresult->num_rows === 0) exit (' ');
	while($ctrow = $ctresult->fetch_array()){
		echo $ctrow['MDText'];
		//echo "<textarea name='newmdtext' style='display:none;' id='mdText554283645f13s58f9a5s5f13' rows='15' class='form-control form-control-sm inputarea'>".$ctrow['MDText']."</textarea>";
	}
	$ct->close();
?>
