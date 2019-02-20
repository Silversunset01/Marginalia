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

//update stylesheet
	$ss = $db->prepare("SELECT styleOption FROM Users WHERE username = ?");
	$ss->bind_param("s", $_SESSION['username']);
	$ss->execute();
	$ssresult = $ss->get_result();
	while($ssrow = $ssresult->fetch_array()){
		$styleOption = $ssrow['styleOption'];
	}
	$ss->close();

//Edit record
	if (isset($_POST['Title'])){ 
		try {
			$EditItm = $db->prepare("UPDATE Notes SET Title = ?, notebook = ?, MDText = ?, HTMLtext = ?, Tag = ? WHERE ID = ? AND owner = ?");
			$EditItm->bind_param("sssssis", $_POST['Title'], $_POST['Notebook'], $_POST['MDText'], $_POST['HTMLText'], $_POST['newCategory'], $_GET['ID'], $_SESSION['username']);
			$EditItm->execute();
			//if($EditItm->affected_rows === 0) exit('Error: ' . $EditItm->error);
			header('Location: main?NB='.$_POST['Notebook'].'&ID='.$_GET['ID'].'&Title='.$_POST['Title']);
			$EditItm->close();
		} catch (Exception $e) {die($e->getMessage());}
	} 
	
//Soft Delete Record
	if (isset($_GET['delete'])){ 
		try {
			$TDelete = $db->prepare("UPDATE Notes SET Trash = '1' WHERE ID = ? AND owner = ?");
			$TDelete->bind_param("is", $_GET['ID'], $_SESSION['username']);
			$TDelete->execute();
			//if($TDelete->affected_rows === 0) exit('Error: '.$TDelete->error);
			header('Location: main');
			$TDelete->close();
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
	<script src="javascript/md-headertest.js"></script>
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
<body onload="mdStart()">
	
 
 <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top pt-0 pb-0"> 
<a class="navbar-brand" href="AllNotes"><i class="fas fa-book-open"></i></a>
		<!-- collapse button -->
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
			<span class="navbar-toggler-icon"></span>
		</button>

	<!-- item -->
	<div class="collapse navbar-collapse" id="collapsibleNavbar">	
		<ul class="navbar-nav w-100">
		<span class="navbar-text smHide">|</span>
			<li class="nav-item">
				<a class="nav-link" href="new"><i class="far fa-file"></i><span class="smShow"> New Note</span></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="editlink" href="#"><i class="far fa-edit"></i><span class="smShow"> Edit</span></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="settings"><i class="fas fa-cog"></i><span class="smShow"> Settings</span></a>
			</li>	
			<li class="nav-item">
				<a class="nav-link" href="#" id="toggleSearch"><i class="fas fa-filter"></i><span class="smShow"> Toggle Filter</span></a>
			</li>
			<!-- Search Box -->
			<form class="form-inline">
				<input class="form-control form-control-sm" type="text" placeholder="Filter Tables" id="myInput" style="display:none; margin-right: 10px;">
			</form>
				
		<span class="navbar-text smHide">|</span>
		<span class="navbar-text font-weight-bold mb-0 ml-2">Notebooks:</span><span id="Notebooks" onchange="displayNotes()"></span>
		<span class="navbar-text font-weight-bold mb-0 ml-2">Notes:</span><span id="Notes" onchange="displayMD()"></span>

		</ul>
	  </div>
 </nav>

<div class="container-fluid">

<div class="formgroup myform" id="MDInput">
		<form action='?' method='POST'>
			<input type='hidden' name='newid' id='newid'>
				<div class="formhead" style="margin-bottom: 10px;">
				<?php
					$ed = $db->prepare("SELECT * FROM Notes WHERE ID = ? AND owner = ?");
					$ed->bind_param("is", $_GET['ID'], $_SESSION['username']);
					$ed->execute();
					$result = $ed->get_result();
					if($result->num_rows === 0) exit('Select an item from the sidebar to edit.');
					while($row = $result->fetch_array()){
						//define tag items
							switch ($row['Tag']) {
								case "caldate": $tag = "Calendar/Date"; 
									break;
								case "folder": $tag = "Documentation";
									break;
								case "flag": $tag = "Bookmarks";
									break;
								case "computer": $tag = "Computers";
									break;
								case "code": $tag = "Code";
									break;
								case "journal": $tag = "Journal/Writing";
									break;
								case "web": $tag = "Websites";
									break;
								case "tasks": $tag = "Tasks";
									break;
								default: $tag = "No Icon";
							};
						//create input box
						echo "<div class='input-group mb-3'>";
						echo "	<input type='text' class='form-control form-control-sm' value='".$row['Title']."' placeholder='Note Name' name='Title' id='Title'>";
						echo "	<input type='text' class='form-control form-control-sm' value='".$row['notebook']."' placeholder='Notebook Name' name='Notebook' id='Notebook'>";
						echo "	<select name='newCategory' id='newCategory' class='form-control form-control-sm'>";
						echo "				<option value='".$row['Tag']."'>Current: ".$tag."</option>";
						echo "				<option value='z-none'>No Icon</option>";
						echo "				<option value='flag'>Bookmarks</option>";
						echo "				<option value='caldate'>Calendar/Date</option>";
						echo "				<option value='code'>Code</option>";
						echo "				<option value='computer'>Computers</option>";
						echo "				<option value='folder'>Documentation</option>";
						echo "				<option value='journal'>Journal/Writing</option>";
						echo "				<option value='tasks'>Tasks</option>";
						echo "				<option value='web'>Websites</option>";
						echo "			</select>";
						echo "</div>";
						echo "<textarea name='MDText' id='MDText' class='form-control form-control-sm inputarea' onkeyup='mdStart()'>".$row['MDText']."</textarea>";
					}
					$ed->close();
				?>
				</div>
				<textarea name="HTMLText" id="HTMLText" readonly="readonly" style="display: none!important;"></textarea>
			<input type='submit' formaction='?ID=<?php echo $_GET['ID']; ?>' class="btn btn-dark btn-sm" value="Save"> 
			<a href="#" id="togglePreview" class="btn btn-secondary btn-sm">Preview</a>
			<a href="main?ID=<?php echo $_GET['ID'];?>" class="btn btn-warning btn-sm">Cancel</a>
			<a href="?delete=true&ID=<?php echo $_GET['ID'];?>" class="btn btn-danger btn-sm float-right">Delete</a>
		</form> 
	</div>
	<div id="Preview" class="mt-5"></div>
</div>

<script>
//Display Notebook Listing
function displayNB(myUsr) {
    if (myUsr.length == 0) { 
        document.getElementById("Notebooks").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("Notebooks").innerHTML = this.responseText;
				displayNotes();
            }
        };
        xmlhttp.open("GET", "php/menuNotebooks.php?USR=" + myUsr, true);
        xmlhttp.send();
    }
};

//Display Notes Listing
function displayNotes() {
	var myNB = document.getElementById("nb1").value; 
	document.getElementById("Notes").innerHTML = myNB;
	var Usr = '<?php echo $_SESSION["username"];?>';
    if (myNB.length == 0) { 
        document.getElementById("Notes").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("Notes").innerHTML = this.responseText;
				displayMD();
            }
        };
        xmlhttp.open("GET", "php/menuNotes.php?NB=" + myNB + "&USR=" + Usr, true);
        xmlhttp.send();
    }
}
</script>
</body>
</html>