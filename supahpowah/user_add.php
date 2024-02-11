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
	<form action="user_add.php" method="post">
		User name: <input type="text" name="user_name" id="user_name"><br>
		User pass: <input type="password" name="user_pass" id="user_pass"><br>
		User email: <input type="email" name="user_email" id="user_email"><br>
		User permission level: <select name="user_permission_level" id="user_permission_level">
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
		<input type="submit" value="Add user" name="submit">
	</form>
	
	<?php
	
	if(!isset($_POST['user_name'])){
		die();
	}
	else{
		extract($_POST);
	
		if($submit){
		
			if ($_POST['user_name']==""){
				echo "Blank user names are not allowed.";
			}
			else if ($_POST['user_pass']==""){
				echo "Blank passwords are not allowed.";
			}
			else if ($_POST['user_email']==""){
				echo "Blank user emails are not allowed";
			}		
			else if ($_POST['user_permission_level']=="" || $_POST['user_permission_level'] < 0 || $_POST['user_permission_level'] > 9){
				echo "User permission level must be set to a value between 0 and 9.";
			}				
			else{
				$user_name = $_POST['user_name'];
				$user_pass = $_POST['user_pass'];
				$user_pass = password_hash($user_pass, PASSWORD_BCRYPT);
				$user_email = $_POST['user_email'];
				$user_permission_level = $_POST['user_permission_level'];
				mysqli_query($mysqli, "INSERT INTO user VALUES(NULL,'$user_name','$user_pass','$user_email','$user_permission_level', NULL, NOW(), NOW())");
				
				$retout = null;
				$retval = null;
			}
		}
	}
	
	?>
	
</body>
</html>
