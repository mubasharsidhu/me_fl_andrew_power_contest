
	<?php require_once('footer_extra_scripts.php'); ?>

	<?php if(current_page() == 'photo') { ?>
  	<!-- Load Facebook SDK for JavaScript -->
  	<div id="fb-root"></div>
 	<script>
	(function(d, s, id) {
    		var js, fjs = d.getElementsByTagName(s)[0];
    		if (d.getElementById(id)) return;
    		js = d.createElement(s); js.id = id;
    		js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
    		fjs.parentNode.insertBefore(js, fjs);
  	}(document, 'script', 'facebook-jssdk'));
	</script>
	<script>
	window.twttr = (function(d, s, id) {
  		var js, fjs = d.getElementsByTagName(s)[0], t = window.twttr || {};
  		if (d.getElementById(id)) return t;
  		js = d.createElement(s);
  		js.id = id;
  		js.src = "https://platform.twitter.com/widgets.js";
  		fjs.parentNode.insertBefore(js, fjs);
		t._e = [];
  		t.ready = function(f) { t._e.push(f); };
  		return t;
	}(document, "script", "twitter-wjs"));
	</script>
	<?php } ?>

	<script type="text/javascript" src="_js/all.min.js"></script>
	<script type="text/javascript" src="_js/jquery-3.5.0.min.js"></script>
	<script type="text/javascript" src="_js/_uploader.js"></script>
	<script type="text/javascript" src="_js/dragscroll.js"></script>

	<script type="text/javascript">
	var mobile = ($(window).width() < 768 ? 1 : 0);
	var this_page = '<?=current_page();?>';
	var logged_id = '<?=(isset($_SESSION['_logged_id']) && is_numeric($_SESSION['_logged_id']) ? $_SESSION['_logged_id'] : 0);?>';
	var logged_user = '<?=(isset($_SESSION['_logged_user']) && $_SESSION['_logged_user'] ? $_SESSION['_logged_user'] : '');?>';
	var photo_id = '<?=(isset($_GET['photo']) && is_numeric($_GET['photo']) ? $_GET['photo'] : 0);?>';
	var this_is_my_profile = '<?=(isset($_SESSION['_logged_user']) && isset($_GET['profile']) && ($_SESSION['_logged_user'] == $_GET['profile']) ? 1 : 0);?>';
	var profile_id = '<?=(isset($_GET['profile']) && isset($profile_id) ? $profile_id : 0);?>';
	var category_required = '<?=($settings['category_required'] == '1' ? 1 : 0);?>';
	var site_url = '<?=$site_url;?>';
	var photo_approval = '<?=(isset($settings['photo_approval']) ? $settings['photo_approval'] : 0);?>';
	var lang_ranking_list_ratings = '<?=_LANG_RANKING_LIST_RATINGS;?>';
	var lang_ranking_list_views = '<?=_LANG_RANKING_LIST_VIEWS;?>';
	var lang_remove_profile_picture = '<?=_LANG_REMOVE_PROFILE_PICTURE;?>';
	var lang_thumb_ratings = '<?=_LANG_THUMB_RATINGS;?>';
	var lang_rotate_photo = '<?=_LANG_ROTATE_PHOTO;?>';
	var lang_remove_photo = '<?=_LANG_REMOVE_PHOTO;?>';
	var lang_error_default = '<?=_LANG_ERROR_DEFAULT;?>';
	var lang_login_error = '<?=_LANG_LOGIN_ERROR;?>';
	var lang_register_account_ready = '<?=_LANG_REGISTER_ACCOUNT_READY;?>';
	var lang_no_comments = '<?=_LANG_NO_COMMENTS;?>';
	var lang_remove_contest_photo = '<?=_LANG_REMOVE_CONTEST_PHOTO;?>';
	var lang_no_photos_uploaded = '<?=_LANG_NO_PHOTOS_UPLOADED;?>';	
	var lang_forgot_found = '<?=_LANG_POP_FORGOT_FOUND;?>';
	var lang_forgot_not_found = '<?=_LANG_POP_FORGOT_NOT_FOUND;?>';
	var is_loading = 1;
	var active_contest = '1';
	var contest_end = '<?=(isset($site_contest['end']) ? $site_contest['end'] : '');?>';
	var preloader_show = '<?=$preloader_show;?>';
	var facebook_appid = '<?=(isset($settings['fb_appid']) ? $settings['fb_appid'] : 0);?>';
	var max_uploadsize_mb = '<?=(isset($settings['max_uploadsize']) ? $settings['max_uploadsize'] : '5');?>';
	var max_uploadsize = '<?=(isset($settings['max_uploadsize']) ? $settings['max_uploadsize'].'000000' : '5000000');?>';
	var max_files = '<?=(isset($settings['max_files']) ? $settings['max_files'] : 5);?>';
	var photo_comments = '<?=(isset($settings['photo_comments']) ? $settings['photo_comments'] : 0);?>';
	var large_ranking = '<?=(isset($settings['ranking_page_large']) ? $settings['ranking_page_large'] : 0);?>';
	var random_photo = '<?=(isset($settings['random_photo']) ? $settings['random_photo'] : 0);?>';
	var visitors_rate = '<?=(isset($settings['visitors_rate']) ? $settings['visitors_rate'] : 0);?>';
	var multi_contest = '<?=$multi_contest;?>';
	var contest_id = '<?=(isset($_GET['contest']) && is_numeric($_GET['contest']) ? $_GET['contest'] : 0);?>';
	var display_thumb_rate = '<?=(!isset($settings['display_thumb_rate']) || $settings['display_thumb_rate'] == '1' ? 1 : 0);?>';
	var comments_review = '<?=(isset($settings['comments_review']) && $settings['comments_review'] == '1' ? 1 : 0);?>';
	var upload_music = '<?=(isset($settings['upload_music']) && $settings['upload_music'] == '1' ? 1 : 0);?>';
	var upload_video = '<?=(isset($settings['upload_video']) && $settings['upload_video'] == '1' ? 1 : 0);?>';
	var content_category = '<?=(isset($settings['content_category']) && $settings['content_category'] == '1' ? 1 : 0);?>';
	</script>

	<script type="text/javascript" src="_js/functions.js?v=1.4.20"></script>
	<?php if(isset($settings['fb_appid']) && $settings['fb_appid'] != '' && !is_logged()) { ?>
	<script type="text/javascript" src="_js/facebook_login.js?v=1.1"></script>
	<?php } ?>
