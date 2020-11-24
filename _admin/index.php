<?php
session_start();

	if(isset($_GET['page']) && $_GET['page'] == 'logout') {
		session_destroy();
		header('location: login.php');
		die();
	}

	if(!isset($_SESSION['_logged_admin'])) {
		header('location: login.php');
		die();
	}

	require_once('../_core/_functions.php');

	if(isset($_GET['page']) && $_GET['page'] == 'meta_tags') {

		$meta_pages = array(
			'home'=>1,
			'contest'=>0,
			'ranking'=>0,
			'profile'=>0,
			'post'=>0,
		);

		if(isset($_POST['submit'])) {

			$meta_tags = array();

			$meta_tags['home'] = array();
			$meta_tags['home']['title'] = full_escape($_POST['home_title']);
			$meta_tags['home']['description'] = full_escape($_POST['home_description']);
			$meta_tags['home']['keywords'] = full_escape($_POST['home_keywords']);

			$meta_tags['contest'] = array();
			$meta_tags['contest']['title'] = full_escape($_POST['contest_title']);
			$meta_tags['contest']['description'] = full_escape($_POST['contest_description']);
			$meta_tags['contest']['keywords'] = full_escape($_POST['contest_keywords']);

			$meta_tags['ranking'] = array();
			$meta_tags['ranking']['title'] = full_escape($_POST['ranking_title']);
			$meta_tags['ranking']['description'] = full_escape($_POST['ranking_description']);
			$meta_tags['ranking']['keywords'] = full_escape($_POST['ranking_keywords']);

			$meta_tags['profile'] = array();
			$meta_tags['profile']['title'] = full_escape($_POST['profile_title']);
			$meta_tags['profile']['description'] = full_escape($_POST['profile_description']);
			$meta_tags['profile']['keywords'] = full_escape($_POST['profile_keywords']);

			$meta_tags['post'] = array();
			$meta_tags['post']['title'] = full_escape($_POST['post_title']);
			$meta_tags['post']['description'] = full_escape($_POST['post_description']);
			$meta_tags['post']['keywords'] = full_escape($_POST['post_keywords']);

			foreach($meta_tags as $key=>$value) {

				$sql_check1 = mysqli_query($db,"SELECT * FROM `meta_tags` WHERE `page` = '".$key."' LIMIT 1");
				if(mysqli_num_rows($sql_check1)) {
					mysqli_query($db,"UPDATE `meta_tags` SET `title` = '".$meta_tags[$key]['title']."', `description` = '".$meta_tags[$key]['description']."', `keywords` = '".$meta_tags[$key]['keywords']."' WHERE `page` = '".$key."' LIMIT 1");
				} else {
					mysqli_query($db,"INSERT INTO `meta_tags` (`page`,`title`,`description`,`keywords`) VALUES ('".$key."','".$meta_tags[$key]['title']."','".$meta_tags[$key]['description']."','".$meta_tags[$key]['keywords']."')");
				}

			}

			$success_msg = 'Changes has been saved';

		}

		$site_meta = array();

		$sql_meta = mysqli_query($db,"SELECT * FROM `meta_tags`");
		while($fetch_meta = mysqli_fetch_array($sql_meta)) {

			$site_meta[$fetch_meta['page']] = array(
				'title'=>$fetch_meta['title'],
				'description'=>$fetch_meta['description'],
				'keywords'=>$fetch_meta['keywords']
			);

		}

	}

	if(isset($_GET['page']) && $_GET['page'] == 'contest') {

		$contest_error_msg = '';

		if(isset($_POST['submit'])) {

			$extra_id = (isset($_POST['extra_id']) && is_numeric($_POST['extra_id']) ? $_POST['extra_id'] : 0);

			$active = (isset($_POST['contest_active']) && $_POST['contest_active'] == 1 ? 1 : 0);

			$title = $description = $end = $disable_countdown = $contest_type = '';
			$end = '0000-00-00T00:00';

			if(isset($_POST['contest_title']) && $_POST['contest_title'] != '') {
				$title = mysqli_real_escape_string($db,$_POST['contest_title']);
			}

			if(isset($_POST['contest_description']) && $_POST['contest_description'] != '') {
				$description = mysqli_real_escape_string($db,$_POST['contest_description']);
			}

			if(isset($_POST['contest_end']) && $_POST['contest_end'] != '') {
				$end = mysqli_real_escape_string($db,$_POST['contest_end']);
			}

			if(isset($_POST['disable_countdown']) && $_POST['disable_countdown'] != '') {
				$disable_countdown = mysqli_real_escape_string($db,$_POST['disable_countdown']);
			}

			// TNSB_EDIT_FOR_CUSTOMIZATION_STARTS_HERE
			if(isset($_POST['contest_type']) && $_POST['contest_type'] != '' && in_array( $_POST['contest_type'], ['five_star', 'hot_not'] ) ) {
				$contest_type = mysqli_real_escape_string($db,$_POST['contest_type']);
			}
			// TNSB_EDIT_FOR_CUSTOMIZATION_ENDS_HERE

			if(!$title) {
				$contest_error_msg = 'Title is required';
			}

			if(!$contest_error_msg) {

				// TNSB_EDIT_FOR_CUSTOMIZATION_STARTS_HERE
				if(isset($extra_id) && $extra_id != '0') {
					mysqli_query($db,"UPDATE `contest` SET `title` = '".$title."', `description` = '".$description."', `active` = '".$active."', `end` = '".$end."', `disable_countdown` = '".$disable_countdown."', `contest_type` = '".$contest_type."' WHERE `id` = '".$extra_id."' LIMIT 1");
				} else {
					mysqli_query($db,"INSERT INTO `contest` (`active`,`title`,`description`,`end`,`disable_countdown`, `contest_type`) VALUES ('".$active."','".$title."','".$description."','".$end."','".$disable_countdown."','".$contest_type."')") or die(mysqli_error($db));
				}
				//TNSB_EDIT_FOR_CUSTOMIZATION_ENDS_HERE

				header('location: index.php?page=contests');

			}

		}

		if(isset($_GET['cid']) && is_numeric($_GET['cid'])) {
			$cid = mysqli_real_escape_string($db,$_GET['cid']);
			$check_contest = mysqli_query($db,"SELECT * FROM `contest` WHERE `id` = '".$cid."' LIMIT 1");
			if(mysqli_num_rows($check_contest)) {
				$site_contest = mysqli_fetch_array($check_contest);
				$site_contest['end'] = str_replace(' ','T',substr($site_contest['end'],0,16));
			}
		}

	}

	if(isset($_GET['page']) && $_GET['page'] == 'pages') {

		if(isset($_GET['new_page']) && isset($_POST['submit'])) {

			if(isset($_GET['npid']) && is_numeric($_GET['npid'])) {
				$npid = mysqli_real_escape_string($db,$_GET['npid']);
			}

			if(isset($_POST['pages_title']) && $_POST['pages_title'] != '') {
				$title = strip_tags($_POST['pages_title']);
				$title = htmlentities($title);
				$title = mysqli_real_escape_string($db,$title);
				$title = trim($title);
			} else {
				$title = '';
			}

			if(isset($_POST['pages_content']) && $_POST['pages_content'] != '') {
				$content = htmlentities($_POST['pages_content']);
				$content = mysqli_real_escape_string($db,$content);
			} else {
				$title = '';
			}

			if(isset($_POST['pages_footer']) && $_POST['pages_footer'] == '1') {
				$footer = 1;
			} else {
				$footer = 0;
			}

			if(isset($npid) && is_numeric($npid)) {
				$sql_s = mysqli_query($db,"SELECT * FROM `pages` WHERE `id` = '".$npid."' LIMIT 1");
				if(mysqli_num_rows($sql_s)) {
					mysqli_query($db,"UPDATE `pages` SET `title` = '".$title."', `content` = '".$content."', `footer` = '".$footer."' WHERE `id` = '".$npid."' LIMIT 1");
				}
			} else {
				mysqli_query($db,"INSERT INTO `pages` (`title`,`content`,`footer`) VALUES ('".$title."','".$content."','".$footer."')");
			}

			header('location: index.php?page=pages');

		}

		if(isset($_GET['npid']) && is_numeric($_GET['npid'])) {

			$current_pages = array();
			$sql_s = mysqli_query($db,"SELECT * FROM `pages` WHERE `id` = '".mysqli_real_escape_string($db,$_GET['npid'])."' LIMIT 1");
			if(mysqli_num_rows($sql_s)) {

				$fetch_s = mysqli_fetch_array($sql_s);

				$current_pages['title'] = $fetch_s['title'];
				$current_pages['content'] = $fetch_s['content'];
				$current_pages['footer'] = $fetch_s['footer'];

			}

		}

	}

	if(isset($_GET['page']) && $_GET['page'] == 'settings') {

		if(isset($_GET['watermark'])) {

			if(isset($_POST['submit'])) {

				$error_new = 0;
				$new_settings = array();

				if(isset($_POST['watermark']) && $_POST['watermark'] == '1') {
					$watermark = 1;
				} else {
					$watermark = 0;
				}

				if(isset($_POST['watermark_position']) && strlen($_POST['watermark_position']) == '2') {
					$watermark_position = mysqli_real_escape_string($db,$_POST['watermark_position']);
				} else {
					$watermark_position = 'cc';
				}

				if(isset($_POST['watermark_opacity']) && is_numeric($_POST['watermark_opacity'])) {
					$watermark_opacity = mysqli_real_escape_string($db,$_POST['watermark_opacity']);
				} else {
					$watermark_opacity = 20;
				}

				$new_settings['watermark'] = $watermark;
				$new_settings['watermark_position'] = $watermark_position;
				$new_settings['watermark_opacity'] = $watermark_opacity;

				if(isset($_FILES['watermark_image']['error']) && $_FILES['watermark_image']['error'] == '0') {
					if($_FILES['watermark_image']['type'] == 'image/png') {
						list($width,$height) = getimagesize($_FILES['watermark_image']['tmp_name']);
						if($width > 260 || $height > 260) {
							$error_msg = 'Your watermark image is too large.';
						} else {
							if(move_uploaded_file($_FILES['watermark_image']['tmp_name'],'../_uploads/watermark.png')) {
								$new_settings['watermark_image'] = '_uploads/watermark.png';
							}
						}
					} else {
						$error_new = 2;
					}
				}

				foreach($new_settings as $key=>$val) {

					$check_s = mysqli_query($db,"SELECT * FROM `settings` WHERE `set_key` = '".$key."' LIMIT 1");
					if(mysqli_num_rows($check_s)) {
						mysqli_query($db,"UPDATE `settings` SET `set_value` = '".$val."' WHERE `set_key` = '".$key."' LIMIT 1");
					} else {
						mysqli_query($db,"INSERT INTO `settings` (`set_key`,`set_value`) VALUES ('".$key."','".$val."')");
					}

				}

				if(!isset($error_msg)) {
					$success_msg = 'Settings has been saved';
				}

			}

		}

		if(isset($_GET['credentials'])) {

			if(isset($_POST['submit'])) {

				if(isset($_POST['admin_user']) && $_POST['admin_user'] != '') {

					$admin_user = mysqli_real_escape_string($db,$_POST['admin_user']);
					mysqli_query($db,"UPDATE `settings` SET `set_value` = '".$admin_user."' WHERE `set_key` = 'admin_user' LIMIT 1");
					$success_msg = 'Credentials updated';

				}

				if(isset($_POST['new_password']) && isset($_POST['repeat_new_password'])) {

					if(strlen($_POST['new_password']) < 6) {
						$error_msg = 'Password must be at least 6 characters';
					} else {
						if($_POST['new_password'] != $_POST['repeat_new_password']) {
							$error_msg = 'Repeated password not matching';
						} else {
							$new_password = mysqli_real_escape_string($db,$_POST['new_password']);
							mysqli_query($db,"UPDATE `settings` SET `set_value` = '".hash('sha512',$new_password)."' WHERE `set_key` = 'admin_pass' LIMIT 1");
							$success_msg = 'Credentials updated';
						}
					}

				}

			}

		}

		if(!isset($_GET['customization']) && !isset($_GET['watermark']) && !isset($_GET['credentials'])) {

			if(isset($_POST['submit'])) {

				$new_settings = array();

				$new_settings['category_required'] = mysqli_real_escape_string($db,$_POST['category_required']);
				$new_settings['site_url'] = mysqli_real_escape_string($db,$_POST['site_url']);
				$new_settings['fb_appid'] = mysqli_real_escape_string($db,$_POST['fb_appid']);
				$new_settings['preloader'] = mysqli_real_escape_string($db,$_POST['preloader']);
				$new_settings['photo_approval'] = mysqli_real_escape_string($db,$_POST['photo_approval']);
				$new_settings['photos_per_page'] = mysqli_real_escape_string($db,$_POST['photos_per_page']);
				$new_settings['max_uploadsize'] = mysqli_real_escape_string($db,$_POST['max_uploadsize']);
				$new_settings['max_files'] = mysqli_real_escape_string($db,$_POST['max_files']);
				$new_settings['photo_comments'] = mysqli_real_escape_string($db,$_POST['photo_comments']);
				$new_settings['hide_user_photo'] = mysqli_real_escape_string($db,$_POST['hide_user_photo']);
				$new_settings['display_exif'] = mysqli_real_escape_string($db,$_POST['display_exif']);
				$new_settings['allow_description'] = mysqli_real_escape_string($db,$_POST['allow_description']);
				$new_settings['random_photo'] = mysqli_real_escape_string($db,$_POST['random_photo']);
				$new_settings['visitors_rate'] = mysqli_real_escape_string($db,$_POST['visitors_rate']);
				$new_settings['min_votes'] = mysqli_real_escape_string($db,$_POST['min_votes']);
				$new_settings['random_page'] = mysqli_real_escape_string($db,$_POST['random_page']);
				$new_settings['vote_own'] = mysqli_real_escape_string($db,$_POST['vote_own']);
				$new_settings['description_links'] = mysqli_real_escape_string($db,$_POST['description_links']);
				$new_settings['disable_register'] = mysqli_real_escape_string($db,$_POST['disable_register']);
				$new_settings['display_thumb_rate'] = mysqli_real_escape_string($db,$_POST['display_thumb_rate']);
				$new_settings['comments_review'] = mysqli_real_escape_string($db,$_POST['comments_review']);
				$new_settings['site_email'] = mysqli_real_escape_string($db,$_POST['site_email']);
				$new_settings['display_related'] = mysqli_real_escape_string($db,$_POST['display_related']);
				$new_settings['upload_music'] = mysqli_real_escape_string($db,$_POST['upload_music']);
				$new_settings['upload_video'] = mysqli_real_escape_string($db,$_POST['upload_video']);
				$new_settings['content_category'] = mysqli_real_escape_string($db,$_POST['content_category']);
				$new_settings['content_ratemode'] = mysqli_real_escape_string($db,$_POST['content_ratemode']);

				foreach($new_settings as $key=>$val) {

					if($key == 'photo_approval' && $val == '0') {
						mysqli_query($db,"UPDATE `content` SET `approved` = '1' WHERE `approved` = '0'");
					}

					if($key == 'category_required' && $val == '1') {
						$sql_el = mysqli_query($db,"SELECT * FROM `categories`");
						if(mysqli_num_rows($sql_el) == '0') {
							$val = 0;
							$error_msg = 'You cannot set category required = Yes (add some categories first)';
							break;
						}
					}

					$check_s = mysqli_query($db,"SELECT * FROM `settings` WHERE `set_key` = '".$key."' LIMIT 1");
					if(mysqli_num_rows($check_s)) {
						mysqli_query($db,"UPDATE `settings` SET `set_value` = '".$val."' WHERE `set_key` = '".$key."' LIMIT 1");
					} else {
						mysqli_query($db,"INSERT INTO `settings` (`set_key`,`set_value`) VALUES ('".$key."','".$val."')");
					}

				}

				if(!isset($error_msg)) {
					$success_msg = 'Settings has been saved';
				}

			}

		}

		if(isset($_GET['customization'])) {

			if(isset($_POST['submit'])) {

				$new_settings = array();

				$new_settings['site_logo'] = mysqli_real_escape_string($db,$_POST['site_logo']);
				$new_settings['home_header'] = mysqli_real_escape_string($db,$_POST['home_header']);
				$new_settings['photo_sidebar'] = mysqli_real_escape_string($db,$_POST['photo_sidebar']);
				$new_settings['site_theme'] = mysqli_real_escape_string($db,$_POST['site_theme']);
				$new_settings['ranking_page_large'] = mysqli_real_escape_string($db,$_POST['ranking_page_large']);
				$new_settings['contact_page'] = mysqli_real_escape_string($db,$_POST['contact_page']);
				$new_settings['display_searchbar'] = mysqli_real_escape_string($db,$_POST['display_searchbar']);

				if(isset($_FILES['site_logo_image']['error']) && $_FILES['site_logo_image']['error'] == '0') {
					if($_FILES['site_logo_image']['type'] == 'image/jpeg' || $_FILES['site_logo_image']['type'] == 'image/png') {
						list($width,$height) = getimagesize($_FILES['site_logo_image']['tmp_name']);
						if(move_uploaded_file($_FILES['site_logo_image']['tmp_name'],'../_img/logo.png')) {
							$new_settings['site_logo_image'] = '_img/logo.png';
						}
					} else {
						$error_new = 2;
						$error_msg = 'Logo image extension not supported.';
					}
				}

				if(!isset($error_msg)) {

					foreach($new_settings as $key=>$val) {

						$check_s = mysqli_query($db,"SELECT * FROM `settings` WHERE `set_key` = '".$key."' LIMIT 1");
						if(mysqli_num_rows($check_s)) {
							mysqli_query($db,"UPDATE `settings` SET `set_value` = '".$val."' WHERE `set_key` = '".$key."' LIMIT 1");
						} else {
							mysqli_query($db,"INSERT INTO `settings` (`set_key`,`set_value`) VALUES ('".$key."','".$val."')");
						}

					}

					$success_msg = 'Settings has been saved';

				}

			}

		}

		$site_settings = get_settings();

	}

	if(!isset($_GET['page'])) {

		$sql_count = mysqli_query($db,"SELECT count(*) as 'total_users' FROM `users`");
		$fetch_count = mysqli_fetch_array($sql_count);

	}

	if(isset($_GET['page']) && $_GET['page'] == 'ratings') {

		$sql_count = mysqli_query($db,"SELECT count(*) as 'total_ratings' FROM `ratings`");
		$fetch_count = mysqli_fetch_array($sql_count);

	}

	if(isset($_GET['page']) && $_GET['page'] == 'categories') {

		$sql_count = mysqli_query($db,"SELECT count(*) as 'total_categories' FROM `categories`");
		$fetch_count = mysqli_fetch_array($sql_count);

	}

	if(isset($_GET['page']) && $_GET['page'] == 'ads') {

		$sql_count = mysqli_query($db,"SELECT count(*) as 'total_ads' FROM `ads`");
		$fetch_count = mysqli_fetch_array($sql_count);

	}

	if(isset($_GET['page']) && $_GET['page'] == 'contests') {

		$sql_count = mysqli_query($db,"SELECT count(*) as 'total_contests' FROM `contest`");
		$fetch_count = mysqli_fetch_array($sql_count);

	}

	if(isset($_GET['page']) && $_GET['page'] == 'pages') {

		$sql_count = mysqli_query($db,"SELECT count(*) as 'total_pages' FROM `pages`");
		$fetch_count = mysqli_fetch_array($sql_count);

	}

	if(isset($_POST['edit_user']) && is_numeric($_POST['edit_user'])) {

		if(isset($_POST['name']) && $_POST['name'] != '') {
			$name = mysqli_real_escape_string($db,$_POST['name']);
		}

		if(isset($_POST['email']) && $_POST['email'] != '') {
			$email = mysqli_real_escape_string($db,$_POST['email']);
		}

		if(isset($_POST['user']) && $_POST['user'] != '') {
			$user = mysqli_real_escape_string($db,$_POST['user']);
		}

		if(!isset($name) || !isset($email) || !isset($user)) {
			$error_msg = 'Complete all fields';
		} else {

			$id = mysqli_real_escape_string($db,$_POST['edit_user']);
			$sql_user = mysqli_query($db,"SELECT * FROM `users` WHERE `user` = '".$user."' AND `id` != '".$id."' LIMIT 1");
			if(mysqli_num_rows($sql_user)) {
				$error_msg = 'User already exists';
			} else {

				$sql_email = mysqli_query($db,"SELECT * FROM `users` WHERE `email` = '".$email."' AND `id` != '".$id."' LIMIT 1");
				if(mysqli_num_rows($sql_email)) {
					$error_msg = 'E-mail already exists';
				} else {

					mysqli_query($db,"UPDATE `users` SET `user` = '".$user."', `email` = '".$email."', `name` = '".$name."' WHERE `id` = '".$id."' LIMIT 1");
					$success_msg = 'User has been updated';

				}

			}

		}

	}

	if(isset($_GET['page']) && $_GET['page'] == 'photos') {

		$sql_photos = mysqli_query($db,"SELECT count(*) as 'total_photos' FROM `content` WHERE `approved` = '1'");
		$fetch_count_photos = mysqli_fetch_array($sql_photos);

		$sql_notapproved_photos = mysqli_query($db,"SELECT count(*) as 'total_notapproved' FROM `content` WHERE `approved` = '0'");
		$fetch_count_notapproved_photos = mysqli_fetch_array($sql_notapproved_photos);

	}

	if(isset($_GET['page']) && ($_GET['page'] == 'videos' || $_GET['page'] == 'music')) {

		$ntype = 0;
		if($_GET['page'] == 'videos') {
			$ntype = 2;
		} else {
			$ntype = 1;
		}

		$sql_photos = mysqli_query($db,"SELECT count(*) as 'total_photos' FROM `content` WHERE `type` = '".$ntype."' AND `approved` = '1'");
		$fetch_count_photos = mysqli_fetch_array($sql_photos);

		$sql_notapproved_photos = mysqli_query($db,"SELECT count(*) as 'total_notapproved' FROM `content` WHERE `type` = '".$ntype."' AND `approved` = '0'");
		$fetch_count_notapproved_photos = mysqli_fetch_array($sql_notapproved_photos);

	}

	if(isset($_GET['page']) && $_GET['page'] == 'language' && isset($_POST['submit'])) {

		$new_langfile = "<?php\n";
		$count_lang = 0;
		if(isset($_POST) && count($_POST)) {
			foreach($_POST as $k=>$v) {
				if($k != 'submit') {
					$v = str_replace('"','',$v);
					$v = str_replace("'","",$v);
					$count_lang++;
					$new_langfile.= "define(\"_LANG_".$k."\", \"".$v."\");\n";
				}
			}
		}
		$new_langfile.= "?>";

		if(strlen($new_langfile) && $count_lang > 100) {
			if(file_put_contents('../_language/english.php', $new_langfile)) {
				$success_msg = 'Language file has been saved.';
			}
		}

	}

	if(isset($_GET['page']) && $_GET['page'] == 'comments') {

		$sql_comments = mysqli_query($db,"SELECT count(*) as 'total_comments' FROM `comments` a INNER JOIN `users` c ON a.`user_id` = c.`id` INNER JOIN `content` b ON a.`photo_id` = b.`id` WHERE a.`approved` = '1'");
		$fetch_count_comments = mysqli_fetch_array($sql_comments);

		$sql_notapproved_comments = mysqli_query($db,"SELECT count(*) as 'total_notapproved' FROM `comments` a INNER JOIN `users` c ON a.`user_id` = c.`id` INNER JOIN `content` b ON a.`photo_id` = b.`id` WHERE a.`approved` = '0'");
		$fetch_count_notapproved_comments = mysqli_fetch_array($sql_notapproved_comments);

	}

	if(isset($_GET['page']) && $_GET['page'] == 'ads') {

		$ad_positions = array(
			'after_header'=>'After header bar',
			'before_footer'=>'Before footer',
			'photo_right'=>'Photo page after rate bar',
			'popup_ad'=>'Popup ad 300x250'
		);

		if(isset($_GET['new_ad'])) {

			if(isset($_POST['submit'])) {

				$ad_title = $ad_code = '';
				$ad_privacy = $ad_position = 0;

				if(isset($_POST['ad_title']) && $_POST['ad_title']) {
					$ad_title = mysqli_real_escape_string($db,$_POST['ad_title']);
				}

				if(isset($_POST['ad_code']) && $_POST['ad_code']) {
					$ad_code = mysqli_real_escape_string($db,$_POST['ad_code']);
				}

				if(isset($_POST['ad_privacy']) && is_numeric($_POST['ad_privacy'])) {
					$ad_privacy = mysqli_real_escape_string($db,$_POST['ad_privacy']);
				}

				if(isset($_POST['ad_position']) && $_POST['ad_position']) {
					$ad_position = mysqli_real_escape_string($db,$_POST['ad_position']);
				}

				if($ad_position != '0' && $ad_code != '') {
					mysqli_query($db,"INSERT INTO `ads` (`ad_title`,`ad_code`,`ad_privacy`,`ad_position`) VALUES ('".$ad_title."','".$ad_code."','".$ad_privacy."','".$ad_position."')") or die(mysqli_error($db));
				}

				header('location: index.php?page=ads');

			}

		} else {

			$ads = array();
			$sql = mysqli_query($db,"SELECT `id`,`ad_title`,`ad_privacy`,`ad_position` FROM `ads` ORDER BY `id` DESC LIMIT 100");
			while($fetch = mysqli_fetch_array($sql)) {
				$ads[] = $fetch;
			}

		}

	}

	if(isset($_GET['page']) && $_GET['page'] == 'categories') {

		if(isset($_POST['category']) && $_POST['category'] != '') {

			$category = trim(mysqli_real_escape_string($db,$_POST['category']));
			if($category) {

				$sql_cat = mysqli_query($db,"SELECT * FROM `categories` WHERE `name` = '".$category."' LIMIT 1");
				if(mysqli_num_rows($sql_cat)) {
					$error_msg = 'Category already exists';
				} else {
					mysqli_query($db,"INSERT INTO `categories` (`name`) VALUES ('".$category."')");
					$success_msg = 'Category added';
				}

			}

		}

		if(isset($_POST['category_edit']) && $_POST['category_edit'] != '') {

			$category = trim(mysqli_real_escape_string($db,$_POST['category_edit']));
			if($category) {

				$sql_cat = mysqli_query($db,"SELECT * FROM `categories` WHERE `name` = '".$category."' AND `id` != '".mysqli_real_escape_string($db,$_GET['edit_category'])."' LIMIT 1");
				if(mysqli_num_rows($sql_cat)) {
					$error_msg = 'Category already exists';
				} else {
					mysqli_query($db,"UPDATE `categories` SET `name` = '".$category."' WHERE `id` = '".mysqli_real_escape_string($db,$_GET['edit_category'])."' LIMIT 1");
					header('location: index.php?page=categories');
				}

			}

		}

	}
