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
		Edit which board? <select name="board_id" id="board_id"><br>
		<?php
			$user_query = mysqli_query($GLOBALS['mysqli'], "SELECT id_board,board_name FROM board");
			while($row_user = mysqli_fetch_assoc($user_query)){
				echo '<option value="' . $row_user['id_board'] . '"> ' . $row_user['id_board'] . ' - ' . $row_user['board_name'] . '</option>';
			}
		?>
		</select><br>
		<input type="submit" value="Choose" formmethod="get">
	</form>
	
	
	<?php
	
	if(!isset($_GET['board_id'])){
		die();
	}
	else{
		$akich_permission_level_strings = array(
			"0" => "Unregistered user",
			"1" => "New member",
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
		$board_id = $_GET['board_id'];
		$board_query = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM board WHERE id_board=$board_id");
		$row = mysqli_fetch_assoc($board_query);
		$board_name = $row['board_name'];
		$board_param_array = json_decode($row['board_parameters'], true);
		$board_default_display_name = $board_param_array['board_default_display_name'];
		if(array_key_exists("board_minimum_permission_level", $board_param_array))
			$board_minimum_permission_level = $board_param_array['board_minimum_permission_level'];
		else
			$board_minimum_permission_level = 0;
		
		echo '<form method="post">';
		echo "Board name: <input type=\"text\" name=\"board_name\" id=\"board_name\" value=\"$board_name\"><br>";
		echo "Board default display name: <input type=\"text\" name=\"board_default_display_name\" id=\"board_default_display_name\" value=\"$board_default_display_name\"><br>";
		echo "Board minimum permission level: <select name=\"board_minimum_permission_level\" id=\"board_minimum_permission_level\"><br>";
		for($i=0; $i<10; $i++){
			$namelevel = $akich_permission_level_strings[$i];
			if($i == $board_minimum_permission_level)
				echo "<option value=\"$i\" selected>";
			else
				echo "<option value=\"$i\">";
			echo "$i - $namelevel</option>";
		}
		echo "</select><br>";
		echo '<input type="submit" value="Edit board" name="submit">';
		echo '</form>';
		
		extract($_POST);

		if(isset($_POST)){
				
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
					
					//mysqli_query($mysqli, "INSERT INTO board VALUES(NULL,'$board_name','$board_param_json')");
					mysqli_query($mysqli, "UPDATE board SET board_name='$board_name', board_parameters='$board_param_json' WHERE id_board=$board_id");
					echo "Success!";
					echo mysqli_error($GLOBALS['mysqli']);
					
					$retout = null;
					$retval = null;
			}
		}
	}
	
	
	?>
	
	
	
	
</body>
</html>
