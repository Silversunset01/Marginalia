<?php
require_once "../config.php";

$db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($db->connect_error) {die("Connection Failed: ".$db->connect_error);}

$mb = $db->prepare("SELECT * FROM Notes WHERE owner = ? AND Trash='0' ORDER BY notebook ASC");
$mb->bind_param("s", $_GET['USR']);
$mb->execute();
$mbresult = $mb->get_result();
$nbList = [];

//Create List of Notebooks
if (empty($_GET['NB'])){
	$nbTitle = "Select a Notebook";
} else {
	$nbTitle = "<b>Notebook:</b> ".$_GET['NB'];
}
echo '<div class="form-group form-inline mb-0 ml-2">';
echo '	<select class="form-control bg-dark navbar-text border border-dark pt-0 pb-0 pl-0 pr-0" id="nb1">';
echo '			<option value="">Choose a Notebook</option>';

while ($mbrow = $mbresult->fetch_array()){
	array_push($nbList, $mbrow['notebook']);
	$nbList = array_unique($nbList);
}

foreach ($nbList as $nb){
	echo '		<option value="'.$nb.'">'.$nb.'</option>';
}

echo '	</select>';
echo '</div>';

$mb->close();
?>