?><!DOCTYPE HTML>
<html>
<head>

	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, maximum-scale=1.0, shrink-to-fit=no">

	<link rel="stylesheet" media="all" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&family=Poppins:wght@100;900&family=Roboto:wght@300;400;700;900&family=Satisfy&display=swap">
	<link rel="stylesheet" media="all" href="css/style.css?v=<?=rand(1,999999);?>" />

	<link rel="stylesheet" media="all" href="css/richtext.min.css" />

</head>
<body>

	<div class="mobile fixed_head">
		<div class="mobile_bars"><i class="fas fa-bars"></i></div>
		<div class="mobile_logo">Contest Platform</div>
		<div class="mobile_slogan">v1.4.2</div>
	</div>

	<div class="ov_visible">
		<div class="admin_tab_1 menu">

			<div class="alt_desktop">
				<div class="desktop desktop_logo">Contest Platform</div>
				<div class="desktop_slogan">version 1.4.2</div>
			</div>

			<div class="padding_top20">

				<a href="index.php">
					<div class="menu_item <?=(!isset($_GET['page']) ? 'menu_item_selected' : '');?>">
						<div class="menu_item_icon"><i class="fas fa-users"></i></div>
						<div class="menu_item_text">Users</div>
					</div>
				</a>
				<a href="index.php?page=photos">
					<div class="menu_item <?=(isset($_GET['page']) && $_GET['page'] == 'photos' ? 'menu_item_selected' : '');?>">
						<div class="menu_item_icon"><i class="fas fa-camera"></i></div>
						<div class="menu_item_text">Photos</div>
					</div>
				</a>
				<a href="index.php?page=music">
					<div class="menu_item <?=(isset($_GET['page']) && $_GET['page'] == 'music' ? 'menu_item_selected' : '');?>">
						<div class="menu_item_icon"><i class="fas fa-music"></i></div>
						<div class="menu_item_text">Music</div>
					</div>
				</a>
				<a href="index.php?page=videos">
					<div class="menu_item <?=(isset($_GET['page']) && $_GET['page'] == 'videos' ? 'menu_item_selected' : '');?>">
						<div class="menu_item_icon"><i class="fas fa-video"></i></div>
						<div class="menu_item_text">Videos</div>
					</div>
				</a>
				<a href="index.php?page=ratings">
					<div class="menu_item <?=(isset($_GET['page']) && $_GET['page'] == 'ratings' ? 'menu_item_selected' : '');?>">
						<div class="menu_item_icon"><i class="fas fa-star"></i></div>
						<div class="menu_item_text">Ratings</div>
					</div>
				</a>
				<a href="index.php?page=categories">
					<div class="menu_item <?=(isset($_GET['page']) && $_GET['page'] == 'categories' ? 'menu_item_selected' : '');?>">
						<div class="menu_item_icon"><i class="fas fa-list"></i></div>
						<div class="menu_item_text">Categories</div>
					</div>
				</a>
				<a href="index.php?page=comments">
					<div class="menu_item <?=(isset($_GET['page']) && $_GET['page'] == 'comments' ? 'menu_item_selected' : '');?>">
						<div class="menu_item_icon"><i class="fas fa-comments"></i></div>
						<div class="menu_item_text">Comments</div>
					</div>
				</a>
				<a href="index.php?page=contests">
					<div class="menu_item <?=(isset($_GET['page']) && $_GET['page'] == 'contests' ? 'menu_item_selected' : '');?>">
						<div class="menu_item_icon"><i class="fas fa-trophy"></i></div>
						<div class="menu_item_text">Contests</div>
					</div>
				</a>
				<a href="index.php?page=meta_tags">
					<div class="menu_item <?=(isset($_GET['page']) && $_GET['page'] == 'meta_tags' ? 'menu_item_selected' : '');?>">
						<div class="menu_item_icon"><i class="fas fa-tags"></i></div>
						<div class="menu_item_text">Meta tags</div>
					</div>
				</a>
				<a href="index.php?page=ads">
					<div class="menu_item <?=(isset($_GET['page']) && $_GET['page'] == 'ads' ? 'menu_item_selected' : '');?>">
						<div class="menu_item_icon"><i class="fas fa-ad"></i></div>
						<div class="menu_item_text">Ads</div>
					</div>
				</a>
				<a href="index.php?page=pages">
					<div class="menu_item <?=(isset($_GET['page']) && $_GET['page'] == 'pages' ? 'menu_item_selected' : '');?>">
						<div class="menu_item_icon"><i class="fas fa-table"></i></div>
						<div class="menu_item_text">Pages</div>
					</div>
				</a>
				<a href="index.php?page=language">
					<div class="menu_item <?=(isset($_GET['page']) && $_GET['page'] == 'language' ? 'menu_item_selected' : '');?>">
						<div class="menu_item_icon"><i class="fas fa-font"></i></div>
						<div class="menu_item_text">Language</div>
					</div>
				</a>
				<a href="index.php?page=settings">
					<div class="menu_item <?=(isset($_GET['page']) && $_GET['page'] == 'settings' ? 'menu_item_selected' : '');?>">
						<div class="menu_item_icon"><i class="fas fa-cog"></i></div>
						<div class="menu_item_text">Settings</div>
					</div>
				</a>
				<a href="index.php?page=logout">
					<div class="menu_item <?=(isset($_GET['page']) && $_GET['page'] == 'logout' ? 'menu_item_selected' : '');?>">
						<div class="menu_item_icon"><i class="fas fa-power-off"></i></div>
						<div class="menu_item_text">Logout</div>
					</div>
				</a>

			</div>

		</div>
		<div class="admin_tab_2">

			<?php
			if(!isset($_GET['page'])) {
				include('modules/users.php');
			}
			if(isset($_GET['page']) && $_GET['page'] == 'photos') {
				include('modules/photos.php');
			}
			if(isset($_GET['page']) && ($_GET['page'] == 'videos' || $_GET['page'] == 'music')) {
				include('modules/content.php');
			}
			if(isset($_GET['page']) && $_GET['page'] == 'ratings') {
				include('modules/ratings.php');
			}
			if(isset($_GET['page']) && $_GET['page'] == 'contests') {
				include('modules/contests.php');
			}
			if(isset($_GET['page']) && $_GET['page'] == 'language') {
				include('modules/language.php');
			}
			if(isset($_GET['page']) && $_GET['page'] == 'ads') {
				include('modules/ads.php');
			}
			if(isset($_GET['page']) && $_GET['page'] == 'pages') {
				include('modules/pages.php');
			}
			if(isset($_GET['page']) && $_GET['page'] == 'contest') {
				include('modules/contest.php');
			}
			if(isset($_GET['page']) && $_GET['page'] == 'meta_tags') {
				include('modules/meta_tags.php');
			}
			if(isset($_GET['page']) && $_GET['page'] == 'comments') {
				include('modules/comments.php');
			}
			if(isset($_GET['page']) && $_GET['page'] == 'categories') {
				include('modules/categories.php');
			}
			if(isset($_GET['page']) && $_GET['page'] == 'settings') {
				include('modules/settings.php');
			}
			?>

		</div>
	</div>

	<script type="text/javascript" src="../_js/all.min.js"></script>
	<script type="text/javascript" src="../_js/jquery-3.5.0.min.js"></script>
	<?php if(isset($_GET['page']) && $_GET['page'] == 'pages' && isset($_GET['new_page'])) { ?>
	<script type="text/javascript" src="js/jquery.richtext.min.js"></script>
	<?php } ?>

	<script type="text/javascript">
	(function($){

	"use strict";

	var is_loading = 0;

	<?php if(isset($_GET['page']) && $_GET['page'] == 'pages' && isset($_GET['new_page'])) { ?>

	$('textarea[name="pages_content"]').richText({

		bold: true,
  		italic: true,
  		underline: true,
 		leftAlign: true,
  		centerAlign: true,
  		rightAlign: true,
  		justify: true,
  		ol: true,
  		ul: true,
  		heading: true,
  		fonts: false,
  		fontColor: true,
  		fontSize: true,
  		imageUpload: false,
  		fileUpload: false,
 		videoEmbed: false,
  		urls: true,
		table: false,
		removeStyles: false,
  		code: true,
		youtubeCookies: false,
		useSingleQuotes: false,
  		height: 0,
  		heightPercentage: 0,
  		id: "",
  		class: "",
  		useParagraph: false,
  		maxlength: 0,
  		callback: undefined

	});
	<?php } ?>

	function onImagesLoaded(container, event) {

		var images = container.getElementsByTagName("img");
    		var loaded = images.length;
    		for (var i = 0; i < images.length; i++) {
        		if (images[i].complete) {
            			loaded--;
        		} else {
            			images[i].addEventListener("load", function() {
                			loaded--;
                			if (loaded == 0) {
                    				event();
                			}
            			});
            			images[i].addEventListener("error", function() {
                			loaded--;
                			if (loaded == 0) {
                    				event();
                			}
            			});
        		}
        		if (loaded == 0) {
            			event();
				fix_footer();
        		}
    		}

	}

	$(document).on('click', '.fixed_head', function() {

		if($('.menu').is(':visible') && $('.menu').css('left') == '0px') {
			$('.admin_tab_1').stop().css('left','-250px');
			$('.fixed_head').stop().css('left','0px');
			$('.admin_tab_2').stop().css('margin-left','0');
			$('body').stop().css('overflow','auto');
		} else {
			$('.admin_tab_2').stop().css('margin-left','250px');
			$('body').stop().css('overflow-x','hidden');
			$('.admin_tab_1').stop().show().css('position','fixed').css('top','0').css('left','0').css('width','250px').css('height','100%');
			$('.fixed_head').stop().css('left','250px');
		}

	});

	$(document).on('click', '.rallr_but', function() {

		var id = $(this).data('id');

		if(!$(this).hasClass('rallr_but_sel')) {

			$('.rallr_but_sel').stop().removeClass('rallr_but_sel');
			$(this).stop().addClass('rallr_but_sel');

			$('.box_selection').stop().hide();
			$('.box_selection_'+id).stop().show();

		}

	});

	function load_users() {

		$('.cloading').stop().show();

		var page_nr = $('.users_results').data('page');
		$('.users_results').stop().data('page',parseInt(page_nr)+1);

		$.post('request.php', { reason: 'users', page_nr: page_nr }, function(get) {

			$('.cloading').stop().hide();
			is_loading = 0;

			if(get['users'].length) {

				var i=0;
				for(i=0;i<=get['users'].length-1;i++) {

					var option_list = ''+
					'<div class="relative">'+
						'<div class="open_pop_menu" data-id="'+get['users'][i].id+'"><i class="fas fa-chevron-down"></i></div>'+
						'<div class="pop_menu" data-id="'+get['users'][i].id+'">'+
							'<div class="pop_menu_item edit_user overflow" data-id="'+get['users'][i].id+'">'+
								'<div class="pop_menu_item_icon"><i class="fas fa-pencil-alt"></i></div>'+
								'<div class="pop_menu_item_text">Edit user</div>'+
							'</div>'+
							'<div class="pop_menu_item border_bottom0 remove_user overflow" data-id="'+get['users'][i].id+'">'+
								'<div class="pop_menu_item_icon red">&#10005;&nbsp;</div>'+
								'<div class="pop_menu_item_text">Remove user</div>'+
							'</div>'+
						'</div>'+
					'</div>';

					var result = ''+
					'<div class="users_dash user user_'+get['users'][i].id+'">'+
						'<div class="users_dash_col center users_dash_col_1 users_dash_col_1_grand"><img src="'+get['users'][i].picture+'" /></div>'+
						'<div class="users_dash_col users_dash_col_2 users_dash_extra_1">'+
							'<span class="bold col_2_dh">'+get['users'][i].name+'</span><br>'+
							'<span><a target="_blank" href="../'+get['users'][i].user+'" class="text_dec_none_black col_2_dg">@'+get['users'][i].user+'</a></span>'+
						'</div>'+
						'<div class="users_dash_col users_dash_col_3 users_dash_extra_2">'+get['users'][i].email+'</div>'+
						'<div class="users_dash_col users_dash_col_4 users_dash_extra_2">'+get['users'][i].regdate+'</div>'+
						'<div class="users_dash_col center users_dash_col_5 users_dash_extra_3">'+option_list+'</div>'+
					'</div>';

					$('.users_results').append(result);

				}

			} else {
				$('.users_results').stop().data('stop', 1);
				if(!$('.user')[0]) {
					$('.no_results').stop().show();
				}
			}

		},'json');

	}

	$(document).on('click', '.rallr_sg', function() {

		$('.pop_ncat').stop().show();
		$('.pop_ncat').stop().data('id','0');

		$('.pop_ncat_head').stop().text('New ad');

		$('#ad_title, #ad_code').stop().val('');
		$('#ad_position').stop().val('after_header');
		$('#ad_privacy').stop().val(1);

	});

	$(document).on('click', '.edit_user', function() {

		$('.open_pop_menu_sel').each(function() {
			$(this).stop().removeClass('open_pop_menu_sel').html('<i class="fas fa-chevron-down"></i>');
		});

		$('.pop_menu').each(function() {
			$(this).stop().hide();
		});

		var id = $(this).data('id');
		$('.pop_ncat').stop().data('id',id);

		$.post('request.php', { reason: 'get_edit_user', id: id }, function(get) {
			$('#euser_email').stop().val(get.email);
			$('#euser_name').stop().val(get.name);
			$('#euser_user').stop().val(get.user);
			$('.pop_ncat').stop().show();
		},'json');

	});

	$(document).on('click', '.edit_fad', function() {

		$('.pop_ncat_head').stop().text('Edit ad');

		$('.open_pop_menu_sel').each(function() {
			$(this).stop().removeClass('open_pop_menu_sel').html('<i class="fas fa-chevron-down"></i>');
		});

		$('.pop_menu').each(function() {
			$(this).stop().hide();
		});

		var id = $(this).data('id');
		$('.pop_ncat').stop().data('id',id);

		$.post('request.php', { reason: 'get_fad', id: id }, function(get) {
			$('#ad_title').stop().val(get.ad_title);
			$('#ad_code').stop().val(get.ad_code);
			$('#ad_privacy').stop().val(get.ad_privacy);
			$('#ad_position').stop().val(get.ad_position);
			$('.pop_ncat').stop().show();
		},'json');

	});

	$(document).on('click', '.pop_ncat_op_submit_su', function() {

		var email = $('#euser_email').val();
		var name = $('#euser_name').val();
		var user = $('#euser_user').val();

		var id = $('.pop_ncat').data('id');

		$('.pop_ncat_op_submit_su').stop().html('<i class="fas fa-spinner fa-spin"></i>');

		$.post('request.php', { reason: 'update_user', user_id: id, email: email, name: name, user: user }, function(get) {
			window.location.reload();
		});

	});

	$(document).on('click', '.pop_ncat_op_submit_sg', function() {

		var ad_title = $('#ad_title').val();
		var ad_code = $('#ad_code').val();
		var ad_position = $('#ad_position').val();
		var ad_privacy = $('#ad_privacy').val();

		var id = $('.pop_ncat').data('id');
		if(id == '0') { id = ''; }

		$('.pop_ncat_op_submit_sg').stop().html('<i class="fas fa-spinner fa-spin"></i>');

		$.post('request.php', { reason: 'update_fad', fad_id: id, ad_title: ad_title, ad_code: ad_code, ad_position: ad_position, ad_privacy: ad_privacy }, function(get) {
			window.location.reload();
		});

	});

	function load_contests() {

		$('.cloading').stop().show();

		$.post('request.php', { reason: 'contests' }, function(get) {

			$('.cloading').stop().hide();

			if(get['contests'].length) {

				var i=0;
				for(i=0;i<=get['contests'].length-1;i++) {

					if(get['contests'][i].active == '1') {
						var active_contest = '<div class="rating_type" style="background:#00cc00;color:#fff;padding:5px;display:inline-block;">Active</div>';
					} else {
						var active_contest = '<div class="rating_type" style="background:#ff0000;color:#fff;padding:5px;display:inline-block;">Inactive</div>';
					}

					var contest_type 		= '<div class="users_dash_col ratings_dash_col_3 center rating_type ' + get['contests'][i].contest_type + '">'+get['contests'][i].contest_type.replace("_", " ")+'</div><div class="clearfix"></div>';

					var option_list = ''+
					'<div class="relative">'+
						'<div class="open_pop_menu" data-id="'+get['contests'][i].id+'"><i class="fas fa-cog"></i></div>'+
						'<div class="pop_menu" data-id="'+get['contests'][i].id+'">'+
						'</div>'+
					'</div>';

					var result = ''+
					'<div class="contest_dash contest contest_'+get['contests'][i].id+' overflow">'+
						'<div class="contests_box_p1">'+
							'<div style="font-size:19px;font-weight:600;color:#222;">'+get['contests'][i].title+'</div>'+
							'<div style="font-size:14px;color:#565858;">'+get['contests'][i].description+'</div>'+
							'<div style="margin-top:20px;overflow:hidden;">'+
								'<div style="background:#f7f7f7;padding:10px;float:left;"><span style="font-weight:600;">'+get['contests'][i].joined+'</span> joined</div>'+
								'<div style="background:#f7f7f7;padding:10px;float:left;margin-left:15px;">Ends in '+get['contests'][i].countdown+'</div>'+
							'</div>'+
						'</div>'+
						'<div class="contests_box_p2">'+
							'<div style="font-size:14px;">'+active_contest+contest_type+'</div>'+
							'<div style="margin-top:15px;">'+
								'<div class="edit_contest" style="cursor:pointer;padding-top:10px;padding-bottom:10px;font-size:14px;" data-id="'+get['contests'][i].id+'"><i class="fas fa-pencil-alt"></i>&nbsp;&nbsp;Edit contest</div>'+
								'<div class="remove_contest" style="cursor:pointer;padding-top:10px;padding-bottom:10px;font-size:14px;color:#ff0000;border-top:1px solid #e1e1e1;" data-id="'+get['contests'][i].id+'">&#10005;&nbsp; Remove contest</div>'+

						'</div>'+
					'</div>';

					$('.contests_results').append(result);

				}

			} else {
				$('.contests_results').stop().data('stop', 1);
				if(!$('.contest')[0]) {
					$('.no_results').stop().show();
				}
			}

		},'json');

	}

	$(document).on('click', '.edit_page', function() {

		var id = $(this).data('id');
		window.location = 'index.php?page=pages&new_page=1&npid='+id;

	});

	$(document).on('click', '.delete_logo_click', function() {

		$.post('request.php', { reason: 'delete_logo' }, function(get) {
			window.location = 'index.php?page=settings&customization=1';
		});

	});

	function load_pages() {

		$('.cloading').stop().show();

		$.post('request.php', { reason: 'pages' }, function(get) {

			$('.cloading').stop().hide();

			if(get['pages'].length) {

				var i=0;
				for(i=0;i<=get['pages'].length-1;i++) {

					if(get['pages'][i].footer == '1') {
						var visible_f = '<span style="color:#00cc00;">Yes</span>';
					} else {
						var visible_f = '<span style="color:#ff0000;">No</span>';
					}

					var option_list = ''+
					'<div class="relative">'+
						'<div class="open_pop_menu" data-id="'+get['pages'][i].id+'"><i class="fas fa-chevron-down"></i></div>'+
						'<div class="pop_menu" data-id="'+get['pages'][i].id+'">'+
							'<a href="../index.php?extra_page='+get['pages'][i].id+'" target="_blank">'+
								'<div class="pop_menu_item">'+
									'<div class="pop_menu_item_icon"><i class="fas fa-eye"></i></div>'+
									'<div class="pop_menu_item_text">View page</div>'+
								'</div>'+
							'</a>'+
							'<div class="pop_menu_item edit_page" data-id="'+get['pages'][i].id+'">'+
								'<div class="pop_menu_item_icon"><i class="fas fa-pencil-alt"></i></div>'+
								'<div class="pop_menu_item_text">Edit page</div>'+
							'</div>'+
							'<div class="pop_menu_item border_bottom0 remove_page" data-id="'+get['pages'][i].id+'">'+
								'<div class="pop_menu_item_icon red">&#10005;&nbsp;</div>'+
								'<div class="pop_menu_item_text">Remove</div>'+
							'</div>'+
						'</div>'+
					'</div>';


					var result = ''+
					'<div class="users_dash page page_'+get['pages'][i].id+'">'+
						'<div class="users_dash_col pages_dash_col_1">'+get['pages'][i].title+'</div>'+
						'<div class="users_dash_col pages_dash_col_2 center">'+visible_f+'</div>'+
						'<div class="users_dash_col pages_dash_col_3 center">'+option_list+'</div>'+
					'</div>';

					$('.pages_results').append(result);

				}

			} else {
				$('.pages_results').stop().data('stop', 1);
				if(!$('.page')[0]) {
					$('.no_results').stop().show();
				}
			}

		},'json');

	}

	$(document).on('click', '.remove_contest', function() {

		if(confirm('Are you sure?')) {

			var id = $(this).data('id');
			$.post('request.php', { reason: 'remove_contest', id: id }, function(get) {
				$('.contest_'+id).remove();
			},'json');

		}

	});

	$(document).on('click', '.remove_page', function() {

		if(confirm('Are you sure?')) {

			var id = $(this).data('id');
			$.post('request.php', { reason: 'remove_page', id: id }, function(get) {
				$('.page_'+id).remove();
			},'json');

		}

	});

	$(document).on('click', '.edit_contest', function() {

		var id = $(this).data('id');
		window.location = 'index.php?page=contest&cid='+id;

	});

	$(document).on('click', '.rallr', function() {

		if(confirm('Are you sure? This will reset all ratings/votes from all photos')) {

			$.post('request.php', { reason: 'rallr' }, function() {
				window.location.reload();
			});

		}

	});

	$(window).scroll(function() {
    		if($(window).scrollTop() > (parseInt($(document).height() - 450) - $(window).height())) {
           		if(is_loading == '0') {

				is_loading = 1;
				<?php if(isset($_GET['page']) && $_GET['page'] == 'photos') { ?>
				if($('.photos_results').data('stop') == '0') {
					load_photos(<?=(isset($_GET['approval']) ? 1 : 0);?>);
				}
				<?php } ?>

				<?php if(isset($_GET['page']) && ($_GET['page'] == 'videos' || $_GET['page'] == 'music')) { ?>
				if($('.photos_results').data('stop') == '0') {
					load_content(<?=(isset($_GET['approval']) ? 1 : 0);?>,'<?=$_GET['page'];?>');
				}
				<?php } ?>

				<?php if(!isset($_GET['page'])) { ?>
				if($('.users_results').data('stop') == '0') {
					load_users();
				}
				<?php } ?>

				<?php if(isset($_GET['page']) && $_GET['page'] == 'ratings') { ?>
				if($('.ratings_results').data('stop') == '0') {
					load_ratings();
				}
				<?php } ?>

				<?php if(isset($_GET['page']) && $_GET['page'] == 'comments') { ?>
				if($('.comments_results').data('stop') == '0') {
					load_comments(<?=(isset($_GET['approval']) ? '1':'0');?>);
				}
				<?php } ?>
			}
		}
	});

	$(document).on('click', '.close_pop', function() {

		$('.big_preview_embed').stop().html('');
		$('.big_preview').stop().hide();

	});

	$(document).on('click', '.click_load_data', function() {

		$('.big_preview_embed').stop().html('');
		$('.big_preview').stop().show();

		var type = $(this).data('type');
		var lo = $(this).data('lo');

		if(type == '1') {
			$('.big_preview_embed').stop().html('<audio controls style="width:100%;margin-top:15px;text-align:center;"><source src="../_uploads/_music/'+lo+'.mp3" type="audio/mpeg"></audio>');
		}

		if(type == '2') {
			$('.big_preview_embed').stop().html('<video controls style="width:100%;height:246px;text-align:center;"><source src="../_uploads/_videos/'+lo+'.mp4" type="video/mp4"></video>');
		}

	});

	function load_photos(type) {

		$('.cloading').stop().show();

		var page_nr = $('.photos_results').data('page');
		$('.photos_results').stop().data('page',parseInt(page_nr)+1);

		$.post('request.php', { reason: 'photos', page_nr: page_nr, type: type }, function(get) {

			if(get['photos'].length) {

				var i=0;
				for(i=0;i<=get['photos'].length-1;i++) {

					if(get['photos'][i].approved == '0') {
						var appr = '<div class="photo_approve">Approve</div>';
					} else {
						var appr = '';
					}

					var photo_option = ''+
					'<div class="photo_option" data-id="'+get['photos'][i].id+'">'+
						'<div class="photo_remove"><i class="fas fa-trash"></i></div>'+
						appr+
					'</div>';

					var photo = ''+
					'<div class="photo photo_'+get['photos'][i].id+' hide">'+
						photo_option+
						'<img src="../_uploads/_photos/'+get['photos'][i].photo+'_400.jpg" />'+
					'</div>';

					$('.photos_results').append(photo);

					var container = document.getElementById('photos_results');

					onImagesLoaded(container, function() {

						$('.cloading').stop().hide();
						is_loading = 0;

						$('.photo').each(function() {
							$(this).stop().show();
						});

					});

				}

			} else {

				$('.cloading').stop().hide();
				is_loading = 0;

				$('.photos_results').data('stop', 1);
				if(!$('.photo_option')[0]) {
					$('.no_results').stop().show();
				}
			}

			is_loading = 0;

		},'json');

	}

	function load_categories() {

		$('.cloading').stop().show();

		$.post('request.php', { reason: 'categories' }, function(get) {

			$('.cloading').stop().hide();

			if(get['categories'].length) {

				var i=0;
				for(i=0;i<=get['categories'].length-1;i++) {

					var category = ''+
					'<div class="categories_dash category_'+get['categories'][i].id+'">'+
						'<div class="categories_dash_1 zcatn_'+get['categories'][i].id+'">'+get['categories'][i].name+'</div>'+
						'<div class="categories_dash_2">'+get['categories'][i].count+' posts</div>'+
						'<div class="categories_dash_3" data-id="'+get['categories'][i].id+'">'+
							'<div class="category_edit"><i class="fas fa-pencil-alt"></i> Edit</div>'+
							'<div class="category_remove">&#10005;</div>'+
						'</div>'+
					'</div>';

					$('.categories_results').append(category);

				}

			} else {
				$('.no_results').stop().show();
			}

		}, 'json');

	}

	$(document).on('click', '.rallr_s', function() {

		$('.pop_ncat').stop().data('id','0');
		$('#ncat_i').val('');
		$('.pop_ncat_head').stop().text('New category');
		$('.pop_ncat').stop().show();

	});

	$(document).on('click', '.pop_ncat_op_cancel', function() {

		$('.pop_ncat').stop().hide();

	});

	$(document).on('click', '.category_edit', function() {

		var id = $(this).parent().data('id');

		$('.pop_ncat').stop().data('id',id);
		$('.pop_ncat_head').stop().text('Edit category');
		$('#ncat_i').val($('.zcatn_'+id).text());
		$('.pop_ncat').stop().show();

	});

	$(document).on('click', '.pop_ncat_op_submit', function() {

		var id = $('.pop_ncat').data('id');
		var ncat = $('#ncat_i').val();

		$.post('request.php', { reason: 'add_ncat', id: id, ncat: ncat }, function(get) {

			window.location.reload();

		},'json');

	});

	function load_content(type,stype) {

		var page_nr = $('.photos_results').data('page');
		$('.photos_results').stop().data('page',parseInt(page_nr)+1);

		$.post('request.php', { reason: stype, page_nr: page_nr, type: type }, function(get) {

			if(get['content'].length) {

				var i=0;
				for(i=0;i<=get['content'].length-1;i++) {

					if(get['content'][i].approved == '0') {
						var appr = '<div class="photo_approve">Approve</div>';
					} else {
						var appr = '';
					}

					if(get['content'][i].type == '1') {
						if(get['content'][i].cover.length > 5) {
							var thumb_picture = '../_uploads/_content_cover/'+get['content'][i].cover+'_400.jpg';
						} else {
							var thumb_picture = '../_img/no_thumb_music.jpg';
						}
					}

					if(get['content'][i].type == '2') {
						if(get['content'][i].cover.length > 5) {
							var thumb_picture = '../_uploads/_content_cover/'+get['content'][i].cover+'_400.jpg';
						} else {
							var thumb_picture = '../_img/no_thumb_video.jpg';
						}
					}

					var photo_option = ''+
					'<div class="photo_option" data-id="'+get['content'][i].id+'">'+
						'<div class="photo_remove"><i class="fas fa-trash"></i></div>'+
						appr+
					'</div>';

					var photo = ''+
					'<div class="photo photo_'+get['content'][i].id+'">'+
						photo_option+
						'<img src="'+thumb_picture+'" class="click_load_data" data-lo="'+get['content'][i].content+'" data-type="'+get['content'][i].type+'" />'+
					'</div>';

					$('.photos_results').append(photo);

				}

			} else {
				$('.photos_results').data('stop', 1);
				if(!$('.photo_option')[0]) {
					$('.no_results').stop().show();
				}
			}

			is_loading = 0;

		},'json');

	}

	function load_ratings() {

		$('.cloading').stop().show();

		var page_nr = $('.ratings_results').data('page');
		$('.ratings_results').stop().data('page',parseInt(page_nr)+1);

		$.post('request.php', { reason: 'ratings', page_nr: page_nr }, function(get) {

			$('.cloading').stop().hide();
			is_loading = 0;

			if(get['ratings'].length) {

				var i=0;
				for(i=0;i<=get['ratings'].length-1;i++) {

					var who_rated = '<a target="_blank" href="../'+get['ratings'][i].user+'" class="text_dec_none">@'+get['ratings'][i].user+'</a>';

					if(get['ratings'][i].user == 'Guest') {
						var who_rated = 'Guest';
					}

					if(get['ratings'][i].type == '0') {
						var ntype = '<i class="fas fa-camera"></i>';
						var thumb_picture = '../_uploads/_photos/'+get['ratings'][i].photo+'_400.jpg';
					}

					if(get['ratings'][i].type == '1') {
						var ntype = '<i class="fas fa-music"></i>';
						if(get['ratings'][i].cover.length > 5) {
							var thumb_picture = '../_uploads/_content_cover/'+get['ratings'][i].cover+'_400.jpg';
						} else {
							var thumb_picture = '../_img/no_thumb_music.jpg';
						}
					}

					if(get['ratings'][i].type == '2') {
						var ntype = '<i class="fas fa-video"></i>';
						if(get['ratings'][i].cover.length > 5) {
							var thumb_picture = '../_uploads/_content_cover/'+get['ratings'][i].cover+'_400.jpg';
						} else {
							var thumb_picture = '../_img/no_thumb_video.jpg';
						}
					}

					ntype = '<a href="../photo-'+get['ratings'][i].id+'" style="text-decoration:none;font-size:15px;color:#777;" target="_blank">'+ntype+'</a>';

					// TNSB_EDIT_FOR_CUSTOMIZATION_STARTS_HERE
					var rating = ''+
					'<div class="users_dash ratings_dash rat rating_'+get['ratings'][i].id+'">'+
						'<div class="users_dash_col center ratings_dash_col_1">'+ntype+'</div>'+
						'<div class="users_dash_col ratings_dash_col_2">'+who_rated+'</div>'+
						'<div class="users_dash_col ratings_dash_col_3 center rating_type ' + get['ratings'][i].rating_type + '">'+get['ratings'][i].rating_type.replace("_", " ")+'</div>'+
						'<div class="users_dash_col ratings_dash_col_4">'+get['ratings'][i].rate+'</div>'+
						'<div class="users_dash_col ratings_dash_col_5">'+get['ratings'][i].ip+'</div>'+
						'<div class="users_dash_col ratings_dash_col_6">'+get['ratings'][i].date+'</div>'+
						'<div class="users_dash_col center ratings_dash_col_7 remove_rating red2" data-id="'+get['ratings'][i].id+'">&#10005;&nbsp;</div>'+
					'</div>';
					// TNSB_EDIT_FOR_CUSTOMIZATION_ENDS_HERE

					$('.ratings_results').append(rating);

				}

			} else {
				$('.ratings_results').stop().data('stop', 1);
				if(!$('.rat')[0]) {
					$('.no_results').stop().show();
				}
			}

		},'json');

	}

	function load_comments(type) {

		$('.cloading').stop().show();

		var page_nr = $('.comments_results').data('page');
		$('.comments_results').stop().data('page',parseInt(page_nr)+1);

		$.post('request.php', { reason: 'comments', page_nr: page_nr, type: type }, function(get) {

			is_loading = 0;
			$('.cloading').stop().hide();

			if(get['comments'].length) {

				var i=0;
				for(i=0;i<=get['comments'].length-1;i++) {

					if(get['comments'][i].type == '0') {
						var thumb_picture = '<i class="fas fa-camera"></i>';
					}

					if(get['comments'][i].type == '1') {
						var thumb_picture = '<i class="fas fa-music"></i>';
					}

					if(get['comments'][i].type == '2') {
						var thumb_picture = '<i class="fas fa-video"></i>';
					}

					var option_list = ''+
					'<div class="relative">'+
						'<div class="open_pop_menu" data-id="'+get['comments'][i].id+'"><i class="fas fa-chevron-down"></i></div>'+
						'<div class="pop_menu" data-id="'+get['comments'][i].id+'">'+
							'<div class="pop_menu_item comment_approve '+(get['comments'][i].approved == '1' ? 'hide':'')+'" data-id="'+get['comments'][i].id+'">'+
								'<div class="pop_menu_item_icon"><i class="fas fa-check"></i></div>'+
								'<div class="pop_menu_item_text">Approve</div>'+
							'</div>'+
							'<div class="pop_menu_item border_bottom0 remove_comment" data-id="'+get['comments'][i].id+'">'+
								'<div class="pop_menu_item_icon red">&#10005;&nbsp;</div>'+
								'<div class="pop_menu_item_text">Remove</div>'+
							'</div>'+
						'</div>'+
					'</div>';

					var comment = ''+
					'<div class="users_dash com comment_'+get['comments'][i].id+'">'+
						'<div class="users_dash_col center comments_dash_col_1">'+
							'<a target="_blank" href="../photo-'+get['comments'][i].photo_id+'" class="text_dec_none" style="color:#777;">'+thumb_picture+'</a>'+
						'</div>'+
						'<div class="users_dash_col comments_dash_col_2">'+
							'<div><a target="_blank" href="../'+get['comments'][i].user+'" class="text_dec_none" style="font-size:15px;font-weight:600;color:#565858;">'+get['comments'][i].name+'</a></div>'+
							'<div style="font-size:13px;">'+get['comments'][i].comment+'</div>'+
						'</div>'+
						'<div class="users_dash_col comments_dash_col_3">'+get['comments'][i].date+'</div>'+
						'<div class="users_dash_col center comments_dash_col_4" data-id="'+get['comments'][i].id+'">'+option_list+'</div>'+
					'</div>';

					$('.comments_results').append(comment);

				}

			} else {
				$('.comments_results').stop().data('stop', 1);
				if(!$('.com')[0]) {
					$('.no_results').stop().show();
				}
			}

		},'json');

	}

	$(document).on('click', '.remove_fad', function() {

		var id = $(this).data('id');
		$.post('request.php', { reason: 'remove_fad', id: id });
		$('.fad_'+id).remove();

	});

	$(document).on('click', '.category_remove', function() {

		if(confirm('Remove category?')) {

			var id = $(this).parent().data('id');
			$.post('request.php', { reason: 'remove_category', id: id });

			$('.category_'+id).stop().remove();

		}

	});

	$(document).on('click', '.photo_remove', function() {

		if(confirm('Remove photo?')) {

			var id = $(this).parent().data('id');

			$.post('request.php', { reason: 'remove_photo', id: id });
			$('.photo_'+id).stop().remove();

			<?php if(isset($_GET['approval'])) { ?>
			var count_tab = 2;
			<?php } else { ?>
			var count_tab = 1;
			<?php } ?>

			var count = parseInt($('#count_photos_'+count_tab).text());
			--count;
			$('#count_photos_'+count_tab).text(count);


		}

	});

	$(document).on('click', '.photo_approve', function() {

		var id = $(this).parent().data('id');

		$.post('request.php', { reason: 'approve_photo', id: id });
		$('.photo_'+id).stop().remove();

		var count2 = parseInt($('#count_photos_2').text());
		--count2;
		$('#count_photos_2').text(count2);

		var count1 = parseInt($('#count_photos_1').text());
		++count1;
		$('#count_photos_1').text(count1);

	});

	$(document).on('click', '.comment_approve', function() {

		var id = $(this).parent().data('id');

		$.post('request.php', { reason: 'approve_comment', id: id });
		$('.comment_'+id).stop().remove();

		var count2 = parseInt($('#count_photos_2').text());
		--count2;
		$('#count_photos_2').text(count2);

		var count1 = parseInt($('#count_photos_1').text());
		++count1;
		$('#count_photos_1').text(count1);

	});

	$(document).on('click', '.remove_rating', function() {

		if(confirm('Remove rating?')) {

			var id = $(this).data('id');
			$.post('request.php', { reason: 'remove_rating', id: id });

			$('.rating_'+id).stop().hide();

		}

	});

	$(document).on('click', '.remove_comment', function() {

		if(confirm('Remove comment?')) {

			var id = $(this).data('id');
			$.post('request.php', { reason: 'remove_comment', id: id });

			$('.comment_'+id).stop().hide();

			<?php if(isset($_GET['approval'])) { ?>
			var count_tab = 2;
			<?php } else { ?>
			var count_tab = 1;
			<?php } ?>

			var count = parseInt($('#count_photos_'+count_tab).text());
			--count;
			$('#count_photos_'+count_tab).text(count);

		}

	});

	$(document).ready(function() {

		<?php if(isset($_GET['page']) && $_GET['page'] == 'contests') { ?>
		load_contests();
		<?php } ?>

		<?php if(isset($_GET['page']) && $_GET['page'] == 'pages' && !isset($_GET['new_page'])) { ?>
		load_pages();
		<?php } ?>

		<?php if(!isset($_GET['page']) && !isset($_GET['edit_user'])) { ?>
		load_users();
		<?php } ?>

		<?php if(isset($_GET['page']) && $_GET['page'] == 'photos') { ?>
		load_photos(<?=(isset($_GET['approval']) ? '1':'0');?>);
		<?php } ?>

		<?php if(isset($_GET['page']) && ($_GET['page'] == 'videos' || $_GET['page'] == 'music')) { ?>
		load_content(<?=(isset($_GET['approval']) ? '1':'0');?>,'<?=$_GET['page'];?>');
		<?php } ?>

		<?php if(isset($_GET['page']) && $_GET['page'] == 'ratings') { ?>
		load_ratings();
		<?php } ?>

		<?php if(isset($_GET['page']) && $_GET['page'] == 'comments') { ?>
		load_comments(<?=(isset($_GET['approval']) ? '1':'0');?>);
		<?php } ?>

		<?php if(isset($_GET['page']) && $_GET['page'] == 'categories') { ?>
		load_categories();
		<?php } ?>

	});

	$(document).on('click', '.remove_user', function() {

		if(confirm('Remove user?')) {

			var id = $(this).data('id');
			$.post('request.php', { reason: 'remove_user', id: id });

			$('.user_'+id).stop().remove();

		}

	});

	$(document).on('click', '.open_pop_menu', function() {

		$('.open_pop_menu_sel').each(function() {
			$(this).stop().removeClass('open_pop_menu_sel').html('<i class="fas fa-chevron-down"></i>');
		});

		var id = $(this).data('id');
		$('.pop_menu').each(function() {
			if($(this).data('id') != id) {
				$(this).stop().hide();
			}
		});

		if($('.pop_menu[data-id="'+id+'"]').is(':visible')) {
			$('.pop_menu[data-id="'+id+'"]').stop().hide();
		} else {
			$('.pop_menu[data-id="'+id+'"]').stop().show();
			$('.open_pop_menu[data-id="'+id+'"]').stop().addClass('open_pop_menu_sel').html('<i class="fas fa-chevron-up"></i>');
		}

	});

	})(jQuery);
	</script>

</body>
</html>
