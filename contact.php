
	<div class="celling_settings margin_top30">

		<div class="settings">
			<h2><?=_LANG_CONTACT_PAGE;?></h2>
		</div>

		<div class="settings_clear"></div>
		<div class="slash"></div>

		<?php if(isset($success_msg) && $success_msg) { ?><div class="success_box"><?=$success_msg;?></div><?php } ?>
		<?php if(isset($error_msg) && $error_msg) { ?><div class="error_box"><?=$error_msg;?></div><?php } ?>

		<?php if(!isset($success_msg)) { ?>
		<div class="settings_results">

			<form action="index.php?contact=1" method="post">

			<div class="setting">
				<div class="setting_left"><?=_LANG_CONTACT_PAGE_NAME;?></div>
				<div class="setting_right">
					<input type="text" placeholder="" name="contact_name" />
				</div>
			</div>

			<div class="setting">
				<div class="setting_left"><?=_LANG_CONTACT_PAGE_EMAIL;?></div>
				<div class="setting_right">
					<input type="text" placeholder="" name="contact_email" />
				</div>
			</div>

			<div class="setting">
				<div class="setting_left"><?=_LANG_CONTACT_PAGE_SUBJECT;?></div>
				<div class="setting_right">
					<input type="text" placeholder="" name="contact_subject" />
				</div>
			</div>

			<div class="setting">
				<div class="setting_left"><?=_LANG_CONTACT_PAGE_MESSAGE;?></div>
				<div class="setting_right">
					<textarea name="contact_message" cols="80" rows="10"></textarea>
				</div>
			</div>

			<div class="slash"></div>

			<div class="setting setting_last">
				<div class="setting_left">&nbsp;</div>
				<div class="setting_right">
					<input type="submit" name="submit" value="<?=_LANG_CONTACT_PAGE_SUBMIT;?>" class="settings_save_changes" />
				</div>
			</div>

			</form>

		</div>
		<?php } ?>

	</div>