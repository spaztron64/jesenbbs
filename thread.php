<!DOCTYPE HTML>
<html>
<head>
	<link rel="stylesheet" href="../../css/main.css">
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
				$loc = $_SERVER['REQUEST_URI'];
				//echo "You are on $loc <br><br>";
				//echo "Filtered: " . akich_get_current_board();
				echo '<h1 id="titlecard">' . akich_show_thread_name() . "</h1>";
				echo '<a href="' . AKICH_ROOT . 'post_add.php?id_thread=' . $_GET['id_thread'] . '">' . $akich_larr['post_reply'] . '</a>';
				echo "<hr>";
				
				$permit_level = akich_get_current_user_permission_level();
				
				$current_user = akich_check_login_status();
				
				$posts = akich_get_posts();
				echo "<table>";
				foreach ($posts as $post){
					echo "<tr>";
					
					if(array_key_exists('post_primary_attachment', $post)){
						echo "<td width=160px>";
						echo '<a href="' . AKICH_ROOT . 'attach/' . $post['post_primary_attachment'] . '">';
						if(file_exists('attach/t_' . $post['post_primary_attachment'] . '.jpg')){
							echo '<img src="' . AKICH_ROOT . 'attach/t_' . $post['post_primary_attachment'] . '.jpg" width=160px></a>';
						}
						else{
							echo '<img src="' . AKICH_ROOT . 'elements/usaflopi.jpg" width=160px></a>';
							echo '<br>' . $post['post_primary_attachment'];
						}
						if(array_key_exists('post_secondary_attachment', $post)){
							foreach ($post['post_secondary_attachment'] as $secondattach){
								echo '<br><a href=' . AKICH_ROOT . 'attach/' . $secondattach . '>' . $secondattach . '</a>';
							}
						}
						echo "</td>";
						echo "<td>";
					}
					
					else{
						//echo '<td style="width: 2px">';
						//echo '<img src="' . AKICH_ROOT . 'blank.gif' . '" width=2px>';
						//echo "</td>";
						echo "<td colspan=2>";
					}
					
					//echo "<td>";
					echo '<span id="username2">' . $post['post_poster_name'] . "</span> - " . $post['post_date_created'] . " - No. " . $post['id_post'];
					$post_parameters = json_decode($post['post_parameters'], true);
					if($permit_level > 7 or $post['post_poster_name'] == $current_user and $post['post_poster_name'] !== "nanashi"){
						if($post_parameters != NULL){
							if(!array_key_exists("post_deleted_by", $post_parameters))
								echo " // " . "<a href=" . AKICH_ROOT . "post_delete.php?delete_post=" . $post['id_post'] . ">Delete</a>";
						}
						else{
							echo " // " . "<a href=" . AKICH_ROOT . "post_delete.php?delete_post=" . $post['id_post'] . ">Delete</a>";
						}
						
					}
					echo "<br><br>";
					$words = explode(" ", $post['post_content']);
					foreach ($words as $word){
						if (strlen($word) > 60)
							echo implode(PHP_EOL, str_split($word, 60)) . "<br>";
						else
							echo $word . " ";
					}
					echo "</td>";
					
					echo "</tr>";
					
					echo "<tr>";
					echo "<td colspan=2>";
					echo "<hr>";
					echo "</td>";
					echo "</tr>";
					
				}
				echo "</table>";
				
			?>
		</div>
</body>
</html>
