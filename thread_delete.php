<!DOCTYPE HTML>
<html>
<head>
	<link rel="stylesheet" href="css/main.css">
	<title>AkiChannel</title>
	<?php
		include_once("includes.php");
	?>
</head>
<body>
	<?php
		include('elements/menubar.php');
	?>
		<div id="bbsmenu">
			<?php
				include('bbsmenu.php');
			?>
		</div>
		<div id="main_content">	
			<?php
				
				$permit_level = akich_get_current_user_permission_level();
			
				$loc = $_SERVER['REQUEST_URI'];
				
				if(isset($_GET['delete_thread'])){
					$id_thread = $_GET['delete_thread'];
					if($permit_level > 7){
						$current_thread_query = mysqli_query($GLOBALS['mysqli'], "SELECT id_board FROM thread WHERE id_thread='$id_thread'");
						$row = mysqli_fetch_assoc($current_thread_query);
						$board_id = $row['id_board'];
						$current_board_query = mysqli_query($GLOBALS['mysqli'], "SELECT board_name FROM board WHERE id_board='$board_id'");
						$row_board = mysqli_fetch_assoc($current_board_query);
						mysqli_query($GLOBALS['mysqli'], "DELETE FROM attachment WHERE id_thread=$id_thread");
						mysqli_query($GLOBALS['mysqli'], "DELETE FROM post WHERE id_thread=$id_thread");
						mysqli_query($GLOBALS['mysqli'], "DELETE FROM thread WHERE id_thread=$id_thread");
						echo "<br>Thread deleted.";
						header("Location:" . AKICH_ROOT . $row_board['board_name'] . '/');
					}
					else{
						echo "<br>You're not allowed to do that!";
					}
				}
				
				echo "<a href=" . AKICH_ROOT . "><br>Back to homepage</a>";
			?>
	
		</div>
</body>
</html>
