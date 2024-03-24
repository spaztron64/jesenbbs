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
				<div class="login">
				<h1>Login</h1>
				<form action="login.php" method="post">
					<input type="text" name="username" placeholder="Username" id="username" required><br>
					<input type="password" name="password" placeholder="Password" id="password" required>
					<input type="submit" value="Login">
				</form>
			</div>
		
		<?php
			//print_r($_SESSION);
			
			if(isset($_GET['func']) and $_GET['func']=="logout"){
				session_destroy();
				session_start();
				header('Location: ' . AKICH_ROOT);
			}
			
			if(akich_check_login_status() == 'nanashi'){
				if($_POST){
					if (!isset($_POST['username'], $_POST['password'])){
						exit('Please fill both the username and password fields!');
					}
					else{
						$username = mysqli_real_escape_string($mysqli, strip_tags($_POST['username']));
						$password = mysqli_real_escape_string($mysqli, strip_tags($_POST['password']));
						
						$account_query = mysqli_execute_query($GLOBALS['mysqli'], "SELECT * FROM user WHERE user_name=?", [$username]);
						echo mysqli_error($GLOBALS['mysqli']);
						$user = mysqli_fetch_assoc($account_query);
						echo mysqli_error($GLOBALS['mysqli']);
						
						error_reporting(0);
						if($user['user_name'] == "nanashi")
							echo "Not a valid user";
						else if($user != NULL){
							if(password_verify($password, $user['user_pass'])){
								if($user['user_parameters'] != NULL){
									$user_parameters = json_decode($user['user_parameters'], true);
									if(array_key_exists("user_verification_code", $user_parameters)){
										echo "User is not verified yet.";
									}
									else{
										goto auth;
									}
								}
								else{
									//echo "Yay!";
									auth:
									session_regenerate_id();
									$_SESSION['akich_user_name'] = $user['user_name'];
									$_SESSION['akich_login_flag'] = 1;
									mysqli_execute_query($GLOBALS['mysqli'], "UPDATE user SET user_date_last_login=NOW() WHERE user_name=?", [$username]);
									header('Location: ' . AKICH_ROOT);
								}
							}
							else
								echo "Incorrect user or password";
						}
						else{
							echo "No user";
						}
						error_reporting(-1);
					}
				}
				else{
					//echo "Loggin' on now";
				}
			}
			else{
				header('Location: ' . AKICH_ROOT);
			}
		?>
		
		</div>
		
	
</body>
</html>
