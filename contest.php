
	<?php if($site_contest['active'] == '0') { exit;?>

	<div class="contest_header">

		<div class="contest_header_sub">

			<div class="contest_header_box">
				<div class="contest_header_icon"><i class="fas fa-crown"></i></div>
				<div class="c cs3"><?=_LANG_NO_CONTEST_ACTIVE;?></div>
			</div>

		</div>

	</div>

	<?php } else { ?>

	<div class="contest_header">

		<div class="contest_header_sub">

			<div class="contest_header_box">
				<div class="contest_header_icon"><i class="fas fa-crown"></i></div>

				<div class="c cs2"><?=$site_contest['title'];?></div>
				<div class="c cs3"><?=$site_contest['description'];?></div>
			</div>

			<?php if($site_contest['disable_countdown'] == '0') { ?>

			<div class="contest_header_timer">
				<div class="contest_timer_box ct_1">-</div>
				<div class="contest_timer_box ct_2">-</div>
				<div class="contest_timer_box ct_3">-</div>
				<div class="contest_timer_box ct_4">-</div>
			</div>

			<div class="contest_header_tile">
				<div class="contest_timer_tile"><?=_LANG_DAYS;?></div>
				<div class="contest_timer_tile"><?=_LANG_HOURS;?></div>
				<div class="contest_timer_tile"><?=_LANG_MINUTES;?></div>
				<div class="contest_timer_tile"><?=_LANG_SECONDS;?></div>
			</div>

			<?php } ?>

		</div>

	</div>

	<?php
	// TNSB_EDIT_FOR_CUSTOMIZATION_STARTS_HERE
	require_once 'hot_not_contest.php';
	// TNSB_EDIT_FOR_CUSTOMIZATION_ENDS_HERE
	?>

	<?php if($multi_contest == '2' && is_logged()) { ?>

	<div class="pop_my_photos">
		<div class="pop_join_contest_box">
			<div class="load_my_photos"></div>
			<div class="join_contest_buttons">
				<div class="click_join_contest"><?=_LANG_SUBMIT;?></div>
				<div class="cancel_contest_join"><?=_LANG_CANCEL;?></div>
			</div>
		</div>
	</div>

	<div class="multi_contest_box">

	<div class="contest_ranking">

		<div class="contest_rankings_box">

			<div class="c cs3 lr565 mphotos_title all_black"><?=_LANG_MY_PHOTOS;?></div>

			<div class="contest_ranking_list dragscroll">

			<?php
			$place = 1;
			if(mysqli_num_rows($sql_r05) == '0') {
				echo '<div class="multi_contest_box_msg all_black">'._LANG_CONTEST_JOIN_NO_PHOTOS.'</div>';
			}
			while($fetch_r = mysqli_fetch_array($sql_r05)) {

				if($fetch_r['type'] == '0') {
					$thumb_picture = $settings['site_url'].'_uploads/_photos/'.$fetch_r['photo'].'_400.jpg';
				}

				if($fetch_r['type'] == '1') {
					if($fetch_r['cover']) {
						$thumb_picture = $settings['site_url'].'_uploads/_content_cover/'.$fetch_r['cover'].'_400.jpg';
					} else {
						$thumb_picture = $settings['site_url'].'_img/no_thumb_music.jpg';
					}
				}

				if($fetch_r['type'] == '2') {
					if($fetch_r['cover']) {
						$thumb_picture = $settings['site_url'].'_uploads/_content_cover/'.$fetch_r['cover'].'_400.jpg';
					} else {
						$thumb_picture = $settings['site_url'].'_img/no_thumb_video.jpg';
					}
				}

				echo '
				<div class="contest_thumb">
					<a href="photo-'.$fetch_r['id'].'">
						<div class="thumb_option_place remove_contest_photo" data-id="'.$fetch_r['id'].'">&nbsp;&#10005;&nbsp;</div>
						<img src="'.$settings['site_url'].'_uploads/_photos/'.$fetch_r['photo'].'_400.jpg" />
					</a>
				</div>';

				$place++;

			}
			?>

			</div>
		</div>

		<div class="open_my_photos margin_top_10"><?=_LANG_SUBMIT_PHOTO;?></div>

	</div>

	</div>

	<?php } ?>

	<div class="contest_ranking">

		<div class="contest_ranking_box">

			<div class="contest_ranking_icon"><i class="fas fa-chart-line"></i></div>
			<div class="c cs3 lr565 all_black"><?=_LANG_CONTEST_RANKING;?></div>

		</div>

		<div class="contest_rankings_box">
			<div class="contest_ranking_list dragscroll">

			<?php
			if(!isset($settings['min_votes'])) {
				$settings['min_votes'] = 0;
			}
			if(mysqli_num_rows($sql_r04) == '0') {
				echo '<div class="contest_no_photos show">'._LANG_CONTEST_NO_PHOTOS.'</div>';
			}
			while($fetch_r = mysqli_fetch_array($sql_r04)) {

				if($fetch_r['type'] == '0') {
					$thumb_picture = $settings['site_url'].'_uploads/_photos/'.$fetch_r['photo'].'_400.jpg';
				}

				if($fetch_r['type'] == '1') {
					if($fetch_r['cover']) {
						$thumb_picture = $settings['site_url'].'_uploads/_content_cover/'.$fetch_r['cover'].'_400.jpg';
					} else {
						$thumb_picture = $settings['site_url'].'_img/no_thumb_music.jpg';
					}
				}

				if($fetch_r['type'] == '2') {
					if($fetch_r['cover']) {
						$thumb_picture = $settings['site_url'].'_uploads/_content_cover/'.$fetch_r['cover'].'_400.jpg';
					} else {
						$thumb_picture = $settings['site_url'].'_img/no_thumb_video.jpg';
					}
				}

				$place = $fetch_r['rank'];
				$crown = '';
				if($place == '1') {
					$crown = '<div class="thumb_ranking_crown"><i class="fas fa-crown"></i></div>';
				}

				echo '
				<div class="contest_thumb">
					<a href="photo-'.$fetch_r['id'].'">
						<div class="thumb_ranking_place">'.$place.'</div>
						'.$crown.'
						<img src="'.$thumb_picture.'" />
					</a>
				</div>';

			}
			?>

			</div>
		</div>
	</div>

	<?php } ?>
