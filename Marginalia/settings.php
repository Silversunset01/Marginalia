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

//Empty Trash
	if (isset($_GET['emptyTrash'])) {
		try {
			$DeleteItm = $db->prepare("DELETE FROM Notes WHERE Trash = '1' AND owner = ?");
			$DeleteItm->bind_param("s", $_SESSION['username']);
			$DeleteItm->execute();
			if($DeleteItm->affected_rows === 0) exit('Error: '.$DeleteItm->error);
			header('Location: main');
			$DeleteItm->close();
		} catch (Exception $e) {die($e->getMessage());}
	}
//Restore Record
	if (isset($_GET['restore'])){ 
		try {
			$Restore = $db->prepare("UPDATE Notes SET Trash = '0' WHERE ID = ? AND owner = ?");
			$Restore->bind_param("is", $_GET['ID'], $_SESSION['username']);
			$Restore->execute();
			//if($Restore->affected_rows === 0) exit('Error: '.$Restore->error);
			header('Location: main?ID='.$_GET['ID']);
			$Restore->close();
		} catch (Exception $e) {die($e->getMessage());}
	}
?>
 
 
<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo SITENAME;?></title>
	<link href="resources/favicon.ico" rel="shortcut icon" type="image/x-icon" />
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

	<link href="https://fonts.googleapis.com/css?family=Montserrat|Source+Code+Pro" rel="stylesheet">
	
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
	<link rel="stylesheet" href="css/style.css">
    <style type="text/css">

    </style>
</head>
<body>
 
<?php include("php/menu.php"); ?>

<div class="container">
	<div class="row">
		<div class="col-sm-8">
			<h1>Information</h1>
			<span>Currently logged in as: <?php echo $_SESSION["username"];?></span><br/>
			<span>Last logged in: [SOME TIME]</span>
		</div>
		<div class="col-sm-4">
			<h1>Options</h1>
			<a href="php/logout.php">Log Out</a><br/>
			<a href="reset-password">Change Password</a><br/>
			<a class="text-danger" href="?emptyTrash=true">Empty Trash</a><span> - This cannot be undone!</span>
		</div>
	</div>
	<div class="row">
		<div class="col-12 container">
			<h1><i class="fas fa-trash-alt"></i> Trash</h1>
		  <ul class="row border-bottom list-unstyled">
			<?php
				$tb = $db->prepare("SELECT * FROM Notes WHERE owner = ? AND Trash='1' ORDER BY Title ASC");
				$tb->bind_param("s", $_SESSION['username']);
				$tb->execute();
				$tbresult = $tb->get_result();
				if($tbresult->num_rows === 0) echo ('<a class="dropdown-item" href="#">Empty</a>');
				while ($tbrow = $tbresult->fetch_array()){
					echo '<li class="col-3"><a href="?restore=true&ID='.$tbrow['ID'].'" class="text-warning"><i class="fas fa-reply"></i></a>&emsp;<a href="main?ID='.$tbrow['ID'].'">'.$tbrow['notebook'].': '.$tbrow['Title'].'</a></li>';
					
				}
				$tb->close();
			?>
		  </ul>
		</div>
		
	</div>
</div>

</body>
</html>