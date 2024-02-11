<!DOCTYPE HTML>
<html>
<head>
	<link rel="stylesheet" href="../css/main.css">
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
			
				echo '<h1 id="titlecard">' . akich_show_board_name() . "</h1>";
					
				if(akich_get_current_user_permission_level() >= akich_get_current_board_permission_level()){
					echo '<a href="' . AKICH_ROOT . 'thread_add.php?board_name=' . akich_get_current_board() . '">' . $akich_larr['create_new_thread'] . '</a>';
					echo "<hr>";
					echo '<table>';
					$posts = akich_get_threads();
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
						echo "</td>";
						echo "<td width=70%>";
					}
						
						else{
							echo "<td colspan=2 width=80%>";
						}
						
						if(iconv_strlen($post['thread_title']) < 60)
							echo '<a href="' . $loc . $post['id_thread'] . '/">' . $post['thread_title'] . '</a>' . "<br>";
						else
							echo '<a href="' . $loc . $post['id_thread'] . '/">' . substr($post['thread_title'], 0, 60) . "..." . '</a>' . "<br>";
						if(iconv_strlen($post['post_content']) < 128)
							echo $post['post_content'] . "<br>";
						else
							echo substr($post['post_content'], 0 , 128) . "..." . "<br>";
						echo "</td>";
						
						echo "<td>";
						echo 'by <span id="username2">' . $post['post_poster_name'] . "</span><br>";
						echo "created on  " . $post['post_date_created'] . "<br>";
						echo "last reply on " . $post['thread_date_updated'] . "<br>";
						if($permit_level > 7)
							echo "<a href=" . AKICH_ROOT . "thread_delete.php?delete_thread=" . $post['id_thread'] . ">Delete";
						echo "</td>";
						
						echo "</tr>";
						
						echo "<tr>";
						echo "<td colspan=3>";
						echo "<hr>";
						echo "</td>";
						echo "</tr>";
						
					}
					echo "</table>";
				}
				else{
					echo '<img src="' . AKICH_ROOT . 'elements/bump.gif"><br><br>';
					echo $akich_larr['board_forbidden'];
				}
			?>
	
		</div>
</body>
</html>
