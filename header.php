		
	<?php if($show_popupad == '1') { ?>
	<div class="pop show" id="ad_pop">
		<div class="pop_content fad3x2">
			<div class="pop_inner">
				<div class="pop_head">
					<span><?=(isset($popupad_title) ? $popupad_title : '');?>s</span>
					<div class="close_pop">&nbsp;&#10005;&nbsp;</div>
				</div>
				<?=get_ads('popup_ad');?>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php if(isset($settings['content_category']) && $settings['content_category'] == '1') { ?>
	<div class="pop" id="content_category">
		<div class="pop_content pop_content_category">
			<div class="pop_inner">
				<div class="pop_head">
					<span><?=_LANG_CHOOSE_UPLOAD_CATEGORY;?></span>
					<div class="close_pop">&nbsp;&#10005;&nbsp;</div>
				</div>
				
				<div class="overflow">
					<?php foreach(get_categories() as $k=>$v) { ?>
					<div class="choose_upload_type" data-id="<?=$v['id'];?>">
						<div class="choose_upload_type_title"><?=$v['name'];?></div>
					</div>
					<?php } ?>
				</div>

			</div>
		</div>
	</div>
	<?php } ?>

	<div class="<?=(is_home() && $settings['home_header'] == '1' ? 'home_h hh':'header_h hh2');?>">

		<div class="header_sub_bg <?=(is_home() && $settings['home_header'] == '1' ? 'home_h bg':'header_h');?>"></div>

		<div class="header_main">

			<div class="logo a">
				<a href="<?=$site_url;?>">
					<?php if(isset($settings['site_logo_image']) && $settings['site_logo_image'] != '') { ?>
						<img src="<?=$site_url;?>_img/logo.png" class="image_logo" border="0" alt="<?=(isset($settings['site_logo']) ? $settings['site_logo'] : 'Hello');?>" />
					<?php } else { ?>
						<?=(isset($settings['site_logo']) ? $settings['site_logo'] : 'Hello');?>
					<?php } ?>
				</a>
			</div>

			<div class="open_menu">
				<i class="fas fa-chevron-right"></i>
			</div>

			<div class="open_upload click_add_photos">
				<i class="fas fa-camera"></i>
			</div>

			<?php if(!is_logged()) { ?>
			<?php if(!isset($settings['disable_register']) || $settings['disable_register'] == '0') { ?>
			<div class="button2 b open_pop header_login_button" data-id="register">
				<?=_LANG_REGISTER;?>
			</div>
			<?php } ?>
			<div class="button b open_pop header_login_button" data-id="login">
				<i class="fas fa-lock"></i>&nbsp;&nbsp;<?=_LANG_LOGIN;?>
			</div>
			<?php } else { ?>

			<div class="button2 b click_add_photos"><?=_LANG_UPLOAD;?></div>
			<div class="open_account_menu usel_none">
				<div class="button3s b action_open_user_menu ov_visible">
					<div class="logged_name_elip">
						<i class="fas fa-user"></i>&nbsp;&nbsp;<?=$_SESSION['_logged_name'];?>
					</div>
					<div class="logged_name_op">
						<span id="d_menu_open"><i class="fas fa-chevron-down"></i></span>
					</div>
				</div>
				<div class="header_user_menu button3_menu">
					
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

				</div>
			</div>

			<?php } ?>

			<a href="index.php?ranking=1" class="inherit_dec_none">
				<div class="button b"><i class="fas fa-chart-line"></i>&nbsp;&nbsp;<?=_LANG_RANKING;?></div>
			</a>

			<?php if($multi_contest == '1') { ?>
			<a href="index.php?contest=<?=(isset($site_contest['id']) ? $site_contest['id'] : 0);?>" class="inherit_dec_none">
				<div class="button b"><i class="fas fa-crown"></i>&nbsp;&nbsp;<?=_LANG_CONTEST;?></div>
			</a>
			<?php } ?>

			<?php if($multi_contest == '2') { ?>
			<a href="index.php?contests=1" class="inherit_dec_none">
				<div class="button b"><i class="fas fa-crown"></i>&nbsp;&nbsp;<?=_LANG_CONTESTS;?></div>
			</a>
			<?php } ?>

			<?php if(isset($settings['random_page']) && $settings['random_page'] == '1') { ?>
			<a href="index.php?random=1" class="inherit_dec_none">
				<div class="button b"><i class="fas fa-star"></i>&nbsp;&nbsp;<?=_LANG_MENU_RATE;?></div>
			</a>
			<?php } ?>

			<a href="index.php" class="inherit_dec_none">
				<div class="button b"><i class="fas fa-clone"></i>&nbsp;&nbsp;<?=_LANG_PHOTOS;?></div>
			</a>

			<?php if((isset($settings['display_searchbar']) && $settings['display_searchbar'] == '1') || !isset($settings['display_searchbar'])) { ?>
			<div class="header_search">
				<div class="header_search_suggestions"></div>
				<div class="header_search_icon">
					<i class="fas fa-search"></i>
				</div>
				<input type="text" class="header_search_input" placeholder="Search ..."/>
			</div>
			<?php } ?>

			<?php if(is_home() && $settings['home_header'] == '1') { ?>
			<div class="head_home">
				<div class="left">
					<div class="c cs2"><?=_LANG_SITE_SLOGAN;?></div>
					<div class="c cs3"><?=_LANG_SITE_SLOGAN_SUB;?></i></div>
				</div>
			</div>
			<?php } ?>
		
		</div>

	</div>

	<?=get_ads('after_header');?>
