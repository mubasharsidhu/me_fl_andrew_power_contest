
	<div class="pop" id="login_pop">

		<div class="pop_content">

			<div class="pop_inner">

				<div class="pop_head">
					<span><?=_LANG_POP_LOGIN_TITLE;?></span>
					<div class="close_pop">&nbsp;&#10005;&nbsp;</div>
				</div>

				<div class="pop_error"></div>
				<div class="pop_succes"></div>
	
				<div class="pop_row pop_row_first">
					<input type="text" id="login_email" autocapitalize="none" placeholder="<?=_LANG_POP_LOGIN_EMAIL;?>" />
				</div>

				<div class="pop_row">
					<div data-id="forgot" class="pop_forgot_button open_pop"><?=_LANG_POP_LOGIN_FORGOT;?></div>
					<input type="password" id="login_password" placeholder="<?=_LANG_POP_LOGIN_PASSWORD;?>" />
				</div>

				<div class="pop_footer">
					<div class="pop_row_button click_login"><?=_LANG_POP_LOGIN_SUBMIT;?></div>
					<?php if(!isset($settings['disable_register']) || $settings['disable_register'] == '0') { ?>
					<div class="pop_login_reg_now">
						<?=_LANG_POP_LOGIN_NOT_MEMBER;?> <span class="pop_login_reg_now_button open_pop" data-id="register"><?=_LANG_POP_LOGIN_REGISTER_NOW;?></span>
					</div>
					<?php } ?>
				</div>

				<?php if(isset($settings['fb_appid']) && $settings['fb_appid'] != '') { ?>
				<div class="pop_login_with">
					<div class="login_fb"><i class="fab fa-facebook"></i>&nbsp;&nbsp;Facebook</div>
				</div>
				<?php } ?>

			</div>
		</div>

	</div>