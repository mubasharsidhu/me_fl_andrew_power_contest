
	<?php if(is_logged()) { ?>

		<form action="_core/_uploader.php" method="post" enctype="multipart/form-data" id="upload_photos" class="hide">
			<?php if(isset($settings['content_category']) && $settings['content_category'] == '1') { ?>
			<input type="hidden" name="content_category_id" id="content_category_id" value="0" />
			<?php } ?>
			<input type="file" name="_uploader[]" multiple accept="<?=accept_upload_type();?>" id="_uploader" />
		</form>

		<form action="_core/_uploader_cover.php" method="post" enctype="multipart/form-data" id="upload_cover_form" class="hide">
			<input type="hidden" name="main_id" id="main_id" value="0" />
			<input type="file" name="_uploader_cover" accept="image/*" id="_uploader_cover" />
		</form>

		<form action="_core/_uploader_profile.php" method="post" enctype="multipart/form-data" id="upload_profile_picture" class="hide">
			<input type="file" name="_uploader_profile" accept="image/*" id="_uploader_profile" />
		</form>

	<?php } ?>

	<div class="footer_shade"></div>
	<div class="footer">

		<div class="footer_inner">
			<div class="footer_left">
				<div class="logo a">
					<a href="<?=$site_url;?>" class="text_dec_none">
						<?php if(isset($settings['site_logo_image']) && $settings['site_logo_image'] != '') { ?>
							<img src="<?=$site_url;?>_img/logo.png" class="image_logo_footer" border="0" alt="<?=(isset($settings['site_logo']) ? $settings['site_logo'] : 'Hello');?>" />
						<?php } else { ?>
							<?=(isset($settings['site_logo']) ? $settings['site_logo'] : 'Hello');?>
						<?php } ?>
					</a>
				</div>
			</div>
			<div class="footer_copyright b"><?=_LANG_FOOTER_COPYRIGHT;?></div>
			<div class="footer_extra_pages">
				<?=(isset($footer_extra_pages) && count($footer_extra_pages) ? implode('&nbsp;&nbsp;-&nbsp;&nbsp;',$footer_extra_pages) : '');?>
			</div>
		</div>

	</div>