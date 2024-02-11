<!DOCTYPE HTML>
<html>
<head>
	<link rel="stylesheet" href="../css/main.css">
	<title>AkiChannel</title>
	<?php
		include_once("../includes.php");
	?>
</head>
<body>
	<?php
	//if(!isset($_SESSION['username'])){
	//	header('Location: '."index.html");
	//}
	?>
	<a href="../supahpowah/">Back to superuser menu</a><br><br>
	<form action="board_add.php?func=add" method="post">
		Board name: <input type="text" name="board_name" id="board_name"><br>
		Board default display name: <input type="text" name="board_default_display_name" id="board_default_display_name"><br>
		Board minimum permission level: <select name="board_minimum_permission_level" id="board_minimum_permission_level"><br>
		<option value="0">0 - Unregistered user</option>
		<option value="1">1 - New member</option>
		<option value="2">2 - Slightly experienced member</option>
		<option value="3">3 - Slightly more experienced member</option>
		<option value="4">4 - Moderately experienced member</option>
		<option value="5">5 - Decently experienced member</option>
		<option value="6">6 - Veteran</option>
		<option value="7">7 - Oldbie</option>
		<option value="8">8 - Moderator</option>
		<option value="9">9 - Administrator / Site Staff</option>
		</select><br>
		<input type="submit" value="Add board" name="submit">
	</form>
	
	<?php
	
	if(!isset($_POST['board_name'])){
		die();
	}
	else{
		extract($_POST);
	
		if($submit){
		
			if ($_POST['board_name']==""){
				echo "Blank board names are not allowed.";
			}
			else if ($_POST['board_default_display_name']==""){
				echo "You must set a default board display name.";
			}
			else{
				$board_name = $_POST['board_name'];
				$board_default_display_name = $_POST['board_default_display_name'];
				$board_minimum_permission_level = $_POST['board_minimum_permission_level'];
				
				$board_param_array = array(
					"board_default_display_name" => "$board_default_display_name",
					"board_minimum_permission_level" => $board_minimum_permission_level,
				);
				
				$board_param_json = json_encode($board_param_array);
				
				mysqli_query($mysqli, "INSERT INTO board VALUES(NULL,'$board_name','$board_param_json')");
				
				$retout = null;
				$retval = null;
			}
		}
	}
	
	?>
	
</body>
</html>
