<!DOCTYPE HTML>
<html>
<head>
	<link rel="stylesheet" href="/akichannel/css/main.css">
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
	
	<form method="get">
		Edit which user? <select name="user_id" id="user_id"><br>
		<?php
			$user_query = mysqli_query($GLOBALS['mysqli'], "SELECT id_user,user_name FROM user");
			while($row_user = mysqli_fetch_assoc($user_query)){
				echo '<option value="' . $row_user['id_user'] . '"> ' . $row_user['id_user'] . ' - ' . $row_user['user_name'] . '</option>';
			}
		?>
		</select><br>
		<input type="submit" value="Choose" formmethod="get">
	</form>
	
	
	<?php
	
	if(!isset($_GET['user_id'])){
		die();
	}
	else{
		$akich_permission_level_strings = array(
			"0" => "Unregistered user (reserved only for nanashi)",
			"1" => "New Member",
			"2" => "Slightly experienced member",
			"3" => "Slightly more experienced member",
			"4" => "Moderately experienced member",
			"5" => "Decently experienced member",
			"6" => "Veteran",
			"7" => "Oldbie",
			"8" => "Moderator",
			"9" => "Administrator / Site Staff",
		);
		
		error_reporting(0);
		$user_id = $_GET['user_id'];
		$user_query = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM user WHERE id_user=$user_id");
		$row = mysqli_fetch_assoc($user_query);
		$user_name = $row['user_name'];
		$user_email = $row['user_email'];
		$user_permission_level = $row['user_permission_level'];
		
		echo '<form method="post">';
		echo "User name: <input type=\"text\" name=\"user_name\" id=\"user_name\" value=\"$user_name\"><br>";
		echo "User email: <input type=\"email\" name=\"user_email\" id=\"user_email\" value=\"$user_email\"><br>";
		echo "User permission level: <select name=\"user_permission_level\" id=\"user_permission_level\"><br>";
		for($i=1; $i<10; $i++){
			$namelevel = $akich_permission_level_strings[$i];
			if($i == $user_permission_level)
				echo "<option value=\"$i\" selected>";
			else
				echo "<option value=\"$i\">";
			echo "$i - $namelevel</option>";
		}
		echo "</select><br>";
		echo '<input type="submit" value="Edit user" name="submit">';
		echo '</form>';
		
		extract($_POST);
		
	
		if(isset($_POST)){
		
			if ($_POST['user_name']==""){
				echo "Blank user names are not allowed.";
			}
			else if ($_POST['user_email']==""){
				echo "Blank user emails are not allowed";
			}		
			else if ($_POST['user_permission_level']=="" || $_POST['user_permission_level'] < 0 || $_POST['user_permission_level'] > 9){
				echo "User permission level must be set to a value between 0 and 9.";
			}				
			else{
				error_reporting(-1);
				$user_name = $_POST['user_name'];
				$user_email = $_POST['user_email'];
				$user_permission_level = $_POST['user_permission_level'];
				mysqli_query($mysqli, "UPDATE user SET user_name='$user_name', user_email='$user_email', user_permission_level=$user_permission_level WHERE id_user=$user_id");
				echo "Success!";
				
				$retout = null;
				$retval = null;
			}
		}
	}
	
	
	?>
	
	
	
	
</body>
</html>
