<?php
session_start();

	require_once('_functions.php');

	if(!isset($_SESSION['_logged'])) {
		return json_encode(array('file'=>''));
		die();
	}

	set_time_limit(600);
	$err = array();
	$files = array();

	$oks = 0;

	if(isset($_FILES['_uploader_profile']) && isset($_FILES['_uploader_profile']['name']) && $_FILES['_uploader_profile']['name']) {

    		$mime = is_valid_image($_FILES['_uploader_profile']['tmp_name']);

		if(!$mime) {
			$mime = $_FILES['_uploader_profile']['type'];
		}

		$photo_id = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVXYZ"), 0, 20);
	
    		$ok = false;
    		switch ($mime) {
       			case 'image/jpg': $ext = 'jpg'; $ok = true; break;
			case 'image/jpeg': $ext = 'jpeg'; $ok = true; break;
			case 'image/png': $ext = 'jpeg'; $ok = true; break;
        	}

		if($ok) {
				
			$image_path = '../_uploads/_profile_pictures/'.$photo_id.'.jpg';
			if(move_uploaded_file($_FILES['_uploader_profile']['tmp_name'],$image_path)) {

				correct_exif_rotation($image_path);
				crop_image($image_path, $image_path, 150, 150);

				mysqli_query($db,"UPDATE `users` SET `profile_picture` = '".$photo_id."' WHERE `id` = '".$_SESSION['_logged_id']."' LIMIT 1");
				$oks = 1;
				$file = $photo_id;

			}

		}
	
	}
	
	$ret = array('ok'=>$oks,'file'=>$file);
	echo json_encode($ret);
?>