
	<div class="padding_20">

		<?php if(isset($contest_error_msg) && $contest_error_msg) { ?><div class="error_box"><?=$contest_error_msg;?></div><?php } ?>

		<div class="settings_results">

			<form action="index.php?page=contest<?=(isset($cid) && is_numeric($cid) ? '&cid='.$cid : '');?>" method="post">

			<?php if(isset($cid) && is_numeric($cid)) { ?>
			<input type="hidden" name="extra_id" value="<?=$cid;?>" />
			<?php } ?>

			<div class="setting">
				<div class="setting_left">Active contest</div>
				<div class="setting_right">
					<select name="contest_active">
						<option value="1" <?=(isset($site_contest['active']) && $site_contest['active'] == '1' ? 'selected':'');?>>Yes</option>
						<option value="0" <?=(isset($site_contest['active']) && $site_contest['active'] == '0' ? 'selected':'');?>>No</option>
					</select>
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Title</div>
				<div class="setting_right">
					<input type="text" placeholder="My contest ..." value="<?=(isset($site_contest['title']) ? $site_contest['title'] : '');?>" name="contest_title" />
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Description</div>
				<div class="setting_right">
					<input type="text" placeholder="My contest is ..." value="<?=(isset($site_contest['description']) ? $site_contest['description'] : '');?>" name="contest_description" />
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Finish date</div>
				<div class="setting_right">
					<input type="datetime-local" value="<?=(isset($site_contest['end']) ? $site_contest['end'] : '');?>" name="contest_end" />
				</div>
			</div>

			<div class="setting">
				<div class="setting_left">Disable countdown</div>
				<div class="setting_right">
					<select name="disable_countdown">
						<option value="0" <?=(isset($site_contest['disable_countdown']) && $site_contest['disable_countdown'] == '0' ? 'selected':'');?>>No</option>
						<option value="1" <?=(isset($site_contest['disable_countdown']) && $site_contest['disable_countdown'] == '1' ? 'selected':'');?>>Yes</option>
					</select>
				</div>
			</div>

			<!-- TNSB_EDIT_FOR_CUSTOMIZATION_STARTS_HERE -->
			<div class="setting">
				<div class="setting_left">Contest Type</div>
				<div class="setting_right">
					<select name="contest_type">
						<option value="five_star" <?=(isset($site_contest['contest_type']) && $site_contest['contest_type'] == 'five_star' ? 'selected':'');?>>Five Star</option>
						<option value="hot_not" <?=(isset($site_contest['contest_type']) && $site_contest['contest_type'] == 'hot_not' ? 'selected':'');?>>Hot or Not</option>
					</select>
				</div>
			</div>
			<!-- TNSB_EDIT_FOR_CUSTOMIZATION_ENDS_HERE -->


			<div class="slash"></div>

			<div class="setting">
				<div class="setting_left">&nbsp;</div>
				<div class="setting_right">
					<input type="submit" name="submit" value="Save changes" class="st_svb" />
				</div>
			</div>

			</form>

		</div>

	</div>
