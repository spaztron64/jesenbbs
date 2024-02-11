<?php

function akich_check_login_status(){
	if(!isset($_SESSION['akich_user_name']) or !isset($_SESSION['akich_login_flag'])){
		return "nanashi"; //Not logged in, return anonymous
	}
	else{
		return $_SESSION['akich_user_name']; //Logged in as
	}
}

function akich_get_current_user_permission_level(){
	$user = akich_check_login_status();
	$permission_query = mysqli_query($GLOBALS['mysqli'], "SELECT user_permission_level FROM user WHERE user_name='$user'");
	$row = mysqli_fetch_assoc($permission_query);
	return $row['user_permission_level'];
}

//Fetches the list of boards and prints it out

function akich_display_boards(){
	$board_list_query = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM board");
					
	$user = akich_check_login_status();
	$permission_query = mysqli_query($GLOBALS['mysqli'], "SELECT user_parameters FROM user WHERE user_name='$user'");
	$row = mysqli_fetch_assoc($permission_query);
	$board_whitelist = json_decode($row['user_parameters'], true);
	if($board_whitelist != NULL && array_key_exists("user_board_whitelist", $board_whitelist)){
		$board_whitelist_array = explode(",", $board_whitelist['user_board_whitelist']);
		while($row = mysqli_fetch_assoc($board_list_query)){	
			$board_name = $row['board_name'];
			$board_parameters_decoded = json_decode($row['board_parameters'], true);
			if(array_key_exists($board_name, $GLOBALS['akich_larr'])){
				$board_display_name = $GLOBALS['akich_larr'][$board_name];
			}
			else{
				$board_display_name = $board_parameters_decoded['board_default_display_name'];
			}
			
			if(in_array($board_name, $board_whitelist_array))
			echo '<a href="' . AKICH_ROOT . $board_name . '/">' . $board_display_name . '</a><br>';
		}
	}
	else{
		while($row = mysqli_fetch_assoc($board_list_query)){	
			$board_name = $row['board_name'];
			$board_parameters_decoded = json_decode($row['board_parameters'], true);
			if(array_key_exists($board_name, $GLOBALS['akich_larr'])){
				$board_display_name = $GLOBALS['akich_larr'][$board_name];
			}
			else{
				$board_display_name = $board_parameters_decoded['board_default_display_name'];
			}
			
			echo '<a href="' . AKICH_ROOT . $board_name . '/">' . $board_display_name . '</a><br>';
		}
	}
}


//Returns the full name of the currently opened board
//Shown in the format /<internal_name/ - <display_name>

function akich_show_board_name(){
	
	$current_board_name = $_GET['board_name'];
	
	$current_board_query = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM board WHERE board_name=\"$current_board_name\"");
	echo mysqli_error($GLOBALS['mysqli']);
	$row = mysqli_fetch_assoc($current_board_query);
	if ($row == null) {
		if(!array_key_exists("board_not_found", $GLOBALS['akich_larr'])){
			return "Board not found!";
			die();
		}
		else{
			return $GLOBALS['akich_larr']["board_not_found"];
		}
	}
	
	$board_name = $row['board_name'];
	$board_parameters_decoded = json_decode($row['board_parameters'], true);
	if(array_key_exists($board_name, $GLOBALS['akich_larr'])){
		$board_display_name = $GLOBALS['akich_larr'][$board_name];
	}
	else{
		$board_display_name = $board_parameters_decoded['board_default_display_name'];
	}
	
	return '/' . $_GET['board_name'] . '/ - ' . $board_display_name;
}

//Gets the current board from the URL and returns a filtered output

function akich_get_current_board(){
	$loc = $_SERVER['REQUEST_URI'];
	$loc_chunks = array_filter(explode('/', $loc));
	return $loc_chunks[2];

}

function akich_get_current_board_permission_level(){
	$current_board = akich_get_current_board();
	$permission_level_query = mysqli_query($GLOBALS['mysqli'], "SELECT board_parameters FROM board WHERE board_name='$current_board'");
	error_reporting(-1);
	$row = mysqli_fetch_assoc($permission_level_query);
	error_reporting(0);
	$board_parameters = json_decode($row['board_parameters'],true);
	
	if($board_parameters != NULL){
		if(array_key_exists("board_minimum_permission_level", $board_parameters)){
			return $board_parameters['board_minimum_permission_level'];
		}
		else{
			return 0;
		}
	}
	else{
		return 0;
	}
}

