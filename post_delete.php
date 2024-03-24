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
				
				$current_user = akich_check_login_status();
			
				$loc = $_SERVER['REQUEST_URI'];
				
				
				if(isset($_GET['delete_post'])){
					$id_post = $_GET['delete_post'];
					$post_query = mysqli_execute_query($GLOBALS['mysqli'], "SELECT * FROM post WHERE id_post=?", [$id_post]);
					$post = mysqli_fetch_assoc($post_query);
					$thread_id = $post['id_thread'];
					$current_thread_query = mysqli_execute_query($GLOBALS['mysqli'], "SELECT * FROM thread WHERE id_thread=?", [$thread_id]);
					$row_thread = mysqli_fetch_assoc($current_thread_query);
					$board_id = $row_thread['id_board'];
					$current_board_query = mysqli_execute_query($GLOBALS['mysqli'], "SELECT board_name FROM board WHERE id_board=?", [$board_id]);
					$row_board = mysqli_fetch_assoc($current_board_query);
					$user_query = mysqli_execute_query($GLOBALS['mysqli'], "SELECT id_user FROM user WHERE user_name=?", [$current_user]);
					$user = mysqli_fetch_assoc($user_query);
					
					if($permit_level > 7){
						mysqli_execute_query($GLOBALS['mysqli'], "DELETE FROM attachment WHERE id_post=?", [$id_post]);
						mysqli_execute_query($GLOBALS['mysqli'], "UPDATE post SET post_content='<b><i>Post deleted by Administration</b></i>' WHERE id_post=?", [$id_post]);
						$post_param_array = array(
							"post_deleted_by" => "admin",
						);
				
						$post_param_json = json_encode($post_param_array);
				
						mysqli_execute_query($mysqli, "UPDATE post SET post_parameters=? WHERE id_post=?", [$post_param_json, $id_post]);
						echo "<br>Post deleted.";
						header("Location:" . AKICH_ROOT . $row_board['board_name'] . '/' . $thread_id);
					}
					else if($current_user != "nanashi"){
						
						if($post['id_user'] == $user['id_user']){
							mysqli_execute_query($GLOBALS['mysqli'], "DELETE FROM attachment WHERE id_post=?", [$id_post]);
							mysqli_execute_query($GLOBALS['mysqli'], "UPDATE post SET post_content='<i>Post deleted by user</i>' WHERE id_post=?", [$id_post]);	
							
							$post_param_array = array(
							"post_deleted_by" => "user",
							);
				
							$post_param_json = json_encode($post_param_array);
				
							mysqli_execute_query($mysqli, "UPDATE post SET post_parameters=? WHERE id_post=?", [$post_param_json, $id_post]);
							
							echo "<br>Post deleted.";
							header("Location:" . AKICH_ROOT . $row_board['board_name'] . '/' . $thread_id);
						}
						else
							echo "<br>You're not allowed to do that!";
					}
					else{
						echo "Get out of here nanashi.";
					}
				}
				
				echo "<a href=" . AKICH_ROOT . "><br>Back to homepage</a>";
			?>
	
		</div>
</body>
</html>
