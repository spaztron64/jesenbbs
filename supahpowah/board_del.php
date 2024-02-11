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
		Delete which board? <select name="board_id" id="board_id"><br>
		<?php
		$board_query = mysqli_query($GLOBALS['mysqli'], "SELECT id_board,board_name FROM board");
			while($row_board = mysqli_fetch_assoc($board_query)){
				echo '<option value="' . $row_board['id_board'] . '"> ' . $row_board['id_board'] . ' - ' . $row_board['board_name'] . '</option>';
			}
		?>
		</select><br>
		<input type="submit" value="Choose">
	</form>
	
	
	<?php
	
	if(!isset($_POST['board_id'])){
		die();
	}
	else{
		$board_id = $_POST['board_id'];
		//Disabling foreign key checks because actually deleting all posts, attachments and threads would scale like shit.
		mysqli_query($GLOBALS['mysqli'], "SET foreign_key_checks = 0");
		mysqli_query($GLOBALS['mysqli'], "DELETE FROM board WHERE id_board=$board_id");
		mysqli_query($GLOBALS['mysqli'], "SET foreign_key_checks = 1");
		echo mysqli_error($GLOBALS['mysqli']);
		echo "Success.";
	}
	
	
	?>
	
	
	
	
</body>
</html>
