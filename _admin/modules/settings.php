
	<div class="padding_20">

		<div class="settings">
			<a href="index.php?page=settings">
				<div class="settings_menu <?=(!isset($_GET['customization']) && !isset($_GET['watermark']) && !isset($_GET['credentials']) ? 'photos_menu_selected':'');?>">General</div>
			</a>
			<a href="index.php?page=settings&customization=1">
				<div class="settings_menu <?=(isset($_GET['customization']) ? 'photos_menu_selected':'');?>">Customization</div>
			</a>
			<a href="index.php?page=settings&watermark=1">
				<div class="settings_menu <?=(isset($_GET['watermark']) ? 'photos_menu_selected':'');?>">Watermark</div>
			</a>
			<a href="index.php?page=settings&credentials=1">
				<div class="settings_menu <?=(isset($_GET['credentials']) ? 'photos_menu_selected':'');?>">Credentials</div>
			</a>
		</div>

		<div class="clear_space"></div>
		<div class="slash"></div>

		<?php if(isset($success_msg) && $success_msg) { ?>
		<div class="success_box_new show">
			<div class="scb_icon"><i class="fas fa-check"></i></div>
			<div class="scb_text"><?=$success_msg;?></div>
		</div>
		<?php } ?>
		<?php if(isset($error_msg) && $error_msg) { ?>
		<div class="no_results">
			<div class="no_results_icon"><i class="fas fa-exclamation-triangle"></i></div>
			<div class="no_results_text"><?=$error_msg;?></div>
		</div>
		<?php } ?>

		<?php if(isset($_GET['customization'])) { ?>

		<div class="settings_results">

			<form action="index.php?page=settings&customization=1" method="post" enctype="multipart/form-data">

			<div class="setting">
				<div class="setting_left">Site logo</div>
				<div class="setting_right">
					<input type="text" placeholder="Hello" value="<?=(isset($site_settings['site_logo']) ? $site_settings['site_logo'] : 'Hello');?>" name="site_logo" />
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Site logo image</div>
				<div class="setting_right">
					<?php if(isset($site_settings['site_logo_image']) && $site_settings['site_logo_image']) { ?>
					<div class="delete_logo_click">Delete logo</div><br>
					<img src="../_img/logo.png?v=<?=rand(1,9999);?>" class="display_logo_image" />
					</div>
					<?php } else { ?>
					<input type="file" name="site_logo_image" accept="image/png,image/jpeg" />
					<?php } ?>
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Display searchbar</div>
				<div class="setting_right">
					<select name="display_searchbar">
						<option value="1" <?=(isset($site_settings['display_searchbar']) && $site_settings['display_searchbar'] == '1' ? 'selected':'');?>>Yes</option>
						<option value="0" <?=(isset($site_settings['display_searchbar']) && $site_settings['display_searchbar'] == '0' ? 'selected':'');?>>No</option>
					</select>
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Contact page</div>
				<div class="setting_right">
					<select name="contact_page">
						<option value="0" <?=(isset($site_settings['contact_page']) && $site_settings['contact_page'] == '0' ? 'selected':'');?>>No</option>
						<option value="1" <?=(isset($site_settings['contact_page']) && $site_settings['contact_page'] == '1' ? 'selected':'');?>>Yes</option>
					</select>
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Home header</div>
				<div class="setting_right">
					<select name="home_header">
						<option value="1" <?=(isset($site_settings['home_header']) && $site_settings['home_header'] == '1' ? 'selected':'');?>>Yes</option>
						<option value="0" <?=(isset($site_settings['home_header']) && $site_settings['home_header'] == '0' ? 'selected':'');?>>No</option>
					</select>
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Photo page sidebar</div>
				<div class="setting_right">
					<select name="photo_sidebar">
						<option value="1" <?=(isset($site_settings['photo_sidebar']) && $site_settings['photo_sidebar'] == '1' ? 'selected':'');?>>Right</option>
						<option value="0" <?=(isset($site_settings['photo_sidebar']) && $site_settings['photo_sidebar'] == '0' ? 'selected':'');?>>Left</option>
					</select>
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Large photos on ranking page</div>
				<div class="setting_right">
					<select name="ranking_page_large">
						<option value="1" <?=(isset($site_settings['ranking_page_large']) && $site_settings['ranking_page_large'] == '1' ? 'selected':'');?>>Yes</option>
						<option value="0" <?=(isset($site_settings['ranking_page_large']) && $site_settings['ranking_page_large'] == '0' ? 'selected':'');?>>No</option>
					</select>
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Site theme</div>
				<div class="setting_right">
					<select name="site_theme">
						<option value="default" <?=(isset($site_settings['site_theme']) && $site_settings['site_theme'] == 'default' ? 'selected':'');?>>Default</option>
						<option value="blue" <?=(isset($site_settings['site_theme']) && $site_settings['site_theme'] == 'blue' ? 'selected':'');?>>Blue</option>
					</select>
				</div>
			</div>

			<div class="slash"></div>

			<div class="setting">
				<div class="setting_left">&nbsp;</div>
				<div class="setting_right">
					<input type="submit" name="submit" value="Save changes" class="st_svb" />
				</div>
			</div>

			</form>

		</div>

		<?php } ?>

		<?php if(isset($_GET['watermark'])) { ?>

		<div class="settings_results">

			<form action="index.php?page=settings&watermark=1" method="post" enctype="multipart/form-data">

			<div class="setting">
				<div class="setting_left">Watermark</div>
				<div class="setting_right">
					<select name="watermark">
						<option value="0" <?=(isset($site_settings['watermark']) && $site_settings['watermark'] == '0' ? 'selected':'');?>>No</option>
						<option value="1" <?=(isset($site_settings['watermark']) && $site_settings['watermark'] == '1' ? 'selected':'');?>>Yes</option>
					</select>
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Current watermark</span></div>
				<div class="setting_right">
					<div class="padding_10"><?=(isset($site_settings['watermark_image']) && file_exists('../'.$site_settings['watermark_image']) ? '<img src="../_uploads/watermark.png?v='.rand(1,9999).'" />':'None');?></div>
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">New watermark image<br><span class="setting_explain">(PNG only - maxium size 250px X 250px)</span></div>
				<div class="setting_right">
					<input type="file" name="watermark_image" accept="image/x-png,image/png" />
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Opacity</div>
				<div class="setting_right">
					<select name="watermark_opacity">
						<?php for($i=1;$i<=10;$i++) { ?>
						<option value="<?=$i;?>0" <?=(isset($site_settings['watermark_opacity']) && $site_settings['watermark_opacity'] == $i.'0' ? 'selected':'');?>><?=$i;?>0%</option>
						<?php } ?>
					</select>
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Position</div>
				<div class="setting_right">
					<select name="watermark_position">
						<option value="cc" <?=(isset($site_settings['watermark_position']) && $site_settings['watermark_position'] == 'cc' ? 'selected':'');?>>Center</option>
						<option value="tl" <?=(isset($site_settings['watermark_position']) && $site_settings['watermark_position'] == 'tl' ? 'selected':'');?>>Top left</option>
						<option value="tr" <?=(isset($site_settings['watermark_position']) && $site_settings['watermark_position'] == 'tr' ? 'selected':'');?>>Top right</option>
						<option value="bl" <?=(isset($site_settings['watermark_position']) && $site_settings['watermark_position'] == 'bl' ? 'selected':'');?>>Bottom left</option>
						<option value="br" <?=(isset($site_settings['watermark_position']) && $site_settings['watermark_position'] == 'br' ? 'selected':'');?>>Bottom right</option>
					</select>
				</div>
			</div>

			<div class="slash"></div>

			<div class="setting">
				<div class="setting_left">&nbsp;</div>
				<div class="setting_right">
					<input type="submit" name="submit" value="Save changes" class="st_svb" />
				</div>
			</div>

			</form>

		</div>

		<?php } ?>

		<?php if(isset($_GET['credentials'])) { ?>

		<div class="settings_results">

			<form action="index.php?page=settings&credentials=1" method="post">

			<div class="setting">
				<div class="setting_left">Admin username</div>
				<div class="setting_right">
					<input type="text" placeholder="admin" value="<?=(isset($site_settings['admin_user']) ? $site_settings['admin_user'] : '');?>" name="admin_user" />
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">New password</div>
				<div class="setting_right">
					<input type="password" placeholder="password" value="" name="new_password" />
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Repeat new password</div>
				<div class="setting_right">
					<input type="password" placeholder="password" value="" name="repeat_new_password" />
				</div>
			</div>

			<div class="slash"></div>

			<div class="setting">
				<div class="setting_left">&nbsp;</div>
				<div class="setting_right">
					<input type="submit" name="submit" value="Save changes" class="st_svb" />
				</div>
			</div>

			</form>

		</div>

		<?php } ?>

		<?php if(!isset($_GET['customization']) && !isset($_GET['watermark']) && !isset($_GET['credentials'])) { ?>

		<div class="settings_results">

			<form action="index.php?page=settings" method="post">

			<div class="setting">
				<div class="setting_left">Site URL<br><span class="setting_explain">(your site url containing https://)</span></div>
				<div class="setting_right">
					<input type="text" placeholder="http://example.com/" value="<?=(isset($site_settings['site_url']) ? $site_settings['site_url'] : '');?>" name="site_url" />
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Site e-mail<br><span class="setting_explain">(used for contact page)</span></div>
				<div class="setting_right">
					<input type="text" placeholder="contact@example.com" value="<?=(isset($site_settings['site_email']) ? $site_settings['site_email'] : '');?>" name="site_email" />
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Facebook AppID<br><span class="setting_explain">(used for login with Facebook option)</span></div>
				<div class="setting_right">
					<input type="text" placeholder="" value="<?=(isset($site_settings['fb_appid']) ? $site_settings['fb_appid'] : '');?>" name="fb_appid" />
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Preloader splash</div>
				<div class="setting_right">
					<select name="preloader">
						<option value="1" <?=(isset($site_settings['preloader']) && $site_settings['preloader'] == '1' ? 'selected':'');?>>Yes</option>
						<option value="0" <?=(isset($site_settings['preloader']) && $site_settings['preloader'] == '0' ? 'selected':'');?>>No</option>
					</select>
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Photo moderation<br><span class="setting_explain">You must approve every uploaded photo before is going to be visible on site</span></div>
				<div class="setting_right">
					<select name="photo_approval">
						<option value="1" <?=(isset($site_settings['photo_approval']) && $site_settings['photo_approval'] == '1' ? 'selected':'');?>>Yes</option>
						<option value="0" <?=(isset($site_settings['photo_approval']) && $site_settings['photo_approval'] == '0' ? 'selected':'');?>>No</option>
					</select>
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Photos per page</div>
				<div class="setting_right">
					<select name="photos_per_page">
						<option value="20" <?=(isset($site_settings['photos_per_page']) && $site_settings['photos_per_page'] == '20' ? 'selected':'');?>>20</option>
						<option value="30" <?=(isset($site_settings['photos_per_page']) && $site_settings['photos_per_page'] == '30' ? 'selected':'');?>>30</option>
						<option value="50" <?=(isset($site_settings['photos_per_page']) && $site_settings['photos_per_page'] == '50' ? 'selected':'');?>>50</option>
						<option value="100" <?=(isset($site_settings['photos_per_page']) && $site_settings['photos_per_page'] == '100' ? 'selected':'');?>>100</option>
					</select>
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Category required<br><span class="setting_explain">Your site will display categories and will be required to select one at registration</span></div>
				<div class="setting_right">
					<select name="category_required">
						<option value="1" <?=(isset($site_settings['category_required']) && $site_settings['category_required'] == '1' ? 'selected':'');?>>Yes</option>
						<option value="0" <?=(isset($site_settings['category_required']) && $site_settings['category_required'] == '0' ? 'selected':'');?>>No</option>
					</select>
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Photo comments</div>
				<div class="setting_right">
					<select name="photo_comments">
						<option value="1" <?=(isset($site_settings['photo_comments']) && $site_settings['photo_comments'] == '1' ? 'selected':'');?>>Yes</option>
						<option value="0" <?=(isset($site_settings['photo_comments']) && $site_settings['photo_comments'] == '0' ? 'selected':'');?>>No</option>
					</select>
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Display photo exif</div>
				<div class="setting_right">
					<select name="display_exif">
						<option value="1" <?=(isset($site_settings['display_exif']) && $site_settings['display_exif'] == '1' ? 'selected':'');?>>Yes</option>
						<option value="0" <?=(isset($site_settings['display_exif']) && $site_settings['display_exif'] == '0' ? 'selected':'');?>>No</option>
					</select>
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Photo description field</div>
				<div class="setting_right">
					<select name="allow_description">
						<option value="0" <?=(isset($site_settings['allow_description']) && $site_settings['allow_description'] == '0' ? 'selected':'');?>>No</option>
						<option value="1" <?=(isset($site_settings['allow_description']) && $site_settings['allow_description'] == '1' ? 'selected':'');?>>Yes</option>
					</select>
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Hide user information on photo page</div>
				<div class="setting_right">
					<select name="hide_user_photo">
						<option value="1" <?=(isset($site_settings['hide_user_photo']) && $site_settings['hide_user_photo'] == '1' ? 'selected':'');?>>Yes</option>
						<option value="0" <?=(isset($site_settings['hide_user_photo']) && $site_settings['hide_user_photo'] == '0' ? 'selected':'');?>>No</option>
					</select>
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Redirect to random photo after rate</div>
				<div class="setting_right">
					<select name="random_photo">
						<option value="1" <?=(isset($site_settings['random_photo']) && $site_settings['random_photo'] == '1' ? 'selected':'');?>>Yes</option>
						<option value="0" <?=(isset($site_settings['random_photo']) && $site_settings['random_photo'] == '0' ? 'selected':'');?>>No</option>
					</select>
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Allow unregistered visitors to rate</div>
				<div class="setting_right">
					<select name="visitors_rate">
						<option value="1" <?=(isset($site_settings['visitors_rate']) && $site_settings['visitors_rate'] == '1' ? 'selected':'');?>>Yes</option>
						<option value="0" <?=(isset($site_settings['visitors_rate']) && $site_settings['visitors_rate'] == '0' ? 'selected':'');?>>No</option>
					</select>
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Create random rate page<br><span class="setting_explain">(a new menu item will be created, redirect to random option should be enabled)</span></div>
				<div class="setting_right">
					<select name="random_page">
						<option value="0" <?=(isset($site_settings['random_page']) && $site_settings['random_page'] == '0' ? 'selected':'');?>>No</option>
						<option value="1" <?=(isset($site_settings['random_page']) && $site_settings['random_page'] == '1' ? 'selected':'');?>>Yes</option>
					</select>
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Minimum votes for contest/ranking page<br><span class="setting_explain">(example minimum 3 votes for photo to appear in contest/ranking page)</span></div>
				<div class="setting_right">
					<input type="number" min="0" max="100" value="<?=(isset($site_settings['min_votes']) ? $site_settings['min_votes'] : 0);?>" name="min_votes" />
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Filesize limit on upload (MB)<br><span class="setting_explain">(value between 1-100)</span></div>
				<div class="setting_right">
					<input type="number" min="1" max="100" value="<?=(isset($site_settings['max_uploadsize']) ? $site_settings['max_uploadsize'] : 1);?>" name="max_uploadsize" />
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Files uploaded in same time<br><span class="setting_explain">(maximum of files allowed be to uploaded in same time)</span></div>
				<div class="setting_right">
					<input type="number" min="1" max="100" value="<?=(isset($site_settings['max_files']) ? $site_settings['max_files'] : 1);?>" name="max_files" />
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Allow to rate their own photos<br><span class="setting_explain">(users can vote their own uploaded photos)</span></div>
				<div class="setting_right">
					<select name="vote_own">
						<option value="1" <?=(isset($site_settings['vote_own']) && $site_settings['vote_own'] == '1' ? 'selected':'');?>>Yes</option>
						<option value="0" <?=(isset($site_settings['vote_own']) && $site_settings['vote_own'] == '0' ? 'selected':'');?>>No</option>
					</select>
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Allow links in photo description</div>
				<div class="setting_right">
					<select name="description_links">
						<option value="0" <?=(isset($site_settings['description_links']) && $site_settings['description_links'] == '0' ? 'selected':'');?>>No</option>
						<option value="1" <?=(isset($site_settings['description_links']) && $site_settings['description_links'] == '1' ? 'selected':'');?>>Yes</option>
					</select>
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Disable user registration</div>
				<div class="setting_right">
					<select name="disable_register">
						<option value="0" <?=(isset($site_settings['disable_register']) && $site_settings['disable_register'] == '0' ? 'selected':'');?>>No</option>
						<option value="1" <?=(isset($site_settings['disable_register']) && $site_settings['disable_register'] == '1' ? 'selected':'');?>>Yes</option>
					</select>
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Display rate on thumb</div>
				<div class="setting_right">
					<select name="display_thumb_rate">
						<option value="1" <?=(isset($site_settings['display_thumb_rate']) && $site_settings['display_thumb_rate'] == '1' ? 'selected':'');?>>Yes</option>
						<option value="0" <?=(isset($site_settings['display_thumb_rate']) && $site_settings['display_thumb_rate'] == '0' ? 'selected':'');?>>No</option>
					</select>
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Comments moderation</div>
				<div class="setting_right">
					<select name="comments_review">
						<option value="0" <?=(isset($site_settings['comments_review']) && $site_settings['comments_review'] == '0' ? 'selected':'');?>>No</option>
						<option value="1" <?=(isset($site_settings['comments_review']) && $site_settings['comments_review'] == '1' ? 'selected':'');?>>Yes</option>
					</select>
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Display related photos<br><span class="setting_explain">(on photo page)</span></div>
				<div class="setting_right">
					<select name="display_related">
						<option value="1" <?=(isset($site_settings['display_related']) && $site_settings['display_related'] == '1' ? 'selected':'');?>>Yes</option>
						<option value="0" <?=(isset($site_settings['display_related']) && $site_settings['display_related'] == '0' ? 'selected':'');?>>No</option>
					</select>
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Allow music upload (mp3)</div>
				<div class="setting_right">
					<select name="upload_music">
						<option value="0" <?=(isset($site_settings['upload_music']) && $site_settings['upload_music'] == '0' ? 'selected':'');?>>No</option>
						<option value="1" <?=(isset($site_settings['upload_music']) && $site_settings['upload_music'] == '1' ? 'selected':'');?>>Yes</option>
					</select>
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Allow video upload (mp4)</div>
				<div class="setting_right">
					<select name="upload_video">
						<option value="0" <?=(isset($site_settings['upload_video']) && $site_settings['upload_video'] == '0' ? 'selected':'');?>>No</option>
						<option value="1" <?=(isset($site_settings['upload_video']) && $site_settings['upload_video'] == '1' ? 'selected':'');?>>Yes</option>
					</select>
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Choose category on upload</div>
				<div class="setting_right">
					<select name="content_category">
						<option value="0" <?=(isset($site_settings['content_category']) && $site_settings['content_category'] == '0' ? 'selected':'');?>>No</option>
						<option value="1" <?=(isset($site_settings['content_category']) && $site_settings['content_category'] == '1' ? 'selected':'');?>>Yes</option>
					</select>
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Rating mode</div>
				<div class="setting_right">
					<select name="content_ratemode">
						<option value="0" <?=(isset($site_settings['content_ratemode']) && $site_settings['content_ratemode'] == '0' ? 'selected':'');?>>5 star ratebar</option>
						<option value="1" <?=(isset($site_settings['content_ratemode']) && $site_settings['content_ratemode'] == '1' ? 'selected':'');?>>Vote button</option>
					</select>
				</div>
			</div>

			<div class="slash"></div>

			<div class="setting">
				<div class="setting_left">&nbsp;</div>
				<div class="setting_right">
					<input type="submit" name="submit" value="Save changes" class="st_svb" />
				</div>
			</div>

			</form>

		</div>
	
		<?php } ?>

	</div>