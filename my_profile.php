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
			
				$akich_permission_level_strings = array(
					"0" => "Unregistered user",
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
			
				$loc = $_SERVER['REQUEST_URI'];
				//echo "You are on $loc <br><br>";
				//echo "Filtered: " . akich_get_current_board();
				echo "<h1>" . $akich_larr['my_profile'] . "</h1>";
				echo "<hr>";
				
				$user = $_SESSION['akich_user_name'];
				$user_query = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM user WHERE user_name='$user'");
				$row = mysqli_fetch_assoc($user_query);
				$user_parameters = json_decode($row['user_parameters'], true);
				
				if($user_parameters != NULL){
					if(array_key_exists("user_picture", $user_parameters)){
						echo '<img src="' . AKICH_ROOT . 'userpfps/' . $user_parameters['user_picture'] . '">';
					}
				}
				//echo "<br><br>";
				//echo '<form action="my_profile.php" method="post" enctype="multipart/form-data">';
					//echo '<input type="file" name="avatar" id="avatar" accept=".jpg,.jpeg,.png,.bmp,.gif"><br>';
					//echo '<input type="submit" value="' . "Upload profile picture" . '" name="submit_pfp">';
				//echo "</form>";
				$user_id = $row['id_user'];
				
				echo "<table>";
					echo "<tr>";
						echo "<td width=160px>";
						echo "Username";
						echo "</td>";
						
						echo "<td>";
						echo $row['user_name'];
						echo "</td>";
					echo "</tr>";
					
					echo "<tr>";
						echo "<td>";
						echo "Email address";
						echo "</td>";
						
						echo "<td>";
						echo '<form action="my_profile.php" method="post">';
							echo '<input type="text" name="user_email" id="user_email" value="' . $row['user_email'] . '">';
							echo '<input type="submit" value="' . "Update email" . '" name="submit_email">';
						echo "</td>";
					echo "</tr>";
					
					echo "<tr>";
						echo "<td>";
						echo "Permission level";
						echo "</td>";
						
						echo "<td>";
						echo $row['user_permission_level'] . " - " . $akich_permission_level_strings[$row['user_permission_level']];
						echo "</td>";
					echo "</tr>";		

					echo "<tr>";
						echo "<td>";
						echo "Date of registration";
						echo "</td>";
						
						echo "<td>";
						echo $row['user_date_registered'];
						echo "</td>";
					echo "</tr>";					
					
					echo "<tr>";
						echo "<td>";
						echo "Date of last login";
						echo "</td>";
						
						echo "<td>";
						echo $row['user_date_last_login'];
						echo "</td>";
					echo "</tr>";					
					
				echo "</table>";
				echo "<hr>";
				echo "<h2>Board whitelist</h2>";
				
				$board_list_query = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM board");
				
				echo "<form method=post>";
				
				while($row = mysqli_fetch_assoc($board_list_query)){
					echo '<input type="checkbox" name="boards[]" value="' . $row['board_name'] . '"/> - ' . $row['board_name'] . '<br>';
				}
				echo '<input type="submit" name="submit" value="Set">';
				
				echo "</form>";
				
				if(isset($_POST['boards']) and is_array($_POST['boards'])){
					
					$whitelist = "";
					
					foreach($_POST['boards'] as $board){
						$whitelist .= $board . ",";
					}
					$whitelist=substr($whitelist, 0, -1);
					//print_r($whitelist);
				
					$whitelist_string = '{"user_board_whitelist": "' . $whitelist . '"}';
					print_r($whitelist_string);
				
					mysqli_query($GLOBALS['mysqli'], "UPDATE user SET user_parameters='$whitelist_string' WHERE id_user='$user_id'");
				}
				else if(isset($_POST['user_email'])){
					$email = $_POST['user_email'];
					mysqli_query($GLOBALS['mysqli'], "UPDATE user SET user_email='$email' WHERE id_user='$user_id'");
					echo "<br>Email updated!";
				}
				
			?>
		</div>
</body>
</html>
