<?php	

		$loc_url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		//echo '<span id="logo">' . $akich_larr['akich_title'] . '</span>';
		echo '<span id="logo"><a href="' . AKICH_ROOT . '">' . '<img src="' . AKICH_ROOT . 'elements/akich_logo.gif" width=180px height=49px>' . '</a></span>';
		echo '<span id="register_login_menu">';
			echo "<a href=" . AKICH_ROOT . "?lang=en><img src=" . AKICH_ROOT . 'elements/loc_en.gif></a> / ';
			echo "<a href=" . AKICH_ROOT . "?lang=sr><img src=" . AKICH_ROOT . 'elements/loc_sr.gif></a> / ';
			echo "<a href=" . AKICH_ROOT . "?lang=jp><img src=" . AKICH_ROOT . 'elements/loc_jp.gif></a> - ';			
			if(akich_check_login_status() == "nanashi"){
				echo '<a href="' . AKICH_ROOT . 'register.php">' . $akich_larr['register'] . '</a>';
				echo '  ';
				echo '<a href="' . AKICH_ROOT . 'login.php">' . $akich_larr['login'] . '</a>';
				//echo 'Registrations are currently closed';
				echo '</span>';
			}
			else{
				echo "Welcome "; echo $_SESSION['akich_user_name'];
				echo ' ';
				echo '<a href="' . AKICH_ROOT . 'my_profile.php">My profile</a>';
				echo ' ';
				if(akich_get_current_user_permission_level() > 8){
					echo '<a href="' . AKICH_ROOT . 'supahpowah/">' . $akich_larr['superuser'] . '</a>';
					echo '  ';
				}				
				echo '<a href="' . AKICH_ROOT . 'login.php?func=logout">Logout</a>';
				echo '</span>';
			}
		?>
		
	</div>
	<hr id="menudivider">
