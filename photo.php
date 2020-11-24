
	<div class="main_celling">

		<div class="celling">

			<div class="overflow">

				<div class="photo_page_left overflow">

					<div class="photo_tab_1 <?=(isset($settings['photo_sidebar']) && $settings['photo_sidebar'] == '1' ? 'right':'');?> photo_order_1 overflow photo_tab_type_<?=$fetch_photo['type'];?>">
						<?php if($fetch_photo['type'] == '1') { ?>
						<audio controls>
  							<source src="_uploads/_music/<?=$fetch_photo['photo'];?>.mp3" type="audio/mpeg">
						</audio>
						<?php } elseif($fetch_photo['type'] == '2') { ?>
						<video width="100%" height="400" controls>
  							<source src="_uploads/_videos/<?=$fetch_photo['photo'];?>.mp4" type="video/mp4">
  						</video>
						<?php } elseif($fetch_photo['type'] == '0') { ?>
						<img src="_uploads/_photos/<?=$fetch_photo['photo'];?>.jpg" />
						<?php } ?>
					</div>

					<div class="photo_page_info photo_order_7">

						<div class="overflow s100">

							<?php if(isset($settings['hide_user_photo']) && $settings['hide_user_photo'] == '0') { ?>

							<div class="photo_user_box">

								<?php if($fetch_user['profile_picture']) { ?>
								<div class="photo_user_profile_pic">
									<a href="<?=$site_url;?><?=$fetch_user['user'];?>" class="inherit_desc_none">
										<img src="<?=$site_url;?>_uploads/_profile_pictures/<?=$fetch_user['profile_picture'];?>.jpg" />
									</a>
								</div>
								<?php } ?>

								<div class="photo_user_infobox">
									<div class="photo_user_box_name">
										<a href="<?=$site_url;?><?=$fetch_user['user'];?>" class="inherit_desc_none"><?=$fetch_user['name'];?></a>
									</div>
									<div class="photo_user_box_user">
										<a href="<?=$site_url;?><?=$fetch_user['user'];?>" class="inherit_desc_none">@<?=$fetch_user['user'];?></a>
									</div>
								</div>

							</div>

							<?php } ?>

							<div class="photo_page_share">
		
								<div class="photo_sharebox_text"><?=_LANG_PHOTO_SHARE_THIS;?></div>
							
								<div class="photo_sharebox_button">
									<div class="fb-share-button" data-size="large" data-href="<?=$site_url;?>photo-<?=$photo_id;?>" data-layout="button"></div>
								</div>

								<div class="photo_sharebox_button">
									<a class="twitter-share-button" href="<?=$site_url;?>photo-<?=$photo_id;?>" data-size="large">Tweet</a>
								</div>

							</div>
	
						</div>

						<?php if(isset($settings['allow_description']) && $settings['allow_description'] == '1') { ?>

						<div class="photo_desc_field"><?=$fetch_photo['description'];?></div>

						<?php } ?>

						<?php if(isset($settings['photo_comments']) && $settings['photo_comments'] == '1') { ?>
						<div class="photo_comments_tab">
							<i class="far fa-comments"></i>&nbsp;&nbsp;<?=_LANG_COMMENTS_TEXT;?> <span class="b700 total_comments"></span>
						</div>

						<div class="photo_comments_box">

							<div class="photo_comments_add">
								<div class="overflow">
									<div class="photo_comments_form_left <?=(!is_logged() ? 'click_notlogged':'');?>">
										<?php if(!is_logged()) { ?>
										<div class="add_comment_input click_notlogged"><?=_LANG_ADD_COMMENT;?></div>
										<?php } else { ?>
										<input type="text" class="add_comment_input" name="add_comment" maxlength="200" placeholder="<?=_LANG_ADD_COMMENT;?>" />
										<?php } ?>
									</div>
									<div class="add_comment"><i class="fas fa-check"></i></div>
								</div>
							</div>
						
							<div class="photo_comments_loading">
								<i class="fas fa-spinner fa-spin"></i>	
							</div>
						
							<div class="photo_comment_pending hide"><?=_LANG_COMMENTS_PENDING;?></div>

							<div class="photo_comments"></div>

						</div>
						<?php } ?>

					</div>
				</div>

				<div class="photo_tab_2 <?=(isset($settings['photo_sidebar']) && $settings['photo_sidebar'] == '1' ? 'left':'');?> relative photo_order_3 overflow">

					<div class="photo_order_2">

						<div class="photo_rate_message"><?=_LANG_PHOTO_RATE_MESSAGE;?></div>

						<div class="photo_list_board">
							<div class="photo_list_board_item">
								<div class="photo_list_board_item_val photo_rank_update">#<?=$fetch_rank['rank'];?></div>
								<div class="photo_list_board_item_key"><?=_LANG_PHOTO_RANK;?></div>
							</div>
							<div class="photo_list_board_item">
								<div class="photo_list_board_item_val nr_ratings_update"><?=$fetch_photo['nr_ratings'];?></div>
								<div class="photo_list_board_item_key"><?=_LANG_PHOTO_RATINGS;?></div>
							</div>
							<div class="photo_list_board_item no_border">
								<div class="photo_list_board_item_val"><?=$fetch_photo['views'];?></div>
								<div class="photo_list_board_item_key"><?=_LANG_PHOTO_VIEWS;?></div>
							</div>
						</div>

						<?php if($fetch_photo['contest'] != '0' || $multi_contest == '1') { ?>

						<div class="photo_rating_action">

							<div class="profile_ratings photo_rating_action_box">

								<?php if($site_contest['active'] == '0' || ($site_contest['active'] == '1' && strtotime($site_contest['end']) > time())) { ?>
									<?php if(isset($settings['content_ratemode']) && $settings['content_ratemode'] == '1') { ?>
									<div class="profile_rating_left2 <?=($i_rated == '0' ? 'rate_active':'rate_inactive');?> extra_rating_s <?=(isset($settings['vote_own']) && $settings['vote_own'] == '0' && is_logged() && isset($_SESSION['_logged_id']) && $_SESSION['_logged_id'] == $fetch_photo['iduser'] ? 'hide':'');?>">
										<?php if($i_rated == '0') { ?>
											<div class="vote_button_click"><?=_LANG_VOTE_BUTTON;?></div>
											<div class="vote_button_clicked hide"><i class="fas fa-check"></i>&nbsp;&nbsp;<?=_LANG_VOTED_BUTTON;?></div>
											<div class="vote_loader hide"><i class="fas fa-spinner fa-spin"></i><br></div>
										<?php } else { ?>
											<div class="vote_button_clicked"><i class="fas fa-check"></i>&nbsp;&nbsp;<?=_LANG_VOTED_BUTTON;?></div>
										<?php } ?>
									</div>							
									<?php } ?>
									<?php if(!isset($settings['content_ratemode']) || (isset($settings['content_ratemode']) && $settings['content_ratemode'] == '0')) { ?>
									<div class="profile_rating_left2 <?=($i_rated == '0' ? 'rate_active':'rate_inactive');?> extra_rating_s <?=(isset($settings['vote_own']) && $settings['vote_own'] == '0' && is_logged() && isset($_SESSION['_logged_id']) && $_SESSION['_logged_id'] == $fetch_photo['iduser'] ? 'hide':'');?>">
										<?php if($i_rated == '0') { ?>
											<?php for($i=1;$i<=5;$i++) { ?>
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" data-star="<?=$i;?>" class="star_click star<?=$i;?> svg83" viewBox="0 0 20 20" data-inline="false">
												<path d="M10 1L7 7l-6 .75l4.13 4.62L4 19l6-3l6 3l-1.12-6.63L19 7.75L13 7zm0 2.24l2.34 4.69l4.65.58l-3.18 3.56l.87 5.15L10 14.88l-4.68 2.34l.87-5.15l-3.18-3.56l4.65-.58z" fill="currentColor"></path>
											</svg>
											<?php } ?>
										<?php } else { ?>
											<?=rating_bar_s(round_rate($fetch_photo['rating']));?>
										<?php } ?>
									</div>	
									<?php } ?>

									<div class="clear"></div>

								<?php } else { ?>
									<div class="photo_rating_no_action"><?=_LANG_RATING_NOT_ALLOWED;?></div>
								<?php } ?>

								<div class="photo_list_board_item photo_list_board_item_current">
									<div class="current_score_text"><?=_LANG_CURRENT_SCORE;?></div>
									<div class="photo_list_board_item_val nr_rating_update current_score_val"><?=$fetch_photo['rating'];?></div>
								</div>

							</div>

						</div>

						<?php } else { ?>
						
						<?php } ?>

					</div>

					<?=get_ads('photo_right');?>

					<?php if(isset($settings['display_exif']) && $settings['display_exif'] == '1' && $fetch_photo['type'] == '0') { ?>

					<div class="clear"></div>
					<div class="photo_information">
				
						<div class="photo_information_title"><?=_LANG_PHOTO_INFORMATION_TITLE;?></div>

						<div class="photo_information_item">
							<div class="photo_information_item_key"><?=_LANG_PHOTO_INFORMATION_MAKE;?></div>
							<div class="photo_information_item_value"><?=($photo_exif['make'] ? $photo_exif['make'] : _LANG_UNAVAILABLE);?></div>
						</div>

						<div class="photo_information_item">
							<div class="photo_information_item_key"><?=_LANG_PHOTO_INFORMATION_MODEL;?></div>
							<div class="photo_information_item_value"><?=($photo_exif['model'] ? $photo_exif['model'] : _LANG_UNAVAILABLE);?></div>
						</div>

						<div class="photo_information_item">
							<div class="photo_information_item_key"><?=_LANG_PHOTO_INFORMATION_EXPOSURE;?></div>
							<div class="photo_information_item_value"><?=($photo_exif['exposure'] ? $photo_exif['exposure'] : _LANG_UNAVAILABLE);?></div>
						</div>

						<div class="photo_information_item">
							<div class="photo_information_item_key"><?=_LANG_PHOTO_INFORMATION_APERTURE;?></div>
							<div class="photo_information_item_value"><?=($photo_exif['aperture'] ? $photo_exif['aperture'] : _LANG_UNAVAILABLE);?></div>
						</div>

						<div class="photo_information_item">
							<div class="photo_information_item_key"><?=_LANG_PHOTO_INFORMATION_ISO;?></div>
							<div class="photo_information_item_value"><?=($photo_exif['iso'] ? $photo_exif['iso'] : _LANG_UNAVAILABLE);?></div>
						</div>

						<div class="photo_information_item">
							<div class="photo_information_item_key"><?=_LANG_PHOTO_INFORMATION_DATE;?></div>
							<div class="photo_information_item_value"><?=($photo_exif['date'] ? $photo_exif['date'] : _LANG_UNAVAILABLE);?></div>
						</div>

					</div>

					<?php } ?>

					<?php if(!isset($settings['display_related']) || (isset($settings['display_related']) && $settings['display_related'] == '1')) { ?>
					<div class="photo_related">

						<br>
	
						<?php
						$start_i=1;
						while($fetch_similar = mysqli_fetch_array($sql_similar_photos)) {
							
							if($fetch_similar['type'] == '0') {
								$thumb_picture = $site_url.'_uploads/_photos/'.$fetch_similar['photo'].'_400.jpg';
							}

							if($fetch_similar['type'] == '1') {
								if($fetch_similar['cover']) {
									$thumb_picture = $site_url.'_uploads/_content_cover/'.$fetch_similar['cover'].'_400.jpg';
								} else {
									$thumb_picture = $site_url.'_img/no_thumb_music.jpg';
								}
							}

							if($fetch_similar['type'] == '2') {
								if($fetch_similar['cover']) {
									$thumb_picture = $site_url.'_uploads/_content_cover/'.$fetch_similar['cover'].'_400.jpg';
								} else {
									$thumb_picture = $site_url.'_img/no_thumb_video.jpg';
								}
							}

							echo '
							<div class="photo_related_item">
								<a href="'.$site_url.'photo-'.$fetch_similar['id'].'">
									<img src="'.$thumb_picture.'" />
								</a>
							</div>';

						}
						?>

					</div>
					<?php } ?>

				</div>

			</div>

			<div class="photo_clear"></div>

		</div>

	</div>