
	<div class="celling_settings margin_top30">

		<div class="settings">
			<a href="index.php?settings=1">
				<div class="settings_menu <?=(!isset($_GET['changepw']) ? 'settings_menu_selected':'');?>"><?=_LANG_PROFILE_SETTINGS;?></div>
			</a>
			<a href="index.php?settings=1&changepw=1">
				<div class="settings_menu <?=(isset($_GET['changepw']) ? 'settings_menu_selected':'');?>"><?=_LANG_CHANGE_PASSWORD;?></div>
			</a>
		</div>

		<div class="settings_clear"></div>
		<div class="slash"></div>

		<?php if(isset($success_msg) && $success_msg) { ?><div class="success_box"><?=$success_msg;?></div><?php } ?>
		<?php if(isset($error_msg) && $error_msg) { ?><div class="error_box"><?=$error_msg;?></div><?php } ?>

		<?php if(!isset($_GET['changepw'])) { ?>

		<div class="settings_results">

			<form action="index.php?settings=1" method="post">

			<div class="setting">
				<div class="setting_left"><?=_LANG_SETTINGS_FULL_NAME;?></div>
				<div class="setting_right">
					<input type="text" maxlength="30" placeholder="<?=_LANG_POP_REGISTER_NAME;?>" value="<?=($user_settings['name'] ? $user_settings['name'] : '');?>" name="set_name" />
				</div>
			</div>

			<div class="setting">
				<div class="setting_left"><?=_LANG_SETTINGS_EMAIL;?></div>
				<div class="setting_right">
					<input type="text" placeholder="<?=_LANG_POP_REGISTER_EMAIL;?>" value="<?=($user_settings['email'] ? $user_settings['email'] : '');?>" name="set_email" />
				</div>
			</div>

			<?php if($settings['category_required'] == '1') { ?>
			<div class="setting">
				<div class="setting_left"><?=_LANG_SETTINGS_CATEGORY;?></div>
				<div class="setting_right">
					<select name="set_category">
						<option value=""><?=_LANG_POP_REGISTER_CATEGORY;?></option>
						<?php foreach(get_categories() as $k=>$v) { ?>
						<option value="<?=$v['id'];?>" <?=($v['id'] == $user_settings['category'] ? 'selected':'');?>><?=$v['name'];?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<?php } ?>

			<div class="setting">
				<div class="setting_left"><?=_LANG_SETTINGS_SLOGAN;?></div>
				<div class="setting_right">
					<input type="text" maxlength="30" placeholder="<?=_LANG_PROFILE_ADD_SLOGAN;?>" maxlength="30" value="<?=($user_settings['slogan'] ? $user_settings['slogan'] : '');?>" name="set_slogan" />
				</div>
			</div>

			<div class="setting">
				<div class="setting_left"><?=_LANG_SETTINGS_USERNAME;?></div>
				<div class="setting_right">
					<input type="text" disabled readonly placeholder="<?=_LANG_SETTINGS_USERNAME;?> ..." maxlength="30" value="<?=($user_settings['user'] ? $user_settings['user'] : '');?>" name="set_user" />
				</div>
			</div>

			<div class="slash"></div>

			<div class="setting setting_last">
				<div class="setting_left">&nbsp;</div>
				<div class="setting_right">
					<input type="submit" name="submit" value="<?=_LANG_SETTINGS_SAVE_CHANGES;?>" class="settings_save_changes" />
				</div>
			</div>

			</form>

		</div>

		<?php } ?>

		<?php if(isset($_GET['changepw'])) { ?>

		<div class="settings_results">

			<form action="index.php?settings=1&changepw=1" method="post">

			<?php if($user_settings['fb_id'] && $user_settings['password'] == '') { ?>
				<!-- create password --!>
			<?php } else { ?>
			<div class="setting">
				<div class="setting_left"><?=_LANG_SETTINGS_CURRENT_PASSWORD;?></div>
				<div class="setting_right">
					<input type="password" placeholder="" name="set_pw_1" />
				</div>
			</div>
			<?php } ?>

			<div class="setting">
				<div class="setting_left"><?=_LANG_SETTINGS_NEW_PASSWORD;?></div>
				<div class="setting_right">
					<input type="password" placeholder="" name="set_pw_2" />
				</div>
			</div>

			<div class="setting">
				<div class="setting_left"><?=_LANG_SETTINGS_REPEAT_NEW_PASSWORD;?></div>
				<div class="setting_right">
					<input type="password" placeholder="" name="set_pw_3" />
				</div>
			</div>

			<div class="slash"></div>

			<div class="setting setting_last">
				<div class="setting_left">&nbsp;</div>
				<div class="setting_right">
					<input type="submit" name="submit" value="<?=_LANG_CHANGE_PASSWORD;?>" class="settings_save_changes" />
				</div>
			</div>

			</form>

		</div>
	
		<?php } ?>

		<div class="settings_clear2"></div>

	</div>