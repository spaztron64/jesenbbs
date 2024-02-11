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
	<form action="upload_logic.php" method="post">
		User name: <input type="text" name="user_name" id="user_name"><br>
		User pass: <input type="text" name="user_pass" id="user_pass"><br>
		User email: <input type="email" name="user_email" id="user_email"><br>
		User permission level: <input type="number" name="user_permission_level" id="user_permission_level"><br>
		<input type="submit" value="Add user" name="submit">
	</form>
	<a href="../">Back to homepage</a>
	
</body>
</html>
