<?php
require_once '_core/_config.php';
require_once '_core/_functions.php';

require 'includes/elo-ratings/Rating.php';

$user_logged = get_user_logged();
$settings    = get_settings();
$contest     = isset($_POST['contest']) ? $_POST['contest'] : $_GET['contest'];
$userId      = isset($_POST['userId']) ? $_POST['userId'] : $user_logged['id'];

require_once 'hot_not_functions.php';

?>

<div class="contest_ranking hot-not-contest" style="text-align:center; min-height:257px;">

	<div class="contest_rankings_box">

			<div class="c cs3 lr565 mphotos_title all_black">Elo Contest <p><sub>(Click to Vote)</sub></p></div>

			<div class="loading_profile_photos" style="display:none">
				<i class="fas fa-spinner fa-spin"></i>
			</div>

			<div id="elo-rating-div" class="contest_ranking_list">

				<?php
				$result 	= tnsb_display_contest( $db, $contest, $userId );
				echo tnsb_get_contest_html( $db, $result, $settings );
				?>

			</div>
	</div>

</div>


<script>
	tnsb_esecute_js();
	function tnsb_esecute_js() {
			(function(d, s, id) {
					console.warn(d);
					console.warn(s);
					console.warn(id);
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
			fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));

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
	}


	function eloRating(photoId, eloScore, comparePhotoId, compareEloScore) {

		var user_id     = <?php echo isset($_SESSION['_logged_id']) ? $_SESSION['_logged_id'] : '0'; ?>;
		var contest_id  = <?php echo isset($_GET['contest']) ? $_GET['contest'] : '0'; ?>;

		if ( user_id == '0' ) {
			$('#login_pop .pop_content').css('height', '351.4px');
			$('#login_pop').show();
			return;
		}

		$('#elo-rating-div').hide( "fast" );
		$('.contest_ranking .loading_profile_photos').css('display', 'block');

		$.post('hot_not_contest.php', {
			isAjax         : 'hot_not_contest',
			contest        : contest_id,
			photoId        : photoId,
			userId         : user_id,
			eloScore       : eloScore,
			comparePhotoId : comparePhotoId,
			compareEloScore: compareEloScore,
		}, function (response) {
			$('#elo-rating-div').html(response);
			$('#elo-rating-div').show('fast');
			$('.contest_ranking .loading_profile_photos').css('display', 'none');
			FB.XFBML.parse();
			twttr.widgets.load();
		});
	}
</script>