//Fetches all threads for a given board and returns appropriate data

function akich_get_threads(){
	$current_board = akich_get_current_board();
	$arr = array();
	$i = 0;
	$thread_query = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM thread WHERE id_board=(SELECT id_board FROM board WHERE board_name=\"$current_board\") ORDER BY thread_date_updated DESC");
	while($row = mysqli_fetch_assoc($thread_query)){ 
		$thread_id = $row['id_thread'];
		$thread_title = $row['thread_title'];
		$thread_upd = $row['thread_date_updated'];
		
		$post_query = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM post WHERE id_thread=$thread_id");
		$row_post = mysqli_fetch_assoc($post_query);
		$post_id = $row_post['id_post'];
		$post_content = $row_post['post_content'];
		$post_date_created = $row_post['post_date_created'];
		//echo "$thread_title<br>";
		
		$user_query = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM user WHERE id_user=(SELECT id_user FROM post WHERE id_post=$post_id)");
		$row_user = mysqli_fetch_assoc($user_query);
		
		$attachment_query = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM attachment WHERE id_post=$post_id AND attachment_is_primary=1");
		$row_attachment = mysqli_fetch_assoc($attachment_query);
		echo mysqli_error($GLOBALS['mysqli']);
		
		$arr[$i]['id_thread'] = $thread_id;
		$arr[$i]['thread_title'] = $thread_title;
		$arr[$i]['post_content'] = $post_content;
		$arr[$i]['post_poster_name'] = $row_user['user_name'];
		$arr[$i]['post_date_created'] = $post_date_created;
		if($row_attachment != NULL)
			$arr[$i]['post_primary_attachment'] = $row_attachment['attachment_filename'];
		$arr[$i]['thread_date_updated'] = $thread_upd;
		$i++;
	}
	return $arr;

}

//Return the currently opened thread's name

function akich_show_thread_name(){
	
	$current_thread_id = $_GET['id_thread'];
	
	$current_board_query = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM thread WHERE id_thread='$current_thread_id'");
	echo mysqli_error($GLOBALS['mysqli']);
	$row = mysqli_fetch_assoc($current_board_query);
	if ($row == null) {
		if(!array_key_exists("thread_not_found", $GLOBALS['akich_larr'])){
			return "Thread not found!";
			die();
		}
		else{
			return $GLOBALS['akich_larr']["thread_not_found"];
		}
	}
	
	$thread_title = $row['thread_title'];
	
	return $thread_title;
}

function akich_get_posts(){
	$current_thread = akich_show_thread_name();
	$arr = array();
	$i = 0;
	$j = 0;
	$post_query = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM post WHERE id_thread=(SELECT id_thread FROM thread WHERE thread_title=\"$current_thread\")");
	echo mysqli_error($GLOBALS['mysqli']);
	while($row = mysqli_fetch_assoc($post_query)){ 
		$post_id = $row['id_post'];
		$post_content = $row['post_content'];
		$post_date_created = $row['post_date_created'];
		$user_query = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM user WHERE id_user=(SELECT id_user FROM post WHERE id_post=$post_id)");
		$attachment_query = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM attachment WHERE id_post=$post_id AND attachment_is_primary=1");
		$row_attachment = mysqli_fetch_assoc($attachment_query);
		$row_user = mysqli_fetch_assoc($user_query);
		$arr[$i]['id_post'] = $post_id;
		$arr[$i]['post_poster_name'] = $row_user['user_name'];
		$arr[$i]['post_content'] = $post_content;
		$arr[$i]['post_parameters'] = $row['post_parameters'];
		$arr[$i]['post_date_created'] = $post_date_created;
		if($row_attachment != NULL)
		$arr[$i]['post_primary_attachment'] = $row_attachment['attachment_filename'];
		
		
		$attachment_sec_query = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM attachment WHERE id_post=$post_id AND attachment_is_primary<>1");
		echo mysqli_error($GLOBALS['mysqli']);
		
		while($row_attachment_sec = mysqli_fetch_assoc($attachment_sec_query)){
			$arr[$i]['post_secondary_attachment'][$j] = $row_attachment_sec['attachment_filename'];
			$j++;
		}		
	
		$i++;
	}
	return $arr;

}


