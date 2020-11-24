
	<div class="celling_settings margin_top30">

		<div class="settings">
			<h2><?=_LANG_PASSWORD_NEW;?></h2>
		</div>

		<div class="settings_clear"></div>
		<div class="slash"></div>

		<?php if(isset($success_msg) && $success_msg) { ?><div class="success_box"><?=$success_msg;?></div><?php } ?>
		<?php if(isset($error_msg) && $error_msg) { ?><div class="error_box"><?=$error_msg;?></div><?php } ?>

		<?php if(!isset($success_msg)) { ?>
		<div class="settings_results">

			<form action="index.php?forgot=<?=mysqli_real_escape_string($db,$_GET['forgot']);?>" method="post">

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

	</div>