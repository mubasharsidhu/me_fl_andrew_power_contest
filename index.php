<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	session_start();

	if(!file_exists('_core/_config.php')) {
		header('location: installer.php');
		die();
	}

	require_once('_core/_functions.php');

	$settings = get_settings();
	$multi_contest = get_multi_contest();
	$user_logged = get_user_logged();

	if(current_page() == 'contest') {
		$contest_id = mysqli_real_escape_string($db,$_GET['contest']);
		$site_contest = get_contest($contest_id);
	} else {
		if($multi_contest == '1') {
			$site_contest = get_contest();
		}
	}

	require_once('_core/load.php');
?><!DOCTYPE HTML>
<html lang="en">
<head>

	<?php
	require_once('head_meta.php');
	require_once('head_scripts.php');
	?>

</head>
<body <?=($preloader_show == '1' ? 'class="overflow"':'');?>>

	<?php if($preloader_show == '1') { ?>
	<div class="preload">

		<div class="preload_box">

			<div class="logo logo_preload a">
				<?php if(isset($settings['site_logo_image']) && $settings['site_logo_image'] != '') { ?>
					<img src="<?=$site_url;?>_img/logo.png" class="image_logo_footer" border="0" alt="<?=(isset($settings['site_logo']) ? $settings['site_logo'] : 'Hello');?>" />
				<?php } else { ?>
					<?=(isset($settings['site_logo']) ? $settings['site_logo'] : 'Hello');?>
				<?php } ?>
			</div>

			<div class="ratings_lol">

				<?php for($i=1;$i<=5;$i++) { ?>
				<div class="star_pr_<?=$i;?>">
					<svg onclick="click_star(<?=$i;?>);" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" class="star<?=$i;?> svg84" viewBox="0 0 20 20" data-inline="false">
						<path d="M10 1L7 7l-6 .75l4.13 4.62L4 19l6-3l6 3l-1.12-6.63L19 7.75L13 7zm0 2.24l2.34 4.69l4.65.58l-3.18 3.56l.87 5.15L10 14.88l-4.68 2.34l.87-5.15l-3.18-3.56l4.65-.58z" fill="currentColor"></path>
					</svg>
				</div>
				<?php } ?>

			</div>
		</div>

	</div>
	<?php } ?>

	<div class="loading_ind" id="_loading">

		<div class="subloading">
			<div class="loading_text"><?=_LANG_UPLOADING;?>&nbsp;&nbsp;<span class="loading_procent">0%</span>&nbsp;&nbsp;&nbsp;</div>
		</div>

	</div>

	<div class="upload_error">
		<div class="upload_error_1"><?=str_replace('%max_files%',$settings['max_files'],_LANG_UPLOAD_ERROR_1);?></div>
		<div class="upload_error_2"><?=str_replace('%max_uploadsize%',$settings['max_uploadsize'],_LANG_UPLOAD_ERROR_2);?></div>
		<div class="upload_error_3"><?=_LANG_ERROR_DEFAULT;?></div>
	</div>

	<?php
	if(!is_logged()) {
		require_once('login.php');
		require_once('forgot.php');
		require_once('register.php');
	}

	require_once('mobile_menu.php');
	?>

	<div class="site">

		<div class="main">

			<?php
			require('header.php');

			if(current_page() == 'home') {
				include('home.php');
			}

			if(current_page() == 'contact') {
				include('contact.php');
			}

			if(current_page() == 'forgot') {
				include('change_pw.php');
			}

			if(current_page() == 'contest') {
				include('contest.php');
			}

			if(current_page() == 'contests') {
				include('contests.php');
			}

			if(current_page() == 'profile') {
				include('profile.php');
			}

			if(current_page() == 'settings') {
				include('settings.php');
			}

			if(current_page() == 'photo') {
				include('photo.php');
			}

			if(current_page() == 'extra_page') {
				include('extra_page.php');
			}

			if(current_page() == 'ranking') {
				include('ranking.php');
			}
			?>

			<?=get_ads('before_footer');?>

		</div>

		<?php
		require_once('footer.php');
		?>

	</div>

	<?php
	require_once('footer_scripts.php');
	?>

</body>
</html>
