<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login");
    exit;
}
define ('loggedUser', $_SESSION["username"]);
require_once "config.php";

$db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($db->connect_error) {die("Connection Failed: ".$db->connect_error);}

?>
 
 
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
	
	<link href="https://fonts.googleapis.com/css?family=Montserrat|Source+Code+Pro" rel="stylesheet">

	<link href="resources/favicon.ico" rel="shortcut icon" type="image/x-icon" />
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
	<title><?php echo SITENAME;?></title>
	<link rel="stylesheet" href="css/style.css">
	<script src="javascript/scripts.js"></script>
    <style type="text/css">
		
    </style>
</head>
<body>
	
 
<?php include("php/menu.php"); ?>



<div class="container-fluid">
<h1>Full List of Notes</h1>
	<ul class="list-unstyled">
		<?php
			$sb = $db->prepare("SELECT * FROM Notes WHERE owner = ? AND Trash='0' ORDER BY notebook ASC");
			$sb->bind_param("s", $_SESSION['username']);
			$sb->execute();
			$sbresult = $sb->get_result();
			if($sbresult->num_rows === 0) exit ('No Notebooks');
			$nbList = [];
			while ($sbrow = $sbresult->fetch_array()){
				array_push($nbList, $sbrow['notebook']);
				$nbList = array_unique($nbList);
			}
			foreach ($nbList as $nb){
				echo '<li>';
				echo '	<a href="#">'.$nb.'</a>';
				echo '	<ul>';
					//Create list of notes based on notebook
						$n = $db->prepare("SELECT * FROM Notes WHERE owner = ? AND notebook = ? and Trash = 0 ORDER BY Tag ASC, Title ASC");
						$n->bind_param("ss", $_SESSION['username'], $nb);
						$n->execute();
						$nresult = $n->get_result();
						while($nrow = $nresult->fetch_array()){
							//define tag items
							switch ($nrow['Tag']) {
								case 'caldate': $tag = '<i class="far fa-calendar-alt"></i>';
									break;
								case 'computer': $tag ='<i class="fas fa-laptop"></i>';
									break;
								case 'code': $tag ='<i class="fas fa-code"></i>';
									break;
								case 'journal': $tag = '<i class="fas fa-book"></i>';
									break;
								case 'folder': $tag = '<i class="far fa-folder-open"></i>';
									break;
								case 'flag': $tag = '<i class="far fa-bookmark"></i>';
									break;
								case 'web': $tag = '<i class="fas fa-globe"></i>';
									break;
								case 'tasks': $tag = '<i class="far fa-check-square"></i>';
									break;
								default: $tag = '';
							};
							echo '<li><a href="main?ID='.$nrow['ID'].'">'.$tag.' '.$nrow['Title'].'</a></li>';
						}
						$n->close();
					//end -> list of notes
				echo '	</ul>';
				echo '</li>';
			}
			$sb->close();
			//end -> list of notebooks
		?>
	</ul>	
	
</div>




<script>
//Filter Tables
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("tbody tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>

<script>
//Show/Hide search box
$(document).ready(function(){
    $("#toggleSearch").click(function(){
        $("#myInput").toggle();
    });
});

</script>

</body>
</html>