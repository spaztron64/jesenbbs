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
	
	<form method="post">
		Delete which user? <select name="user_id" id="user_id"><br>
		<?php
			$user_query = mysqli_query($GLOBALS['mysqli'], "SELECT id_user,user_name FROM user");
			while($row_user = mysqli_fetch_assoc($user_query)){
				echo '<option value="' . $row_user['id_user'] . '"> ' . $row_user['id_user'] . ' - ' . $row_user['user_name'] . '</option>';
			}
		?>
		</select>
		<br><input type="submit" value="Delete">
	</form>
	
	
	<?php
	
	if(!isset($_POST['user_id'])){
		die();
	}
	else{
		$user_id = $_POST['user_id'];
		mysqli_query($GLOBALS['mysqli'], "DELETE FROM attachment WHERE id_user=$user_id");
		mysqli_query($GLOBALS['mysqli'], "DELETE FROM post WHERE id_user=$user_id");
		mysqli_query($GLOBALS['mysqli'], "DELETE FROM user WHERE id_user=$user_id");
		echo mysqli_error($GLOBALS['mysqli']);
		echo "Success.";
	}
	
	
	?>
	
	
	
	
</body>
</html>
