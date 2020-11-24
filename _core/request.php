<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
session_start();

	require_once('_functions.php');

	$settings = get_settings();

	if(!isset($settings['min_votes'])) {
		$settings['min_votes'] = 0;
	}

	$return = array('error'=>1,'error_text'=>_LANG_ERROR_DEFAULT);

	if(!isset($_POST['reason'])) {
		echo json_encode($return);
		die();
	} else {
		$reason = mysqli_real_escape_string($db,$_POST['reason']);
	}

	if($reason == 'forgot' && isset($_POST['email']) && $_POST['email']) {

		$email = mysqli_real_escape_string($db,$_POST['email']);

		$sql = mysqli_query($db,"SELECT * FROM `users` WHERE `email` = '".$email."' LIMIT 1");
		if(mysqli_num_rows($sql)) {

			$fetch = mysqli_fetch_array($sql);

			$return['error'] = 0;
			$email_key = md5($fetch['password'].$fetch['registered']);

			require 'PHPMailer/src/Exception.php';
			require 'PHPMailer/src/PHPMailer.php';
			require 'PHPMailer/src/SMTP.php';

			$mail = new PHPMailer(true);

			try {
				$domain_name = parse_url($settings['site_url']);
  				$mail->setFrom('contact@'.$domain_name['host'], $settings['site_logo']);
    				$mail->addAddress($fetch['email'], $fetch['name']);
    				$mail->isHTML(true);
    				$mail->Subject = 'Forgot password - '.$settings['site_logo'];
    				$mail->Body    = 'Hello '.$fetch['name'].',<br><br>To change your current password go to: <a href="'.$settings['site_url'].'index.php?forgot='.$email_key.'">'.$settings['site_url'].'index.php?forgot='.$email_key.'</a>';
    				$mail->send();
			} catch (Exception $e) { }

		}

	}

	if($reason == 'next_random') {

		$extra_sql = '';
		if(isset($settings['vote_own']) && $settings['vote_own'] == '0' && is_logged()) {
			$extra_sql = " `iduser` != '".$_SESSION['_logged_id']."' AND ";
		}

		if(is_logged()) {
			$sql_s = mysqli_query($db,"SELECT `id` FROM `content` WHERE $extra_sql `id` NOT IN (select photo_id FROM `ratings` WHERE iduser = '".$_SESSION['_logged_id']."') ORDER BY rand() LIMIT 1");
		} else {
			$sql_s = mysqli_query($db,"SELECT `id` FROM `content` WHERE `id` NOT IN (select photo_id FROM `ratings` WHERE ip = '".my_ip()."') ORDER BY rand() LIMIT 1");
		}

		if(mysqli_num_rows($sql_s)) {
			$fetch_s = mysqli_fetch_array($sql_s);
			$return['error'] = 0;
			$return['random'] = $fetch_s['id'];
		}

	}

	if($reason == 'update_description' && isset($_POST['photo_id']) && is_numeric($_POST['photo_id']) && is_logged()) {

		$photo_id = mysqli_real_escape_string($db,$_POST['photo_id']);
		$sql_update_desc = mysqli_query($db,"SELECT * FROM `content` WHERE `id` = '".$photo_id."' AND `iduser` = '".$_SESSION['_logged_id']."' LIMIT 1");
		if(mysqli_num_rows($sql_update_desc)) {

			$description = mysqli_real_escape_string($db,$_POST['description']);

			if(isset($settings['description_links']) && $settings['description_links'] == '1') {
				$description = strip_tags($description,'<a>');
			} else {
				$description = strip_tags($description);
			}

			mysqli_query($db,"UPDATE `content` SET `description` = '".$description."' WHERE `id` = '".$photo_id."' AND `iduser` = '".$_SESSION['_logged_id']."' LIMIT 1");

		}

	}

	if($reason == 'remove_contest_photo' && is_logged() && isset($_POST['photo_id']) && is_numeric($_POST['photo_id'])) {

		$photo_id = mysqli_real_escape_string($db,$_POST['photo_id']);

		$sql = mysqli_query($db,"SELECT * FROM `content` WHERE `iduser` = '".$_SESSION['_logged_id']."' AND `id` = '".$photo_id."' LIMIT 1");
		if(mysqli_num_rows($sql)) {
			mysqli_query($db,"UPDATE `content` SET `contest` = '0', `nr_ratings` = '0', `rating` = '0' WHERE `iduser` = '".$_SESSION['_logged_id']."' AND `id` = '".$photo_id."' LIMIT 1");
		}

	}

	if($reason == 'join_contest' && is_logged() && isset($_POST['contest_id']) && is_numeric($_POST['contest_id']) && isset($_POST['photos'])) {

		$photos = json_decode($_POST['photos'],true);
		$contest_id = mysqli_real_escape_string($db,$_POST['contest_id']);

		if(isset($photos) && count($photos)) {

			foreach($photos as $photo) {
				$photo = mysqli_real_escape_string($db,$photo);
				if(is_numeric($photo)) {
					$sql_s = mysqli_query($db,"SELECT * FROM `content` WHERE `iduser` = '".$_SESSION['_logged_id']."' AND `contest` = '0' AND `id` = '".$photo."' LIMIT 1");
					if(mysqli_num_rows($sql_s)) {
						mysqli_query($db,"UPDATE `content` SET `contest` = '".$contest_id."', `nr_ratings` = '0', `rating` = '0' WHERE `iduser` = '".$_SESSION['_logged_id']."' AND `id` = '".$photo."' LIMIT 1");
					}
				}
			}

		}

	}

	if($reason == 'get_description' && isset($_POST['photo_id']) && is_numeric($_POST['photo_id']) && is_logged()) {

		$photo_id = mysqli_real_escape_string($db,$_POST['photo_id']);
		$sql_desc = mysqli_query($db,"SELECT `description`,`type`,`cover` FROM `content` WHERE `id` = '".$photo_id."' LIMIT 1");
		$fetch_desc = mysqli_fetch_array($sql_desc);
		$return['description'] = $fetch_desc['description'];
		$return['type'] = $fetch_desc['type'];
		$return['cover'] = $fetch_desc['cover'];
		$return['error'] = 0;

	}

	if($reason == 'add_comment' && is_logged() && isset($_POST['comment']) && isset($_POST['photo_id']) && is_numeric($_POST['photo_id'])) {

		$photo_id = mysqli_real_escape_string($db,$_POST['photo_id']);
		$comment = mysqli_real_escape_string($db,strip_tags($_POST['comment']));
		$comment = trim($comment);

		if(isset($settings['comments_review']) && $settings['comments_review'] == '1') {
			$approved = 0;
		} else {
			$approved = 1;
		}

		if(strlen($comment) > 1 && $photo_id) {
			mysqli_query($db,"INSERT INTO `comments` (`photo_id`,`user_id`,`comment`,`approved`) VALUES ('".$photo_id."','".$_SESSION['_logged_id']."','".$comment."','".$approved."')");
			$return['error'] = 0;
			$return['comment'] = $comment;
		}

	}

	if($reason == 'load_my_photos' && is_logged()) {

		$return['my_photos'] = array();

		$sql = mysqli_query($db,"SELECT * FROM `content` WHERE `iduser` = '".$_SESSION['_logged_id']."' AND `contest` = '0' AND `approved` = '1' ORDER BY `id` DESC LIMIT 50");
		while($fetch = mysqli_fetch_array($sql)) {

			$return['my_photos'][] = array(
				'id'=>$fetch['id'],
				'photo'=>$fetch['photo'],
				'type'=>$fetch['type'],
				'cover'=>$fetch['cover'],
			);

		}

	}

	if($reason == 'comments' && isset($_POST['photo_id']) && is_numeric($_POST['photo_id'])) {

		$return['comments'] = array();

		$photo_id = mysqli_real_escape_string($db,$_POST['photo_id']);
		$sql_comments = mysqli_query($db,"SELECT a.`id`, a.`user_id`, a.`comment`, a.`date`, b.`user`, b.`name`, b.`profile_picture` FROM `comments` a INNER JOIN `users` b ON a.`user_id` = b.`id` WHERE a.`approved` = '1' AND a.`photo_id` = '".$photo_id."' ORDER BY a.`id` DESC LIMIT 100") or die(mysqli_error($db));
		while($fetch_comment = mysqli_fetch_array($sql_comments)) {

			$return['comments'][] = array(
				'id'=>$fetch_comment['id'],
				'name'=>$fetch_comment['name'],
				'picture'=>$fetch_comment['profile_picture'],
				'date'=>date_to_text($fetch_comment['date']),
				'comment'=>$fetch_comment['comment'],
				'user'=>$fetch_comment['user'],
			);

		}
	}

	if($reason == 'search') {

		$return['list'] = array();

		if(isset($_POST['term']) && strlen(trim($_POST['term']))) {

			$term = mysqli_real_escape_string($db,$_POST['term']);
			$sql = mysqli_query($db,"SELECT * FROM `users` WHERE `name` LIKE '%".$term."%' OR `user` LIKE '%".$term."%' LIMIT 5");
			while($fetch = mysqli_fetch_array($sql)) {

				$return['list'][] = array(
					'user'=>$fetch['user'],
					'name'=>$fetch['name'],
					'picture'=>$fetch['profile_picture'],
				);

			}

		}

	}

	if($reason == 'rankings') {

		$order = 'rating';

		if(isset($_POST['order']) && is_numeric($_POST['order'])) {
			$col_rating     = 'rating';
			$col_nr_ratings = 'nr_ratings';
			$col_views      = 'elo_views';
			if ( 'hot_not' == $_POST['rating_type'] ) {
				$col_rating     = 'elo_score';
				$col_nr_ratings = 'elo_nr_ratings';
				$col_views      = 'elo_views';
			}
			if($_POST['order'] == 1) { $order = $col_rating . ' DESC, ' . $col_nr_ratings; }
			if($_POST['order'] == 2) { $order = $col_nr_ratings; }
			if($_POST['order'] == 3) { $order = $col_views; }
		}

		$contest = $contest_y = '';
		if(isset($_POST['contest']) && $_POST['contest'] != '' && is_numeric($_POST['contest'])) {
			$contest_id = mysqli_real_escape_string($db,$_POST['contest']);
			$contest_s = " AND temp.`contest` = '".$contest_id."' ";
			$contest = " AND `contest` = '".$contest_id."' ";
		}

		$category = $category_s = '';
		if(isset($_POST['category']) && is_numeric($_POST['category']) && $_POST['category'] != '-1') {
			$category = " WHERE `category` = '".mysqli_real_escape_string($db,$_POST['category'])."' $contest AND `nr_ratings` >= '".$settings['min_votes']."' ";
			$category_s = " WHERE temp.`category_y` = '".mysqli_real_escape_string($db,$_POST['category'])."' $contest_y ";
		} else {
			$category = " WHERE `nr_ratings` >= '".$settings['min_votes']."' $contest ";
		}

		$return['list'] = array();

		mysqli_query($db,"SET @rank := 0");

		$page_nr = (isset($_POST['page_nr']) && is_numeric($_POST['page_nr']) ? mysqli_real_escape_string($db,$_POST['page_nr']) : 0);
		$limit   = 25;
		$offset  = $page_nr * $limit;

		// TNSB_EDIT_FOR_CUSTOMIZATION_STARTS_HERE
		if($_POST['rating_type']=='hot_not'){
				$column_rating    = 'ROUND(temp.elo_score, 2) as rating';
				$column_nr_rating = 'temp.elo_nr_ratings as nr_ratings';
				$column_views     = 'temp.`elo_views` as views';
		}else{
				$column_rating    = 'temp.rating as rating';
				$column_nr_rating = 'temp.nr_ratings as nr_ratings';
				$column_views     = 'temp.`views` as views';
		}

		$sql_rank = mysqli_query($db,"SELECT b.name,b.user,b.id as id2,`rank`,temp.`id` as id3,`iduser`,`category` as 'category_s'," . $column_nr_rating . ",".$column_rating."," . $column_views . ",`photo`,`type`,`cover` FROM (SELECT (@rank := @rank + 1) AS rank, `id`, `iduser`,`rating`,`category` as 'category_y',`nr_ratings`,`views`,`photo`,`type`,`cover`, `elo_score`, `elo_nr_ratings`, `elo_views` FROM `content` $category ORDER BY $order DESC) temp JOIN `users` b ON temp.iduser = b.id $category_s ORDER BY `rank` ASC LIMIT $offset,$limit") or die(mysqli_error($db));
		// TNSB_EDIT_FOR_CUSTOMIZATION_ENDS_HERE

		$tot = 0;
		while($fetch = mysqli_fetch_array($sql_rank)) {

			if(!isset($settings['content_ratemode']) || (isset($settings['content_ratemode']) && $settings['content_ratemode'] == '0')) {

				if($fetch['rating'] == '0') { $fetch['rating'] = '0.00'; }

				$rating_score = $fetch['rating'];

				if(strlen($rating_score) == '1') {
					$rating_score = $rating_score.'.00';
				}

				if(strlen($rating_score) == '3') {
					$rating_score = $rating_score.'0';
				}

			}

			if(isset($settings['content_ratemode']) && $settings['content_ratemode'] == '1') {

				$rating_score = $fetch['rating'];

			}

			$return['list'][] = array(
				'id'=>$fetch['id3'],
				'rank'=>$fetch['rank'],
				'photo'=>$fetch['photo'],
				'user'=>$fetch['user'],
				'name'=>$fetch['name'],
				'rating'=>$fetch['rating'],
				'rating_score'=>$rating_score,
				'nr_ratings'=>round($fetch['nr_ratings'],2),
				'views'=>$fetch['views'],
				'type'=>$fetch['type'],
				'cover'=>$fetch['cover'],
			);

			$tot++;

		}

		if($tot) {
			$return['error'] = 0;
		}

	}

	if($reason == 'photos' && isset($_POST['type']) && ($_POST['type'] == 'home' || $_POST['type'] == 'profile')) {

		$return['error'] = 0;
		$return['files'] = array();

		$type = mysqli_real_escape_string($db,$_POST['type']);
		$page_nr = (isset($_POST['page_nr']) && is_numeric($_POST['page_nr']) ? mysqli_real_escape_string($db,$_POST['page_nr']) : 0);

		$limit = $settings['photos_per_page'];
		$offset = $page_nr * $limit;

		if($type == 'home') {
			if(isset($_POST['category']) && is_numeric($_POST['category']) && $_POST['category'] != '-1') {
				$category = mysqli_real_escape_string($db,$_POST['category']);
				$sql = mysqli_query($db,"SELECT * FROM `content` WHERE `approved` = '1' AND `category` = '".$category."' ORDER BY `id` DESC LIMIT $offset,$limit");
			} else {
				$sql = mysqli_query($db,"SELECT * FROM `content` WHERE `approved` = '1' ORDER BY `id` DESC LIMIT $offset,$limit");
			}
		}

		if($type == 'profile') {
			$profile_id = mysqli_real_escape_string($db,$_POST['id2']);
			$sql = mysqli_query($db,"SELECT * FROM `content` WHERE `approved` = '1' AND `iduser` = '".$profile_id."' ORDER BY `id` DESC LIMIT $offset,$limit");
		}

		while($fetch = mysqli_fetch_array($sql)) {

			if(!isset($settings['content_ratemode']) || (isset($settings['content_ratemode']) && $settings['content_ratemode'] == '0')) {
				if($fetch['rating'] == '0') { $fetch['rating'] = '0.00'; }
				$rate1 = round_rate($fetch['rating']);
				$rate2 = check_rating($fetch['rating']);
			}

			if(isset($settings['content_ratemode']) && $settings['content_ratemode'] == '1') {
				$rate1 = $rate2 = $fetch['rating'];
			}

			$return['files'][] = array(
				'ratings'=>$fetch['nr_ratings'],
				'rate'=>$rate1,
				'rate_real'=>$rate2,
				'photo'=>$fetch['photo'],
				'id'=>$fetch['id'],
				'iduser'=>$fetch['iduser'],
				'type'=>$fetch['type'],
				'cover'=>$fetch['cover'],
			);
		}

	}

	if($reason == 'remove_profile_picture' && is_logged()) {

		$sql_s = mysqli_query($db,"SELECT `profile_picture` FROM `users` WHERE `id` = '".$_SESSION['_logged_id']."' LIMIT 1");
		if(mysqli_num_rows($sql_s)) {
			$fetch_s = mysqli_fetch_array($sql_s);
			if(file_exists('../_uploads/_profile_pictures/'.$fetch_s['profile_picture'].'.jpg')) {
				unlink('../_uploads/_profile_pictures/'.$fetch_s['profile_picture'].'.jpg');
			}
			mysqli_query($db,"UPDATE `users` SET `profile_picture` = '' WHERE `id` = '".$_SESSION['_logged_id']."' LIMIT 1");
		}

	}

	if($reason == 'remove_photo') {

		if(isset($_SESSION['_logged_id']) && isset($_POST['id']) && is_numeric($_POST['id'])) {

			$photo_id = mysqli_real_escape_string($db,$_POST['id']);
			$sql_s = mysqli_query($db,"SELECT `photo` FROM `content` WHERE `id` = '".$photo_id."' AND `iduser` = '".$_SESSION['_logged_id']."' LIMIT 1");
			if(mysqli_num_rows($sql_s)) {
				$fetch_s = mysqli_fetch_array($sql_s);
				if(file_exists('../_uploads/_photos/'.$fetch_s['photo'].'.jpg')) {
					unlink('../_uploads/_photos/'.$fetch_s['photo'].'.jpg');
				}
				if(file_exists('../_uploads/_photos/'.$fetch_s['photo'].'_400.jpg')) {
					unlink('../_uploads/_photos/'.$fetch_s['photo'].'_400.jpg');
				}
				mysqli_query($db,"DELETE FROM `content` WHERE `id` = '".$photo_id."' AND `iduser` = '".$_SESSION['_logged_id']."' LIMIT 1");
			}

		}

	}

	if($reason == 'login') {

		if(isset($_POST['email']) && $_POST['email'] && isset($_POST['password']) && $_POST['password']) {

			$email = strtolower(trim(mysqli_real_escape_string($db,$_POST['email'])));
			$password = mysqli_real_escape_string($db,$_POST['password']);

			if($email && $password && strlen($password) > 5) {
				$check = mysqli_query($db,"SELECT * FROM `users` WHERE `email` = '".$email."' AND `password` = '".hash('sha512',$password)."' LIMIT 1");
				if(mysqli_num_rows($check)) {

					$get = mysqli_fetch_array($check);
					$_SESSION['_logged'] = 1;
					$_SESSION['_logged_id'] = $get['id'];
					$_SESSION['_logged_user'] = $get['user'];
					$_SESSION['_logged_name'] = $get['name'];

					$return['error'] = 0;

				} else {

					$return['error'] = 2;
					$return['error_text'] = _LANG_LOGIN_ERROR;

				}
			} else {
				$return['error'] = 2;
				$return['error_text'] = _LANG_LOGIN_ERROR;
			}

		}

	}

	if($reason == 'register') {

		if(isset($_POST['email']) && $_POST['email'] && isset($_POST['name']) && $_POST['name'] && isset($_POST['password']) && $_POST['password'] && isset($_POST['repeat_password']) && $_POST['repeat_password']) {

			$return['error'] = 0;

			$email = trim(mysqli_real_escape_string($db,$_POST['email']));
			$name = trim(mysqli_real_escape_string($db,$_POST['name']));
			$password = mysqli_real_escape_string($db,$_POST['password']);
			$repeat_password = mysqli_real_escape_string($db,$_POST['repeat_password']);
			$category = mysqli_real_escape_string($db,$_POST['category']);

			$name = strip_tags($name);
			$email = strip_tags($email);

			if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$return['error'] = 2;
				$return['error_text'] = _LANG_REGISTER_ERROR_EMAIL;
			}

			if($return['error'] == '0' && strlen($password) < 6) {
				$return['error'] = 3;
				$return['error_text'] = _LANG_REGISTER_ERROR_PASSWORD;
			}

			if($return['error'] == '0' && ($password != $repeat_password)) {
				$return['error'] = 4;
				$return['error_text'] = _LANG_REGISTER_ERROR_REPEAT_PASSWORD;
			}

			if($return['error'] == '0') {
				$check_email = mysqli_query($db,"SELECT * FROM `users` WHERE `email` = '".$email."' LIMIT 1");
				if(mysqli_num_rows($check_email)) {
					$return['error'] = 5;
					$return['error_text'] = _LANG_REGISTER_ERROR_EMAIL_EXISTS;
				}
			}

			if($settings['category_required'] == '1' && $return['error'] == '0') {
				if(!is_numeric($category)) {
					$return['error'] = 6;
					$return['error_text'] = _LANG_REGISTER_ERROR_CATEGORY;
				}
			}

			if($return['error'] == '0') {
				$user = user_generator($name);
				mysqli_query($db,"INSERT INTO `users` (`email`,`name`,`password`,`user`,`category`) VALUES ('".$email."','".$name."','".hash('sha512',$password)."','".$user."','".$category."')") or die(mysqli_error($db));

				$my_id = mysqli_insert_id($db);

				$check = mysqli_query($db,"SELECT * FROM `users` WHERE `id` = '".$my_id."' LIMIT 1");
				if(mysqli_num_rows($check)) {

					$get = mysqli_fetch_array($check);
					$_SESSION['_logged'] = 1;
					$_SESSION['_logged_id'] = $get['id'];
					$_SESSION['_logged_user'] = $get['user'];
					$_SESSION['_logged_name'] = $get['name'];

					$return['error'] = 0;

				}
			}

		}

	}

	if($reason == 'fb_login') {

		if(isset($_POST['fb_userid']) && $_POST['fb_userid']) {

			$fb_userid = mysqli_real_escape_string($db,$_POST['fb_userid']);

			$return['error'] = 0;

			if($fb_userid) {

				$check = mysqli_query($db,"SELECT * FROM `users` WHERE `fb_id` = '".$fb_userid."' LIMIT 1");
				if(mysqli_num_rows($check)) {

					$get = mysqli_fetch_array($check);
					$_SESSION['_logged'] = 1;
					$_SESSION['_logged_id'] = $get['id'];
					$_SESSION['_logged_user'] = $get['user'];
					$_SESSION['_logged_name'] = $get['name'];

					$return['error'] = 0;

				} else {

					if(isset($_POST['email']) && strstr($_POST['email'],'@') && isset($_POST['name']) && $_POST['name'] != '') {

						$email = trim(mysqli_real_escape_string($db,$_POST['email']));
						$name = trim(mysqli_real_escape_string($db,$_POST['name']));

						$name = strip_tags($name);
						$email = strip_tags($email);

						if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
							$return['error'] = 2;
						}

						if($return['error'] == '0') {
							$check_email = mysqli_query($db,"SELECT * FROM `users` WHERE `email` = '".$email."' LIMIT 1");
							if(mysqli_num_rows($check_email)) {

								$fetch_mail = mysqli_fetch_array($check_email);
								mysqli_query($db,"UPDATE `users` SET `fb_id` = '".$fb_userid."' WHERE `id` = '".$fetch_mail['id']."' LIMIT 1");

								$_SESSION['_logged'] = 1;
								$_SESSION['_logged_id'] = $fetch_mail['id'];
								$_SESSION['_logged_user'] = $fetch_mail['user'];
								$_SESSION['_logged_name'] = $fetch_mail['name'];

								$return['error'] = 0;

							} else {

								$user = user_generator($name);
								mysqli_query($db,"INSERT INTO `users` (`email`,`name`,`user`,`fb_id`) VALUES ('".$email."','".$name."','".$user."','".$fb_userid."')") or die(mysqli_error($db));
								$db_id = mysqli_insert_id($db);
								if($db_id) {

									$_SESSION['_logged'] = 1;
									$_SESSION['_logged_id'] = $db_id;
									$_SESSION['_logged_user'] = $user;
									$_SESSION['_logged_name'] = $name;

									$return['error'] = 0;

								}

							}

						}

					}

				}

			}

		}

	}

	if($reason == 'rotate' && isset($_POST['image_id']) && is_numeric($_POST['image_id']) && is_logged()) {

		$image_id = mysqli_real_escape_string($db,$_POST['image_id']);
		$sql = mysqli_query($db,"SELECT * FROM `content` WHERE `id` = '".$image_id."' AND `iduser` = '".$_SESSION['_logged_id']."' LIMIT 1");
		if(mysqli_num_rows($sql)) {

			$fetch = mysqli_fetch_array($sql);
			$new_photo_id = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVXYZ"), 0, 20);
			rotate_image('../_uploads/_photos/'.$fetch['photo'].'.jpg', '../_uploads/_photos/'.$new_photo_id.'.jpg');
			crop_image('../_uploads/_photos/'.$new_photo_id.'.jpg', '../_uploads/_photos/'.$new_photo_id.'_400.jpg', 400, 400);

			mysqli_query($db,"UPDATE `content` SET `photo` = '".$new_photo_id."' WHERE `id` = '".$image_id."' AND `iduser` = '".$_SESSION['_logged_id']."' LIMIT 1");
			$return['error'] = 0;
			$return['file'] = $new_photo_id;

		}

	}

	if($reason == 'update_slogan' && is_logged()) {

		if(isset($_POST['slogan']) && $_POST['slogan'] != '') {
			$slogan = mysqli_real_escape_string($db,$_POST['slogan']);
		} else {
			$slogan = '';
		}

		$slogan = trim($slogan);
		$slogan = strip_tags($slogan);

		if($slogan != _LANG_PROFILE_ADD_SLOGAN) {
			mysqli_query($db,"UPDATE `users` SET `slogan` = '".$slogan."' WHERE `id` = '".$_SESSION['_logged_id']."' LIMIT 1");
		}

	}

	if($reason == 'rate' && isset($_POST['rate']) && ($_POST['rate'] > 0 && $_POST['rate'] < 6) && isset($_POST['photo_id']) && is_numeric($_POST['photo_id'])) {

		$rate = mysqli_real_escape_string($db,$_POST['rate']);
		$photo_id = mysqli_real_escape_string($db,$_POST['photo_id']);

		if(is_logged()) {
			mysqli_query($db,"INSERT IGNORE INTO `ratings` (`iduser`,`rate`,`photo_id`,`ip`) VALUES ('".$_SESSION['_logged_id']."','".$rate."','".$photo_id."','".my_ip()."')");
		} else {
			mysqli_query($db,"INSERT IGNORE INTO `ratings` (`iduser`,`rate`,`photo_id`,`ip`) VALUES ('0','".$rate."','".$photo_id."','".my_ip()."')");
		}

		$sql_count = mysqli_query($db,"SELECT SUM(rate) as 'rating', count(*) as 'total_ratings' FROM `ratings` WHERE `photo_id` = '".$photo_id."'");
		if(mysqli_num_rows($sql_count)) {
			$fetch_count = mysqli_fetch_array($sql_count);

			if(isset($settings['content_ratemode']) && $settings['content_ratemode'] == '1') {

				$total_ratings = $new_rating = $fetch_count['total_ratings'];

			}

			if(!isset($settings['content_ratemode']) || (isset($settings['content_ratemode']) && $settings['content_ratemode'] == '0')) {

				if($fetch_count['rating'] && $fetch_count['total_ratings']) {
					$new_rating = $fetch_count['rating'] / $fetch_count['total_ratings'];
					$total_ratings = $fetch_count['total_ratings'];
					if(strlen($new_rating) == 1) { $new_rating = $new_rating.'.00'; }
					if(strlen($new_rating) == 3) { $new_rating = $new_rating.'0'; }
					if(strlen($new_rating) > 4) { $new_rating = substr($new_rating,0,4); }
				} else {
					$new_rating = '0.00';
					$total_ratings = 0;
				}

			}

			mysqli_query($db,"UPDATE `content` SET `nr_ratings` = '".$total_ratings."', `rating` = '".$new_rating."' WHERE `id` = '".$photo_id."' LIMIT 1");

			$sql_s = mysqli_query($db,"SELECT `contest` FROM `content` WHERE `id` = '".$photo_id."' LIMIT 1");
			$fetch_photo = mysqli_fetch_array($sql_s);

			mysqli_query($db,"SET @rank := 0");
			if($fetch_photo['contest']) {
				$sql_rank = mysqli_query($db,"SELECT `rank`,`id`,`contest` FROM (SELECT (@rank := @rank + 1) AS rank, `id`, `contest` FROM `content` WHERE `contest` = '".$fetch_photo['contest']."' ORDER BY `rating` DESC) temp WHERE `id` = '".$photo_id."'") or die(mysqli_error($db));
			} else {
				$sql_rank = mysqli_query($db,"SELECT `rank`,`id` FROM (SELECT (@rank := @rank + 1) AS rank, `id` FROM `content` ORDER BY `rating` DESC) temp WHERE `id` = '".$photo_id."'") or die(mysqli_error($db));
			}
			$fetch_rank = mysqli_fetch_array($sql_rank);

			$return['error'] = 0;
			$return['new_rank'] = $fetch_rank['rank'];
			$return['new_rating'] = $new_rating;
			$return['nr_ratings'] = $total_ratings;
			$return['real_rate'] = round_rate($new_rating);

			if($settings['min_votes'] > $total_ratings) {
				$return['new_rank'] = '';
			}

		}

	}

	if($reason == 'logout') {
		session_destroy();
		die();
	}

	echo json_encode($return);
?>
