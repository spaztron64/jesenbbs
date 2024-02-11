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
	$current_board_name = $_GET['board_name'];
	//if(!isset($_SESSION['username'])){
	//	header('Location: '."index.html");
	//}
	echo '<form action="thread_add.php?board_name=' . $current_board_name . '" method="post" enctype="multipart/form-data">';
	
		echo $akich_larr['thread_title'] . ': <input type="text" name="thread_title" id="thread_title"><br>';
		echo $akich_larr['post_content'] . ':<textarea name="post_content" id="post_content"></textarea><br>';
		echo $akich_larr['primary_attachment'] . ':<input type="file" name="primary_attachment" id="primary_attachment" accept=".jpg,.jpeg,.png,.bmp,.gif,.zip,.7z,.rar,.lha,.lzh"><br>';
		echo $akich_larr['attachment'] . '2 :<input type="file" name="attach2" id="attach2" accept=".jpg,.jpeg,.png,.bmp,.gif,.zip,.7z,.rar,.lha,.lzh"><br>';
		echo $akich_larr['attachment'] . '3 :<input type="file" name="attach3" id="attach3" accept=".jpg,.jpeg,.png,.bmp,.gif,.zip,.7z,.rar,.lha,.lzh"><br>';
		echo $akich_larr['attachment'] . '4 :<input type="file" name="attach4" id="attach4" accept=".jpg,.jpeg,.png,.bmp,.gif,.zip,.7z,.rar,.lha,.lzh"><br>';
		echo '<input type="submit" value="' . $akich_larr['create_thread'] . '" name="submit">';
	echo "</form>";
	echo "<br>Acceptable filetypes:<br><br>Images (JPG/PNG/BMP/GIF)<br>Archives(ZIP/7Z/RAR/LHA/LZH)<br><br>";
	echo '<a href="' . AKICH_ROOT . $current_board_name . '/">' . $akich_larr['back_to_board'] . '</a><br><br>';
	?>
	
	<?php
	
	
	$current_board_query = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM board WHERE board_name='$current_board_name'");
	$row = mysqli_fetch_assoc($current_board_query);
	
	$current_user = akich_check_login_status();
	$current_user_query = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM user WHERE user_name=\"$current_user\"");
	$row_user = mysqli_fetch_assoc($current_user_query);
	
//	echo "Creating thread on /" . $row['board_name'] . "/, as user " . $row_user['user_name'];
	
	
	if(!isset($_POST['thread_title'])){
		die();
	}
	else{
		extract($_POST);
	
		if($submit){
		
			if ($_POST['thread_title']==""){
				echo "<br><br>Name your thread!";
			}
			else if ($_POST['post_content']==""){
				echo "<br><br>You must provide some text!";
			}
			else if (strlen($_POST['thread_title']) > 200){
				echo "<br><br>Title too long! (200 characters max)<br>";
			}
			else if (strlen($_POST['post_content']) > 8192){
				echo "<br><br>Too much text! (8192 characters max)<br>";
			}
			else if (($_FILES['attach2']['error'] == UPLOAD_ERR_OK || $_FILES['attach3']['error'] == UPLOAD_ERR_OK || $_FILES['attach4']['error'] == UPLOAD_ERR_OK) && $_FILES['primary_attachment']['error'] == UPLOAD_ERR_NO_FILE){
				//if(){
					echo "<br><br> Secondary attachments without a primary attachment are not allowed.<br>";
				//}
			}
			else{
				$current_board = $row['id_board'];
				$current_user = $row_user['id_user'];
				$thread_title = mysqli_real_escape_string($mysqli, strip_tags($_POST['thread_title']));
				$post_content = mysqli_real_escape_string($mysqli, strip_tags($_POST['post_content']));
				
				
				//File shit goes here
				
				
				
				
				
				mysqli_query($mysqli, "INSERT INTO thread VALUES($current_board,NULL,'$thread_title',NOW(), NOW())");
				echo mysqli_error($mysqli) . "<br><br>";
				$thread_id = mysqli_insert_id($mysqli);
				$visitor_addr = $_SERVER['REMOTE_ADDR'] /*. " / " . $_SERVER['REMOTE_HOST']*/;
				
				mysqli_autocommit($GLOBALS['mysqli'],false);
				
				mysqli_query($mysqli, "INSERT INTO post VALUES($thread_id,$current_user,NULL,'$post_content',NULL,NOW(), NOW(), '$visitor_addr')");
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
				
				// End file shit
				
				if ($sql_transaction != 1){
					echo "Error, rolling back transaction.";
					mysqli_rollback($GLOBALS['mysqli']);
				}
				else{
					mysqli_commit($GLOBALS['mysqli']);
					header("Location:" . AKICH_ROOT . $current_board_name . '/' . $thread_id);
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
