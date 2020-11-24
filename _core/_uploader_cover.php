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

	$file = '';

	if(isset($_POST['main_id']) && is_numeric($_POST['main_id']) && isset($_FILES['_uploader_cover']) && isset($_FILES['_uploader_cover']['name']) && $_FILES['_uploader_cover']['name']) {

		$main_id = mysqli_real_escape_string($db,$_POST['main_id']);
    		$mime = is_valid_image($_FILES['_uploader_cover']['tmp_name']);

		if(!$mime) {
			$mime = $_FILES['_uploader_cover']['type'];
		}

		$photo_id = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVXYZ"), 0, 20);
	
    		$ok = false;
    		switch ($mime) {
       			case 'image/jpg': $ext = 'jpg'; $ok = true; break;
			case 'image/jpeg': $ext = 'jpeg'; $ok = true; break;
			case 'image/png': $ext = 'jpeg'; $ok = true; break;
        	}

		if($ok) {
				
			$image_path = '../_uploads/_content_cover/'.$photo_id.'.jpg';
			if(move_uploaded_file($_FILES['_uploader_cover']['tmp_name'],$image_path)) {

				correct_exif_rotation($image_path);
				crop_image($image_path, $image_path, 150, 150);

				compress_image($image_path, 70);
				crop_image($image_path, '../_uploads/_content_cover/'.$photo_id.'_400.jpg', 400, 400);
					
				if(isset($settings['watermark']) && $settings['watermark'] == '1' && isset($settings['watermark_image']) && file_exists('../'.$settings['watermark_image'])) {
					apply_watermark($image_path, '../'.$settings['watermark_image'], $settings['watermark_position'], $settings['watermark_opacity']);
				}

				mysqli_query($db,"UPDATE `content` SET `cover` = '".$photo_id."' WHERE `id` = '".$main_id."' AND `iduser` = '".$_SESSION['_logged_id']."' LIMIT 1");
				$oks = 1;
				$file = $photo_id;

			}

		}
	
	}
	
	$ret = array('ok'=>$oks,'file'=>$file);
	echo json_encode($ret);
?>