function insertAttachment($conn, $filename, $is_primary, $post_id, $thread_id, $user_id) {
    $sql = "INSERT INTO attachment VALUES ($thread_id, $user_id, $post_id, NULL, \"$filename\", \"$filename\", NULL, $is_primary)";
	echo mysqli_error($conn);
    if (mysqli_query($conn, $sql)) {
        echo "Attachment inserted successfully.";
    } else {
        echo "Error inserting attachment: " . mysqli_error($conn);
    }
}

/**
*   Auxiliar function to convert images to JPG
*/
function convertImage($originalImage) {

    switch (exif_imagetype($originalImage)) {
        case IMAGETYPE_PNG:
            $imageTmp=imagecreatefrompng($originalImage);
            break;
        case IMAGETYPE_JPEG:
            $imageTmp=imagecreatefromjpeg($originalImage);
            break;
        case IMAGETYPE_GIF:
            $imageTmp=imagecreatefromgif($originalImage);
            break;
        case IMAGETYPE_BMP:
            $imageTmp=imagecreatefrombmp($originalImage);
            break;
        // Defaults to JPG
        default:
            $imageTmp=imagecreatefromjpeg($originalImage);
            break;
    }

    // quality is a value from 0 (worst) to 100 (best)
	ob_start();
    imagejpeg($imageTmp);
	$processed_image = ob_get_contents();
	ob_end_clean();
	imagedestroy($imageTmp);

    return $processed_image;
}

// Function to handle file upload
function handleFileUpload($fileInputName, $post_id, $attachmentIndex) {
	
	$accept = ["jpg", "jpeg", "png", "bmp", "gif", "zip", "7z", "rar", "lha", "lzh"];
	$ext = strtolower(pathinfo($fileInputName['name'], PATHINFO_EXTENSION));
	
	if (!in_array($ext, $accept)){
		echo "Filetype not allowed.";
		return 0;
	}
	
	$post_query = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM post WHERE id_post=$post_id");
	$row_post = mysqli_fetch_assoc($post_query);
	
	$thread_id = $row_post['id_thread'];
	$user_id = $row_post['id_user'];
	
    $targetDir = "attach/";
    $uploadedFile = $fileInputName['name'];
    $tmpName = $fileInputName['tmp_name'];
    $attachmentFilename = time() . "_" . basename($uploadedFile);
    $targetFilePath = $targetDir . $attachmentFilename;

    if (move_uploaded_file($tmpName, $targetFilePath)) {
		if($attachmentIndex == 1 && exif_imagetype($targetFilePath) != false){
			list($width, $height) = getimagesize($targetFilePath);
			
			$newwidth = $width * 0.20;
			$newheight = $height * 0.20;
			
			$thumb = imagecreatetruecolor($newwidth, $newheight);
			
			$tmpfile = tmpfile();
			$tmpname = stream_get_meta_data($tmpfile);
			fwrite($tmpfile, convertImage($targetFilePath));
			
			$source = imagecreatefromjpeg($tmpname['uri']);
			
			fclose($tmpfile);
			
			if($source != false){
				if(imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height)){
					if(imagejpeg($thumb, $targetDir . 't_' . $attachmentFilename . '.jpg')){
						insertAttachment($GLOBALS['mysqli'], $attachmentFilename, $attachmentIndex, $post_id, $thread_id, $user_id);
						$GLOBALS['sql_transaction'] = 1;
					}
				}
			}
		}
		else{
			insertAttachment($GLOBALS['mysqli'], $attachmentFilename, $attachmentIndex, $post_id, $thread_id, $user_id);
			$GLOBALS['sql_transaction'] = 1;
		}
		
    } else {
        echo "Error uploading file.";
    }
}

?>