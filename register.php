
	<div class="pop" id="register_pop">

		<div class="pop_content">

			<div class="pop_inner">

				<div class="pop_head">
					<span><?=_LANG_POP_REGISTER_TITLE;?></span>
					<div class="close_pop">&nbsp;&#10005;&nbsp;</div>
				</div>

				<div class="pop_error"></div>
				<div class="pop_succes"></div>
	
				<div class="pop_row pop_row_first">
					<input type="text" id="register_email" autocapitalize="none" placeholder="<?=_LANG_POP_REGISTER_EMAIL;?>" />
				</div>

				<div class="pop_row">
					<input type="text" id="register_name" maxlength="30" placeholder="<?=_LANG_POP_REGISTER_NAME;?>" />
				</div>

				<?php if($settings['category_required'] == '1') { ?>
				<div class="pop_row">
					<select id="register_category">
						<option value=""><?=_LANG_POP_REGISTER_CATEGORY;?></option>
						<?php foreach(get_categories() as $k=>$v) { ?>
						<option value="<?=$v['id'];?>"><?=$v['name'];?></option>
						<?php } ?>
					</select>
				</div>
				<?php } ?>

				<div class="pop_row">
					<input type="password" id="register_password" placeholder="<?=_LANG_POP_REGISTER_PASSWORD;?>" />
				</div>

				<div class="pop_row">
					<input type="password" id="register_repeat_password" placeholder="<?=_LANG_POP_REGISTER_REPEAT_PASSWORD;?>" />
				</div>

				<div class="pop_footer">
					<div class="pop_row_button click_register"><?=_LANG_POP_REGISTER_SUBMIT;?></div>
				</div>

				<?php if(isset($settings['fb_appid']) && $settings['fb_appid'] != '') { ?>
				<div class="pop_login_with">
					<div class="login_fb"><i class="fab fa-facebook"></i>&nbsp;&nbsp;Facebook</div>
				</div>
				<?php } ?>

			</div>

		</div>

	</div>