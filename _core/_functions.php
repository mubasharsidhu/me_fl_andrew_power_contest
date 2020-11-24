<?php
	require_once('_config.php');

	$path = (strstr($_SERVER['PHP_SELF'],'_core/') || strstr($_SERVER['PHP_SELF'],'_admin/')? '../': '');
	require_once($path.'_language/english.php');

	function accept_upload_type() {

		global $settings;

		$accept_up = array();
		$accept_up[] = 'image/*';
		if(isset($settings['upload_music']) && $settings['upload_music'] == '1') {
			$accept_up[] = 'audio/mp3';
		}
		if(isset($settings['upload_video']) && $settings['upload_video'] == '1') {
			$accept_up[] = 'video/mp4';
		}

		return implode(',',$accept_up);

	}

	function get_ads($position) {
			
		global $db;

		$privacy = (is_logged() ? 3 : 2);

		if($privacy == '2') {
			$sql = mysqli_query($db,"SELECT * FROM `ads` WHERE `ad_position` = '".$position."' AND `ad_privacy` IN ('1','2') ORDER BY rand() LIMIT 1");
		}
		if($privacy == '1') {
			$sql = mysqli_query($db,"SELECT * FROM `ads` WHERE `ad_position` = '".$position."' AND `ad_privacy` = '1' ORDER BY rand() LIMIT 1");
		}
		if($privacy == '3') {
			$sql = mysqli_query($db,"SELECT * FROM `ads` WHERE `ad_position` = '".$position."' AND `ad_privacy` IN ('1','3') ORDER BY rand() LIMIT 1");
		}

		if(mysqli_num_rows($sql)) {
			$fetch = mysqli_fetch_array($sql);
			return $fetch['ad_code'];
		} else {
			return false;
		}

	}

	if(!function_exists('imagecopymerge_alpha')) {
	
		function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct) { 
		
			$cut = imagecreatetruecolor($src_w, $src_h); 
			imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h); 
			imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h); 
			imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct);
		
		}

	}

	function apply_watermark($photo, $watermark, $position, $opacity) {

   		list($width, $height) = getimagesize($watermark);
   		$info = getimagesize($photo);
    		$mime = $info['mime'];

		$stamp = imagecreatefrompng($watermark);

   		switch($mime) {
			case 'image/jpeg':
				$im = imagecreatefromjpeg($photo);
			break;
			case 'image/png':
				$im = imagecreatefrompng($photo);
			break;
		}

		$sx = imagesx($stamp);
		$sy = imagesy($stamp);

		if($position == 'br') {
			$marge_right = imagesx($im) - $sx - 10;
			$marge_bottom = imagesy($im) - $sy - 10;
		}
	
		if($position == 'cc') {
			$marge_right = (imagesx($im) - $width)/2;
			$marge_bottom = (imagesy($im) - $height)/2;
		}

		if($position == 'tl') {
			$marge_right = 10;
			$marge_bottom = 10;
		}

		if($position == 'bl') {
			$marge_right = 10;
			$marge_bottom = imagesy($im) - $height - 10;
		}

		if($position == 'tr') {
			$marge_right = imagesx($im) - $width - 10;
			$marge_bottom = 10;
		}

  		imagesavealpha($stamp, true);
       	 	$trans_background = imagecolorallocatealpha($stamp, 0, 0, 0, 127);
        	imagefill($stamp, 0, 0, $trans_background);

		imagecopymerge_alpha($im, $stamp, $marge_right, $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp), $opacity);

		imagejpeg($im, $photo, 100);
		imagedestroy($im);
		imagedestroy($stamp);

	}

	function full_escape($string) {

		global $db;

		$string = str_replace('"','',$string);
		$string = mysqli_real_escape_string($db,$string);
		$string = strip_tags($string);

		return $string;

	}

	function site_metatags($page) {

		global $db;

		$sql = mysqli_query($db,"SELECT * FROM `meta_tags` WHERE `page` = '".$page."' LIMIT 1");
		if(mysqli_num_rows($sql)) {
			$fetch = mysqli_fetch_array($sql);
			$meta_tags = array(
				'title'=>$fetch['title'],
				'description'=>$fetch['description'],
				'keywords'=>$fetch['keywords']
			);
		} else {
			$meta_tags = array('title'=>'','description'=>'','keywords'=>'');
		}

		return $meta_tags;

	}
	
	function get_exif_photo($imagePath) {
		
		$notFound = "";

		if ((isset($imagePath)) and (file_exists($imagePath)) && function_exists('exif_read_data')) {

			try {
				$exif_ifd0 = @exif_read_data($imagePath); 
      			} catch (Exception $exp) {
				$exif_ifd0 = false;
			}    
     
      			if(isset($exif_ifd0['Make'])) {
        			$camMake = $exif_ifd0['Make'];
      			} else {
				$camMake = $notFound;
			}
     
      			if(isset($exif_ifd0['Model'])) {
        			$camModel = $exif_ifd0['Model'];
      			} else {
				$camModel = $notFound;
			}
     
     			if(isset($exif_ifd0['COMPUTED']['ApertureFNumber'])) {
        			$camExposure = $exif_ifd0['ExposureTime'];
      			} else {
				$camExposure = $notFound;
			}

      			if(isset($exif_ifd0['COMPUTED']['ApertureFNumber'])) {
        			$camAperture = $exif_ifd0['COMPUTED']['ApertureFNumber'];
      			} else {
				$camAperture = $notFound;
			}
     
      			if(isset($exif_ifd0['DateTime'])) {
        			$camDate = $exif_ifd0['DateTime'];
				$new_camDate = explode(' ', $camDate);
				$new_camDate[0] = str_replace(':','-',$new_camDate[0]);
				$camDate = $new_camDate[0].' '.$new_camDate[1];
      			} else {
				$camDate = '0000-00-00 00:00:00';
			}

      			if(isset($exif_ifd0['ISOSpeedRatings'])) {
        			$camIso = $exif_ifd0['ISOSpeedRatings'];
      			} else {
				$camIso = $notFound;
			}
     
      			$return = array();
      			$return['make'] = $camMake;
      			$return['model'] = $camModel;
      			$return['exposure'] = $camExposure;
      			$return['aperture'] = $camAperture;
      			$return['date'] = $camDate;
      			$return['iso'] = $camIso;
      
			return $return;
   
    		} else {

      			$return = array();
      			$return['make'] = $notFound;
      			$return['model'] = $notFound;
      			$return['exposure'] = $notFound;
      			$return['aperture'] = $notFound;
      			$return['date'] = $notFound;
      			$return['iso'] = $notFound;

    		}

	}

	function crop_image($photo, $new_photo, $nw, $nh) {

		list($width, $height, $type) = getimagesize($photo);
	
		if($type == 2) {
			$typ = 'image/jpeg';
		}

		if($type == 3) {
			$typ = 'image/png';
		}

		if($type == 1) {
			$typ = 'image/gif';
		}
	
		if(!isset($typ)) {
			return false;
		} else {

			if($nw == $nh && ($nw == '400' || $nw == '150')) {
				$newwidth = $nw;
				$newheight = $nh;

				$src_x = $src_y = 0;
				$src_w = $width;
				$src_h = $height;

				$cmp_x = $width / $newwidth;
				$cmp_y = $height / $newheight;
				
				if ($cmp_x > $cmp_y) {
					$src_w = round ($width / $cmp_x * $cmp_y);
					$src_x = round (($width - ($width / $cmp_x * $cmp_y)) / 2);
				} elseif($cmp_y > $cmp_x) {
					$src_h = round ($height / $cmp_y * $cmp_x);
					$src_y = round (($height - ($height / $cmp_y * $cmp_x)) / 2);
				}
			} else {
				$percent = min($nw / $width, $nh / $height);
				$newwidth = floor($width * $percent);
				$newheight = floor($height * $percent);	
				$src_x = $src_y = 0;
				$src_w = $width;
				$src_h = $height;
			}

			$thumb = imagecreatetruecolor($newwidth, $newheight);

			$quality = 70;
	
			if($typ == 'image/jpeg') {
				$source = imagecreatefromjpeg($photo);
			} elseif($typ == 'image/gif') {
				$source = imagecreatefromgif($photo);
			} elseif($typ == 'image/png') {
				$source = imagecreatefrompng($photo);
			}

			imagecopyresampled($thumb, $source, 0, 0, $src_x, $src_y, $newwidth, $newheight, $src_w, $src_h);
			imagejpeg($thumb, $new_photo, $quality);

			return true;

		}

	}

	function date_to_text($tm,$rcs = 0) {

		$tm = strtotime($tm);
		$tm = $tm - (60 * 60 * 3);

   		$cur_tm = time();
		$dif = $cur_tm-$tm;
   		$pds = array('second','minute','hour','day','week','month','year','decade');
   		$lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);
   		
		for($v = sizeof($lngh)-1; ($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--); if($v < 0) $v = 0; $_tm = $cur_tm-($dif%$lngh[$v]);

   		$no = floor($no);
		if($no <> 1) $pds[$v] .='s';
		$x=sprintf("%d %s ",$no,$pds[$v]);
   		
		if(($rcs == 1)&&($v >= 1)&&(($cur_tm-$_tm) > 0)) $x .= time_ago($_tm);
   
		return $x;

	}

	function is_valid_image($filename) {

    		$mime_types = array(
			'png' => 'image/png',
       			'jpeg' => 'image/jpeg',
        		'jpg' => 'image/jpeg',
        		'gif' => 'image/gif',
		);

    		if(function_exists('mime_content_type')) {
        		$mimetype = mime_content_type($filename);
        		return $mimetype;
    		}

    		if(function_exists('finfo_open')) {
        		$finfo = finfo_open(FILEINFO_MIME);
        		$mimetype = finfo_file($finfo, $filename);
        		finfo_close($finfo);
        		return $mimetype;
		} else {
        		return false;
    		}

	}

	function get_logged_user_category() {

		global $db;

		if(is_logged()) {
			$sql = mysqli_query($db,"SELECT `category` FROM `users` WHERE `id` = '".$_SESSION['_logged_id']."' LIMIT 1");
			if(mysqli_num_rows($sql)) {
				$fetch = mysqli_fetch_array($sql);
				return $fetch['category'];
			} else {
				return 0;
			}
		} else {
			return 0;
		}

	}

	function get_user_logged() {

		global $db;

		if(is_logged()) {

			$sql = mysqli_query($db,"SELECT * FROM `users` WHERE `id` = '".$_SESSION['_logged_id']."' LIMIT 1");
			if(mysqli_num_rows($sql)) {
				return mysqli_fetch_array($sql);
			} else {
				return array();
			}
		
		}

	}

	function get_multi_contest() { 

		global $db;
		
		$sql = mysqli_query($db,"SELECT count(*) as 'total' FROM `contest` WHERE `active` = '1'");
		$fetch = mysqli_fetch_array($sql);

		if($fetch['total'] == '0') {
			return 0;
		} else {
			if($fetch['total'] == '1') {
				return 1;
			} else {
				return 2;
			}
		}

	}

	function get_contest($id=0) {

		global $db;

		if($id == '0') {
			$check_contest = mysqli_query($db,"SELECT * FROM `contest` WHERE `active` = '1' LIMIT 1");
		} else {
			$check_contest = mysqli_query($db,"SELECT * FROM `contest` WHERE `id` = '".$id."' LIMIT 1");
		}
		if(mysqli_num_rows($check_contest)) {
			$fetch_contest = mysqli_fetch_array($check_contest);
			$fetch_contest['end'] = str_replace('-','/',$fetch_contest['end']);
			return array(
				'id'=>$fetch_contest['id'],
				'active'=>$fetch_contest['active'],
				'title'=>$fetch_contest['title'],
				'description'=>$fetch_contest['description'],
				'end'=>$fetch_contest['end'],
				'disable_countdown'=>$fetch_contest['disable_countdown']
			);
		} else {
			return array(
				'id'=>0,
				'active'=>'0',
				'title'=>'',
				'description'=>'',
				'end'=>'0000/00/00 00:00:00',
				'disable_countdown'=>0
			);
		}

	}

	function get_settings($key=false) {
		
		global $db;
		
		$settings = array();

		if($key) {
			$sql = mysqli_query($db,"SELECT * FROM `settings` WHERE `set_key` = '".$key."' LIMIT 1");
		} else {
			$sql = mysqli_query($db,"SELECT * FROM `settings`");
		}

		while($fetch = mysqli_fetch_array($sql)) {
			$settings[$fetch['set_key']] = $fetch['set_value'];
		}

		return $settings;

	}

	function is_logged() {

		if(isset($_SESSION['_logged']) && $_SESSION['_logged'] == '1') {
			return true;
		} else { 
			return false;
		}

	}

	function is_my_profile($user) {
		
		if(is_logged() && isset($_SESSION['_logged_user']) && $_SESSION['_logged_user'] == $user) {
			return true;
		} else {
			return false;
		}

	}

	function get_categories($page=false) {

		global $db;

		$categories = array();
		if($page == 'home') {
			$categories[] = array('id'=>'-1','name'=>_LANG_CATEGORY_DEFAULT);
		}

		$sql = mysqli_query($db,"SELECT * FROM `categories` ORDER BY `name` ASC");
		while($fetch = mysqli_fetch_array($sql)) {
			$categories[] = array('id'=>$fetch['id'],'name'=>$fetch['name']);
		}

		return $categories;

	}

	function my_ip() {

		return $_SERVER['REMOTE_ADDR'];
	
	}

	function rating_bar($rate,$scale=1) {

		$full = 'M10 1l3 6l6 .75l-4.12 4.62L16 19l-6-3l-6 3l1.13-6.63L1 7.75L7 7z';
		$empty = 'M10 1L7 7l-6 .75l4.13 4.62L4 19l6-3l6 3l-1.12-6.63L19 7.75L13 7zm0 2.24l2.34 4.69l4.65.58l-3.18 3.56l.87 5.15L10 14.88l-4.68 2.34l.87-5.15l-3.18-3.56l4.65-.58z';
		$half = 'M10 1L7 7l-6 .75l4.13 4.62L4 19l6-3l6 3l-1.12-6.63L19 7.75L13 7zm0 2.24l2.34 4.69l4.65.58l-3.18 3.56l.87 5.15L10 14.88V3.24z';

		$star_full = '<svg width="1em" height="1em"><path d="'.$full.'" fill="currentColor" style="transform:scale('.$scale.');"></path></svg>';
		$star_empty = '<svg width="1em" height="1em"><path d="'.$empty.'" fill="currentColor" style="transform:scale('.$scale.');"></path></svg>';
		$star_half = '<svg width="1em" height="1em"><path d="'.$half.'" fill="currentColor" style="transform:scale('.$scale.');"></path></svg>';

		$star = '';

		if($rate == '0') {
			$star = $star_empty.$star_empty.$star_empty.$star_empty.$star_empty;
		}

		if($rate == '0.5') {
			$star = $star_half.$star_empty.$star_empty.$star_empty.$star_empty;
		}

		if($rate == '1') {
			$star = $star_full.$star_empty.$star_empty.$star_empty.$star_empty;
		}

		if($rate == '1.5') {
			$star = $star_full.$star_half.$star_empty.$star_empty.$star_empty;
		}

		if($rate == '2') {
			$star = $star_full.$star_full.$star_empty.$star_empty.$star_empty;
		}

		if($rate == '2.5') {
			$star = $star_full.$star_full.$star_half.$star_empty.$star_empty;
		}

		if($rate == '3') {
			$star = $star_full.$star_full.$star_full.$star_empty.$star_empty;
		}

		if($rate == '3.5') {
			$star = $star_full.$star_full.$star_full.$star_half.$star_empty;
		}

		if($rate == '4') {
			$star = $star_full.$star_full.$star_full.$star_full.$star_empty;
		}

		if($rate == '4.5') {
			$star = $star_full.$star_full.$star_full.$star_full.$star_half;
		}

		if($rate == '5') {
			$star = $star_full.$star_full.$star_full.$star_full.$star_full;
		}

		return $star;

	}

	function i_rated($photo_id) { 

		global $db;

		if(is_logged()) {
			$sql = mysqli_query($db,"SELECT * FROM `ratings` WHERE `iduser` = '".$_SESSION['_logged_id']."' AND `photo_id` = '".$photo_id."' LIMIT 1");
		} else {
			$sql = mysqli_query($db,"SELECT * FROM `ratings` WHERE `ip` = '".my_ip()."' AND `photo_id` = '".$photo_id."' LIMIT 1");
		}

		if(mysqli_num_rows($sql)) {
			$fetch = mysqli_fetch_array($sql);
			return $fetch['rate'];
		} else {
			return 0;
		}

	}

	function rating_bar_s($rate) {

		$full = 'M10 1l3 6l6 .75l-4.12 4.62L16 19l-6-3l-6 3l1.13-6.63L1 7.75L7 7z';
		$empty = 'M10 1L7 7l-6 .75l4.13 4.62L4 19l6-3l6 3l-1.12-6.63L19 7.75L13 7zm0 2.24l2.34 4.69l4.65.58l-3.18 3.56l.87 5.15L10 14.88l-4.68 2.34l.87-5.15l-3.18-3.56l4.65-.58z';
		$half = 'M10 1L7 7l-6 .75l4.13 4.62L4 19l6-3l6 3l-1.12-6.63L19 7.75L13 7zm0 2.24l2.34 4.69l4.65.58l-3.18 3.56l.87 5.15L10 14.88V3.24z';

		$star_full = '
		<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" data-star="1" class="star_click svg83" viewBox="0 0 20 20" data-inline="false">
			<path d="'.$full.'" fill="currentColor"></path>
		</svg>';
		$star_empty = '
		<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" data-star="1" class="star_click svg83" viewBox="0 0 20 20" data-inline="false">
			<path d="'.$empty.'" fill="currentColor"></path>
		</svg>';
		$star_half = '
		<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" data-star="1" class="star_click svg83" viewBox="0 0 20 20" data-inline="false">
			<path d="'.$half.'" fill="currentColor"></path>
		</svg>';

		$star = '';

		if($rate == '0') {
			$star = $star_empty.$star_empty.$star_empty.$star_empty.$star_empty;
		}

		if($rate == '0.5') {
			$star = $star_half.$star_empty.$star_empty.$star_empty.$star_empty;
		}

		if($rate == '1') {
			$star = $star_full.$star_empty.$star_empty.$star_empty.$star_empty;
		}

		if($rate == '1.5') {
			$star = $star_full.$star_half.$star_empty.$star_empty.$star_empty;
		}

		if($rate == '2') {
			$star = $star_full.$star_full.$star_empty.$star_empty.$star_empty;
		}

		if($rate == '2.5') {
			$star = $star_full.$star_full.$star_half.$star_empty.$star_empty;
		}

		if($rate == '3') {
			$star = $star_full.$star_full.$star_full.$star_empty.$star_empty;
		}

		if($rate == '3.5') {
			$star = $star_full.$star_full.$star_full.$star_half.$star_empty;
		}

		if($rate == '4') {
			$star = $star_full.$star_full.$star_full.$star_full.$star_empty;
		}

		if($rate == '4.5') {
			$star = $star_full.$star_full.$star_full.$star_full.$star_half;
		}

		if($rate == '5') {
			$star = $star_full.$star_full.$star_full.$star_full.$star_full;
		}

		return $star;

	}

	function user_generator($name) {

		global $db;

		$name = trim(strtolower($name));
		$new_username = preg_replace("/[^A-Za-z0-9]/", '', $name);

   		$query = mysqli_query($db,"SELECT COUNT(id) as user_count FROM `users` WHERE `user` LIKE '%".$new_username."%'");
    		$fetch = mysqli_fetch_array($query);
		$count = $fetch['user_count'];

    		if(!empty($count)) {
        		$new_username = $new_username . $count;
    		}

    		if(strlen($new_username) > 3) {
			return $new_username;
		} else {
			return time();
		}

	}

	function rotate_image($filename,$new_filename) {

		$source = imagecreatefromjpeg($filename);
		$rotate = imagerotate($source, -90, 0);
		imagejpeg($rotate, $new_filename);

	}

    	function compress_image($source, $quality) {
        		
		list($width, $height, $type, $attr) = getimagesize($source);

        	if($type == '2') {
			$image = imagecreatefromjpeg($source);
       		} elseif($type == '1') {
			$image = imagecreatefromgif($source);
       		} elseif($type == '3') {
			$image = imagecreatefrompng($source);
        	}
		
		if($width > 1500 || $height > 1500) {
			crop_image($source, $source, 1500, 1500);
		} else {
			imagejpeg($image, $source, $quality);
		}

	}

	function check_rating($rating) {

		$new_rating = $rating;

		if(strlen($rating) == 1) {
			$new_rating = $rating.'.00';
		}

		if(strlen($rating) == 3) {
			$new_rating = $rating.'0';
		}

		return $new_rating;

	}

	function round_rate($rating) {

		$final_rate = 0.00;
		if(strstr($rating,'.')) {

			$deploy_rate = explode('.',$rating);
			if(isset($deploy_rate[1]) && $deploy_rate[1]) {

				if(substr($deploy_rate[1],0,1) == '0') {
					$final_rate = $deploy_rate[0];
				} else {
					if($deploy_rate[1] > 24) {
						if($deploy_rate[1] > 74) {
							$final_rate = $deploy_rate[0] + 1;
						} else {
							$final_rate = $deploy_rate[0].'.5';
						}
					} else {
						$final_rate = $deploy_rate[0];
					}
				}

				if($final_rate > 5) {
					$final_rate = 5;
				}
	
			}

		} else {
			$final_rate = $rating;
		}

		return $final_rate;

	}

	function is_home() {

		if(!isset($_GET['profile']) && !isset($_GET['contact']) && !isset($_GET['forgot']) && !isset($_GET['contests']) && !isset($_GET['photo']) && !isset($_GET['contest']) && !isset($_GET['extra_page']) && !isset($_GET['settings']) && !isset($_GET['ranking'])) {
			return true;
		}

	}

	function current_page() {

		if(!isset($_GET['profile']) && !isset($_GET['contact']) && !isset($_GET['forgot']) && !isset($_GET['contests']) && !isset($_GET['photo']) && !isset($_GET['contest']) && !isset($_GET['extra_page']) && !isset($_GET['settings']) && !isset($_GET['ranking'])) {
			return 'home';
		}
		
		if(isset($_GET['settings'])) {
			return 'settings';
		}

		if(isset($_GET['contact'])) {
			return 'contact';
		}

		if(isset($_GET['extra_page'])) {
			return 'extra_page';
		}

		if(isset($_GET['forgot'])) {
			return 'forgot';
		}

		if(isset($_GET['contest'])) {
			return 'contest';
		}

		if(isset($_GET['contests'])) {
			return 'contests';
		}

		if(isset($_GET['profile'])) {
			return 'profile';
		}

		if(isset($_GET['photo'])) {
			return 'photo';
		}

		if(isset($_GET['ranking'])) {
			return 'ranking';
		}

	}

	function correct_exif_rotation($filename) {
  
		if(function_exists('exif_read_data')) {

    			$exif = @exif_read_data($filename);

    			if($exif && isset($exif['Orientation'])) {

      				$orientation = $exif['Orientation'];

      				if($orientation != 1){

        				$img = imagecreatefromjpeg($filename);
        				$deg = 0;

        				switch ($orientation) {
          					case 3:
            						$deg = 180;
            					break;
          					case 6:
            						$deg = 270;
            					break;
          					case 8:
           						$deg = 90;
            					break;
        				}

        				if($deg) {
          					$img = imagerotate($img, $deg, 0);        
        				}
        					
					imagejpeg($img, $filename, 95);
      
				}

    			}
  
		}    
	
	}
?>