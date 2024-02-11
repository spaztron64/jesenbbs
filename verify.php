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
			//print_r($_SESSION);
			
			if(isset($_GET['verification_code'])){
				$verification_code = $_GET['verification_code'];
				$user_query = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM user WHERE user_parameters='{\"user_verification_code\":$verification_code}'");
				$row = mysqli_fetch_assoc($user_query);
				if(!$row){
					echo '<img src="' . AKICH_ROOT . 'elements/bump.gif"><br><br>';
					echo "User does not exist or is already verified.";
				}
				else{
					mysqli_query($GLOBALS['mysqli'], "UPDATE user SET user_parameters=NULL WHERE user_parameters='{\"user_verification_code\":$verification_code}'");
					echo '<img src="' . AKICH_ROOT . 'elements/arlewin.gif"><br><br>';
					echo "User verification successful. Please log in now!";
				}
			}
			else{
				echo '<img src="' . AKICH_ROOT . 'elements/bump.gif"><br><br>';
				echo "You're not supposed to be here.";
			}
			
			echo "<a href=" . AKICH_ROOT . "><br>Back to homepage</a>";
		?>
		
		</div>
		
	
</body>
</html>
