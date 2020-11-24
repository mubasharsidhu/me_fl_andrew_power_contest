
	<div class="pop" id="edit_photo" data-id="0">

		<div class="pop_content h260">

			<div class="pop_inner">

				<div class="pop_head">
					<span><?=_LANG_POP_EDIT_PHOTO;?></span>
					<div class="close_pop">&nbsp;&#10005;&nbsp;</div>
				</div>

				<div class="pop_error"></div>
				<div class="pop_succes"></div>

				<div class="pop_row overflow margin_top20">
					<div class="pop_content_editor_left hide">
						<div class="pop_content_editor_cover">
							<img src="_img/no_thumb_music.jpg" class="preview_content_cover" />
							<div class="change_photo_cover"><?=_LANG_CONTENT_COVER_CHANGE;?></div>
						</div>
					</div>
					<div class="pop_content_editor_right pop_content_editor_full">
						<textarea id="edit_photo_description" rows="7" maxlength="250" placeholder="<?=_LANG_POP_EDIT_PHOTO_PLACEHOLDER;?>"></textarea>
					</div>
				</div>

				<div class="pop_footer">
					<div class="pop_row_button click_save_photo"><?=_LANG_POP_SAVE_PHOTO;?></div>
				</div>

			</div>

		</div>

	</div>

	<div class="profile_head">

		<div class="profile_inner">

			<div class="profile_inner_picture">
				<div class="profile_picture_box">
					<?php if($fetch_profile['profile_picture']) { ?>
					<img src="<?=$site_url;?>_uploads/_profile_pictures/<?=$fetch_profile['profile_picture'];?>.jpg" />
					<?php } ?>
				</div>
				<?php if(is_my_profile($fetch_profile['user'])) { ?>
				<div class="profile_picture_options">
					<div class="upload_new_profile">
						<i class="fas fa-camera"></i>
					</div>
					<div class="remove_profile_picture <?=($fetch_profile['profile_picture'] ? 'inline_block':'hide');?>">
						<i class="fas fa-trash"></i>
					</div>
				</div>
				<?php } ?>
			</div>

			<div class="profile_inner_info">
				<div class="profile_inner_info_name <?=(!$fetch_profile['slogan'] && !is_my_profile($fetch_profile['user']) ? 'margin_top_10':'');?>">
					<?=$fetch_profile['name'];?>
				</div>
				<div class="profile_inner_info_slogan <?=(is_my_profile($fetch_profile['user']) ? 'edit_profile_slogan' : '');?>" <?=(is_my_profile($fetch_profile['user']) ? 'contenteditable="true"':'');?>>
					<?=($fetch_profile['slogan'] ? $fetch_profile['slogan'] : _LANG_PROFILE_ADD_SLOGAN);?>
				</div>
				<?php if(!isset($settings['content_ratemode']) || (isset($settings['content_ratemode']) && $settings['content_ratemode'] == '0')) { ?>
				<div class="profile_inner_info_rating <?=(!$fetch_profile['slogan'] && !is_my_profile($fetch_profile['user']) ? 'margin_top_5':'');?>">
					<?=rating_bar(round_rate($profile_rating));?>
				</div>
				<?php } ?>
			</div>

		</div>

		<div class="profile_head_sub">

			<div class="profile_head_col">
				<div class="profile_head_col_val my_profile_count_photos"><?=(isset($fetch_count['total_photos']) && $fetch_count['total_photos'] ? $fetch_count['total_photos'] : 0);?></div>
				<div class="profile_head_col_name"><?=_LANG_PROFILE_PHOTOS;?></div>
			</div>

			<div class="profile_head_col">
				<div class="profile_head_col_val"><?=(isset($fetch_count['total_ratings']) && $fetch_count['total_ratings'] ? $fetch_count['total_ratings'] : 0);?></div>
				<div class="profile_head_col_name"><?=_LANG_PROFILE_TOTAL_RATINGS;?></div>
			</div>

			<div class="profile_head_col no_border">
				<div class="profile_head_col_val"><?=(isset($fetch_count['total_views']) && $fetch_count['total_views'] ? $fetch_count['total_views'] : 0);?></div>
				<div class="profile_head_col_name"><?=_LANG_PROFILE_TOTAL_VIEWS;?></div>
			</div>


		</div>

	
	</div>

	<div class="profile_tab_photos">

		<?php if(is_logged() && (isset($_SESSION['_logged_user']) && ($_SESSION['_logged_user'] == $_GET['profile']))) { ?>
		<div class="profile_photos_pending <?=($fetch_not_count['total_not'] > 0 ? 'show':'');?>"><?=_LANG_PHOTOS_PENDING;?></div>
		<?php } ?>

		<div id="profile_photos" data-page="0" data-stop="0" class="profile_photos"></div>
			
		<div class="loading_profile_photos">
			<i class="fas fa-spinner fa-spin"></i>
		</div>
			
		<div class="profile_no_photos">
			<div class="f18"><?=(isset($_SESSION['_logged_id']) && $_SESSION['_logged_id'] == $profile_id ? _LANG_MY_PROFILE_NO_PHOTOS:_LANG_PROFILE_NO_PHOTOS);?></div>
			<?=(isset($_SESSION['_logged_id']) && $_SESSION['_logged_id'] == $profile_id ? '<div class="profile_page_add_photos click_add_photos">'._LANG_MY_PROFILE_UPLOAD.'</div>':'');?>
		</div>
	
	</div>

	<div class="clear_profile"></div>