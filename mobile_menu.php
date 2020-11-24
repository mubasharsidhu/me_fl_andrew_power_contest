
	<div class="menu_mobile">

		<div class="menu_mobile_close">
			<div class="close_menu left"><i class="fas fa-chevron-left"></i></div>
		</div>

		<?php if(is_logged()) { ?>
		<div class="menu_mobile_logged">

			<a href="<?=$site_url;?><?=(isset($user_logged['user']) ? $user_logged['user'] : '');?>" class="text_dec_none">

				<div class="menu_mobile_pic">
	
					<div class="menu_mobile_pic_left">
						<?php if($user_logged['profile_picture']) { ?>
						<img src="<?=$site_url;?>_uploads/_profile_pictures/<?=$user_logged['profile_picture'];?>.jpg" />
					<?php } ?>
					</div>

					<div class="menu_mobile_pic_right">
						<div class="menu_mobile_pic_name"><?=(isset($user_logged['name']) ? $user_logged['name'] : '-');?></div>
						<div class="menu_mobile_pic_user">@<?=(isset($user_logged['user']) ? $user_logged['user'] : '-');?></div>
					</div>

				</div>

			</a>

		</div>
		<?php } ?>

		<div class="menu_mobile_buttons">

			<div class="button5 click_add_photos">
				<div class="button5_icon"><i class="fas fa-camera"></i></div>
				<div class="button5_text"><?=_LANG_UPLOAD;?></div>
			</div>

			<div class="button5 click_settings">
				<a href="index.php" class="inherit_desc_none">
					<div class="button5_icon"><i class="fas fa-image"></i></div>
					<div class="button5_text"><?=_LANG_PHOTOS;?></div>
				</a>
			</div>

			<?php if(isset($settings['random_page']) && $settings['random_page'] == '1') { ?>
			<div class="button5">
				<a href="index.php?random=1" class="inherit_dec_none">
					<div class="button5_icon"><i class="fas fa-star"></i></div>
					<div class="button5_text"><?=_LANG_MENU_RATE;?></div>
				</a>
			</div>
			<?php } ?>

			<?php if($multi_contest == '1') { ?>
			<div class="button5">
				<a href="index.php?contest=<?=(isset($site_contest['id']) ? $site_contest['id'] : 0);?>" class="inherit_desc_none">
					<div class="button5_icon"><i class="fas fa-crown"></i></div>
					<div class="button5_text"><?=_LANG_CONTEST;?></div>
				</a>
			</div>
			<?php } ?>

			<?php if($multi_contest == '2') { ?>
			<div class="button5">
				<a href="index.php?contests=1" class="inherit_desc_none">
					<div class="button5_icon"><i class="fas fa-crown"></i></div>
					<div class="button5_text"><?=_LANG_CONTESTS;?></div>
				</a>
			</div>
			<?php } ?>

			<div class="button5">
				<a href="index.php?ranking=1" class="inherit_desc_none">
					<div class="button5_icon"><i class="fas fa-chart-line"></i></div>
					<div class="button5_text"><?=_LANG_RANKING;?></div>
				</a>
			</div>

			<?php if(!is_logged()) { ?>

			<div class="button5 open_pop" data-id="login">
				<div class="button5_icon"><i class="fas fa-lock"></i></div>
				<div class="button5_text"><?=_LANG_LOGIN;?></div>
			</div>

			<?php if(!isset($settings['disable_register']) || $settings['disable_register'] == '0') { ?>
			<div class="button5 open_pop" data-id="register">
				<div class="button5_icon"><i class="fas fa-plus"></i></div>
				<div class="button5_text"><?=_LANG_REGISTER;?></div>
			</div>
			<?php } ?>

			<?php } else { ?>
			<div class="button5 click_settings">
				<div class="button5_icon"><i class="fas fa-cog"></i></div>
				<div class="button5_text"><?=_LANG_SETTINGS;?></div>
			</div>

			<div class="button5 click_my_profile">
				<div class="button5_icon"><i class="fas fa-user"></i></div>
				<div class="button5_text"><?=_LANG_MY_PROFILE;?></div>
			</div>

			<div class="button5 click_logout">
				<div class="button5_icon"><i class="fas fa-power-off"></i></div>
				<div class="button5_text"><?=_LANG_LOGOUT;?></div>
			</div>
			<?php } ?>

		</div>

	</div>