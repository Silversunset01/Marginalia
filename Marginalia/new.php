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

ini_set('display_errors', '1');
ini_set('error_reporting', E_ALL);

//Add new record
	if (isset($_POST['addnew'])){ 
		try {
			$NewItm = $db->prepare("INSERT INTO Notes (owner, notebook, Title, MDText, HTMLText, Tag) VALUES (?, ?, ?, ?, ?, ?)");
			$NewItm->bind_param("ssssss", $_SESSION["username"], $_POST['Notebook'], $_POST['Title'], $_POST['MDText'], $_POST['HTMLText'], $_POST['Category']);
			$NewItm->execute();
			header('Location: main');
			$NewItm->close();
		} catch (Exception $e) {die($e->getMessage());}
	}
?>
 
 
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
	<script src="javascript/marked.js"></script>
	<script src="javascript/md-formatting.js"></script>
	<script src="javascript/md-preview.js"></script>
    <style type="text/css">
		html{
			height: 100%;
			 width: 100%;
		}
		 body {
			height: calc(100% - 100px);
		}
		 .container-fluid {
			height: 100%;
		}
		 .myform, .formhead, form, textarea {
			height: 100%;
		}
		 #Preview {
			display: none;
			height: calc(100% - 40px);
			overflow-y: auto;
			border-radius: .2rem;
			padding: .25rem .5rem;
			/*border: 1px solid #ced4da;*/
		}
		 #MDText {
			height: calc(100% - 40px);
		}
    </style>
</head>
<body>
	
<?php include("php/menu.php"); ?>

<div class="container-fluid">

	<div class="formgroup myform" id="MDInput">
		<form action='?' method='POST'>
			<input type='hidden' name='addnew' value='true'>
				<div class="input-group mb-3">
					<input type="text" name="Title" id="Title" placeholder='Note Title' class="form-control form-control-sm">
					<input type="text" name="Notebook" id="Notebook" placeholder='Notebook' class="form-control form-control-sm">
					<select name="Category" id="Category" class="form-control form-control-sm">
						<option value="z-none">No Icon</option>
						<option value="flag">Bookmarks</option>
						<option value="caldate">Calendar/Date</option>
						<option value="code">Code</option>
						<option value="computer">Computers</option>
						<option value="folder">Documentation</option>
						<option value="journal">Journal/Writing</option>
						<option value="tasks">Tasks</option>
						<option value="web">Websites</option>
					</select>
				</div>
				<textarea name="MDText" id="MDText" rows="15" class="form-control inputarea" onkeyup="mdStart()"></textarea>
				<textarea name="HTMLText" id="HTMLText" readonly="readonly" class="form-control" style="display: none!important;"></textarea>
			<input type='submit' class="btn btn-dark btn-sm mt-3" value="Save">
			<a href="#" id="togglePreview" class="btn btn-secondary btn-sm mt-3">Preview</a>
		</form>
	</div>
	<div id="Preview" class="mt-5 mb-5"></div>
</div>

</body>
</html>