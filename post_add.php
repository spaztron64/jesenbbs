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
	$current_thread_id = $_GET['id_thread'];
	//if(!isset($_SESSION['username'])){
	//	header('Location: '."index.html");
	//}
	echo '<form action="post_add.php?id_thread=' . $current_thread_id . '" method="post" enctype="multipart/form-data">';
	
		echo $akich_larr['post_content'] . ':<textarea name="post_content" id="post_content"></textarea><br>';
		echo $akich_larr['primary_attachment'] . ':<input type="file" name="primary_attachment" id="primary_attachment" accept=".jpg,.jpeg,.png,.bmp,.gif,.zip,.7z,.rar,.lha,.lzh"><br>';
		echo $akich_larr['attachment'] . '2 :<input type="file" name="attach2" id="attach2" accept=".jpg,.jpeg,.png,.bmp,.gif,.zip,.7z,.rar,.lha,.lzh"><br>';
		echo $akich_larr['attachment'] . '3 :<input type="file" name="attach3" id="attach3" accept=".jpg,.jpeg,.png,.bmp,.gif,.zip,.7z,.rar,.lha,.lzh"><br>';
		echo $akich_larr['attachment'] . '4 :<input type="file" name="attach4" id="attach4" accept=".jpg,.jpeg,.png,.bmp,.gif,.zip,.7z,.rar,.lha,.lzh"><br>';
		echo '<input type="submit" value="' . $akich_larr['post_reply'] . '" name="submit">';
	echo "</form>";
	echo "<br>Acceptable filetypes:<br><br> Images (JPG/PNG/BMP/GIF)<br> Archives (ZIP/7Z/RAR/LHA/LZH)<br><br>";
	
	?>
	
	<?php
	
	
	
	
	$current_thread_query = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM thread WHERE id_thread='$current_thread_id'");
	$row = mysqli_fetch_assoc($current_thread_query);
	
	$board_id = $row['id_board'];
	
	$current_board_query = mysqli_query($GLOBALS['mysqli'], "SELECT board_name FROM board WHERE id_board='$board_id'");
	$row_board = mysqli_fetch_assoc($current_board_query);
	
	$current_user = akich_check_login_status();
	$current_user_query = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM user WHERE user_name='$current_user'");
	$row_user = mysqli_fetch_assoc($current_user_query);
	
	//echo "Creating reply on thread no. " . $row['id_thread'] . ", as user " . $row_user['user_name'];
	
	echo '<br><br><a href="' . AKICH_ROOT . $row_board['board_name'] . '/' . $row['id_thread'] . '">' . $akich_larr['back_to_thread'] . '</a><br><br>';
	
	if(!isset($_POST['post_content'])){
		die();
	}
	else{
		extract($_POST);
	
		if($submit){
		
			if ($_POST['post_content']==""){
				echo "<br><br>You must provide some text!";
			}
			else if (strlen($_POST['post_content']) > 8192){
				echo "<br><br>Too much text! (8192 characters max)<br>";
			}
			else if (($_FILES['attach2']['error'] == UPLOAD_ERR_OK || $_FILES['attach3']['error'] == UPLOAD_ERR_OK || $_FILES['attach4']['error'] == UPLOAD_ERR_OK) && $_FILES['primary_attachment']['error'] == UPLOAD_ERR_NO_FILE){
				echo "<br><br> Secondary attachments without a primary attachment are not allowed.<br>";
			}
			else{
				$current_thread = $row['id_thread'];
				$current_user = $row_user['id_user'];
				$post_content = mysqli_real_escape_string($mysqli, strip_tags($_POST['post_content']));
				$visitor_addr = $_SERVER['REMOTE_ADDR'] /* . " / " . $_SERVER['REMOTE_HOST']*/;
				
				mysqli_autocommit($GLOBALS['mysqli'],false);
				
				mysqli_query($GLOBALS['mysqli'], "INSERT INTO post VALUES($current_thread,$current_user,NULL,'$post_content',NULL,NOW(), NOW(), '$visitor_addr')");
				echo mysqli_error($mysqli) . "<br><br>";
				
				echo mysqli_error($mysqli) . "<br><br>";
				$post_id = mysqli_insert_id($mysqli);
				
				if($_FILES['primary_attachment']['error'] == UPLOAD_ERR_OK)
					handleFileUpload($_FILES['primary_attachment'], $post_id, 1);
				if($_FILES['attach2']['error'] == UPLOAD_ERR_OK)
					handleFileUpload($_FILES['attach2'], $post_id, 0);
				if($_FILES['attach3']['error'] == UPLOAD_ERR_OK)
					handleFileUpload($_FILES['attach3'], $post_id, 0);
				if($_FILES['attach4']['error'] == UPLOAD_ERR_OK)
					handleFileUpload($_FILES['attach4'], $post_id, 0);
				if(empty($_FILES['primary_attachment']['name']) and empty($_FILES['attach2']['name']) and empty($_FILES['attach3']['name']) and empty($_FILES['attach4']['name'])){
					echo "No files are uploaded, and that's cool";
					$sql_transaction = 1;
				}
				
				if ($sql_transaction != 1){
					echo "<br>Error, posting aborted!";
					mysqli_rollback($GLOBALS['mysqli']);
				}
				else{
					mysqli_query($GLOBALS['mysqli'], "UPDATE thread SET thread_date_updated=NOW() WHERE id_thread=$current_thread");
					mysqli_commit($GLOBALS['mysqli']);
					header("Location:" . AKICH_ROOT . $row_board['board_name'] . '/' . $row['id_thread']);
				}
				
				$retout = null;
				$retval = null;
			}
		}
	}
	
	?>
	</div>
	
</body>
</html>
