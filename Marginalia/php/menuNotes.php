<?php
require_once "../config.php";

$db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($db->connect_error) {die("Connection Failed: ".$db->connect_error);}

//Create List of Notes
$mb2 = $db->prepare("SELECT * FROM Notes WHERE owner = ? AND Notebook = ? AND Trash='0' ORDER BY Tag ASC, Title ASC");
$mb2->bind_param("ss", $_GET['USR'], $_GET['NB']);
$mb2->execute();
$mb2result = $mb2->get_result();

if (empty($_GET['ID'])){
	$noteTitle = "Select a Note";
} else {
	$noteTitle = "<b>Note:</b> ".$_GET['Title'];
}


echo '<div class="form-group form-inline mb-0 ml-2">';
echo '	<select class="form-control bg-dark navbar-text border border-dark pt-0 pb-0 pl-0 pr-0" id="note1">';

while ($mb2row = $mb2result->fetch_array()){
	echo '		<option name="'.$mb2row['Title'].'" value="'.$mb2row['ID'].'">'.$mb2row['Title'].'</option>';	
}
echo '	</select>';
echo '</div>';			
$mb2->close();
?>