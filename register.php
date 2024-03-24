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
				<h1>Register</h1>
				<form action="register.php" method="post">
					<span style="width: 150px; float: left; display: inline-block">E-Mail</span> <input type="email" name="email" placeholder="E-Mail" id="email" required><br>
					<span style="width: 150px; float: left; display: inline-block">User name</span> <input type="text" name="username" placeholder="Username" id="username" required><br>
					<span style="width: 150px; float: left; display: inline-block">Password</span> <input type="password" name="password" placeholder="Password" id="password" required><br>
					<span style="width: 150px; float: left; display: inline-block">Confirm password</span> <input type="password" name="password_confirm" placeholder="Password" id="password_confirm" required><br>
					<input type="submit" value="Register">
				</form>
			</div>
		
		<?php
				if($_POST){
					if (!isset($_POST['username'], $_POST['password'], $_POST['email'], $_POST['password_confirm'])){
						exit('Please fill in all of the necessary fields!');
					}
					else{
						$username = mysqli_real_escape_string($mysqli, strip_tags($_POST['username']));
						$password = mysqli_real_escape_string($mysqli, strip_tags($_POST['password']));
						$password = password_hash($password, PASSWORD_BCRYPT);
						$email = $_POST['email'];
						
						if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
							echo "Invalid email.";
							exit();
						}
						
						$account_query = mysqli_execute_query($GLOBALS['mysqli'], "SELECT * FROM user WHERE user_name=? OR user_email=?", [$username, $email]);
						echo mysqli_error($GLOBALS['mysqli']);
						$user = mysqli_fetch_assoc($account_query);
						echo mysqli_error($GLOBALS['mysqli']);
						
						
						if($user != NULL and $user['user_name'] == "nanashi")
							echo "<br> This username is reserved.";
						else if($user != NULL){
								echo "<br>This E-mail or username is already in use.";
							}
						else if($_POST['password'] != $_POST['password_confirm']){
								echo "<br>Password confirmation failed.";
							}
						else{
							mysqli_autocommit($GLOBALS['mysqli'],false);
							$verification_code = rand(1000000000,mt_getrandmax());
							$user_code = '\'{"user_verification_code":' . $verification_code . '}\'';
							mysqli_execute_query($GLOBALS['mysqli'], "INSERT INTO user VALUES(NULL,?,?,?,'1', ?, NOW(), NOW())", [$username, $password, $email, $user_code]);
							echo mysqli_error($GLOBALS['mysqli']);
							
							ini_set('SMTP', 'localhost');
							ini_set('smtp_port', 25);
							
							$to = $email;
							$subject = "AkiChannel: new user verification";
							$message = "Welcome to AkiChannel, " . $username . "!\n\n" . "To be able to use your account, please verify it by visiting the following page:\n\n" . "https://lainnet.superglobalmegacorp.com/akichannel/verify.php?verification_code=" . $verification_code . " \n\n" . "If for any reason you are not able to verify your account, please contact staff on one of the appropriate nanashi support boards\n\n".
							
							$headers = "From:noreply@superglobalmegacorp.com";
							mail($to, $subject, $message, $headers);
							
							echo "<br><br>Registration successful. Please check your inbox and verify your account.";
							mysqli_commit($GLOBALS['mysqli']);
						}
						
					}
				}
		?>
		
		</div>
		
	
</body>
</html>
