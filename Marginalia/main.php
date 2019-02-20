<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login");
    exit;
}

require_once "config.php";

$db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($db->connect_error) {die("Connection Failed: ".$db->connect_error);}

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
	<script src="javascript/scripts.js"></script>
    <style type="text/css">
		
    </style>
</head>
<!--<body onload="mdStart()">-->
<body onload="displayMD('<?php echo $_SESSION["username"];?>', '<?php echo $_GET['ID'];?>')">
 
<?php include("php/menu.php"); ?>

<div class="container-fluid">
	<div id="parseText"></div>
</div>


<script>
//Display markdown text in the body of the page
function displayMD(myUsr, myID) {
    if (myID.length == 0) { 
        document.getElementById("parseText").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("parseText").innerHTML = marked(this.responseText);
				$("li:has(input:checked)").css("text-decoration", "line-through").css("font-style", "italic").css("color", "gray");
				$("li:has(input[type='checkbox'])").css("list-style-type", "none").css("margin-left", "-25px");
            }
        };
        xmlhttp.open("GET", "php/currentnote.php?ID=" + myID + "&USR=" + myUsr, true);
        xmlhttp.send();
    }
}
</script>

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